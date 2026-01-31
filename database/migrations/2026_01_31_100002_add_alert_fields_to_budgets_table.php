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
      Schema::table('budgets', function (Blueprint $table) {
         $table->integer('alert_threshold')->default(80); // % ngưỡng cảnh báo
         $table->boolean('alert_enabled')->default(true);
         $table->timestamp('last_alert_at')->nullable();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::table('budgets', function (Blueprint $table) {
         $table->dropColumn(['alert_threshold', 'alert_enabled', 'last_alert_at']);
      });
   }
};
