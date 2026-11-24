<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipTransaction;
use App\Models\Payment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        MembershipTransaction::query()->where('status', 'Approved')->whereNotNull('valid_until')->where('valid_until', '<', now())->update(['status' => 'Expired']);

        return view('dashboard', [
            'totalMembers' => Member::query()->count(),
            'activeMembers' => Member::query()->where('is_active', true)->count(),
            'pendingTransactions' => MembershipTransaction::query()->where('status', 'Pending')->count(),
            'collectedPayments' => Payment::query()->sum('amount'),
            'recentTransactions' => MembershipTransaction::query()->with('member')->latest()->limit(8)->get(),
            'transactionsToReview' => MembershipTransaction::query()->whereIn('status', ['Pending', 'Paid'])->count(),
        ]);
    }
}
