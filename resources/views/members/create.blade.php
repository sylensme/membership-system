@extends('layouts.member')

@section('title', 'Add Member')

@section('content')
    <div class="page-heading">
        <h1>Add new member</h1>
        <a class="button secondary" href="{{ route('members.index') }}">Back to members</a>
    </div>

    <form class="card form-panel" action="{{ route('members.store') }}" method="POST">
        @csrf
        @include('members._form', ['member' => null, 'categories' => $categories, 'levels' => $levels])
        <button class="button" type="submit">Save member</button>
    </form>
@endsection
