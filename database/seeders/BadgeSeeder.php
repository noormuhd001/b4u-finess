<?php

namespace Database\Seeders;

use App\Models\Badges;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            ['name' => 'First Workout', 'icon' => 'img/first.png', 'description' => 'Completed your first workout!'],
            ['name' => 'Consistency King', 'icon' => 'img/consistency.png', 'description' => 'Worked out 7 days in a row!'],
            ['name' => 'Strength Beast', 'icon' => 'img/strength.png', 'description' => 'Lifted over 100kg in one session!'],
        ];

        foreach ($badges as $badge) {
            Badges::create($badge);
        }
    }
}
