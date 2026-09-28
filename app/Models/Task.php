<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'case_id',
        'assigned_to',
        'priority',
        'status',
        'due_date',
        'completed_date',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_date' => 'date',
        ];
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(LegalCase::class, 'case_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function isOpen(): bool
    {
        return in_array($this->status, ['Todo', 'In Progress'], true);
    }

    // Overdue: belum selesai dan due_date sudah lewat (sebelum hari ini)
    protected function isOverdue(): Attribute
    {
        return Attribute::get(
            fn () => $this->isOpen()
                && $this->due_date !== null
                && $this->due_date->lt(Carbon::today())
        );
    }

    // Due Soon: belum selesai, belum overdue, jatuh tempo dalam N hari (config worklog.tasks.due_soon_days).
    protected function isDueSoon(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->isOpen() || $this->due_date === null || $this->is_overdue) {
                return false;
            }

            return $this->due_date->lte(
                Carbon::today()->addDays((int) config('worklog.tasks.due_soon_days'))
            );
        });
    }
}