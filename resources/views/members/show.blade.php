@extends('layouts.member')

@section('title', $member->name.' · Member Desk')
@section('context', 'Member profile')

@section('content')
    <div class="page-heading">
        <div>
            <h1>{{ $member->name }}</h1>
            <p>{{ $member->category->name }} member since {{ $member->created_at->format('F Y') }}.</p>
        </div>
        <div class="actions">
            <a class="button ghost" href="{{ route('members.index') }}">Back</a>
            <a class="button" href="{{ route('members.edit', $member) }}">Edit details</a>
        </div>
    </div>

    <section class="panel panel-pad profile-card" aria-labelledby="profile-details-title">
        <div class="profile-identity">
            <span class="profile-identity__initial">{{ Str::of($member->name)->explode(' ')->map(fn ($part) => Str::substr($part, 0, 1))->take(2)->implode('') }}</span>
            <h2 id="profile-details-title">{{ $member->name }}</h2>
            <span class="status {{ $member->is_active ? '' : 'inactive' }}">{{ $member->is_active ? 'Active' : 'Inactive' }}</span>
            <p>{{ $member->membership_number }}</p>
            <p>{{ $member->category->name }} · {{ $member->level->name }}</p>
        </div>

        <dl class="profile">
            <dt>Email</dt><dd>{{ $member->email }}</dd>
            <dt>Phone</dt><dd>{{ $member->phone ?: '—' }}</dd>
            <dt>Membership fee</dt><dd>₱{{ number_format((float) $member->level->fee, 2) }}</dd>
            <dt>Validity period</dt><dd>{{ $member->level->validity_months }} months</dd>
            <dt>Benefits</dt><dd>{{ $member->level->benefits ?: '—' }}</dd>
            <dt>Address</dt><dd>{{ $member->address ?: '—' }}</dd>
            <dt>Member since</dt><dd>{{ $member->created_at->format('F j, Y') }}</dd>
        </dl>
    </section>

    <section class="panel panel-pad" aria-labelledby="history-title">
        <div class="table-meta"><strong id="history-title">Transaction history</strong><a class="button small" href="{{ route('transactions.create', ['member_id' => $member->id]) }}">New application or renewal</a></div>
        @if ($member->transactions->isEmpty())
            <p class="muted">No applications or renewals recorded yet.</p>
        @else
            <div class="table-wrap"><table>
                <thead><tr><th>Reference</th><th>Date</th><th>Type</th><th>Status</th><th class="num">Amount</th><th class="num">Paid</th></tr></thead>
                <tbody>@foreach ($member->transactions->sortByDesc('created_at') as $transaction)
                    <tr><td><a href="{{ route('transactions.show', $transaction) }}">{{ $transaction->reference_number }}</a></td><td>{{ $transaction->created_at->format('M j, Y') }}</td><td>{{ $transaction->type }}</td><td><span class="status {{ strtolower($transaction->status) }}">{{ $transaction->status }}</span></td><td class="num">₱{{ number_format((float) $transaction->amount, 2) }}</td><td class="num">₱{{ number_format((float) $transaction->payments->sum('amount'), 2) }}</td></tr>
                @endforeach</tbody>
            </table></div>
        @endif
    </section>

    <section class="panel panel-pad" aria-labelledby="membership-actions-title">
        <div class="page-heading">
            <div>
                <h2 id="membership-actions-title">Membership actions</h2>
                <p>Deactivation keeps this profile in the directory. Deletion removes it permanently.</p>
            </div>
            <div class="actions">
                @if ($member->is_active)
                    <form action="{{ route('members.deactivate', $member) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="button warning" type="submit">Deactivate member</button>
                    </form>
                @endif
                <form action="{{ route('members.destroy', $member) }}" method="POST" onsubmit="return confirm('Delete {{ addslashes($member->name) }} permanently?')">
                    @csrf
                    @method('DELETE')
                    <button class="button danger" type="submit">Delete member</button>
                </form>
            </div>
        </div>
    </section>
@endsection
