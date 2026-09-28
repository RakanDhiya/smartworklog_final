<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('attendance'));
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['Present', 'Late', 'Leave', 'Sick', 'Absent'])],
            'check_in' => ['nullable', 'date'],
            'check_out' => ['nullable', 'date', 'after_or_equal:check_in'],
            'notes' => ['nullable', 'string'],
        ];
    }
}