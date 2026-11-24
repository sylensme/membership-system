@extends('layouts.member')
@section('title', 'Edit category · Member Desk')
@section('context', 'Membership setup')
@section('content')<div class="page-heading"><div><h1>Edit category</h1><p>Changes apply to future applications and renewals.</p></div><a class="button ghost" href="{{ route('categories.index') }}">Back</a></div><form class="card form-panel" action="{{ route('categories.update', $category) }}" method="POST">@csrf @method('PUT') @include('categories._form', ['category' => $category])<button class="button" type="submit">Save changes</button></form>@endsection
