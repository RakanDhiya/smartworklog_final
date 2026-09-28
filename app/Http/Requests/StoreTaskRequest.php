<?php

namespace App\Http\Requests;

use App\Models\Task;

class StoreTaskRequest extends TaskFormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Task::class);
    }
}