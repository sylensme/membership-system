@extends('layouts.member')
@section('title', 'Record payment · Member Desk')
@section('context', 'Payment processing')
@section('content')
<div class="page-heading"><div><h1>Record a payment</h1><p>Payments must be applied to a pending membership transaction.</p></div><a class="button ghost" href="{{ route('transactions.index') }}">Transactions</a></div>
<section class="panel panel-pad"><div class="table-wrap"><table><thead><tr><th>Transaction</th><th>Member</th><th>Plan</th><th>Status</th><th class="num">Due</th><th class="num">Paid</th><th class="num">Balance</th><th></th></tr></thead><tbody>
@forelse ($transactions as $transaction)@php($paid = (float) $transaction->payments->sum('amount'))<tr><td><a href="{{ route('transactions.show', $transaction) }}">{{ $transaction->reference_number }}</a></td><td>{{ $transaction->member->name }}</td><td>{{ $transaction->level_name }}</td><td>Pending</td><td class="num">₱{{ number_format((float) $transaction->amount, 2) }}</td><td class="num">₱{{ number_format($paid, 2) }}</td><td class="num">₱{{ number_format(max(0, (float) $transaction->amount - $paid), 2) }}</td><td><a class="button small" href="{{ route('transactions.show', $transaction) }}">Open payment form</a></td></tr>@empty<tr><td colspan="8"><div class="empty-state"><div><h2>No pending balances</h2><p>All transactions have been paid, approved, or cancelled.</p><a class="button" href="{{ route('transactions.create') }}">New transaction</a></div></div></td></tr>@endforelse
</tbody></table></div></section>
@endsection
