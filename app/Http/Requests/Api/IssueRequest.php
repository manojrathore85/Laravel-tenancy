<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class IssueRequest extends FormRequest
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
            'project_id' => 'required|exists:projects,id',
            //'created_by' => 'required|exists:users,id',
            'issue_type' => 'required',
            'severity'  => 'required',
            'summery'=> 'required|string|max:255',
            'assigned_to' => 'required|exists:users,id',
            'description' => 'required|string|max:1000',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',           
        ];
    }
}
