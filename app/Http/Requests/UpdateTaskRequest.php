<?php

namespace App\Http\Requests;

class UpdateTaskRequest extends TaskFormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('task'));
    }
}