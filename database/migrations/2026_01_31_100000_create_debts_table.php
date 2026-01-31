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
      Schema::create('debts', function (Blueprint $table) {
         $table->id();
         $table->foreignId('user_id')->constrained()->onDelete('cascade');
         $table->string('person_name');              // Tên người vay/cho vay
         $table->string('phone')->nullable();        // SĐT liên hệ
         $table->enum('type', ['lend', 'borrow']);   // Cho vay / Đi vay
         $table->decimal('amount', 15, 2);           // Số tiền gốc
         $table->decimal('remaining', 15, 2);        // Số tiền còn nợ
         $table->string('description')->nullable();  // Lý do vay
         $table->date('debt_date');                  // Ngày vay
         $table->date('due_date')->nullable();       // Hạn trả (nếu có)
         $table->enum('status', ['pending', 'partial', 'settled'])->default('pending');
         $table->timestamps();

         $table->index(['user_id', 'type', 'status']);
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('debts');
   }
};
