<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
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
        $plan = $this->route('plan') ? $this->route('plan') : null;
        return [
            'title' => 'required|min:3|max:50|unique:plans,title,' . $plan,
            'subtitle' => 'required|min:3|max:50',
            'price' => 'required|numeric|min:1',
            'no_of_user' => 'required|numeric|min:3|max:50',
            'no_of_project' => 'required|numeric|min:3|max:50',
            'whats_up_intigration' => 'required|in:true,false,0,1',
            'sms_intigration' => 'required|in:true,false,0,1',
            'status' => 'required|in:0,1',
        ];
    }
}
