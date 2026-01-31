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
      Schema::create('family_members', function (Blueprint $table) {
         $table->id();
         $table->foreignId('family_id')->constrained()->onDelete('cascade');
         $table->foreignId('user_id')->constrained()->onDelete('cascade');
         $table->enum('role', ['admin', 'member', 'viewer'])->default('member');
         $table->timestamps();

         $table->unique(['family_id', 'user_id']);
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('family_members');
   }
};
