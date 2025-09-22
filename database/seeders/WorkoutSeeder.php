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
            ['workout_name' => 'Push Ups', 'body_part' => 'Chest', 'image' => 'img/pushup.jpg'],
            ['workout_name' => 'Bench Press', 'body_part' => 'Chest', 'image' => 'img/bench-press.jpg'],
            ['workout_name' => 'Incline Bench Press', 'body_part' => 'Chest', 'image' => 'img/incline-bench-press.jpg'],
            ['workout_name' => 'Chest Fly', 'body_part' => 'Chest', 'image' => 'img/chest-fly.jpg'],
            ['workout_name' => 'Cable Crossover', 'body_part' => 'Chest', 'image' => 'img/cable-crossover.jpg'],
            ['workout_name' => 'Dumbbell Pullover', 'body_part' => 'Chest', 'image' => 'img/dumbbell-pullover.jpg'],
            ['workout_name' => 'Cable Chest Press', 'body_part' => 'Chest', 'image' => 'img/cable-chest-press.jpg'],

            // Back
            ['workout_name' => 'Pull Ups', 'body_part' => 'Back', 'image' => 'img/pull-up.jpg'],
            ['workout_name' => 'Deadlift', 'body_part' => 'Back', 'image' => 'img/deadlift.jpg'],
            ['workout_name' => 'Lat Pulldown', 'body_part' => 'Back', 'image' => 'img/lat-pull-down.jpg'],
            ['workout_name' => 'Seated Row', 'body_part' => 'Back', 'image' => 'img/seated-row.jpg'],
            ['workout_name' => 'T-Bar Row', 'body_part' => 'Back', 'image' => 'img/t-bar-row.jpg'],
            ['workout_name' => 'Bent Over Row', 'body_part' => 'Back', 'image' => 'img/bend-over-row.jpg'],
            ['workout_name' => 'Back Extension', 'body_part' => 'Back', 'image' => 'img/back-extension.jpg'],

            // Legs
            ['workout_name' => 'Squats', 'body_part' => 'Legs', 'image' => 'img/squats.jpg'],
            ['workout_name' => 'Lunges', 'body_part' => 'Legs', 'image' => 'img/lunges.jpg'],
            ['workout_name' => 'Leg Press', 'body_part' => 'Legs', 'image' => 'img/leg-press.jpg'],
            ['workout_name' => 'Leg Curl', 'body_part' => 'Legs', 'image' => 'img/leg-curl.jpg'],
            ['workout_name' => 'Leg Extension', 'body_part' => 'Legs', 'image' => 'img/leg-extension.jpg'],
            ['workout_name' => 'Calf Raises', 'body_part' => 'Legs', 'image' => 'img/calf-raises.jpg'],
            ['workout_name' => 'Glute Bridge', 'body_part' => 'Legs', 'image' => 'img/glute-bridge.jpg'],

            // Arms
            ['workout_name' => 'Bicep Curls', 'body_part' => 'Arms', 'image' => 'img/bicep-curls.jpg'],
            ['workout_name' => 'Hammer Curls', 'body_part' => 'Arms', 'image' => 'img/hammer-curls.jpg'],
            ['workout_name' => 'Tricep Dips', 'body_part' => 'Arms', 'image' => 'img/tricep-dips.jpg'],
            ['workout_name' => 'Tricep Pushdown', 'body_part' => 'Arms', 'image' => 'img/tricep-pushdown.jpg'],
            ['workout_name' => 'Preacher Curl', 'body_part' => 'Arms', 'image' => 'img/preacher-curl.jpg'],
            ['workout_name' => 'Overhead Tricep Extension', 'body_part' => 'Arms', 'image' => 'img/overhead-tricep.jpg'],
            ['workout_name' => 'Concentration Curl', 'body_part' => 'Arms', 'image' => 'img/concentration-curl.jpg'],

            // Shoulders
            ['workout_name' => 'Shoulder Press', 'body_part' => 'Shoulders', 'image' => 'img/shoulder-press.jpg'],
            ['workout_name' => 'Lateral Raises', 'body_part' => 'Shoulders', 'image' => 'img/lateral-raises.jpg'],
            ['workout_name' => 'Front Raises', 'body_part' => 'Shoulders', 'image' => 'img/front-raises.jpg'],
            ['workout_name' => 'Arnold Press', 'body_part' => 'Shoulders', 'image' => 'img/arnold-press.jpg'],
            ['workout_name' => 'Reverse Fly', 'body_part' => 'Shoulders', 'image' => 'img/reverse-fly.jpg'],
            ['workout_name' => 'Shrugs', 'body_part' => 'Shoulders', 'image' => 'img/shrugs.jpg'],
            ['workout_name' => 'Upright Row', 'body_part' => 'Shoulders', 'image' => 'img/upright-row.jpg'],

            // Core
            ['workout_name' => 'Plank', 'body_part' => 'Core', 'image' => 'img/plank.jpg'],
            ['workout_name' => 'Russian Twist', 'body_part' => 'Core', 'image' => 'img/russian-twist.jpg'],
            ['workout_name' => 'Sit Ups', 'body_part' => 'Core', 'image' => 'img/sit-ups.jpg'],
            ['workout_name' => 'Leg Raises', 'body_part' => 'Core', 'image' => 'img/leg-raises.jpg'],
            ['workout_name' => 'Bicycle Crunches', 'body_part' => 'Core', 'image' => 'img/bicycle-crunches.jpg'],
            ['workout_name' => 'Mountain Climbers', 'body_part' => 'Core', 'image' => 'img/mountain-climbers.jpg'],
            ['workout_name' => 'Hanging Knee Raises', 'body_part' => 'Core', 'image' => 'img/hanging-knee-raises.jpg'],
        ];

        DB::table('workouts')->insert($workouts);
    }
}
