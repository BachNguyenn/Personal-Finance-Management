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

    // Debts (Sổ Nợ)
    Route::resource('debts', \App\Http\Controllers\DebtController::class);
    Route::post('/debts/{debt}/payments', [\App\Http\Controllers\DebtController::class, 'addPayment'])->name('debts.payments.store');
    Route::post('/debts/{debt}/settle', [\App\Http\Controllers\DebtController::class, 'settle'])->name('debts.settle');

    // Budget Alerts
    Route::get('/alerts', [\App\Http\Controllers\AlertController::class, 'index'])->name('alerts.index');
    Route::get('/alerts/unread-count', [\App\Http\Controllers\AlertController::class, 'unreadCount'])->name('alerts.unread-count');
    Route::get('/alerts/recent', [\App\Http\Controllers\AlertController::class, 'recent'])->name('alerts.recent');
    Route::post('/alerts/{alert}/read', [\App\Http\Controllers\AlertController::class, 'markAsRead'])->name('alerts.mark-read');
    Route::post('/alerts/read-all', [\App\Http\Controllers\AlertController::class, 'markAllAsRead'])->name('alerts.mark-all-read');

    // Family Finance
    Route::get('/families', [\App\Http\Controllers\FamilyController::class, 'index'])->name('families.index');
    Route::get('/families/create', [\App\Http\Controllers\FamilyController::class, 'create'])->name('families.create');
    Route::post('/families', [\App\Http\Controllers\FamilyController::class, 'store'])->name('families.store');
    Route::get('/families/join', [\App\Http\Controllers\FamilyController::class, 'joinForm'])->name('families.join-form');
    Route::post('/families/join', [\App\Http\Controllers\FamilyController::class, 'join'])->name('families.join');
    Route::get('/families/{family}', [\App\Http\Controllers\FamilyController::class, 'show'])->name('families.show');
    Route::delete('/families/{family}/leave', [\App\Http\Controllers\FamilyController::class, 'leave'])->name('families.leave');
    Route::patch('/families/{family}/members/{member}/role', [\App\Http\Controllers\FamilyController::class, 'updateRole'])->name('families.update-role');
    Route::delete('/families/{family}/members/{member}', [\App\Http\Controllers\FamilyController::class, 'removeMember'])->name('families.remove-member');
    Route::post('/families/{family}/share-wallet', [\App\Http\Controllers\FamilyController::class, 'shareWallet'])->name('families.share-wallet');
    Route::delete('/families/{family}/wallets/{wallet}', [\App\Http\Controllers\FamilyController::class, 'unshareWallet'])->name('families.unshare-wallet');
    Route::get('/families/{family}/report', [\App\Http\Controllers\FamilyController::class, 'report'])->name('families.report');

    // Smart Insights
    Route::get('/insights', [\App\Http\Controllers\InsightsController::class, 'index'])->name('insights.index');

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
