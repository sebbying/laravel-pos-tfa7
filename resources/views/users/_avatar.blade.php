@php
    $avatarUrl = $user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->avatar)
        : asset('images/avatar-placeholder.svg');
@endphp
<img class="avatar" src="{{ $avatarUrl }}" alt="Profile picture of {{ $user->full_name }}" width="64" height="64" loading="lazy">
