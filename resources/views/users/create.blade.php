@extends('layouts.app')
@section('title', 'Create User')
@section('content')
<h1>Create User</h1>
<p>Fields marked * are required.</p>
<form action="{{ route('users.store') }}" method="POST" class="form">
    @include('users._form', ['submitLabel' => 'Create User'])
</form>
@endsection
