<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainer_daily_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('plan_date');
            $table->text('daily_instruction');
            $table->text('diet_plan')->nullable();
            $table->text('progress_feedback')->nullable();
            $table->timestamps();
            $table->unique(['trainer_id', 'user_id', 'plan_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainer_daily_plans');
    }
};
