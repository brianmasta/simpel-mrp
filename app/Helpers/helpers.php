<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

if (!function_exists('bulan_romawi')) {
    function bulan_romawi($bulan)
    {
        $romawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $romawi[$bulan] ?? '';
    }
}

function logActivity(string $activity, $model = null)
{
    ActivityLog::create([
        'user_id' => Auth::id(),
        'role' => Auth::user()->role ?? null,
        'activity' => $activity,
        'model' => $model ? class_basename($model) : null,
        'model_id' => $model->id ?? null,
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);
}
