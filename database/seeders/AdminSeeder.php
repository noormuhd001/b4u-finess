<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'nr@gmail.com',
            'password' => Hash::make('Nr@123456'),
            'role' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
