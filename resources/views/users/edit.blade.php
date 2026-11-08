@extends('layouts.app')
@section('title', 'Edit User')
@section('content')
<h1>Edit User</h1>
<p>Fields marked * are required.</p>
<form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="form">
    @method('PUT')
    @include('users._form', ['submitLabel' => 'Save Changes'])
</form>
@endsection
