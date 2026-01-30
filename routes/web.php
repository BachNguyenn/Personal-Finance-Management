<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Wallets
    Route::resource('wallets', WalletController::class)->except(['show']);

    // Categories
    Route::patch('categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Transactions
    Route::resource('transactions', TransactionController::class)->except(['show']);

    // Budgets
    Route::resource('budgets', \App\Http\Controllers\BudgetController::class)->except(['show']);

    // Savings Goals
    Route::resource('savings_goals', \App\Http\Controllers\SavingsGoalController::class)->except(['show']);
    Route::post('savings_goals/{savings_goal}/add-money', [\App\Http\Controllers\SavingsGoalController::class, 'addMoney'])->name('savings_goals.add_money');

    // Reports
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Language Switch
Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'vi', 'ja'])) {
        Illuminate\Support\Facades\Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('language.switch');

require __DIR__ . '/auth.php';
