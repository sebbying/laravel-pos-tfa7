<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UserRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $uniqueUsername = Rule::unique('users', 'username');
        if ($user = $this->route('user')) {
            $uniqueUsername->ignore($user);
        }
        return [
            'username' => ['required', 'string', 'max:50', $uniqueUsername],
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'avatar' => [
                'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048',
                'dimensions:max_width=4096,max_height=4096',
            ],
        ];
    }
    public function attributes(): array { return ['full_name' => 'full name']; }
    public function messages(): array
    {
        return [
            'avatar.max' => 'The profile picture must not exceed 2 MB.',
            'avatar.mimes' => 'Choose a JPG, PNG, or WebP image.',
            'avatar.dimensions' => 'The image dimensions must not exceed 4096 by 4096 pixels.',
        ];
    }
}
