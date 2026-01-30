<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Income vs Expense (Last 12 Months)
        // 1. Income vs Expense (Last 12 Months)
        // Calculated via PHP loop below to be DB-agnostic


        $months = [];
        $incomes = [];
        $expenses = [];

        for ($i = 11; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();
            $label = $monthStart->format('m/Y');

            $months[] = $label;

            $incomes[] = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                ->sum('amount');

            $expenses[] = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                ->sum('amount');
        }

        // 2. Expense by Category (This Month)
        $expenseByCategory = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->with('category')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->get();

        $categoryLabels = [];
        $categoryData = [];
        $categoryColors = [];

        foreach ($expenseByCategory as $item) {
            if ($item->category) {
                $categoryLabels[] = $item->category->name;
                $categoryData[] = $item->total;
                $categoryColors[] = $item->category->color ?? '#cccccc';
            }
        }

        return view('reports.index', compact(
            'months',
            'incomes',
            'expenses',
            'categoryLabels',
            'categoryData',
            'categoryColors'
        ));
    }

    public function export()
    {
        $fileName = 'transactions_' . date('Y-m-d') . '.csv';
        $transactions = Transaction::where('user_id', Auth::id())
            ->with(['wallet', 'category'])
            ->orderBy('transaction_date', 'desc')
            ->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('ID', 'Date', 'Type', 'Amount', 'Wallet', 'Category', 'Description');

        $callback = function () use ($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $transaction) {
                $row['ID'] = $transaction->id;
                $row['Date'] = $transaction->transaction_date->format('Y-m-d');
                $row['Type'] = $transaction->type;
                $row['Amount'] = $transaction->amount;
                $row['Wallet'] = $transaction->wallet->name ?? 'N/A';
                $row['Category'] = $transaction->category->name ?? 'N/A';
                $row['Description'] = $transaction->description;

                fputcsv($file, array($row['ID'], $row['Date'], $row['Type'], $row['Amount'], $row['Wallet'], $row['Category'], $row['Description']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
