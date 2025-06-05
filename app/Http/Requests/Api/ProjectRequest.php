<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Tenant\Project;

class ProjectRequest extends FormRequest
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
        $project = $this->route('project') ? $this->route('project') : null;
        //dd($this->route('project'));
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['string','max:5', 'unique:projects,code,' . $project],
            'project_lead' => ['required'],
            'status' => ['required', 'in:0,1'],
        ];
    }
}
