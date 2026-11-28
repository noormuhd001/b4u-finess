<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_plans', function (Blueprint $table) {
            $table->json('features')->nullable();
        });

        Schema::create('membership_offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('discount_type');
            $table->decimal('discount_value', 10, 2);
            $table->json('applicable_durations');
            $table->date('starts_at');
            $table->date('expires_at');
            $table->string('coupon_code')->nullable()->unique();
            $table->unsignedInteger('maximum_usage')->nullable();
            $table->unsignedInteger('usage_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('membership_offer_plan', function (Blueprint $table) {
            $table->foreignId('membership_offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('membership_plan_id')->constrained()->cascadeOnDelete();
            $table->primary(['membership_offer_id', 'membership_plan_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedSmallInteger('membership_duration_months')->default(1);
            $table->decimal('membership_original_price', 10, 2)->nullable();
            $table->decimal('membership_discount_amount', 10, 2)->nullable();
            $table->decimal('membership_final_price', 10, 2)->nullable();
            $table->foreignId('membership_offer_id')->nullable()->constrained()->nullOnDelete();
        });

        DB::table('users')->whereNotNull('membership_plan_id')->update([
            'membership_duration_months' => DB::raw(
                '(SELECT CASE WHEN duration_months IN (1, 3, 6, 12) THEN duration_months ELSE 1 END '.
                'FROM membership_plans WHERE membership_plans.id = users.membership_plan_id)'
            ),
        ]);

        $regularWithPt = DB::table('membership_plans')->where('name', 'Regular with PT')->first();
        $regular = DB::table('membership_plans')->where('name', 'Regular')->first();
        if ($regularWithPt && ! $regular) {
            DB::table('membership_plans')->where('id', $regularWithPt->id)->update(['name' => 'Regular']);
        } elseif ($regularWithPt) {
            DB::table('membership_plans')->where('id', $regularWithPt->id)->update(['is_active' => false]);
        }

        foreach ([
            'Basic' => [
                'price' => 500,
                'description' => 'Gym equipment access, basic workout plans, general trainer assistance, and monthly weight tracking.',
                'features' => ['Gym equipment access', 'Basic workout plans', 'General trainer assistance', 'Monthly weight tracking'],
            ],
            'Regular' => [
                'price' => 1000,
                'description' => 'Everything in Basic, plus personalized training, dedicated guidance, weekly progress tracking, and nutrition guidance.',
                'features' => ['Everything in Basic', 'Personalized workout plans', 'Dedicated trainer guidance', 'Weekly progress tracking', 'Nutrition guidance'],
            ],
            'Premium' => [
                'price' => 2000,
                'description' => 'Everything in Regular, plus personal training sessions, a customized diet plan, weekly assessments, priority support, and detailed reports.',
                'features' => ['Everything in Regular', 'Personal trainer sessions', 'Customized diet plan', 'Weekly fitness assessment', 'Priority trainer support', 'Detailed progress reports'],
            ],
        ] as $name => $plan) {
            DB::table('membership_plans')
                ->where('name', $name)
                ->update([
                    'price' => $plan['price'],
                    'description' => $plan['description'],
                    'features' => json_encode($plan['features']),
                    'duration_months' => 1,
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('membership_offer_id');
            $table->dropColumn([
                'membership_duration_months',
                'membership_original_price',
                'membership_discount_amount',
                'membership_final_price',
            ]);
        });

        Schema::dropIfExists('membership_offer_plan');
        Schema::dropIfExists('membership_offers');

        Schema::table('membership_plans', function (Blueprint $table) {
            $table->dropColumn('features');
        });
    }
};
