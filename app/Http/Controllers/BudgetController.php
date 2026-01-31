<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $budgets = Budget::where('user_id', Auth::id())
            ->with('category')
            ->get();

        // Calculate progress for each budget
        foreach ($budgets as $budget) {
            $spent = $budget->category->transactions()
                ->where('user_id', Auth::id())
                ->where('type', 'expense')
                ->whereBetween('transaction_date', [$budget->start_date, $budget->end_date])
                ->sum('amount');

            $budget->spent = $spent;
            $budget->progress = ($budget->amount > 0) ? ($spent / $budget->amount) * 100 : 0;
        }

        return view('budgets.index', compact('budgets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('user_id', Auth::id())
            ->where('type', 'expense')
            ->get();

        return view('budgets.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'alert_threshold' => 'nullable|integer|min:50|max:100',
            'alert_enabled' => 'nullable|boolean',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['alert_threshold'] = $validated['alert_threshold'] ?? 80;
        $validated['alert_enabled'] = $request->boolean('alert_enabled');

        Budget::create($validated);

        return redirect()->route('budgets.index')
            ->with('success', 'Ngân sách đã được tạo thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::where('user_id', Auth::id())
            ->where('type', 'expense')
            ->get();

        return view('budgets.edit', compact('budget', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'alert_threshold' => 'nullable|integer|min:50|max:100',
            'alert_enabled' => 'nullable|boolean',
        ]);

        $validated['alert_enabled'] = $request->boolean('alert_enabled');

        $budget->update($validated);

        return redirect()->route('budgets.index')
            ->with('success', 'Ngân sách đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $budget->delete();

        return redirect()->route('budgets.index')
            ->with('success', 'Ngân sách đã được xóa!');
    }
}
