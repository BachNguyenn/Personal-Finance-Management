<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $faker = \Faker\Factory::create();

        foreach ($users as $user) {
            $wallets = Wallet::where('user_id', $user->id)->get();
            $incomeCategories = Category::where('user_id', $user->id)->where('type', 'income')->get();
            $expenseCategories = Category::where('user_id', $user->id)->where('type', 'expense')->get();

            if ($wallets->count() === 0 || $incomeCategories->count() === 0 || $expenseCategories->count() === 0) {
                continue;
            }

            // Create 50 random transactions for each user
            for ($i = 0; $i < 50; $i++) {
                $isIncome = rand(0, 10) < 3; // 30% income, 70% expense
                $type = $isIncome ? 'income' : 'expense';
                $category = $isIncome ? $incomeCategories->random() : $expenseCategories->random();
                $wallet = $wallets->random();
                $amount = $isIncome ? rand(1000000, 20000000) : rand(50000, 2000000); // Random amount
                $date = $faker->dateTimeBetween('-3 months', 'now');

                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $wallet->id,
                    'category_id' => $category->id,
                    'type' => $type,
                    'amount' => $amount,
                    'transaction_date' => $date,
                    'description' => $faker->sentence(3),
                    'note' => $faker->sentence(6),
                ]);

                // Update wallet balance
                if ($type === 'income') {
                    $wallet->increment('balance', $amount);
                } else {
                    $wallet->decrement('balance', $amount);
                }
            }
        }
    }
}
