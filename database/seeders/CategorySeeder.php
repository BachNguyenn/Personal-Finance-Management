<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
   /**
    * Run the database seeds.
    */
   public function run(): void
   {
      $users = User::all();

      foreach ($users as $user) {
         // Income categories
         $incomeCategories = [
            ['name' => 'Lương', 'icon' => 'bi-cash-stack', 'color' => '#28a745'],
            ['name' => 'Thưởng', 'icon' => 'bi-gift', 'color' => '#20c997'],
            ['name' => 'Đầu tư', 'icon' => 'bi-graph-up-arrow', 'color' => '#17a2b8'],
            ['name' => 'Bán hàng', 'icon' => 'bi-shop', 'color' => '#6f42c1'],
            ['name' => 'Thu nhập khác', 'icon' => 'bi-plus-circle', 'color' => '#6c757d'],
         ];

         foreach ($incomeCategories as $category) {
            Category::create([
               'user_id' => $user->id,
               'name' => $category['name'],
               'type' => 'income',
               'icon' => $category['icon'],
               'color' => $category['color'],
            ]);
         }

         // Expense categories
         $expenseCategories = [
            ['name' => 'Ăn uống', 'icon' => 'bi-cup-hot', 'color' => '#dc3545'],
            ['name' => 'Đi lại', 'icon' => 'bi-car-front', 'color' => '#fd7e14'],
            ['name' => 'Mua sắm', 'icon' => 'bi-bag', 'color' => '#e83e8c'],
            ['name' => 'Hóa đơn & Tiện ích', 'icon' => 'bi-receipt', 'color' => '#ffc107'],
            ['name' => 'Giải trí', 'icon' => 'bi-controller', 'color' => '#6610f2'],
            ['name' => 'Sức khỏe', 'icon' => 'bi-heart-pulse', 'color' => '#d63384'],
            ['name' => 'Giáo dục', 'icon' => 'bi-book', 'color' => '#0dcaf0'],
            ['name' => 'Gia đình', 'icon' => 'bi-house-heart', 'color' => '#198754'],
            ['name' => 'Chi tiêu khác', 'icon' => 'bi-three-dots', 'color' => '#6c757d'],
         ];

         foreach ($expenseCategories as $category) {
            Category::create([
               'user_id' => $user->id,
               'name' => $category['name'],
               'type' => 'expense',
               'icon' => $category['icon'],
               'color' => $category['color'],
            ]);
         }
      }
   }
}
