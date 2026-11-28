<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            [
                'name' => 'Basic',
                'price' => 500,
                'description' => 'Gym equipment access, basic workout plans, general trainer assistance, and monthly weight tracking.',
                'features' => ['Gym equipment access', 'Basic workout plans', 'General trainer assistance', 'Monthly weight tracking'],
            ],
            [
                'name' => 'Regular',
                'price' => 1000,
                'description' => 'Everything in Basic, plus personalized training, dedicated guidance, weekly progress tracking, and nutrition guidance.',
                'features' => ['Everything in Basic', 'Personalized workout plans', 'Dedicated trainer guidance', 'Weekly progress tracking', 'Nutrition guidance'],
            ],
            [
                'name' => 'Premium',
                'price' => 2000,
                'description' => 'Everything in Regular, plus personal training sessions, a customized diet plan, weekly assessments, priority support, and detailed reports.',
                'features' => ['Everything in Regular', 'Personal trainer sessions', 'Customized diet plan', 'Weekly fitness assessment', 'Priority trainer support', 'Detailed progress reports'],
            ],
        ] as $plan) {
            MembershipPlan::firstOrCreate(
                ['name' => $plan['name']],
                $plan + ['duration_months' => 1, 'is_active' => true]
            );
        }
    }
}
