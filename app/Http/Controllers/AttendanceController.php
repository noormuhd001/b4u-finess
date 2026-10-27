<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        abort_unless((int) $user->role === 0, 403);

        $user->load('membershipPlan');
        $lastVisit = $user->attendanceRecords()->latest('checked_in_at')->first();
        $checkedInToday = $user->attendanceRecords()
            ->whereDate('check_in_date', today())
            ->exists();
        $attendance = $user->attendanceRecords()
            ->latest('checked_in_at')
            ->paginate(10);

        return view('attendance.index', [
            'user' => $user,
            'lastVisit' => $lastVisit,
            'checkedInToday' => $checkedInToday,
            'attendance' => $attendance,
            'canCheckIn' => $user->hasActiveMembership(),
        ]);
    }

    public function checkIn()
    {
        $user = Auth::user();
        abort_unless((int) $user->role === 0, 403);

        $result = DB::transaction(function () use ($user) {
            $lockedUser = $user->newQuery()->lockForUpdate()->findOrFail($user->id);

            if (! $lockedUser->hasActiveMembership()) {
                return 'membership_inactive';
            }

            if ($lockedUser->attendanceRecords()->whereDate('check_in_date', today())->exists()) {
                return 'already_checked_in';
            }

            $lockedUser->attendanceRecords()->create([
                'check_in_date' => today(),
                'checked_in_at' => now(),
            ]);

            return 'checked_in';
        });

        if ($result === 'membership_inactive') {
            return redirect()->route('attendance.index')
                ->with('error', 'Check-in is unavailable. An active membership with payment received is required.');
        }

        if ($result === 'already_checked_in') {
            return redirect()->route('attendance.index')->with('error', 'You have already checked in today.');
        }

        return redirect()->route('attendance.index')->with('success', 'You are checked in. Have a great workout!');
    }
}
