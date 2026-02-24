<?php

namespace App\Http\Controllers;

use App\Models\Central\ShareOffer as CentralShareOffer;
use App\Models\Central\Buyer;
use App\Models\Central\BuyerHolding;
use App\Models\Central\ShareOperation;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\AlertService;
use Mpdf\Mpdf;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = CentralShareOffer::on('central')
            ->where('status', 'active')
            ->where('approval_status', 'real_estate_approved');

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'LIKE', '%' . $request->city . '%');
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price_per_share', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_share', '<=', $request->max_price);
        }

        // Filter by availability status
        if ($request->get('availability') === 'low') {
            $query->whereRaw('available_shares > 0 AND available_shares < 10');
        } elseif ($request->get('availability') === 'high') {
            $query->where('available_shares', '>=', 10);
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price_per_share', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_per_share', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $offers = $query->paginate(30)->appends($request->query());

        // Get unique cities for filter dropdown
        $cities = CentralShareOffer::on('central')
            ->where('status', 'active')
            ->where('approval_status', 'real_estate_approved')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->pluck('city')
            ->filter()
            ->sort()
            ->values();

        // Get statistics
        $stats = [
            'total_offers' => CentralShareOffer::on('central')->where('status', 'active')->where('approval_status', 'real_estate_approved')->count(),
            'total_cities' => $cities->count(),
            'avg_price' => CentralShareOffer::on('central')
                ->where('status', 'active')
                ->where('approval_status', 'real_estate_approved')
                ->avg('price_per_share'),
        ];

        return view('marketplace.offers.index', compact('offers', 'cities', 'stats'));
    }

    public function show(CentralShareOffer $offer)
    {
        return view('marketplace.offers.show', compact('offer'));
    }

    public function buy(Request $request, CentralShareOffer $offer)
    {
        // Require central login and an existing Buyer profile
        if (! \Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $intended = url()->current();
            return redirect()->route('marketplace.login', ['intended' => $intended]);
        }
        $user = \Illuminate\Support\Facades\Auth::guard('web')->user();
        $existingBuyer = Buyer::on('central')->where('user_id', $user->getKey())->first();
        if (! $existingBuyer) {
            $intended = url()->current();
            return redirect()->route('marketplace.register', ['intended' => $intended])
                ->withErrors(['buyer' => __('يجب إنشاء حساب مشتري أولاً')]);
        }

        $data = $request->validate([
            'full_name' => ['nullable','string','max:255'],
            'email' => ['nullable','email','max:255'],
            'phone' => ['nullable','string','max:30'],
            'national_id' => ['nullable','string','max:50'],
            'shares' => ['required','integer','min:1'],
        ]);

        $shares = (int) $data['shares'];
        $buyer = $existingBuyer;

        $centralConn = DB::connection('central');
        $centralConn->transaction(function () use (&$data, $shares, $offer, $centralConn, $buyer) {
            $offer->refresh();
            if ($offer->status !== 'active' || $offer->available_shares < $shares) {
                abort(422, __('الكمية غير متاحة حالياً'));
            }

            // Use existing buyer bound to current user

            $pricePer = $offer->price_per_share;
            $amount = $pricePer * $shares;

            $op = ShareOperation::on('central')->create([
                'offer_id' => $offer->id,
                'tenant_id' => $offer->tenant_id,
                'buyer_id' => $buyer->id,
                'type' => 'purchase',
                'shares_count' => $shares,
                'price_per_share' => $pricePer,
                'amount_total' => $amount,
                'currency' => $offer->currency,
                'status' => 'completed',
            ]);

            // Update offer counters
            $offer->available_shares -= $shares;
            $offer->sold_shares += $shares;
            $offer->save();

            // Upsert holding
            $holding = BuyerHolding::on('central')->firstOrNew([
                'buyer_id' => $buyer->id,
                'offer_id' => $offer->id,
            ]);
            $newOwned = ($holding->shares_owned ?? 0) + $shares;
            $holding->shares_owned = $newOwned;
            $holding->avg_price_per_share = $pricePer; // simplified
            $holding->last_transaction_at = now();
            $holding->save();

            // Mirror operation and counters into tenant database
            $tenant = DB::connection('central')->table('tenants')->where('TenantID', $offer->tenant_id)->first();
            if ($tenant) {
                // Configure tenant connection dynamically
                config([
                    'database.connections.tenant' => [
                        'driver' => 'mysql',
                        'host' => $tenant->DBHost ?: config('database.connections.mysql.host'),
                        'port' => $tenant->DBPort ?: config('database.connections.mysql.port'),
                        'database' => $tenant->DBName,
                        'username' => 'root',
                        'password' => null,
                        'charset' => 'utf8mb4',
                        'collation' => 'utf8mb4_unicode_ci',
                        'prefix' => '',
                        'strict' => true,
                    ],
                ]);
                DB::purge('tenant');
                DB::reconnect('tenant');

                // Update tenant offer counters
                $tenantOffer = DB::connection('tenant')->table('share_offers')->where('central_offer_id', $offer->id)->first();
                if ($tenantOffer) {
                    DB::connection('tenant')->table('share_offers')->where('id', $tenantOffer->id)->update([
                        'available_shares' => max(0, (int)$tenantOffer->available_shares - $shares),
                        'sold_shares' => ((int)$tenantOffer->sold_shares) + $shares,
                        'updated_at' => now(),
                    ]);

                    // Record tenant-side operation
                    DB::connection('tenant')->table('share_operations')->insert([
                        'central_operation_id' => $op->id,
                        'offer_id' => $tenantOffer->id,
                        'type' => 'purchase',
                        'shares_count' => $shares,
                        'price_per_share' => $pricePer,
                        'amount_total' => $amount,
                        'currency' => $offer->currency,
                        'status' => 'completed',
                        'metadata' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Send alerts to central admins and tenant
                app(AlertService::class)->adminPurchaseCompleted((int)$tenant->TenantID, [
                    'offer_id' => $offer->id,
                    'tenant_id' => (int)$tenant->TenantID,
                    'subdomain' => $tenant->Subdomain,
                    'buyer' => $buyer->full_name,
                    'shares' => $shares,
                    'amount' => $amount,
                    'currency' => $offer->currency,
                    'message' => __('شراء :shares سهم بقيمة :amount :currency على عرض ":title"', [
                        'shares' => $shares,
                        'amount' => number_format($amount, 2),
                        'currency' => $offer->currency,
                        'title' => $offer->title,
                    ]),
                ]);

                app(AlertService::class)->tenantNotify((int)$tenant->TenantID, config('database.connections.tenant'), 'purchase_completed', __('تمت عملية شراء أسهم'), __('تم شراء :shares سهم (المشتري: :buyer)', [
                    'shares' => $shares,
                    'buyer' => $buyer->full_name,
                ]), [
                    'central_offer_id' => $offer->id,
                    'shares' => $shares,
                    'amount' => $amount,
                    'currency' => $offer->currency,
                ]);
            }
        });

        return redirect()->route('marketplace.offers.index')
            ->with('status', __('تم تنفيذ عملية الشراء بنجاح'));
    }

    public function availability(Request $request, CentralShareOffer $offer)
    {
        $requested = (int) $request->query('shares', 0);
        $offer->refresh();
        $available = (int) $offer->available_shares;
        $ok = $requested > 0 && $offer->status === 'active' && $available >= $requested;
        return response()->json([
            'ok' => $ok,
            'available' => $available,
            'status' => $offer->status,
            'message' => $ok ? null : __('الكمية غير متاحة حالياً'),
        ]);
    }

    public function generatePdf(Request $request, CentralShareOffer $offer)
    {
        // Check if offer is approved and has checkpoints
        if ($offer->approval_status !== 'real_estate_approved' || $offer->realEstateCheckpoints->isEmpty()) {
            abort(404, 'تقرير المراجعة العقارية غير متوفر');
        }

        $html = view('pdf.real-estate-report', [
            'offer' => $offer,
            'checkpoints' => $offer->realEstateCheckpoints,
            'tenant' => $offer->tenant
        ])->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_header' => 10,
            'margin_footer' => 10,
            'default_font' => 'dejavusans',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        $mpdf->SetDirectionality('rtl');
        $mpdf->WriteHTML($html);
        
        $filename = 'تقرير-المراجعة-العقارية-' . $offer->id . '.pdf';

        if ($request->query('download')) {
            return response()->streamDownload(function() use ($mpdf) {
                echo $mpdf->Output('', 'S');
            }, $filename, ['Content-Type' => 'application/pdf']);
        }

        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }
}