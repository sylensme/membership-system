@extends('layouts.member')
@section('title', 'Edit level · Member Desk')
@section('context', 'Membership setup')
@section('content')<div class="page-heading"><div><h1>Edit level</h1><p>Price changes apply to future transactions; existing references keep their recorded amount.</p></div><a class="button ghost" href="{{ route('levels.index') }}">Back</a></div><form class="card form-panel" action="{{ route('levels.update', $level) }}" method="POST">@csrf @method('PUT') @include('levels._form', ['level' => $level])<button class="button" type="submit">Save changes</button></form>@endsection
