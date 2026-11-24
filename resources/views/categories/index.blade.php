@extends('layouts.member')
@section('title', 'Categories · Member Desk')
@section('context', 'Membership setup')
@section('content')
<div class="page-heading"><div><h1>Membership categories</h1><p>Define the member groups available during registration and renewal.</p></div><a class="button" href="{{ route('categories.create') }}">Add category</a></div>
<section class="panel panel-pad"><div class="table-wrap"><table><thead><tr><th>Category</th><th>Description</th><th>Members</th><th>Availability</th><th>Actions</th></tr></thead><tbody>
@forelse ($categories as $category)<tr><td><strong>{{ $category->name }}</strong></td><td>{{ $category->description ?: '—' }}</td><td>{{ $category->members_count }}</td><td><span class="status {{ $category->is_active ? '' : 'inactive' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td><td><div class="actions"><a class="button small secondary" href="{{ route('categories.edit', $category) }}">Edit</a>@if ($category->members_count === 0)<form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">@csrf @method('DELETE')<button class="button small danger" type="submit">Delete</button></form>@endif</div></td></tr>
@empty<tr><td colspan="5">No categories have been created.</td></tr>@endforelse
</tbody></table></div></section>
@endsection
