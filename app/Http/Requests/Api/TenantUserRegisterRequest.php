<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Tenant\User as TenantUser;

class TenantUserRegisterRequest extends FormRequest
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
        $userId = $this->route('id') ? $this->route('id') : null;
        $isProfileUpdate = $this->routeIs('profile.update');
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(TenantUser::class)->ignore($userId)
            ],
            'role' => $isProfileUpdate ? ['nullable', 'string'] : ['required', 'string'], // Role required only if not a profile update
            'gender' => ['required', 'in:Male,Female'],
            'phone' => [
                'required',
                'numeric',
                Rule::unique(TenantUser::class)->ignore($userId)
            ],
        ];
    }
}
