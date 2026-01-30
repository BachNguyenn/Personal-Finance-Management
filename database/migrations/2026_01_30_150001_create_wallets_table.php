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
      Schema::create('wallets', function (Blueprint $table) {
         $table->id();
         $table->foreignId('user_id')->constrained()->onDelete('cascade');
         $table->string('name');
         $table->string('type')->default('cash'); // cash, bank, e-wallet
         $table->decimal('balance', 15, 2)->default(0);
         $table->string('currency', 3)->default('VND');
         $table->string('icon')->nullable();
         $table->string('color', 7)->nullable(); // Hex color
         $table->boolean('is_default')->default(false);
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('wallets');
   }
};
