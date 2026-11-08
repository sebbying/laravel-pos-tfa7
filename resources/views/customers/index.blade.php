@extends('layouts.app')
@section('title', 'Customers')
@section('content')
<div class="page-heading"><h1>Customers</h1><a class="button" href="{{ route('customers.create') }}">Create Customer</a></div>
<div class="table-wrap"><table><thead><tr><th>ID</th><th>Full name</th><th>Email</th><th>Phone</th><th>Address</th><th>Action</th></tr></thead><tbody>
@forelse($customers as $customer)
<tr><td>{{ $customer->id }}</td><td>{{ $customer->full_name }}</td><td>{{ $customer->email }}</td><td>{{ $customer->phone ?: '—' }}</td><td>{{ $customer->address ?: '—' }}</td><td><a href="{{ route('customers.edit', $customer) }}">Edit</a></td></tr>
@empty<tr><td colspan="6">No customers yet.</td></tr>@endforelse
</tbody></table></div>
@include('partials.pagination', ['records' => $customers])
@endsection
