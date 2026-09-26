<?php

namespace App\Services;

use App\Models\CaseStatusHistory;
use App\Models\LegalCase;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CaseService
{
    public function paginate(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = LegalCase::query()->with(['client:id,name', 'pic:id,name']);

        if (! $user->hasRole('admin')) {
            $query->where(function ($q) use ($user) {
                $q->where('pic_user_id', $user->id)
                    ->orWhereHas('team', fn ($q2) => $q2->where('users.id', $user->id));
            });
        }

        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($q2) use ($search) {
                $q2->where('case_number', 'ilike', "%{$search}%")
                    ->orWhere('title', 'ilike', "%{$search}%");
            });
        });

        $query->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status));
        $query->when($filters['priority'] ?? null, fn ($q, $priority) => $q->where('priority', $priority));

        return $query->orderByDesc('created_at')->paginate(15)->withQueryString();
    }

    public function create(array $data, User $creator): LegalCase
    {
        return DB::transaction(function () use ($data, $creator) {
            $case = LegalCase::create([
                ...$data,
                'created_by' => $creator->id,
            ]);

            $case->team()->attach($case->pic_user_id, [
                'role_in_case' => 'Lead',
                'assigned_at' => now(),
            ]);

            CaseStatusHistory::create([
                'case_id' => $case->id,
                'status' => $case->status,
                'notes' => 'Case dibuat.',
                'changed_by' => $creator->id,
            ]);

            return $case;
        });
    }

    public function update(LegalCase $case, array $data, User $actor): LegalCase
    {
        return DB::transaction(function () use ($case, $data, $actor) {
            $oldStatus = $case->status;
            $oldPicUserId = $case->pic_user_id;

            $case->update($data);

            if ($case->status !== $oldStatus) {
                CaseStatusHistory::create([
                    'case_id' => $case->id,
                    'status' => $case->status,
                    'notes' => "Status diubah dari {$oldStatus} ke {$case->status}.",
                    'changed_by' => $actor->id,
                ]);
            }

            if ($case->pic_user_id !== $oldPicUserId) {
                $case->team()->syncWithoutDetaching([
                    $case->pic_user_id => ['role_in_case' => 'Lead', 'assigned_at' => now()],
                ]);
            }

            return $case;
        });
    }

    public function delete(LegalCase $case): void
    {
        $case->delete();
    }

    public function addTeamMember(LegalCase $case, User $user, string $roleInCase): void
    {
        $case->team()->syncWithoutDetaching([
            $user->id => ['role_in_case' => $roleInCase, 'assigned_at' => now()],
        ]);
    }

    public function removeTeamMember(LegalCase $case, User $user): void
    {
        if ($case->pic_user_id === $user->id) {
            throw new \InvalidArgumentException('PIC (Primary) tidak dapat dihapus dari tim. Ganti PIC lewat form Edit Case.');
        }

        $case->team()->detach($user->id);
    }
}