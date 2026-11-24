@extends('layouts.member')
@section('title', 'Applications · Member Desk')
@section('context', 'Membership applications')
@section('content')
<div class="page-heading"><div><p class="eyebrow">Applicant review</p><h1>Applications</h1><p>Public applications are linked to member records and their pending transactions.</p></div><a class="button secondary" href="{{ route('applications.create') }}" target="_blank">Open application form</a></div>
<section class="panel panel-pad"><div class="table-wrap"><table><thead><tr><th>Reference</th><th>Applicant</th><th>Plan</th><th>Location</th><th>Submitted</th><th>Status</th></tr></thead><tbody>
@forelse ($applications as $application)<tr><td><a href="{{ route('applications.show', $application) }}">{{ $application->reference_number }}</a></td><td>{{ $application->member->name }}<br><small>{{ $application->member->email }}</small></td><td>{{ $application->transaction->category_name }} · {{ $application->transaction->level_name }}</td><td>{{ $application->latitude !== null ? number_format($application->latitude, 4).', '.number_format($application->longitude, 4) : 'Not selected' }}</td><td>{{ $application->created_at->format('M j, Y') }}</td><td><span class="status {{ strtolower($application->transaction->status) }}">{{ $application->transaction->status }}</span></td></tr>
@empty<tr><td colspan="6"><div class="empty-state"><div><h2>No applications yet</h2><p>Share the application form link with prospective members.</p><a class="button" href="{{ route('applications.create') }}" target="_blank">Open application form</a></div></div></td></tr>@endforelse
</tbody></table></div></section>
@endsection
