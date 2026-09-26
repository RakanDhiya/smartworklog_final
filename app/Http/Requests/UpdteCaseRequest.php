<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('case'));
    }

    public function rules(): array
    {
        return [
            'case_number' => [
                'required', 'string', 'max:50',
                Rule::unique('cases', 'case_number')->ignore($this->route('case')),
            ],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'client_id' => ['required', 'exists:clients,id'],
            'pic_user_id' => ['required', 'exists:users,id'],
            'case_type' => ['required', 'string', 'max:50'],
            'status' => ['required', Rule::in(['Draft', 'Active', 'On Hold', 'Completed', 'Closed'])],
            'priority' => ['required', Rule::in(['Low', 'Medium', 'High', 'Urgent'])],
            'start_date' => ['nullable', 'date'],
            'deadline' => ['nullable', 'date', 'after_or_equal:start_date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}