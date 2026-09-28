<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Attendance::class);

        $validated = $request->validate([
            'start' => ['nullable', 'date'],
            'end' => ['nullable', 'date', 'after_or_equal:start'],
            'status' => ['nullable', Rule::in(AttendanceService::STATUSES)],
        ]);

        // Default periode: bulan berjalan.
        $start = isset($validated['start'])
            ? Carbon::parse($validated['start'])->startOfDay()
            : Carbon::now()->startOfMonth();

        $end = isset($validated['end'])
            ? Carbon::parse($validated['end'])->endOfDay()
            : Carbon::now()->endOfMonth();

        // Kalau hanya start yang diisi dan melewati akhir bulan berjalan,
        // pakai akhir bulan dari start supaya periode tidak terbalik.
        if ($end->lt($start)) {
            $end = $start->copy()->endOfMonth();
        }

        $user = $request->user();

        return view('attendances.index', [
            'attendances' => $this->attendanceService->paginate($user, $start, $end, $validated['status'] ?? null),
            'todayAttendance' => $this->attendanceService->todayStatus($user),
            'statistics' => $this->attendanceService->statistics($user, $start, $end),
            'start' => $start,
            'end' => $end,
        ]);
    }

    public function checkIn(Request $request): RedirectResponse
    {
        $this->authorize('checkInOut', Attendance::class);

        try {
            $this->attendanceService->checkIn($request->user());
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('attendances.index')->with('error', $e->getMessage());
        }

        return redirect()->route('attendances.index')->with('success', 'Check-in berhasil.');
    }

    public function checkOut(Request $request): RedirectResponse
    {
        $this->authorize('checkInOut', Attendance::class);

        try {
            $this->attendanceService->checkOut($request->user());
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('attendances.index')->with('error', $e->getMessage());
        }

        return redirect()->route('attendances.index')->with('success', 'Check-out berhasil.');
    }

    public function edit(Attendance $attendance): View
    {
        $this->authorize('update', $attendance);

        return view('attendances.edit', compact('attendance'));
    }

    public function update(UpdateAttendanceRequest $request, Attendance $attendance): RedirectResponse
    {
        $this->attendanceService->update($attendance, $request->validated());

        return redirect()->route('attendances.index')->with('success', 'Attendance berhasil diperbarui.');
    }
}