@extends('layouts.app')
@section('title', 'Create Customer')
@section('content')
<h1>Create Customer</h1>
<p>Fields marked * are required.</p>
<form action="{{ route('customers.store') }}" method="POST" class="form">
    @include('customers._form', ['submitLabel' => 'Create Customer'])
</form>
@endsection
