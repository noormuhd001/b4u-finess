<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedSmallInteger('duration_months')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('membership_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->string('cash_payment_status')->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('membership_plan_id');
            $table->dropColumn('cash_payment_status');
        });

        Schema::dropIfExists('membership_plans');
    }
};
