<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   /**
    * Run the migrations.
    */
   public function up(): void
   {
      Schema::create('budget_alerts', function (Blueprint $table) {
         $table->id();
         $table->foreignId('budget_id')->constrained()->onDelete('cascade');
         $table->foreignId('user_id')->constrained()->onDelete('cascade');
         $table->integer('percentage_used'); // % đã sử dụng tại thời điểm cảnh báo
         $table->decimal('spent_amount', 15, 2);
         $table->enum('alert_type', ['warning', 'exceeded']); // 80% vs 100%
         $table->boolean('is_read')->default(false);
         $table->timestamps();

         $table->index(['user_id', 'is_read']);
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('budget_alerts');
   }
};
