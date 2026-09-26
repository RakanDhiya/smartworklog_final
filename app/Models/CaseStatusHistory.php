<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseStatusHistory extends Model
{
    const UPDATED_AT = null; // immutable log, tidak ada updated_at

    protected $fillable = [
        'case_id',
        'status',
        'notes',
        'changed_by',
    ];

    public function case(): BelongsTo
    {
        return $this->belongsTo(LegalCase::class, 'case_id');
    }

    public function changer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}