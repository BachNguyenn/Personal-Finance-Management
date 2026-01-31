<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Debt;
use App\Models\SavingsGoal;
use App\Models\Transaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InsightsController extends Controller
{
   /**
    * Display the smart insights dashboard.
    */
   public function index()
   {
      $userId = Auth::id();
      $now = Carbon::now();

      // Get financial health score
      $healthScore = $this->calculateHealthScore($userId);

      // Get spending insights
      $insights = $this->getSpendingInsights($userId);

      // Get smart tips
      $tips = $this->getSmartTips($userId);

      // Get monthly comparison
      $monthlyComparison = $this->getMonthlyComparison($userId);

      // Get top expenses
      $topExpenses = $this->getTopExpenses($userId);

      // Get predictions
      $predictions = $this->getSpendingPredictions($userId);

      return view('insights.index', compact(
         'healthScore',
         'insights',
         'tips',
         'monthlyComparison',
         'topExpenses',
         'predictions'
      ));
   }

   /**
    * Calculate Financial Health Score (0-100).
    */
   private function calculateHealthScore($userId): array
   {
      $score = 0;
      $maxScore = 100;
      $breakdown = [];

      // 1. Savings Rate (0-25 points)
      $thisMonth = Carbon::now();
      $income = Transaction::where('user_id', $userId)
         ->where('type', 'income')
         ->whereMonth('transaction_date', $thisMonth->month)
         ->whereYear('transaction_date', $thisMonth->year)
         ->sum('amount');

      $expense = Transaction::where('user_id', $userId)
         ->where('type', 'expense')
         ->whereMonth('transaction_date', $thisMonth->month)
         ->whereYear('transaction_date', $thisMonth->year)
         ->sum('amount');

      $savingsRate = $income > 0 ? (($income - $expense) / $income) * 100 : 0;
      $savingsScore = min(25, max(0, $savingsRate * 0.5)); // 50% savings = 25 points
      $score += $savingsScore;
      $breakdown['savings'] = ['score' => round($savingsScore), 'max' => 25, 'rate' => round($savingsRate, 1)];

      // 2. Budget Adherence (0-25 points)
      $budgets = Budget::where('user_id', $userId)
         ->whereDate('start_date', '<=', $thisMonth)
         ->whereDate('end_date', '>=', $thisMonth)
         ->get();

      $budgetScore = 0;
      $budgetCount = $budgets->count();
      if ($budgetCount > 0) {
         $underBudget = 0;
         foreach ($budgets as $budget) {
            $spent = $budget->getSpentAmount();
            if ($spent <= $budget->amount) {
               $underBudget++;
            }
         }
         $budgetScore = ($underBudget / $budgetCount) * 25;
      } else {
         $budgetScore = 15; // Neutral if no budgets set
      }
      $score += $budgetScore;
      $breakdown['budget'] = ['score' => round($budgetScore), 'max' => 25, 'count' => $budgetCount];

      // 3. Debt Management (0-25 points)
      $totalDebtOwed = Debt::where('user_id', $userId)
         ->where('type', 'borrow')
         ->where('status', '!=', 'settled')
         ->sum('remaining');

      $totalDebtLent = Debt::where('user_id', $userId)
         ->where('type', 'lend')
         ->where('status', '!=', 'settled')
         ->sum('remaining');

      // More money lent than owed = good
      $debtRatio = $totalDebtOwed > 0 ? $totalDebtLent / $totalDebtOwed : 1;
      $debtScore = min(25, $debtRatio * 12.5);
      if ($totalDebtOwed == 0 && $totalDebtLent == 0) {
         $debtScore = 20; // Neutral
      }
      $score += $debtScore;
      $breakdown['debt'] = ['score' => round($debtScore), 'max' => 25, 'owed' => $totalDebtOwed, 'lent' => $totalDebtLent];

      // 4. Savings Goals Progress (0-25 points)
      $goals = SavingsGoal::where('user_id', $userId)->get();
      $goalScore = 0;
      if ($goals->count() > 0) {
         $avgProgress = $goals->avg(function ($goal) {
            return min(100, ($goal->current_amount / max(1, $goal->target_amount)) * 100);
         });
         $goalScore = ($avgProgress / 100) * 25;
      } else {
         $goalScore = 12.5; // Neutral if no goals
      }
      $score += $goalScore;
      $breakdown['goals'] = ['score' => round($goalScore), 'max' => 25, 'count' => $goals->count()];

      // Determine grade
      $grade = match (true) {
         $score >= 90 => ['letter' => 'A+', 'color' => 'success', 'label' => 'Xuất sắc'],
         $score >= 80 => ['letter' => 'A', 'color' => 'success', 'label' => 'Rất tốt'],
         $score >= 70 => ['letter' => 'B', 'color' => 'info', 'label' => 'Tốt'],
         $score >= 60 => ['letter' => 'C', 'color' => 'warning', 'label' => 'Khá'],
         $score >= 50 => ['letter' => 'D', 'color' => 'warning', 'label' => 'Trung bình'],
         default => ['letter' => 'F', 'color' => 'danger', 'label' => 'Cần cải thiện'],
      };

      return [
         'score' => round($score),
         'max' => $maxScore,
         'grade' => $grade,
         'breakdown' => $breakdown,
      ];
   }

   /**
    * Get spending insights.
    */
   private function getSpendingInsights($userId): array
   {
      $thisMonth = Carbon::now();
      $lastMonth = Carbon::now()->subMonth();

      $thisMonthExpense = Transaction::where('user_id', $userId)
         ->where('type', 'expense')
         ->whereMonth('transaction_date', $thisMonth->month)
         ->whereYear('transaction_date', $thisMonth->year)
         ->sum('amount');

      $lastMonthExpense = Transaction::where('user_id', $userId)
         ->where('type', 'expense')
         ->whereMonth('transaction_date', $lastMonth->month)
         ->whereYear('transaction_date', $lastMonth->year)
         ->sum('amount');

      $change = $lastMonthExpense > 0
         ? (($thisMonthExpense - $lastMonthExpense) / $lastMonthExpense) * 100
         : 0;

      // Get highest spending category
      $topCategoryData = Transaction::where('user_id', $userId)
         ->where('type', 'expense')
         ->whereMonth('transaction_date', $thisMonth->month)
         ->whereYear('transaction_date', $thisMonth->year)
         ->selectRaw('category_id, SUM(amount) as total')
         ->groupBy('category_id')
         ->orderByDesc('total')
         ->first();

      $topCategory = null;
      if ($topCategoryData && $topCategoryData->category_id) {
         $topCategory = Category::find($topCategoryData->category_id);
         $topCategoryData->category = $topCategory;
      }

      // Average daily spending
      $daysInMonth = $thisMonth->daysInMonth;
      $daysPassed = $thisMonth->day;
      $avgDailySpending = $daysPassed > 0 ? $thisMonthExpense / $daysPassed : 0;

      return [
         'thisMonth' => $thisMonthExpense,
         'lastMonth' => $lastMonthExpense,
         'changePercent' => round($change, 1),
         'changeDirection' => $change > 0 ? 'up' : ($change < 0 ? 'down' : 'same'),
         'topCategory' => $topCategoryData,
         'avgDailySpending' => round($avgDailySpending),
         'daysPassed' => $daysPassed,
         'daysRemaining' => $daysInMonth - $daysPassed,
      ];
   }

   /**
    * Get smart tips based on spending patterns.
    */
   private function getSmartTips($userId): array
   {
      $tips = [];
      $thisMonth = Carbon::now();

      // Check savings rate
      $income = Transaction::where('user_id', $userId)
         ->where('type', 'income')
         ->whereMonth('transaction_date', $thisMonth->month)
         ->sum('amount');

      $expense = Transaction::where('user_id', $userId)
         ->where('type', 'expense')
         ->whereMonth('transaction_date', $thisMonth->month)
         ->sum('amount');

      if ($income > 0 && ($expense / $income) > 0.8) {
         $tips[] = [
            'icon' => 'fas fa-piggy-bank',
            'type' => 'warning',
            'title' => 'Tỷ lệ tiết kiệm thấp',
            'message' => 'Bạn đang chi tiêu hơn 80% thu nhập. Hãy cố gắng tiết kiệm ít nhất 20%.'
         ];
      }

      // Check for overspending categories
      $budgets = Budget::where('user_id', $userId)
         ->whereDate('start_date', '<=', now())
         ->whereDate('end_date', '>=', now())
         ->with('category')
         ->get();

      foreach ($budgets as $budget) {
         $percentage = $budget->getUsedPercentage();
         if ($percentage >= 90 && $percentage < 100) {
            $tips[] = [
               'icon' => 'fas fa-exclamation-triangle',
               'type' => 'warning',
               'title' => 'Sắp vượt ngân sách: ' . $budget->category->name,
               'message' => "Bạn đã sử dụng {$percentage}% ngân sách cho danh mục này."
            ];
         }
      }

      // Check overdue debts
      $overdueDebts = Debt::where('user_id', $userId)
         ->where('status', '!=', 'settled')
         ->whereNotNull('due_date')
         ->where('due_date', '<', now())
         ->count();

      if ($overdueDebts > 0) {
         $tips[] = [
            'icon' => 'fas fa-calendar-times',
            'type' => 'danger',
            'title' => 'Có khoản nợ quá hạn',
            'message' => "Bạn có {$overdueDebts} khoản nợ đã quá hạn thanh toán."
         ];
      }

      // Check savings goals
      $lowProgressGoals = SavingsGoal::where('user_id', $userId)
         ->whereRaw('current_amount < target_amount * 0.3')
         ->whereDate('target_date', '<=', now()->addMonth())
         ->count();

      if ($lowProgressGoals > 0) {
         $tips[] = [
            'icon' => 'fas fa-bullseye',
            'type' => 'info',
            'title' => 'Mục tiêu tiết kiệm cần chú ý',
            'message' => "{$lowProgressGoals} mục tiêu tiết kiệm sắp đến hạn nhưng tiến độ còn thấp."
         ];
      }

      // Good tips
      if (empty($tips)) {
         $tips[] = [
            'icon' => 'fas fa-thumbs-up',
            'type' => 'success',
            'title' => 'Tuyệt vời!',
            'message' => 'Tài chính của bạn đang ổn định. Hãy tiếp tục duy trì!'
         ];
      }

      return $tips;
   }

   /**
    * Get monthly comparison data.
    */
   private function getMonthlyComparison($userId): array
   {
      $data = [];

      for ($i = 5; $i >= 0; $i--) {
         $date = Carbon::now()->subMonths($i);

         $income = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereMonth('transaction_date', $date->month)
            ->whereYear('transaction_date', $date->year)
            ->sum('amount');

         $expense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $date->month)
            ->whereYear('transaction_date', $date->year)
            ->sum('amount');

         $data[] = [
            'month' => $date->format('m/Y'),
            'monthName' => $date->translatedFormat('M'),
            'income' => $income,
            'expense' => $expense,
            'savings' => $income - $expense,
         ];
      }

      return $data;
   }

   /**
    * Get top 5 highest expenses this month.
    */
   private function getTopExpenses($userId): array
   {
      $thisMonth = Carbon::now();

      $expenses = Transaction::where('user_id', $userId)
         ->where('type', 'expense')
         ->whereMonth('transaction_date', $thisMonth->month)
         ->whereYear('transaction_date', $thisMonth->year)
         ->orderByDesc('amount')
         ->with('category')
         ->limit(5)
         ->get();

      return $expenses->map(function ($transaction) {
         return [
            'description' => $transaction->description,
            'amount' => $transaction->amount,
            'date' => $transaction->transaction_date->format('d/m'),
            'category' => $transaction->category ? [
               'name' => $transaction->category->name,
               'color' => $transaction->category->color,
               'icon' => $transaction->category->icon,
            ] : null,
         ];
      })->toArray();
   }

   /**
    * Get spending predictions.
    */
   private function getSpendingPredictions($userId): array
   {
      $thisMonth = Carbon::now();

      // Get average spending from last 3 months
      $avgMonthlyExpense = 0;
      for ($i = 1; $i <= 3; $i++) {
         $date = Carbon::now()->subMonths($i);
         $avgMonthlyExpense += Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $date->month)
            ->whereYear('transaction_date', $date->year)
            ->sum('amount');
      }
      $avgMonthlyExpense = $avgMonthlyExpense / 3;

      // Current month progress
      $currentExpense = Transaction::where('user_id', $userId)
         ->where('type', 'expense')
         ->whereMonth('transaction_date', $thisMonth->month)
         ->whereYear('transaction_date', $thisMonth->year)
         ->sum('amount');

      $daysPassed = $thisMonth->day;
      $daysInMonth = $thisMonth->daysInMonth;
      $daysRemaining = $daysInMonth - $daysPassed;

      // Predict end of month spending (linear projection)
      $predictedTotal = $daysPassed > 0
         ? ($currentExpense / $daysPassed) * $daysInMonth
         : $avgMonthlyExpense;

      // Compare to average
      $vsAverage = $avgMonthlyExpense > 0
         ? (($predictedTotal - $avgMonthlyExpense) / $avgMonthlyExpense) * 100
         : 0;

      return [
         'currentExpense' => round($currentExpense),
         'predictedTotal' => round($predictedTotal),
         'avgMonthly' => round($avgMonthlyExpense),
         'vsAveragePercent' => round($vsAverage, 1),
         'daysRemaining' => $daysRemaining,
         'dailyBudget' => $daysRemaining > 0 ? round(($avgMonthlyExpense - $currentExpense) / $daysRemaining) : 0,
      ];
   }
}
