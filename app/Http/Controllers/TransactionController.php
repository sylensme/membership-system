<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\MembershipLevel;
use App\Models\MembershipTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        MembershipTransaction::query()->where('status', 'Approved')->whereNotNull('valid_until')->where('valid_until', '<', now())->update(['status' => 'Expired']);
        $status = $request->query('status');
        $transactions = MembershipTransaction::query()->with(['member', 'payments'])
            ->when(in_array($status, ['Pending', 'Paid', 'Approved', 'Expired', 'Cancelled'], true), fn (Builder $query) => $query->where('status', $status))
            ->latest()->get();

        return view('transactions.index', compact('transactions', 'status'));
    }

    public function create(): View
    {
        return view('transactions.create', [
            'members' => Member::query()->where('is_active', true)->orderBy('name')->get(),
            'categories' => MembershipCategory::query()->where('is_active', true)->orderBy('name')->get(),
            'levels' => MembershipLevel::query()->where('is_active', true)->orderBy('fee')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'membership_category_id' => ['required', 'exists:membership_categories,id'],
            'membership_level_id' => ['required', 'exists:membership_levels,id'],
            'type' => ['required', 'in:Application,Renewal'],
        ]);

        $member = Member::query()->findOrFail($data['member_id']);
        if (! $member->is_active) {
            return back()->withInput()->withErrors(['member_id' => 'Reactivate this member before creating a transaction.']);
        }

        $category = MembershipCategory::query()->findOrFail($data['membership_category_id']);
        $level = MembershipLevel::query()->findOrFail($data['membership_level_id']);
        if (! $category->is_active || ! $level->is_active) {
            return back()->withInput()->withErrors(['membership_level_id' => 'Choose an active category and level.']);
        }

        $transaction = MembershipTransaction::create([
            'member_id' => $member->id,
            'membership_category_id' => $category->id,
            'membership_level_id' => $level->id,
            'created_by' => $request->user()->id,
            'reference_number' => $this->referenceNumber('TXN'),
            'type' => $data['type'],
            'status' => 'Pending',
            'category_name' => $category->name,
            'level_name' => $level->name,
            'amount' => $level->fee,
            'validity_months' => $level->validity_months,
            'benefits' => $level->benefits,
        ]);

        return redirect()->route('transactions.show', $transaction)->with('success', 'Transaction created. Record payment to continue.');
    }

    public function show(MembershipTransaction $transaction): View
    {
        $transaction->load(['member', 'payments']);

        return view('transactions.show', compact('transaction'));
    }

    public function approve(MembershipTransaction $transaction): RedirectResponse
    {
        if ($transaction->status !== 'Paid' || (float) $transaction->payments()->sum('amount') < (float) $transaction->amount) {
            return back()->withErrors(['transaction' => 'Only fully paid transactions can be approved.']);
        }

        DB::transaction(function () use ($transaction): void {
            $startsAt = now();
            $transaction->update([
                'status' => 'Approved',
                'approved_at' => $startsAt,
                'valid_from' => $startsAt,
                'valid_until' => $startsAt->copy()->addMonths($transaction->validity_months),
            ]);
            $transaction->member()->update([
                'membership_category_id' => $transaction->membership_category_id,
                'membership_level_id' => $transaction->membership_level_id,
                'is_active' => true,
            ]);
        });

        return back()->with('success', 'Paid transaction approved and membership activated.');
    }

    public function cancel(MembershipTransaction $transaction): RedirectResponse
    {
        if ($transaction->status !== 'Pending') {
            return back()->withErrors(['transaction' => 'Only pending transactions can be cancelled.']);
        }

        $transaction->update(['status' => 'Cancelled']);

        return back()->with('success', 'Transaction cancelled.');
    }

    public function receipt(MembershipTransaction $transaction): View
    {
        $transaction->load(['member', 'payments']);

        return view('transactions.receipt', compact('transaction'));
    }

    private function referenceNumber(string $prefix): string
    {
        do {
            $referenceNumber = $prefix.'-'.now()->format('Ymd').'-'.Str::upper(Str::random(8));
        } while (MembershipTransaction::query()->where('reference_number', $referenceNumber)->exists());

        return $referenceNumber;
    }
}
