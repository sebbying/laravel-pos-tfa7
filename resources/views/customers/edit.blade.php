@extends('layouts.app')
@section('title', 'Edit Customer')
@section('content')
<h1>Edit Customer</h1>
<p>Fields marked * are required.</p>
<form action="{{ route('customers.update', $customer) }}" method="POST"  class="form">
    @method('PUT')
    @include('customers._form', ['submitLabel' => 'Save Changes'])
</form>
@endsection
