@csrf
<div class="field"><label for="username">Username *</label><input id="username" name="username" value="{{ old('username', $user->username ?? '') }}" maxlength="50" required autocomplete="username" @error('username') aria-invalid="true" aria-describedby="username-error" @enderror>@error('username')<p id="username-error" class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="full_name">Full name *</label><input id="full_name" name="full_name" value="{{ old('full_name', $user->full_name ?? '') }}" maxlength="100" required autocomplete="name" @error('full_name') aria-invalid="true" aria-describedby="full-name-error" @enderror>@error('full_name')<p id="full-name-error" class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" maxlength="150" autocomplete="email">@error('email')<p class="error">{{ $message }}</p>@enderror</div>
@isset($user)
<div class="field"><label for="avatar">Profile picture</label>
    @include('users._avatar', ['user' => $user])
    <input id="avatar" name="avatar" type="file" accept="image/jpeg,image/png,image/webp" aria-describedby="avatar-help @error('avatar') avatar-error @enderror">
    <p id="avatar-help" class="muted">JPG, PNG, or WebP. Maximum 2 MB and 4096 × 4096 pixels. Saved as a 256 × 256 JPEG thumbnail. Leave blank to keep the current picture. After a validation error, select the file again.</p>
    @error('avatar')<p id="avatar-error" class="error">{{ $message }}</p>@enderror
</div>
@endisset
<div class="actions"><button type="submit">{{ $submitLabel }}</button><a href="{{ route('users.index') }}">Cancel</a></div>
