<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegalCase extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cases';

    protected $fillable = [
        'case_number',
        'title',
        'description',
        'client_id',
        'pic_user_id',
        'case_type',
        'status',
        'priority',
        'start_date',
        'deadline',
        'end_date',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'deadline' => 'date',
            'end_date' => 'date',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Team = seluruh employee yang terlibat di case ini (termasuk PIC), via pivot case_assignments
    // Foreign key dieksplisitkan karena Eloquent akan menebak 'legal_case_id' dari nama class LegalCase
    public function team(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'case_assignments', 'case_id', 'user_id')
            ->withPivot(['role_in_case', 'assigned_at'])
            ->withTimestamps();
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(CaseStatusHistory::class, 'case_id')->latest('created_at');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'case_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'case_id');
    }

    // Apakah user terlibat di case ini (PIC atau anggota tim).
    // Memakai relasi `team` yang sudah di-eager-load kalau tersedia, supaya tidak memicu query per baris di halaman list
    public function involves(User $user): bool
    {
        if ($this->pic_user_id === $user->id) {
            return true;
        }

        if ($this->relationLoaded('team')) {
            return $this->team->contains('id', $user->id);
        }

        return $this->team()->where('users.id', $user->id)->exists();
    }

    // Case yang boleh diakses user: Admin semua, selain itu hanya (dia PIC atau anggota tim)
    public function scopeAccessibleBy(Builder $query, User $user): Builder
    {
        if ($user->hasRole('admin')) {
            return $query;
        }

        return $query->where(function ($q) use ($user) {
            $q->where('pic_user_id', $user->id)
                ->orWhereHas('team', fn ($t) => $t->where('users.id', $user->id));
        });
    }
}