<?php

namespace App\Console\Commands;

use App\Models\Budget;
use App\Models\BudgetAlert;
use Illuminate\Console\Command;

class CheckBudgetAlerts extends Command
{
   /**
    * The name and signature of the console command.
    */
   protected $signature = 'budgets:check-alerts';

   /**
    * The console command description.
    */
   protected $description = 'Check all active budgets and create alerts when thresholds are exceeded';

   /**
    * Execute the console command.
    */
   public function handle()
   {
      $this->info('Checking budget alerts...');

      // Get all active budgets with alerts enabled
      $budgets = Budget::where('alert_enabled', true)
         ->whereDate('start_date', '<=', now())
         ->whereDate('end_date', '>=', now())
         ->with('category', 'user')
         ->get();

      $alertsCreated = 0;

      foreach ($budgets as $budget) {
         $spentAmount = $budget->getSpentAmount();
         $percentageUsed = $budget->getUsedPercentage();

         // Check if we need to create an alert
         $alertType = null;

         if ($percentageUsed >= 100) {
            $alertType = 'exceeded';
         } elseif ($percentageUsed >= $budget->alert_threshold) {
            $alertType = 'warning';
         }

         if ($alertType) {
            // Check if we already sent an alert of this type today
            $existingAlert = BudgetAlert::where('budget_id', $budget->id)
               ->where('alert_type', $alertType)
               ->whereDate('created_at', now()->toDateString())
               ->exists();

            if (!$existingAlert) {
               BudgetAlert::create([
                  'budget_id' => $budget->id,
                  'user_id' => $budget->user_id,
                  'percentage_used' => round($percentageUsed),
                  'spent_amount' => $spentAmount,
                  'alert_type' => $alertType,
               ]);

               $budget->update(['last_alert_at' => now()]);

               $alertsCreated++;

               $this->line("  - Alert created for budget #{$budget->id} ({$budget->category->name}): {$percentageUsed}%");
            }
         }
      }

      $this->info("Done! {$alertsCreated} alert(s) created.");

      return Command::SUCCESS;
   }
}
