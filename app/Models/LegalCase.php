<?php

namespace App\Models;

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

    /**
     * Team = seluruh employee yang terlibat di case ini (termasuk PIC),
     * via pivot table case_assignments. Ini yang dipakai untuk
     * object-level authorization di CasePolicy — BUKAN pic_user_id saja.
     */
    /**
     * NOTE: foreign key pivot dieksplisitkan ('case_id', 'user_id')
     * karena Eloquent secara default akan menebak 'legal_case_id'
     * dari nama class LegalCase — bukan 'case_id' seperti kolom
     * sebenarnya di tabel case_assignments (yang mengikuti nama
     * tabel `cases`, bukan nama class model).
     */
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

    // NOTE: relasi activities(), tasks(), documents(), schedules()
    // ditambahkan begitu tabel masing-masing dibuat di Phase 5-9.
}