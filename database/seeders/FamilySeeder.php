<?php

namespace Database\Seeders;

use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Database\Seeder;

class FamilySeeder extends Seeder
{
   /**
    * Run the database seeds.
    */
   public function run(): void
   {
      $admin = User::where('email', 'admin@example.com')->first();

      if (!$admin) {
         return;
      }

      // Create second user for family demo
      $user2 = User::firstOrCreate(
         ['email' => 'user2@example.com'],
         [
            'name' => 'Nguyễn Văn B',
            'password' => bcrypt('password'),
         ]
      );

      $user3 = User::firstOrCreate(
         ['email' => 'user3@example.com'],
         [
            'name' => 'Trần Thị C',
            'password' => bcrypt('password'),
         ]
      );

      // Create family
      $family = Family::firstOrCreate(
         ['name' => 'Gia đình Admin'],
         [
            'invite_code' => 'DEMO1234',
            'created_by' => $admin->id,
         ]
      );

      // Add members
      FamilyMember::firstOrCreate(
         ['family_id' => $family->id, 'user_id' => $admin->id],
         ['role' => 'admin']
      );

      FamilyMember::firstOrCreate(
         ['family_id' => $family->id, 'user_id' => $user2->id],
         ['role' => 'member']
      );

      FamilyMember::firstOrCreate(
         ['family_id' => $family->id, 'user_id' => $user3->id],
         ['role' => 'viewer']
      );

      // Share a wallet with family if exists
      $wallet = \App\Models\Wallet::where('user_id', $admin->id)->first();
      if ($wallet && !$wallet->family_id) {
         $wallet->update(['family_id' => $family->id]);
      }

      $this->command->info('Created family: ' . $family->name . ' with code: ' . $family->invite_code);
   }
}
