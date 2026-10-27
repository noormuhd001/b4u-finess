<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Basic', 'price' => 500, 'description' => 'Basic gym membership.'],
            ['name' => 'Regular with PT', 'price' => 1000, 'description' => 'Regular membership with personal training.'],
            ['name' => 'Premium', 'price' => 2000, 'description' => 'Premium gym membership.'],
        ] as $plan) {
            MembershipPlan::firstOrCreate(
                ['name' => $plan['name']],
                [
                    'description' => $plan['description'],
                    'price' => $plan['price'],
                    'duration_months' => 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
