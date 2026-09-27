<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityTypeRequest;
use App\Http\Requests\UpdateActivityTypeRequest;
use App\Models\ActivityType;
use App\Services\ActivityTypeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ActivityTypeController extends Controller
{
    public function __construct(
        protected ActivityTypeService $activityTypeService
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', ActivityType::class);

        return view('activity-types.index', [
            'activityTypes' => $this->activityTypeService->all(),
        ]);
    }

    public function store(StoreActivityTypeRequest $request): RedirectResponse
    {
        $this->activityTypeService->create($request->validated());

        return redirect()->route('activity-types.index')->with('success', 'Activity Type berhasil dibuat.');
    }

    public function update(UpdateActivityTypeRequest $request, ActivityType $activityType): RedirectResponse
    {
        $this->activityTypeService->update($activityType, $request->validated());

        return redirect()->route('activity-types.index')->with('success', 'Activity Type berhasil diperbarui.');
    }

    public function destroy(ActivityType $activityType): RedirectResponse
    {
        $this->authorize('delete', ActivityType::class);

        try {
            $this->activityTypeService->delete($activityType);
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('activity-types.index')->with('error', $e->getMessage());
        }

        return redirect()->route('activity-types.index')->with('success', 'Activity Type berhasil dihapus.');
    }
}