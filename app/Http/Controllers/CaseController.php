<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCaseTeamMemberRequest;
use App\Http\Requests\StoreCaseRequest;
use App\Http\Requests\UpdateCaseRequest;
use App\Models\Client;
use App\Models\LegalCase;
use App\Models\User;
use App\Services\CaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaseController extends Controller
{
    public function __construct(
        protected CaseService $caseService
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', LegalCase::class);

        $cases = $this->caseService->paginate($request->user(), $request->only('search', 'status', 'priority'));

        return view('cases.index', compact('cases'));
    }

    public function create(): View
    {
        $this->authorize('create', LegalCase::class);

        return view('cases.create', [
            'clients' => Client::orderBy('name')->get(['id', 'name']),
            'picOptions' => User::role(['admin', 'lawyer'])->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreCaseRequest $request): RedirectResponse
    {
        $case = $this->caseService->create($request->validated(), $request->user());

        return redirect()->route('cases.index')->with('success', "Case {$case->case_number} berhasil dibuat.");
    }

    public function show(LegalCase $case): View
    {
        $this->authorize('view', $case);

        $case->load(['client', 'pic', 'creator', 'team', 'statusHistories.changer', 'activities.user', 'activities.activityType']);

        return view('cases.show', [
            'case' => $case,
            'availableUsers' => User::role(['admin', 'lawyer', 'staff'])
                ->whereNotIn('id', $case->team->pluck('id'))
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function edit(LegalCase $case): View
    {
        $this->authorize('update', $case);

        return view('cases.edit', [
            'case' => $case,
            'clients' => Client::orderBy('name')->get(['id', 'name']),
            'picOptions' => User::role(['admin', 'lawyer'])->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdateCaseRequest $request, LegalCase $case): RedirectResponse
    {
        $this->caseService->update($case, $request->validated(), $request->user());

        return redirect()->route('cases.index')->with('success', 'Case berhasil diperbarui.');
    }

    public function destroy(LegalCase $case): RedirectResponse
    {
        $this->authorize('delete', $case);

        $this->caseService->delete($case);

        return redirect()->route('cases.index')->with('success', 'Case berhasil dihapus.');
    }

    public function addTeamMember(AddCaseTeamMemberRequest $request, LegalCase $case): RedirectResponse
    {
        $user = User::findOrFail($request->validated('user_id'));

        $this->caseService->addTeamMember($case, $user, $request->validated('role_in_case'));

        return redirect()->route('cases.show', $case)->with('success', "{$user->name} ditambahkan ke tim.");
    }

    public function removeTeamMember(LegalCase $case, User $user): RedirectResponse
    {
        $this->authorize('assign', $case);

        try {
            $this->caseService->removeTeamMember($case, $user);
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('cases.show', $case)->with('error', $e->getMessage());
        }

        return redirect()->route('cases.show', $case)->with('success', "{$user->name} dihapus dari tim.");
    }
}