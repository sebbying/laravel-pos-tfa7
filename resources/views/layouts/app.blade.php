<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laravel POS') | TFA7</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header><div class="shell"><strong>Laravel POS</strong><nav aria-label="Main navigation"><a href="{{ route('customers.index') }}">Customers</a><a href="{{ route('users.index') }}">User Accounts</a></nav></div></header>
<main class="shell">
    @if(session('success'))<p class="success" role="status">{{ session('success') }}</p>@endif
    @if($errors->any())
        <div class="errors" role="alert"><strong>Please correct the following:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    @yield('content')
</main>
</body>
</html>
