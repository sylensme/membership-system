@extends('layouts.member')
@section('title', 'Add level · Member Desk')
@section('context', 'Membership setup')
@section('content')<div class="page-heading"><div><h1>Add level</h1><p>Fee and validity are copied into each transaction.</p></div><a class="button ghost" href="{{ route('levels.index') }}">Back</a></div><form class="card form-panel" action="{{ route('levels.store') }}" method="POST">@csrf @include('levels._form', ['level' => null])<button class="button" type="submit">Save level</button></form>@endsection
