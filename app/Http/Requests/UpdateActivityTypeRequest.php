<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateActivityTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', \App\Models\ActivityType::class);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:50',
                Rule::unique('activity_types', 'name')->ignore($this->route('activity_type')),
            ],
            'description' => ['nullable', 'string'],
        ];
    }
}