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
      Schema::create('families', function (Blueprint $table) {
         $table->id();
         $table->string('name');                      // Tên gia đình
         $table->string('invite_code', 8)->unique();  // Mã mời (8 ký tự)
         $table->foreignId('created_by')->constrained('users');
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('families');
   }
};
