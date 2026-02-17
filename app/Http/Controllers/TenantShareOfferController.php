<?php

namespace App\Http\Controllers;

use App\Models\TenantShareOffer;
use App\Models\Tenant;
use App\Models\Central\ShareOffer as CentralShareOffer;
use App\Services\ShareOfferSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class TenantShareOfferController extends Controller
{
    public function index(string $subdomain)
    {
        $offers = TenantShareOffer::orderByDesc('id')->paginate(15);
        return view('pages.tenant.shares.index', compact('offers','subdomain'));
    }

    public function create(string $subdomain)
    {
        return view('pages.tenant.shares.create', compact('subdomain'));
    }

    public function store(Request $request, string $subdomain, ShareOfferSyncService $sync)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'title_ar' => ['nullable','string','max:255'],
            'description' => ['required','string'],
            'description_ar' => ['nullable','string'],
            'city' => ['required','string','max:64'],
            // address لم يعد مطلوبًا
            'total_shares' => ['required','integer','min:1'],
            'available_shares' => ['required','integer','min:0','lte:total_shares'],
            'price_per_share' => ['required','numeric','min:0'],
            // currency لم يعد مطلوبًا
            'status' => ['nullable','in:draft,active,paused,completed,cancelled'],
            'images' => ['required','array','min:1','max:15'],
            'images.*' => ['required','image','mimes:jpg,jpeg,png,webp','max:5120'],
        ], [
            'images.required' => 'يجب إضافة صورة واحدة على الأقل للعرض',
            'images.min' => 'يجب إضافة صورة واحدة على الأقل للعرض',
            'images.*.required' => 'يجب أن يكون الملف صورة صالحة',
            'images.*.image' => 'يجب أن يكون الملف صورة',
            'images.*.mimes' => 'صيغة الصورة يجب أن تكون: jpg, jpeg, png, webp',
            'images.*.max' => 'حجم الصورة يجب أن لا يتجاوز 5 ميجابايت',
        ]);

        $data['sold_shares'] = $data['sold_shares'] ?? 0;
        // الحالة الافتراضية نشط
        $data['status'] = $data['status'] ?? 'active';
        // اجعل العملة ثابتة وتحفظ كود العملة دون عرضها
        $data['currency'] = 'SAR';

        // Enable query logging for diagnostics
        \Illuminate\Support\Facades\DB::connection('tenant')->enableQueryLog();
        \Illuminate\Support\Facades\DB::connection('central')->enableQueryLog();

        // Resolve tenant info (for image naming and central sync)
        $tenant = Tenant::on('central')->where('Subdomain', $subdomain)->firstOrFail();
        $tenantId = (int) $tenant->TenantID;

        $createdId = null;
        $centralId = null;
        $createdOffer = null;
        try {
            // Ensure tenant table exists before attempting insert
            if (! Schema::connection('tenant')->hasTable('share_offers')) {
                throw new \RuntimeException(__('جدول عروض الأسهم غير موجود في قاعدة بيانات المستأجر'));
            }

            DB::connection('tenant')->transaction(function () use (&$data, $subdomain, $request, $tenantId, &$createdId, &$createdOffer) {
                // Handle images upload (up to 15) and set cover_image/media
                $media = [];
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        if ($file && $file->isValid()) {
                            $ext = $file->extension();
                            $filename = $tenantId . '_' . Str::random(32) . ($ext ? ('.' . $ext) : '');
                            $path = $file->storeAs('offers', $filename, 'public');
                            $media[] = $path;
                        }
                    }
                }
                if (!empty($media)) {
                    $data['cover_image'] = $media[0];
                    $data['media'] = $media;
                }

                // Prepare payload for Eloquent and ensure tenant_id is set
                $payload = $data;
                unset($payload['images']);
                $payload['tenant_id'] = $tenantId;

                // Create via Eloquent on tenant connection
                $offer = TenantShareOffer::on('tenant')->create($payload);
                if (! $offer || ! $offer->id) {
                    throw new \RuntimeException(__('فشل إنشاء العرض في قاعدة بيانات المستأجر'));
                }
                $createdId = $offer->id;
                \Log::info('Tenant offer created', ['tenant_offer_id' => $offer->id, 'subdomain' => $subdomain]);
                // تأكد من ثبات حفظ العرض
                $exists = DB::connection('tenant')->table('share_offers')->where('id', $offer->id)->exists();
                if (! $exists) {
                    throw new \RuntimeException(__('لم يتم حفظ العرض بشكل صحيح'));
                }

                // Keep the offer for later central sync outside the tenant transaction
                $createdOffer = $offer;
            });

            // After tenant commit, verify existence and then sync to central
            $existsAfter = DB::connection('tenant')->table('share_offers')->where('id', $createdId)->exists();
            if (! $existsAfter) {
                throw new \RuntimeException(__('لم يتم تأكيد الصف في قاعدة بيانات الفرعية بعد الحفظ'));
            }

            $central = $sync->upsertToCentral($createdOffer, $tenant);
            $centralId = $central->id ?? null;
            \Log::info('Central offer upserted', ['central_offer_id' => $centralId, 'tenant_offer_id' => $createdId]);

            // If tenant record missing for any reason, clean up central to keep consistency
            if (! DB::connection('tenant')->table('share_offers')->where('id', $createdId)->exists()) {
                // Clean central to keep consistency
                if ($centralId) {
                    CentralShareOffer::on('central')->where('id', $centralId)->delete();
                    \Log::warning('Central offer deleted due to missing tenant offer', ['central_offer_id' => $centralId, 'expected_tenant_offer_id' => $createdId]);
                }

                // Build a clear reason message for the user
                $tenantDb = DB::connection('tenant')->getDatabaseName();
                $hasTable = false;
                $columnsInfo = null;
                $columnsErr = null;
                try {
                    $hasTable = \Illuminate\Support\Facades\Schema::connection('tenant')->hasTable('share_offers');
                    if ($hasTable) {
                        $columnsInfo = DB::connection('tenant')->select('SHOW COLUMNS FROM share_offers');
                    }
                } catch (\Throwable $ce) {
                    $columnsErr = $ce->getMessage();
                }

                $reason = __('تم إلغاء الحفظ: لم يُحفظ العرض في الفرعية، لذا تم حذف السجل من المركزية.');
                if (! $hasTable) {
                    $reason .= ' ' . __('السبب: جدول عروض الأسهم غير موجود في قاعدة بيانات الفرعية (%db).', ['db' => $tenantDb]);
                } else {
                    $reason .= ' ' . __('السبب: لم يتم تأكيد الصف في قاعدة بيانات الفرعية (%db).', ['db' => $tenantDb]);
                }

                if (config('app.debug')) {
                    $extra = [
                        'tenant_db' => $tenantDb,
                        'tenant_has_table' => $hasTable,
                        'tenant_columns' => $columnsInfo,
                        'columns_error' => $columnsErr,
                        'tenant_offer_id_expected' => $createdId,
                        'central_offer_id' => $centralId,
                    ];
                    $reason .= ' | diag=' . json_encode($extra, JSON_UNESCAPED_UNICODE);
                }

                return back()->withErrors(['general' => $reason]);
            }

            // Log executed queries for tenant and central
            $tenantQueries = DB::connection('tenant')->getQueryLog();
            $centralQueries = DB::connection('central')->getQueryLog();
            \Log::info('Offer creation query logs', [
                'tenant' => $tenantQueries,
                'central' => $centralQueries,
                'tenant_db_after' => DB::connection('tenant')->getDatabaseName(),
                'created_id' => $createdId,
            ]);

            // For local debug, flash queries to session to display on index page
            if (config('app.debug')) {
                session()->flash('debug_queries', [
                    'tenant' => $tenantQueries,
                    'central' => $centralQueries,
                    'tenant_db' => DB::connection('tenant')->getDatabaseName(),
                    'central_db' => DB::connection('central')->getDatabaseName(),
                ]);
            }
        } catch (\Throwable $e) {
            // Collect query logs for error diagnostics
            $tenantQueries = DB::connection('tenant')->getQueryLog();
            $centralQueries = DB::connection('central')->getQueryLog();
            \Log::error('Failed to create tenant share offer', [
                'error' => $e->getMessage(),
                'tenant_queries' => $tenantQueries,
                'central_queries' => $centralQueries,
            ]);

            $msg = $e->getMessage();
            if (config('app.debug')) {
                // Append query snapshots to the error message for local debugging
                $msg .= ' | tenantQueries=' . json_encode($tenantQueries, JSON_UNESCAPED_UNICODE);
                $msg .= ' | centralQueries=' . json_encode($centralQueries, JSON_UNESCAPED_UNICODE);
            }
            return back()->withErrors(['general' => __('تعذر حفظ العرض: :msg', ['msg' => $msg])]);
        }

        return redirect()->route('tenant.subdomain.shares.index', ['subdomain' => $subdomain])
            ->with('status', __('تم إنشاء العرض وتمت مزامنته للمركزية'));
    }

    public function edit(string $subdomain, int $share)
    {
        $offer = TenantShareOffer::on('tenant')->findOrFail($share);
        return view('pages.tenant.shares.edit', [
            'subdomain' => $subdomain,
            'offer' => $offer,
        ]);
    }

    public function update(Request $request, string $subdomain, int $share, ShareOfferSyncService $sync)
    {
        $share = TenantShareOffer::on('tenant')->findOrFail($share);
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'title_ar' => ['nullable','string','max:255'],
            'description' => ['required','string'],
            'description_ar' => ['nullable','string'],
            'city' => ['required','string','max:64'],
            // address لم يعد مطلوبًا
            'total_shares' => ['required','integer','min:1'],
            'available_shares' => ['required','integer','min:0','lte:total_shares'],
            'price_per_share' => ['required','numeric','min:0'],
            // currency لم يعد مطلوبًا
            'status' => ['nullable','in:draft,active,paused,completed,cancelled'],
            'images' => ['nullable','array','max:15'],
            'images.*' => ['image','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);

        // اجعل العملة ثابتة وتحفظ كود العملة دون عرضها
        $data['currency'] = 'SAR';

        // Resolve tenant info (for image naming in updates)
        $tenant = Tenant::on('central')->where('Subdomain', $subdomain)->firstOrFail();
        $tenantId = (int) $tenant->TenantID;

        // Append newly uploaded images to media
        $media = is_array($share->media) ? $share->media : [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $ext = $file->extension();
                    $filename = $tenantId . '_' . Str::random(32) . ($ext ? ('.' . $ext) : '');
                    $path = $file->storeAs('offers', $filename, 'public');
                    $media[] = $path;
                }
            }
        }
        if (!empty($media)) {
            $data['media'] = $media;
            $data['cover_image'] = $media[0] ?? $share->cover_image;
        }

        try {
            $share->fill($data)->save();

            $tenant = Tenant::on('central')->where('Subdomain', $subdomain)->firstOrFail();
            $sync->upsertToCentral($share, $tenant);
        } catch (\Throwable $e) {
            \Log::error('Failed to update tenant share offer', ['error' => $e->getMessage()]);
            return back()->withErrors(['general' => __('تعذر تحديث العرض: :msg', ['msg' => $e->getMessage()])]);
        }

        return redirect()->route('tenant.subdomain.shares.index', ['subdomain' => $subdomain])
            ->with('status', __('تم تحديث العرض وتمت مزامنته للمركزية'));
    }

    public function destroy(string $subdomain, int $share)
    {
        $model = TenantShareOffer::on('tenant')->findOrFail($share);
        
        try {
            DB::beginTransaction();
            
            // Find central offer using the link stored in tenant
            if ($model->central_offer_id) {
                $centralOffer = CentralShareOffer::on('central')->find($model->central_offer_id);
                
                if ($centralOffer) {
                    // Check if there are active secondary market offers
                    $hasActiveSales = DB::connection('central')
                        ->table('buyer_sale_offers')
                        ->where('original_offer_id', $centralOffer->id)
                        ->where('status', 'active')
                        ->exists();
                    
                    if ($hasActiveSales) {
                        DB::rollBack();
                        return back()->withErrors([
                            'error' => 'لا يمكن حذف هذا العرض لأنه يحتوي على عروض بيع نشطة في السوق الثانوي. يرجى إلغاء جميع العروض أولاً.'
                        ]);
                    }
                    
                    // Check if there are any buyer holdings
                    $hasBuyerHoldings = DB::connection('central')
                        ->table('buyer_holdings')
                        ->where('offer_id', $centralOffer->id)
                        ->where('shares_count', '>', 0)
                        ->exists();
                    
                    if ($hasBuyerHoldings) {
                        // Instead of deleting, mark as cancelled
                        $centralOffer->status = 'cancelled';
                        $centralOffer->save();
                        \Log::info('Central offer marked as cancelled due to buyer holdings', ['central_offer_id' => $centralOffer->id]);
                    } else {
                        // Safe to delete
                        $centralOffer->delete();
                        \Log::info('Central offer deleted', ['central_offer_id' => $centralOffer->id]);
                    }
                }
            }
            
            // Delete from tenant
            $model->delete();
            
            DB::commit();
            
            return redirect()->route('tenant.subdomain.shares.index', ['subdomain' => $subdomain])
                ->with('status', __('تم حذف العرض من كل من قاعدة البيانات الفرعية والمركزية'));
                
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Failed to delete tenant share offer', [
                'error' => $e->getMessage(),
                'tenant_offer_id' => $share,
                'subdomain' => $subdomain
            ]);
            
            return back()->withErrors([
                'error' => __('تعذر حذف العرض: :msg', ['msg' => $e->getMessage()])
            ]);
        }
    }

    /**
     * Remove a single image from offer media and delete file.
     */
    public function removeImage(Request $request, string $subdomain, int $share, ShareOfferSyncService $sync)
    {
        $request->validate(['image' => 'required|string']);
        $offer = TenantShareOffer::on('tenant')->findOrFail($share);
        $image = $request->input('image');

        $media = is_array($offer->media) ? $offer->media : [];
        
        // منع حذف آخر صورة - يجب أن يبقى صورة واحدة على الأقل
        if (count($media) <= 1) {
            return response()->json([
                'ok' => false, 
                'error' => 'لا يمكن حذف جميع الصور. يجب أن يبقى صورة واحدة على الأقل للعرض'
            ], 422);
        }
        
        $media = array_values(array_filter($media, function ($m) use ($image) {
            return $m !== $image;
        }));

        // Update cover if needed
        if ($offer->cover_image === $image) {
            $offer->cover_image = $media[0] ?? null;
        }
        $offer->media = $media;
        $offer->save();

        // Delete physical file (if present)
        try { Storage::disk('public')->delete($image); } catch (\Throwable $e) {}

        // Sync to central
        $tenant = Tenant::on('central')->where('Subdomain', $subdomain)->firstOrFail();
        $sync->upsertToCentral($offer, $tenant);

        return response()->json(['ok' => true, 'media' => $media, 'cover_image' => $offer->cover_image]);
    }
}
