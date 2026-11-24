@extends('layouts.member')
@section('title', 'Add category · Member Desk')
@section('context', 'Membership setup')
@section('content')<div class="page-heading"><div><h1>Add category</h1><p>Create a category for membership records.</p></div><a class="button ghost" href="{{ route('categories.index') }}">Back</a></div><form class="card form-panel" action="{{ route('categories.store') }}" method="POST">@csrf @include('categories._form', ['category' => null])<button class="button" type="submit">Save category</button></form>@endsection
