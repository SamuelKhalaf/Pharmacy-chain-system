<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string name
 * @property string email
 * @property string password
 * @property int role_id
 * @property int|null branch_id
 */
class CreateModeratorRequest extends FormRequest
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
            'name' => 'required|min:4|max:50|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role_id' => ['required',Rule::in(UserRole::values())],
            'branch_id' => 'nullable|exists:branches,id'
        ];
    }
}
