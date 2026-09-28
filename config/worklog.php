<?php

return [
    // Attendance
    //late_threshold: jam batas check-in dianggap "Present" (di jam ini atau sebelumnya) vs "Late" (setelah jam ini). Format 24 jam "H:i"
    'attendance' => [
        'late_threshold' => '09:00',
    ],

    // Tasks
    // due_soon_days: task yang belum selesai dan jatuh tempo dalam N hari ke depan (termasuk hari ini) ditandai "Due Soon"
    'tasks' => [
        'due_soon_days' => 3,
    ],
];