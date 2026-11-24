@extends('layouts.member')
@section('title', 'Transactions · Member Desk')
@section('context', 'Membership processing')
@section('content')
<div class="page-heading"><div><h1>Transactions</h1><p>Applications, renewals, payments, and membership approvals.</p></div><div class="actions"><a class="button ghost" href="{{ route('payments.create') }}">Record payment</a><a class="button" href="{{ route('transactions.create') }}">New transaction</a></div></div>
<nav class="filter-tabs" aria-label="Filter transactions"><a href="{{ route('transactions.index') }}" @if (!$status) aria-current="page" @endif>All</a>@foreach (['Pending', 'Paid', 'Approved', 'Expired', 'Cancelled'] as $state)<a href="{{ route('transactions.index', ['status' => $state]) }}" @if ($status === $state) aria-current="page" @endif>{{ $state }}</a>@endforeach</nav>
<section class="panel panel-pad"><div class="table-wrap"><table><thead><tr><th>Reference</th><th>Member</th><th>Plan</th><th>Type</th><th>Status</th><th>Date</th><th class="num">Amount</th><th class="num">Paid</th></tr></thead><tbody>
@forelse ($transactions as $transaction)<tr><td><a href="{{ route('transactions.show', $transaction) }}">{{ $transaction->reference_number }}</a></td><td><a href="{{ route('members.show', $transaction->member) }}">{{ $transaction->member->name }}</a><br><small>{{ $transaction->member->membership_number }}</small></td><td>{{ $transaction->category_name }} · {{ $transaction->level_name }}</td><td>{{ $transaction->type }}</td><td><span class="status {{ strtolower($transaction->status) }}">{{ $transaction->status }}</span></td><td>{{ $transaction->created_at->format('M j, Y') }}</td><td class="num">₱{{ number_format((float) $transaction->amount, 2) }}</td><td class="num">₱{{ number_format((float) $transaction->payments->sum('amount'), 2) }}</td></tr>
@empty<tr><td colspan="8"><div class="empty-state"><div><h2>No transactions found</h2><p>Create an application or renewal to get started.</p><a class="button" href="{{ route('transactions.create') }}">New transaction</a></div></div></td></tr>@endforelse
</tbody></table></div></section>
@endsection
