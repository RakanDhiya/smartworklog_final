<?php

namespace App\Services;

use App\Models\ActivityType;
use Illuminate\Database\Eloquent\Collection;

class ActivityTypeService
{
    public function all(): Collection
    {
        return ActivityType::orderBy('name')->get();
    }

    public function create(array $data): ActivityType
    {
        return ActivityType::create($data);
    }

    public function update(ActivityType $activityType, array $data): ActivityType
    {
        $activityType->update($data);

        return $activityType;
    }

        // Cegah penghapusan activity type yang masih dipakai oleh Activity yang sudah ada — mencegah data activity kehilangan kategori
    public function delete(ActivityType $activityType): void
    {
        if ($activityType->activities()->exists()) {
            throw new \InvalidArgumentException(
                'Activity Type ini masih dipakai oleh data Activity yang ada dan tidak dapat dihapus.'
            );
        }

        $activityType->delete();
    }

}
