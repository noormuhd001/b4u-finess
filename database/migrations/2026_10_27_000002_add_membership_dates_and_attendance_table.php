<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('membership_started_at')->nullable();
            $table->timestamp('membership_expires_at')->nullable()->index();
        });

        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('check_in_date');
            $table->timestamp('checked_in_at');
            $table->timestamps();
            $table->unique(['user_id', 'check_in_date']);
            $table->index('checked_in_at');
        });

        $planDurations = DB::table('membership_plans')->pluck('duration_months', 'id');
        $paidUsers = DB::table('users')
            ->where('cash_payment_status', 'received')
            ->whereNotNull('membership_plan_id')
            ->whereNull('membership_started_at')
            ->get(['id', 'membership_plan_id', 'updated_at']);

        foreach ($paidUsers as $user) {
            $durationMonths = $planDurations[$user->membership_plan_id] ?? null;
            if (! $durationMonths) {
                continue;
            }

            $startedAt = $user->updated_at
                ? Carbon::parse($user->updated_at)
                : now();

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'membership_started_at' => $startedAt,
                    'membership_expires_at' => $startedAt->copy()->addMonthsNoOverflow($durationMonths),
                ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['membership_expires_at']);
            $table->dropColumn(['membership_started_at', 'membership_expires_at']);
        });
    }
};
