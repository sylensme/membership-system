@extends('layouts.member')
@section('title', 'Levels · Member Desk')
@section('context', 'Membership setup')
@section('content')
<div class="page-heading"><div><h1>Membership levels</h1><p>Set the fee, validity period, and benefits used for each application or renewal.</p></div><a class="button" href="{{ route('levels.create') }}">Add level</a></div>
<section class="panel panel-pad"><div class="table-wrap"><table><thead><tr><th>Level</th><th class="num">Fee</th><th>Validity</th><th>Benefits</th><th>Members</th><th>Availability</th><th>Actions</th></tr></thead><tbody>
@forelse ($levels as $level)<tr><td><strong>{{ $level->name }}</strong></td><td class="num">₱{{ number_format((float) $level->fee, 2) }}</td><td>{{ $level->validity_months }} months</td><td>{{ $level->benefits ?: '—' }}</td><td>{{ $level->members_count }}</td><td><span class="status {{ $level->is_active ? '' : 'inactive' }}">{{ $level->is_active ? 'Active' : 'Inactive' }}</span></td><td><div class="actions"><a class="button small secondary" href="{{ route('levels.edit', $level) }}">Edit</a>@if ($level->members_count === 0)<form action="{{ route('levels.destroy', $level) }}" method="POST" onsubmit="return confirm('Delete this level?')">@csrf @method('DELETE')<button class="button small danger" type="submit">Delete</button></form>@endif</div></td></tr>
@empty<tr><td colspan="7">No levels have been created.</td></tr>@endforelse
</tbody></table></div></section>
@endsection
