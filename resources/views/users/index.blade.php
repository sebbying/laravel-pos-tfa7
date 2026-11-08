@extends('layouts.app')
@section('title', 'User Accounts')
@section('content')
<div class="page-heading"><h1>User Accounts</h1><a class="button" href="{{ route('users.create') }}">Create User</a></div>
<div class="table-wrap"><table><thead><tr><th>Avatar</th><th>ID</th><th>Username</th><th>Full name</th><th>Email</th><th>Action</th></tr></thead><tbody>
@forelse($users as $user)
<tr><td>@include('users._avatar', ['user' => $user])</td><td>{{ $user->id }}</td><td>{{ $user->username }}</td><td>{{ $user->full_name }}</td><td>{{ $user->email ?: '—' }}</td><td><a href="{{ route('users.edit', $user) }}">Edit</a></td></tr>
@empty<tr><td colspan="6">No users yet.</td></tr>@endforelse
</tbody></table></div>
@include('partials.pagination', ['records' => $users])
@endsection
