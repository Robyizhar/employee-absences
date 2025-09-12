<?php
return [
    'work_start' => env('WORK_START', '08:00:00'),
    'work_end'   => env('WORK_END', '17:00:00'),
    'grace_seconds' => env('ATTENDANCE_GRACE_SECONDS', 0), // toleransi masuk
    'duplicate_threshold_seconds' => env('ATT_DUP_SEC', 30), // jika scan berulang dalam X detik -> duplicate
    'timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),
];
