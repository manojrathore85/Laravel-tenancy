<?php

namespace App\Http\Requests;

use App\Models\Tenant;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required','string','email', 'max:255','unique:tenants,email'],        
            'domain' => ['required','string','regex:/^[a-zA-Z0-9-]{3,50}$/','max:255','unique:domains,domain'],        
            'gender' => ['required', 'in:Male,Female'],
            'phone' => ['required','digits:10','numeric','unique:tenants,phone']
        ];
        
    }
    public function messages()
    {   
        return [
            'domain.regex' => 'valid domain can be content of (a–z, 0–9, hyphens only)',
        ];
    }
}
