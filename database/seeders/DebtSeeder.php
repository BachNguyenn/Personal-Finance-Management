<?php

namespace Database\Seeders;

use App\Models\Debt;
use App\Models\DebtPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DebtSeeder extends Seeder
{
   /**
    * Run the database seeds.
    */
   public function run(): void
   {
      $user = User::where('email', 'admin@example.com')->first();

      if (!$user) {
         return;
      }

      // Debts - Lending (Cho vay)
      $debt1 = Debt::firstOrCreate(
         [
            'user_id' => $user->id,
            'person_name' => 'Nguyễn Văn A',
            'type' => 'lend',
         ],
         [
            'phone' => '0912345678',
            'amount' => 5000000,
            'remaining' => 3000000,
            'description' => 'Cho vay mua laptop',
            'debt_date' => Carbon::now()->subDays(30),
            'due_date' => Carbon::now()->addDays(30),
            'status' => 'partial',
         ]
      );

      // Add payment for debt1
      if ($debt1->wasRecentlyCreated) {
         DebtPayment::create([
            'debt_id' => $debt1->id,
            'amount' => 2000000,
            'payment_date' => Carbon::now()->subDays(10),
            'note' => 'Trả lần 1'
         ]);
      }

      $debt2 = Debt::firstOrCreate(
         [
            'user_id' => $user->id,
            'person_name' => 'Trần Văn B',
            'type' => 'lend',
         ],
         [
            'phone' => '0923456789',
            'amount' => 2000000,
            'remaining' => 2000000,
            'description' => 'Cho vay tiền cưới',
            'debt_date' => Carbon::now()->subDays(15),
            'due_date' => Carbon::now()->addDays(45),
            'status' => 'pending',
         ]
      );

      // Debts - Borrowing (Đi vay)
      $debt3 = Debt::firstOrCreate(
         [
            'user_id' => $user->id,
            'person_name' => 'Lê Thị C',
            'type' => 'borrow',
         ],
         [
            'phone' => '0934567890',
            'amount' => 10000000,
            'remaining' => 5000000,
            'description' => 'Vay tiền mua xe máy',
            'debt_date' => Carbon::now()->subDays(60),
            'due_date' => Carbon::now()->addDays(120),
            'status' => 'partial',
         ]
      );

      // Add payments for debt3
      if ($debt3->wasRecentlyCreated) {
         DebtPayment::create([
            'debt_id' => $debt3->id,
            'amount' => 3000000,
            'payment_date' => Carbon::now()->subDays(30),
            'note' => 'Trả lần 1'
         ]);
         DebtPayment::create([
            'debt_id' => $debt3->id,
            'amount' => 2000000,
            'payment_date' => Carbon::now()->subDays(7),
            'note' => 'Trả lần 2'
         ]);
      }

      $debt4 = Debt::firstOrCreate(
         [
            'user_id' => $user->id,
            'person_name' => 'Phạm Văn D',
            'type' => 'borrow',
         ],
         [
            'phone' => '0945678901',
            'amount' => 1500000,
            'remaining' => 0,
            'description' => 'Vay tiền ăn cưới',
            'debt_date' => Carbon::now()->subDays(90),
            'due_date' => Carbon::now()->subDays(30),
            'status' => 'settled',
         ]
      );

      // Overdue debt
      Debt::firstOrCreate(
         [
            'user_id' => $user->id,
            'person_name' => 'Hoàng Văn E',
            'type' => 'lend',
         ],
         [
            'phone' => '0956789012',
            'amount' => 3000000,
            'remaining' => 3000000,
            'description' => 'Cho vay tiền học',
            'debt_date' => Carbon::now()->subDays(120),
            'due_date' => Carbon::now()->subDays(30), // Overdue
            'status' => 'pending',
         ]
      );

      $this->command->info('Created sample debts data');
   }
}
