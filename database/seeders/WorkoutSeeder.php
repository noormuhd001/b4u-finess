<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workouts = [
            // Chest
            ['workout_name' => 'Push Ups', 'body_part' => 'Chest', 'image' => 'img/pushup.jpg', 'kcal_per_minute' => 8],
            ['workout_name' => 'Bench Press', 'body_part' => 'Chest', 'image' => 'img/bench-press.jpg', 'kcal_per_minute' => 6],
            ['workout_name' => 'Incline Bench Press', 'body_part' => 'Chest', 'image' => 'img/incline-bench-press.jpg', 'kcal_per_minute' => 6],
            ['workout_name' => 'Chest Fly', 'body_part' => 'Chest', 'image' => 'img/chest-fly.jpg', 'kcal_per_minute' => 5],
            ['workout_name' => 'Cable Crossover', 'body_part' => 'Chest', 'image' => 'img/cable-crossover.jpg', 'kcal_per_minute' => 5],
            ['workout_name' => 'Dumbbell Pullover', 'body_part' => 'Chest', 'image' => 'img/dumbbell-pullover.jpg', 'kcal_per_minute' => 5],
            ['workout_name' => 'Cable Chest Press', 'body_part' => 'Chest', 'image' => 'img/cable-chest-press.jpg', 'kcal_per_minute' => 5],

            // Back
            ['workout_name' => 'Pull Ups', 'body_part' => 'Back', 'image' => 'img/pull-up.jpg', 'kcal_per_minute' => 10],
            ['workout_name' => 'Deadlift', 'body_part' => 'Back', 'image' => 'img/deadlift.jpg', 'kcal_per_minute' => 9],
            ['workout_name' => 'Lat Pulldown', 'body_part' => 'Back', 'image' => 'img/lat-pull-down.jpg', 'kcal_per_minute' => 6],
            ['workout_name' => 'Seated Row', 'body_part' => 'Back', 'image' => 'img/seated-row.jpg', 'kcal_per_minute' => 6],
            ['workout_name' => 'T-Bar Row', 'body_part' => 'Back', 'image' => 'img/t-bar-row.jpg', 'kcal_per_minute' => 7],
            ['workout_name' => 'Bent Over Row', 'body_part' => 'Back', 'image' => 'img/bend-over-row.jpg', 'kcal_per_minute' => 7],
            ['workout_name' => 'Back Extension', 'body_part' => 'Back', 'image' => 'img/back-extension.jpg', 'kcal_per_minute' => 4],

            // Legs
            ['workout_name' => 'Squats', 'body_part' => 'Legs', 'image' => 'img/squats.jpg', 'kcal_per_minute' => 8],
            ['workout_name' => 'Lunges', 'body_part' => 'Legs', 'image' => 'img/lunges.jpg', 'kcal_per_minute' => 7],
            ['workout_name' => 'Leg Press', 'body_part' => 'Legs', 'image' => 'img/leg-press.jpg', 'kcal_per_minute' => 6],
            ['workout_name' => 'Leg Curl', 'body_part' => 'Legs', 'image' => 'img/leg-curl.jpg', 'kcal_per_minute' => 5],
            ['workout_name' => 'Leg Extension', 'body_part' => 'Legs', 'image' => 'img/leg-extension.jpg', 'kcal_per_minute' => 5],
            ['workout_name' => 'Calf Raises', 'body_part' => 'Legs', 'image' => 'img/calf-raises.jpg', 'kcal_per_minute' => 4],
            ['workout_name' => 'Glute Bridge', 'body_part' => 'Legs', 'image' => 'img/glute-bridge.jpg', 'kcal_per_minute' => 5],

            // Arms
            ['workout_name' => 'Bicep Curls', 'body_part' => 'Arms', 'image' => 'img/bicep-curls.jpg', 'kcal_per_minute' => 4],
            ['workout_name' => 'Hammer Curls', 'body_part' => 'Arms', 'image' => 'img/hammer-curls.jpg', 'kcal_per_minute' => 4],
            ['workout_name' => 'Tricep Dips', 'body_part' => 'Arms', 'image' => 'img/tricep-dips.jpg', 'kcal_per_minute' => 5],
            ['workout_name' => 'Tricep Pushdown', 'body_part' => 'Arms', 'image' => 'img/tricep-pushdown.jpg', 'kcal_per_minute' => 4],
            ['workout_name' => 'Preacher Curl', 'body_part' => 'Arms', 'image' => 'img/preacher-curl.jpg', 'kcal_per_minute' => 4],
            ['workout_name' => 'Overhead Tricep Extension', 'body_part' => 'Arms', 'image' => 'img/overhead-tricep.jpg', 'kcal_per_minute' => 4],
            ['workout_name' => 'Concentration Curl', 'body_part' => 'Arms', 'image' => 'img/concentration-curl.jpg', 'kcal_per_minute' => 4],

            // Shoulders
            ['workout_name' => 'Shoulder Press', 'body_part' => 'Shoulders', 'image' => 'img/shoulder-press.jpg', 'kcal_per_minute' => 6],
            ['workout_name' => 'Lateral Raises', 'body_part' => 'Shoulders', 'image' => 'img/lateral-raises.jpg', 'kcal_per_minute' => 5],
            ['workout_name' => 'Front Raises', 'body_part' => 'Shoulders', 'image' => 'img/front-raises.jpg', 'kcal_per_minute' => 5],
            ['workout_name' => 'Arnold Press', 'body_part' => 'Shoulders', 'image' => 'img/arnold-press.jpg', 'kcal_per_minute' => 6],
            ['workout_name' => 'Reverse Fly', 'body_part' => 'Shoulders', 'image' => 'img/reverse-fly.jpg', 'kcal_per_minute' => 5],
            ['workout_name' => 'Shrugs', 'body_part' => 'Shoulders', 'image' => 'img/shrugs.jpg', 'kcal_per_minute' => 4],
            ['workout_name' => 'Upright Row', 'body_part' => 'Shoulders', 'image' => 'img/upright-row.jpg', 'kcal_per_minute' => 5],

            // Core
            ['workout_name' => 'Plank', 'body_part' => 'Core', 'image' => 'img/plank.jpg', 'kcal_per_minute' => 6],
            ['workout_name' => 'Russian Twist', 'body_part' => 'Core', 'image' => 'img/russian-twist.jpg', 'kcal_per_minute' => 5],
            ['workout_name' => 'Sit Ups', 'body_part' => 'Core', 'image' => 'img/sit-ups.jpg', 'kcal_per_minute' => 5],
            ['workout_name' => 'Leg Raises', 'body_part' => 'Core', 'image' => 'img/leg-raises.jpg', 'kcal_per_minute' => 5],
            ['workout_name' => 'Bicycle Crunches', 'body_part' => 'Core', 'image' => 'img/bicycle-crunches.jpg', 'kcal_per_minute' => 6],
            ['workout_name' => 'Mountain Climbers', 'body_part' => 'Core', 'image' => 'img/mountain-climbers.jpg', 'kcal_per_minute' => 8],
            ['workout_name' => 'Hanging Knee Raises', 'body_part' => 'Core', 'image' => 'img/hanging-knee-raises.jpg', 'kcal_per_minute' => 6],
        ];

        DB::table('workouts')->insert($workouts);
    }
}
