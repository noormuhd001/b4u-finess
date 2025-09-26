<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('weight')->nullable()->change();
            $table->string('height')->nullable()->change();
            $table->integer('gender')->nullable()->change();
            $table->integer('goal')->nullable()->change();
            $table->string('forgot_token', 100)->nullable();
            $table->dateTime('expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('weight')->change();
            $table->string('height')->change();
            $table->integer('gender')->change();
            $table->integer('goal')->change();
            $table->dropColumn('forgot_token');
            $table->dropColumn('expires_at');
        });
    }
};
