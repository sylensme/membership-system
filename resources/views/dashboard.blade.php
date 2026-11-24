@extends('layouts.member')

@section('title', 'Dashboard · Member Desk')
@section('context', 'Membership overview')

@section('content')
    <div class="page-heading">
        <div><h1>Dashboard</h1><p>Membership activity, payment totals, and transactions waiting for action.</p></div>
        <a class="button" href="{{ route('transactions.create') }}">New transaction</a>
    </div>

    <section class="app-stats" aria-label="Membership totals">
        <div class="app-stat"><span>Total members</span><strong>{{ number_format($totalMembers) }}</strong></div>
        <div class="app-stat"><span>Active members</span><strong>{{ number_format($activeMembers) }}</strong></div>
        <div class="app-stat"><span>Pending transactions</span><strong>{{ number_format($pendingTransactions) }}</strong></div>
        <div class="app-stat"><span>Payments collected</span><strong>₱{{ number_format((float) $collectedPayments, 2) }}</strong></div>
    </section>

    @if ($transactionsToReview > 0)
        <div class="review-strip"><span>{{ $transactionsToReview }} transaction{{ $transactionsToReview === 1 ? '' : 's' }} need review or approval</span><a href="{{ route('transactions.index', ['status' => 'Paid']) }}">Review paid transactions →</a></div>
    @endif

    <section class="panel panel-pad" aria-labelledby="recent-title">
        <div class="table-meta"><strong id="recent-title">Recent transactions</strong><a href="{{ route('transactions.index') }}">View all</a></div>
        @if ($recentTransactions->isEmpty())
            <div class="empty-state"><div><div class="empty-state__mark" aria-hidden="true"></div><h2>No transactions yet</h2><p>Create an application or renewal to start tracking membership payments.</p><a class="button" href="{{ route('transactions.create') }}">Create transaction</a></div></div>
        @else
            <div class="table-wrap"><table><thead><tr><th>Reference</th><th>Member</th><th>Type</th><th>Status</th><th>Date</th><th class="num">Amount</th></tr></thead><tbody>
                @foreach ($recentTransactions as $transaction)
                    <tr><td><a href="{{ route('transactions.show', $transaction) }}">{{ $transaction->reference_number }}</a></td><td>{{ $transaction->member->name }}</td><td>{{ $transaction->type }}</td><td><span class="status {{ strtolower($transaction->status) }}">{{ $transaction->status }}</span></td><td>{{ $transaction->created_at->format('M j, Y') }}</td><td class="num">₱{{ number_format((float) $transaction->amount, 2) }}</td></tr>
                @endforeach
            </tbody></table></div>
        @endif
    </section>
@endsection
