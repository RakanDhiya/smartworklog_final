<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddCaseTeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('assign', $this->route('case'));
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                // Tidak boleh menambahkan user yang sudah jadi anggota tim.
                Rule::unique('case_assignments', 'user_id')->where('case_id', $this->route('case')->id),
            ],
            'role_in_case' => ['required', 'string', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.unique' => 'User ini sudah menjadi anggota tim case.',
        ];
    }
}