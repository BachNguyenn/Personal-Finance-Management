<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoal;
use App\Models\Wallet;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SavingsGoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $goals = SavingsGoal::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate progress percentage
        foreach ($goals as $goal) {
            $goal->progress = ($goal->target_amount > 0) ?
                ($goal->current_amount / $goal->target_amount) * 100 : 0;
        }

        $wallets = Wallet::where('user_id', Auth::id())->get();

        return view('savings_goals.index', compact('goals', 'wallets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('savings_goals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0.01',
            'target_date' => 'nullable|date|after:today',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'active';

        SavingsGoal::create($validated);

        return redirect()->route('savings_goals.index')
            ->with('success', 'Mục tiêu tiết kiệm đã được tạo!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SavingsGoal $savings_goal) // Use implicit binding properly
    {
        if ($savings_goal->user_id !== Auth::id()) {
            abort(403);
        }
        return view('savings_goals.edit', compact('savings_goal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SavingsGoal $savings_goal)
    {
        if ($savings_goal->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0.01',
            'target_date' => 'nullable|date',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        $savings_goal->update($validated);

        return redirect()->route('savings_goals.index')
            ->with('success', 'Mục tiêu đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SavingsGoal $savings_goal)
    {
        if ($savings_goal->user_id !== Auth::id()) {
            abort(403);
        }
        $savings_goal->delete();
        return redirect()->route('savings_goals.index')->with('success', 'Mục tiêu đã được xóa!');
    }

    /**
     * Add money to the savings goal
     */
    public function addMoney(Request $request, SavingsGoal $savings_goal)
    {
        if ($savings_goal->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'wallet_id' => 'required|exists:wallets,id',
        ]);

        DB::transaction(function () use ($request, $savings_goal) {
            $amount = $request->amount;
            $wallet = Wallet::where('id', $request->wallet_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // 1. Create Expense Transaction
            // Find or create "Savings" category? Or just no category? Or specific one?
            // Let's create a generic "Tiết kiệm" category if not exists or assume user has one?
            // Better: Just make it nullable category or plain expense. 
            // Let's use Description so user knows.

            Transaction::create([
                'user_id' => Auth::id(),
                'wallet_id' => $wallet->id,
                'type' => 'expense',
                'amount' => $amount,
                'transaction_date' => now(),
                'description' => 'Nạp tiền vào mục tiêu: ' . $savings_goal->name,
                'category_id' => null, // Or logic to find 'Savings' category
            ]);

            // 2. Decrement Wallet
            $wallet->decrement('balance', $amount);

            // 3. Increment Goal
            $savings_goal->increment('current_amount', $amount);
        });

        return redirect()->back()->with('success', 'Đã nạp tiền vào mục tiêu thành công!');
    }
}
