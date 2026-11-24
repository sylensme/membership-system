<?php

namespace App\Http\Controllers;

use App\Models\MembershipTransaction;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function create(): View
    {
        $transactions = MembershipTransaction::query()->with(['member', 'payments'])->where('status', 'Pending')->latest()->get();

        return view('payments.create', compact('transactions'));
    }

    public function store(Request $request, MembershipTransaction $transaction): RedirectResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'decimal:0,2', 'min:0.01', 'max:99999999.99'],
            'method' => ['required', 'in:Cash,Bank transfer,Card,Online'],
            'paid_at' => ['required', 'date', 'before_or_equal:now'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $transaction, $data): void {
            $transaction = MembershipTransaction::query()->lockForUpdate()->findOrFail($transaction->id);
            if ($transaction->status !== 'Pending') {
                throw \Illuminate\Validation\ValidationException::withMessages(['payment' => 'Payments can only be added to pending transactions.']);
            }

            $due = (float) $transaction->amount - (float) $transaction->payments()->sum('amount');
            if ((float) $data['amount'] > $due) {
                throw \Illuminate\Validation\ValidationException::withMessages(['amount' => 'Payment cannot exceed the remaining balance of ₱'.number_format($due, 2).'.']);
            }

            do {
                $referenceNumber = 'PAY-'.now()->format('Ymd').'-'.Str::upper(Str::random(8));
            } while (Payment::query()->where('reference_number', $referenceNumber)->exists());

            $transaction->payments()->create($data + [
                'received_by' => $request->user()->id,
                'reference_number' => $referenceNumber,
            ]);

            if ((float) $data['amount'] >= $due) {
                $transaction->update(['status' => 'Paid']);
            }
        });

        return redirect()->route('transactions.show', $transaction)->with('success', 'Payment recorded.');
    }
}
