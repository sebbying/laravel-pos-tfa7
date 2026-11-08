<?php
namespace App\Http\Controllers;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\AvatarService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;
class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index', ['users' => User::orderBy('id', 'desc')->paginate(10)]);
    }
    public function create(): View { return view('users.create'); }
    public function store(UserRequest $request): RedirectResponse
    {
        User::create($request->safe()->only(['username', 'full_name', 'email']));
        return to_route('users.index')->with('success', 'User created successfully. Edit the user to upload a profile picture.');
    }
    public function edit(User $user): View { return view('users.edit', compact('user')); }
    public function update(UserRequest $request, User $user, AvatarService $avatars): RedirectResponse
    {
        $data = $request->safe()->only(['username', 'full_name', 'email']);
        $oldAvatar = $user->avatar;
        $newAvatar = null;
        if ($request->hasFile('avatar')) {
            $newAvatar = $avatars->store($request->file('avatar'));
            $data['avatar'] = $newAvatar;
        }
        try {
            DB::transaction(fn () => $user->update($data));
        } catch (Throwable $exception) {
            if ($newAvatar) { Storage::disk('public')->delete($newAvatar); }
            throw $exception;
        }
        // Delete the previous file only after the database update succeeds.
        if ($newAvatar && $oldAvatar && str_starts_with($oldAvatar, 'avatars/')) {
            Storage::disk('public')->delete($oldAvatar);
        }
        return to_route('users.index')->with('success', 'User updated successfully.');
    }
}
