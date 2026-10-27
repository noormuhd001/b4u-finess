<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class AttendanceManagementController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'period' => ['nullable', Rule::in(['daily', 'weekly', 'monthly'])],
        ]);
        $period = $validated['period'] ?? 'daily';
        $rangeStart = match ($period) {
            'weekly' => Carbon::now()->startOfWeek(),
            'monthly' => Carbon::now()->startOfMonth(),
            default => Carbon::today(),
        };
        $rangeEnd = match ($period) {
            'weekly' => Carbon::now()->endOfWeek(),
            'monthly' => Carbon::now()->endOfMonth(),
            default => Carbon::today()->endOfDay(),
        };

        $records = AttendanceRecord::with('user:id,name,email')
            ->whereBetween('checked_in_at', [$rangeStart, $rangeEnd])
            ->latest('checked_in_at')
            ->paginate(15, ['*'], 'attendance_page')
            ->withQueryString();

        $counts = [
            'check_ins' => AttendanceRecord::whereBetween('checked_in_at', [$rangeStart, $rangeEnd])->count(),
            'members' => AttendanceRecord::whereBetween('checked_in_at', [$rangeStart, $rangeEnd])
                ->distinct()
                ->count('user_id'),
        ];

        $inactiveMembers = User::query()
            ->with('membershipPlan')
            ->where('role', 0)
            ->where('status', true)
            ->where('cash_payment_status', 'received')
            ->whereNotNull('membership_expires_at')
            ->where('membership_expires_at', '>', now())
            ->whereDoesntHave('attendanceRecords', function ($attendanceQuery) {
                $attendanceQuery->where('checked_in_at', '>=', now()->subDays(30));
            })
            ->withMax('attendanceRecords', 'checked_in_at')
            ->orderBy('attendance_records_max_checked_in_at')
            ->paginate(10, ['*'], 'inactive_page')
            ->withQueryString();

        return view('admin.attendance.index', compact(
            'records',
            'counts',
            'period',
            'rangeStart',
            'rangeEnd',
            'inactiveMembers'
        ));
    }
}
