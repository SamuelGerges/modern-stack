<?php

namespace App\Http\Requests\API\Auth;

use App\Http\Requests\API\BaseApiRequest;

class RegisterRequest extends BaseApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'     => 'The name field is required.',
            'email.required'    => 'The email field is required.',
            'email.email'       => 'The email must be a valid email address.',
            'email.unique'      => 'This email is already taken.',
            'password.required' => 'The password field is required.',
            'password.min'      => 'The password must be at least 6 characters.',
        ];
    }

}
