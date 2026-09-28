<?php

namespace App\Http\Requests;

use App\Models\LegalCase;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class TaskFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'case_id' => [
                // Non-admin wajib memilih case; hanya Admin boleh tanpa case.
                Rule::requiredIf(fn () => $this->user()->hasRole('admin')),
                'nullable',
                Rule::exists('cases', 'id')->whereNull('deleted_at'),
            ],
            'assigned_to' => [
                'required',
                Rule::exists('users', 'id')->where('is_active', true)->whereNull('deleted_at'),
            ],
            'priority' => ['required', Rule::in(TaskService::PRIORITIES)],
            'status' => ['required', Rule::in(TaskService::STATUSES)],
            'due_date' => ['nullable', 'date'],
        ];
    }

    // Validasi relasi antar-data:
    // 1. Non-admin hanya boleh memakai case yang dia terlibat.
    // 2. Assignee harus anggota tim (atau PIC) case yang dipilih
    public function after(): array
        {
            return [
                function (Validator $validator) {
                    if ($validator->errors()->isNotEmpty() || ! $this->filled('case_id')) {
                        return;
                    }

                    $case = LegalCase::find($this->integer('case_id'));
                    $actor = $this->user();

                    if (! $actor->hasRole('admin') && ! $case->involves($actor)) {
                        $validator->errors()->add('case_id', 'Anda tidak terlibat di case ini.');

                        return;
                    }

                    $assignee = User::find($this->integer('assigned_to'));

                    if (! $case->involves($assignee)) {
                        $validator->errors()->add(
                            'assigned_to',
                            'Assignee harus anggota tim case ini. Minta Admin menambahkannya lewat tab Team pada case.'
                        );
                    }
                },
            ];
        }
}
