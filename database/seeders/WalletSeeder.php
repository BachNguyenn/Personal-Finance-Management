<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
   /**
    * Run the database seeds.
    */
   public function run(): void
   {
      $users = User::all();

      foreach ($users as $user) {
         // Default cash wallet
         Wallet::create([
            'user_id' => $user->id,
            'name' => 'Tiền mặt',
            'type' => 'cash',
            'balance' => 0,
            'currency' => 'VND',
            'icon' => 'bi-wallet2',
            'color' => '#28a745',
            'is_default' => true,
         ]);

         // Bank account
         Wallet::create([
            'user_id' => $user->id,
            'name' => 'Ngân hàng',
            'type' => 'bank',
            'balance' => 0,
            'currency' => 'VND',
            'icon' => 'bi-bank',
            'color' => '#007bff',
            'is_default' => false,
         ]);
      }
   }
}
