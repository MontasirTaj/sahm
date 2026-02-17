<?php

namespace App\Http\Controllers;

use App\Models\Central\Buyer;
use App\Models\Central\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuyerWalletController extends Controller
{
    /**
     * Financial Dashboard - عرض المحفظة والتفاصيل المالية
     */
    public function index()
    {
        $user = Auth::guard('web')->user();
        $buyer = Buyer::on('central')->where('user_id', $user->id)->first();

        if (!$buyer) {
            return redirect()->route('buyer.dashboard')
                ->withErrors(['error' => 'يجب إنشاء حساب مشتري أولاً']);
        }

        // Get or create wallet
        $wallet = $buyer->getOrCreateWallet();

        // Get recent transactions (last 20)
        $recentTransactions = WalletTransaction::on('central')
            ->where('buyer_id', $buyer->id)
            ->with(['saleOffer.originalOffer', 'relatedBuyer'])
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        // Get all transactions with pagination
        $transactions = WalletTransaction::on('central')
            ->where('buyer_id', $buyer->id)
            ->with(['saleOffer.originalOffer', 'relatedBuyer'])
            ->orderByDesc('created_at')
            ->paginate(15);

        // Statistics
        $stats = [
            'current_balance' => $wallet->balance,
            'available_balance' => $wallet->available_balance,
            'pending_balance' => $wallet->pending_balance,
            'total_deposits' => $wallet->total_deposits,
            'total_withdrawals' => $wallet->total_withdrawals,
            'total_transactions' => WalletTransaction::on('central')->where('buyer_id', $buyer->id)->count(),
        ];

        // Transactions by type
        $transactionsByType = WalletTransaction::on('central')
            ->select('type', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->where('buyer_id', $buyer->id)
            ->where('status', 'completed')
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        // Monthly summary (last 6 months)
        $monthlySummary = WalletTransaction::on('central')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN type IN ("deposit", "sale") THEN amount ELSE 0 END) as deposits'),
                DB::raw('SUM(CASE WHEN type IN ("withdrawal", "purchase") THEN amount ELSE 0 END) as withdrawals')
            )
            ->where('buyer_id', $buyer->id)
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        return view('buyer.wallet', compact(
            'buyer',
            'wallet',
            'recentTransactions',
            'transactions',
            'stats',
            'transactionsByType',
            'monthlySummary'
        ));
    }

    /**
     * إيداع في المحفظة
     */
    public function deposit(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:bank_transfer,credit_card',
            'reference' => 'nullable|string|max:255',
        ]);

        $user = Auth::guard('web')->user();
        $buyer = Buyer::on('central')->where('user_id', $user->id)->first();

        if (!$buyer) {
            return redirect()->back()->withErrors(['error' => 'حساب المشتري غير موجود']);
        }

        $wallet = $buyer->getOrCreateWallet();

        DB::beginTransaction();

        try {
            // إيداع المبلغ في المحفظة
            $description = "إيداع في المحفظة";
            if ($data['payment_method'] === 'bank_transfer') {
                $description .= " - تحويل بنكي";
            } else {
                $description .= " - بطاقة ائتمانية";
            }
            
            if (!empty($data['reference'])) {
                $description .= " (رقم المرجع: {$data['reference']})";
            }

            $wallet->deposit(
                $data['amount'],
                $description,
                [
                    'payment_method' => $data['payment_method'],
                    'reference' => $data['reference'] ?? null,
                ]
            );

            DB::commit();

            return redirect()->back()->with('success', 'تم إيداع ' . number_format($data['amount'], 2) . ' ريال في محفظتك بنجاح!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * سحب من المحفظة
     */
    public function withdraw(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
            'bank_account' => 'required|string',
            'iban' => 'required|string',
        ]);

        $user = Auth::guard('web')->user();
        $buyer = Buyer::on('central')->where('user_id', $user->id)->first();

        if (!$buyer) {
            return redirect()->back()->withErrors(['error' => 'حساب المشتري غير موجود']);
        }

        $wallet = $buyer->wallet;

        if (!$wallet) {
            return redirect()->back()->withErrors(['error' => 'المحفظة غير موجودة']);
        }

        DB::beginTransaction();

        try {
            $wallet->withdraw(
                $data['amount'],
                "طلب سحب إلى حساب بنكي: {$data['iban']}",
                [
                    'bank_account' => $data['bank_account'],
                    'iban' => $data['iban'],
                    'status' => 'pending_approval',
                ]
            );

            DB::commit();

            return redirect()->back()->with('success', 'تم تسجيل طلب السحب بنجاح. سيتم مراجعته وتحويل المبلغ خلال 1-3 أيام عمل.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
