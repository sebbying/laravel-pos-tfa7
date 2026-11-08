<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class CustomerRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
        ];
    }
    public function attributes(): array { return ['full_name' => 'full name']; }
}
