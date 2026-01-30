<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   public function index()
   {
      $user = Auth::user();
      $now = Carbon::now();

      // Get summary data for current month
      $totalIncome = Transaction::where('user_id', $user->id)
         ->thisMonth()
         ->income()
         ->sum('amount');

      $totalExpense = Transaction::where('user_id', $user->id)
         ->thisMonth()
         ->expense()
         ->sum('amount');

      $netResult = $totalIncome - $totalExpense;

      $totalBalance = Wallet::where('user_id', $user->id)->sum('balance');

      // Get wallets with balance
      $wallets = Wallet::where('user_id', $user->id)
         ->orderBy('is_default', 'desc')
         ->get();

      // Get expense by category (for pie chart)
      $expenseByCategory = Transaction::where('user_id', $user->id)
         ->thisMonth()
         ->expense()
         ->select('category_id', DB::raw('SUM(amount) as total'))
         ->groupBy('category_id')
         ->with('category')
         ->get();

      $expenseLabels = [];
      $expenseData = [];
      $expenseColors = [];

      foreach ($expenseByCategory as $item) {
         $expenseLabels[] = $item->category ? $item->category->name : 'Không phân loại';
         $expenseData[] = (float) $item->total;
         $expenseColors[] = $item->category ? $item->category->color : '#6c757d'; // Default gray
      }

      // Get daily transactions for line chart (last 30 days)
      $dailyTransactions = Transaction::where('user_id', $user->id)
         ->whereBetween('transaction_date', [
            $now->copy()->subDays(29)->startOfDay(),
            $now->copy()->endOfDay()
         ])
         ->select(
            'type',
            'transaction_date',
            DB::raw('SUM(amount) as total')
         )
         ->groupBy('type', 'transaction_date')
         ->orderBy('transaction_date')
         ->get();

      // Format for trend chart
      $trendLabels = [];
      $trendIncomeData = [];
      $trendExpenseData = [];

      for ($i = 29; $i >= 0; $i--) {
         $date = $now->copy()->subDays($i)->format('Y-m-d');
         // Format date for label (e.g., 25/01)
         $trendLabels[] = $now->copy()->subDays($i)->format('d/m');

         $dayIncome = $dailyTransactions
            ->filter(function ($t) use ($date) {
               return $t->transaction_date->format('Y-m-d') === $date && $t->type === 'income';
            })->first();
         $trendIncomeData[] = $dayIncome ? (float) $dayIncome->total : 0;

         $dayExpense = $dailyTransactions
            ->filter(function ($t) use ($date) {
               return $t->transaction_date->format('Y-m-d') === $date && $t->type === 'expense';
            })->first();
         $trendExpenseData[] = $dayExpense ? (float) $dayExpense->total : 0;
      }

      // Get recent transactions
      $recentTransactions = Transaction::where('user_id', $user->id)
         ->with(['category', 'wallet'])
         ->orderBy('transaction_date', 'desc')
         ->orderBy('created_at', 'desc')
         ->limit(10)
         ->get();

      return view('dashboard', compact(
         'totalIncome',
         'totalExpense',
         'netResult',
         'totalBalance',
         'wallets',
         'expenseLabels',
         'expenseData',
         'expenseColors',
         'trendLabels',
         'trendIncomeData',
         'trendExpenseData',
         'recentTransactions'
      ));
   }
}
