@extends('layouts.member')
@section('title', 'New transaction · Member Desk')
@section('context', 'Membership processing')
@section('content')
<div class="page-heading"><div><h1>New application or renewal</h1><p>Choose the member and plan. The fee and benefits are saved with the transaction.</p></div><a class="button ghost" href="{{ route('transactions.index') }}">Back</a></div>
<form class="card form-panel" action="{{ route('transactions.store') }}" method="POST">@csrf
<div class="grid"><div class="field"><label for="member_id">Member</label><select id="member_id" name="member_id" required><option value="">Select a member</option>@foreach ($members as $member)<option value="{{ $member->id }}" @selected((string) old('member_id', request('member_id')) === (string) $member->id)>{{ $member->name }} · {{ $member->membership_number }}</option>@endforeach</select>@error('member_id')<p class="error-text">{{ $message }}</p>@enderror</div>
<div class="field"><label for="type">Transaction type</label><select id="type" name="type" required><option value="Application" @selected(old('type') === 'Application')>New application</option><option value="Renewal" @selected(old('type') === 'Renewal')>Renewal</option></select>@error('type')<p class="error-text">{{ $message }}</p>@enderror</div>
<div class="field"><label for="membership_category_id">Category</label><select id="membership_category_id" name="membership_category_id" required><option value="">Select a category</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected((string) old('membership_category_id') === (string) $category->id)>{{ $category->name }}</option>@endforeach</select>@error('membership_category_id')<p class="error-text">{{ $message }}</p>@enderror</div>
<div class="field"><label for="membership_level_id">Level and fee</label><select id="membership_level_id" name="membership_level_id" required><option value="">Select a level</option>@foreach ($levels as $level)<option value="{{ $level->id }}" @selected((string) old('membership_level_id') === (string) $level->id)>{{ $level->name }} · ₱{{ number_format((float) $level->fee, 2) }} / {{ $level->validity_months }} months</option>@endforeach</select>@error('membership_level_id')<p class="error-text">{{ $message }}</p>@enderror</div></div>
<button class="button" type="submit">Create transaction</button></form>
@endsection
