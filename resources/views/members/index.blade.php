@extends('layouts.member')

@section('title', 'Members · Member Desk')
@section('context', 'Member directory')

@section('content')
    @php
        $activeMembers = $members->where('is_active', true)->count();
        $inactiveMembers = $members->count() - $activeMembers;
        $categoriesInUse = $members->pluck('membership_category_id')->unique()->count();
    @endphp

    <div class="page-heading">
        <div>
            <h1>Members</h1>
            <p>{{ $members->count() }} {{ Str::plural('profile', $members->count()) }} in the current view. Keep status, level, and membership terms clear.</p>
        </div>
        <a class="button" href="{{ route('members.create') }}">Add member</a>
    </div>

    <section class="app-stats" aria-label="Membership summary">
        <div class="app-stat"><span>Profiles</span><strong>{{ $members->count() }}</strong></div>
        <div class="app-stat"><span>Active</span><strong>{{ $activeMembers }}</strong></div>
        <div class="app-stat"><span>Categories</span><strong>{{ $categoriesInUse }}</strong></div>
        <div class="app-stat"><span>Inactive</span><strong>{{ $inactiveMembers }}</strong></div>
    </section>

    <form class="filter-bar" action="{{ route('members.index') }}" method="GET">
        <div class="field">
            <label for="name">Search by name</label>
            <input id="name" name="name" type="search" value="{{ $filters['name'] }}" placeholder="Enter a member name">
        </div>
        <div class="field">
            <label for="category">Category</label>
            <select id="category" name="category">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) ($filters['category'] ?? '') === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="level">Level</label>
            <select id="level" name="level">
                <option value="">All levels</option>
                @foreach ($levels as $level)
                    <option value="{{ $level->id }}" @selected((string) ($filters['level'] ?? '') === (string) $level->id)>{{ $level->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="actions">
            <button class="button" type="submit">Apply</button>
            <a class="button ghost" href="{{ route('members.index') }}">Clear</a>
        </div>
    </form>

    <section class="panel panel-pad" aria-labelledby="member-list-title">
        <div class="table-meta">
            <strong id="member-list-title">Member directory</strong>
            <span>{{ $activeMembers }} active · {{ $inactiveMembers }} inactive</span>
        </div>

        @if ($members->isEmpty())
            <div class="empty-state">
                <div>
                    <div class="empty-state__mark" aria-hidden="true"></div>
                    <h2>No members in this view</h2>
                    <p>Add the first profile, or clear the filters to return to the full directory.</p>
                    <a class="button" href="{{ route('members.create') }}">Add member</a>
                </div>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Level</th>
                            <th class="num">Fee</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $member)
                            <tr>
                                <td><strong><a href="{{ route('members.show', $member) }}">{{ $member->name }}</a></strong><br><small>{{ $member->membership_number }} · {{ $member->email }}</small></td>
                                <td>{{ $member->category->name }}</td>
                                <td>{{ $member->level->name }}</td>
                                <td class="num">₱{{ number_format((float) $member->level->fee, 2) }}</td>
                                <td><span class="status {{ $member->is_active ? '' : 'inactive' }}">{{ $member->is_active ? 'Active' : 'Inactive' }}</span></td>
                                <td>
                                    <div class="actions">
                                        <a class="button small ghost" href="{{ route('members.show', $member) }}">View</a>
                                        <a class="button small secondary" href="{{ route('members.edit', $member) }}">Edit</a>
                                        @if ($member->is_active)
                                            <form action="{{ route('members.deactivate', $member) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="button small warning" type="submit">Deactivate</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section class="setup-strip" aria-labelledby="setup-title">
        <div>
            <h2 id="setup-title">Membership setup</h2>
            <p>The directory supports four categories and four membership levels.</p>
        </div>
        <div class="setup-strip__values">
            <span>Student</span><span>Regular</span><span>Senior Citizen</span><span>Corporate</span>
        </div>
    </section>
@endsection
