<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index(): View
    {
        $user = auth()->user();

        return match (true) {
            $user->hasRole('admin') => view('dashboard.admin', [
                'stats' => $this->dashboardService->getAdminStats(),
            ]),
            $user->hasRole('lawyer') => view('dashboard.lawyer', [
                'stats' => $this->dashboardService->getLawyerStats($user),
            ]),
            $user->hasRole('staff') => view('dashboard.staff', [
                'stats' => $this->dashboardService->getStaffStats($user),
            ]),
            default => abort(403, 'Role tidak dikenali.'),
        };
    }
}