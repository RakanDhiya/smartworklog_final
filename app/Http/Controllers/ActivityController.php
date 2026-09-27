<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\LegalCase;
use App\Services\ActivityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function __construct(
        protected ActivityService $activityService
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Activity::class);

        $activities = $this->activityService->paginate(
            $request->user(),
            $request->only('case_id', 'activity_type_id', 'date')
        );

        return view('activities.index', [
            'activities' => $activities,
            'cases' => LegalCase::orderBy('case_number')->get(['id', 'case_number', 'title']),
            'activityTypes' => ActivityType::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Activity::class);

        return view('activities.create', [
            'cases' => LegalCase::orderBy('case_number')->get(['id', 'case_number', 'title']),
            'activityTypes' => ActivityType::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreActivityRequest $request): RedirectResponse
    {
        $this->activityService->create($request->validated(), $request->user());

        return redirect()->route('activities.index')->with('success', 'Activity berhasil dicatat.');
    }

    public function edit(Activity $activity): View
    {
        $this->authorize('update', $activity);

        return view('activities.edit', [
            'activity' => $activity,
            'cases' => LegalCase::orderBy('case_number')->get(['id', 'case_number', 'title']),
            'activityTypes' => ActivityType::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdateActivityRequest $request, Activity $activity): RedirectResponse
    {
        $this->activityService->update($activity, $request->validated());

        return redirect()->route('activities.index')->with('success', 'Activity berhasil diperbarui.');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $this->authorize('delete', $activity);

        $this->activityService->delete($activity);

        return redirect()->route('activities.index')->with('success', 'Activity berhasil dihapus.');
    }
}