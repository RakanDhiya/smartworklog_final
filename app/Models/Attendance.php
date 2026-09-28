<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'check_in' => 'datetime',
            'check_out' => 'datetime',
        ];
    }

    // Durasi kerja dalam menit (null kalau belum check-in/check-out).
    // Hanya untuk tampilan per-baris; total periode dihitung di database
    protected function workMinutes(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->check_in || ! $this->check_out) {
                return null;
            }

            return (int) $this->check_in->diffInMinutes($this->check_out);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}