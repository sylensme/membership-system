@extends('layouts.member')

@section('title', 'Edit Member')

@section('content')
    <div class="page-heading">
        <h1>Edit member</h1>
        <a class="button secondary" href="{{ route('members.show', $member) }}">Cancel</a>
    </div>

    <form class="card form-panel" action="{{ route('members.update', $member) }}" method="POST">
        @csrf
        @method('PUT')
        @include('members._form', ['member' => $member, 'categories' => $categories, 'levels' => $levels])
        <button class="button" type="submit">Update member</button>
    </form>
@endsection
