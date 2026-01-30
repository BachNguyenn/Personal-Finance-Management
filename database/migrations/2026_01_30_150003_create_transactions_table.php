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
      Schema::create('transactions', function (Blueprint $table) {
         $table->id();
         $table->foreignId('user_id')->constrained()->onDelete('cascade');
         $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
         $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
         $table->enum('type', ['income', 'expense', 'transfer']);
         $table->decimal('amount', 15, 2);
         $table->date('transaction_date');
         $table->string('description')->nullable();
         $table->text('note')->nullable();
         $table->string('attachment')->nullable(); // Ảnh đính kèm
         $table->foreignId('to_wallet_id')->nullable()
            ->constrained('wallets')->onDelete('set null');
         $table->timestamps();

         $table->index(['user_id', 'transaction_date']);
         $table->index(['user_id', 'type']);
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('transactions');
   }
};
