<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\SavingsGoal;
use Illuminate\Database\Seeder;

class SavingsGoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Goal 1: New Vehicle
            SavingsGoal::create([
                'user_id' => $user->id,
                'name' => 'Mua xe mới',
                'target_amount' => 50000000,
                'current_amount' => 15000000,
                'target_date' => now()->addMonths(6),
                'color' => '#007bff',
                'icon' => 'fas fa-motorcycle',
                'status' => 'active',
                'description' => 'Tiết kiệm mua Honda SH',
            ]);

            // Goal 2: Travel
            SavingsGoal::create([
                'user_id' => $user->id,
                'name' => 'Du lịch Châu Âu',
                'target_amount' => 100000000,
                'current_amount' => 20000000,
                'target_date' => now()->addYear(),
                'color' => '#28a745',
                'icon' => 'fas fa-plane',
                'status' => 'active',
                'description' => 'Chuyến đi mơ ước',
            ]);
        }
    }
}
