<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('cases.create');
    }

    public function rules(): array
    {
        return [
            'case_number' => ['required', 'string', 'max:50', 'unique:cases,case_number'],
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