<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\DebtPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
   /**
    * Display a listing of the debts.
    */
   public function index(Request $request)
   {
      $userId = Auth::id();
      $type = $request->get('type', 'lend'); // Default to "Cho vay"

      $debts = Debt::where('user_id', $userId)
         ->where('type', $type)
         ->orderByRaw("CASE WHEN status = 'settled' THEN 1 ELSE 0 END")
         ->orderBy('debt_date', 'desc')
         ->get();

      // Summary statistics
      $totalLend = Debt::where('user_id', $userId)->lend()->pending()->sum('remaining');
      $totalBorrow = Debt::where('user_id', $userId)->borrow()->pending()->sum('remaining');

      return view('debts.index', compact('debts', 'type', 'totalLend', 'totalBorrow'));
   }

   /**
    * Show the form for creating a new debt.
    */
   public function create(Request $request)
   {
      $type = $request->get('type', 'lend');
      return view('debts.create', compact('type'));
   }

   /**
    * Store a newly created debt.
    */
   public function store(Request $request)
   {
      $validated = $request->validate([
         'person_name' => 'required|string|max:255',
         'phone' => 'nullable|string|max:20',
         'type' => 'required|in:lend,borrow',
         'amount' => 'required|numeric|min:1',
         'description' => 'nullable|string|max:500',
         'debt_date' => 'required|date',
         'due_date' => 'nullable|date|after_or_equal:debt_date',
      ]);

      $validated['user_id'] = Auth::id();
      $validated['remaining'] = $validated['amount'];
      $validated['status'] = 'pending';

      Debt::create($validated);

      $message = $validated['type'] === 'lend'
         ? __('Đã thêm khoản cho vay mới!')
         : __('Đã thêm khoản đi vay mới!');

      return redirect()->route('debts.index', ['type' => $validated['type']])
         ->with('success', $message);
   }

   /**
    * Display the specified debt with payment history.
    */
   public function show(Debt $debt)
   {
      $this->authorize('view', $debt);

      $debt->load('payments');

      return view('debts.show', compact('debt'));
   }

   /**
    * Show the form for editing the debt.
    */
   public function edit(Debt $debt)
   {
      $this->authorize('update', $debt);

      return view('debts.edit', compact('debt'));
   }

   /**
    * Update the specified debt.
    */
   public function update(Request $request, Debt $debt)
   {
      $this->authorize('update', $debt);

      $validated = $request->validate([
         'person_name' => 'required|string|max:255',
         'phone' => 'nullable|string|max:20',
         'description' => 'nullable|string|max:500',
         'due_date' => 'nullable|date',
      ]);

      $debt->update($validated);

      return redirect()->route('debts.show', $debt)
         ->with('success', __('Cập nhật thành công!'));
   }

   /**
    * Remove the specified debt.
    */
   public function destroy(Debt $debt)
   {
      $this->authorize('delete', $debt);

      $type = $debt->type;
      $debt->delete();

      return redirect()->route('debts.index', ['type' => $type])
         ->with('success', __('Đã xóa khoản nợ!'));
   }

   /**
    * Add a payment to the debt.
    */
   public function addPayment(Request $request, Debt $debt)
   {
      $this->authorize('update', $debt);

      $validated = $request->validate([
         'amount' => 'required|numeric|min:1|max:' . $debt->remaining,
         'payment_date' => 'required|date',
         'note' => 'nullable|string|max:255',
      ]);

      $validated['debt_id'] = $debt->id;

      DebtPayment::create($validated);

      // Update remaining amount
      $debt->remaining -= $validated['amount'];

      // Update status
      if ($debt->remaining <= 0) {
         $debt->remaining = 0;
         $debt->status = 'settled';
      } else {
         $debt->status = 'partial';
      }

      $debt->save();

      return redirect()->route('debts.show', $debt)
         ->with('success', __('Đã ghi nhận thanh toán!'));
   }

   /**
    * Mark the debt as fully settled.
    */
   public function settle(Debt $debt)
   {
      $this->authorize('update', $debt);

      if ($debt->remaining > 0) {
         // Create a final payment for the remaining amount
         DebtPayment::create([
            'debt_id' => $debt->id,
            'amount' => $debt->remaining,
            'payment_date' => now(),
            'note' => __('Thanh toán toàn bộ'),
         ]);
      }

      $debt->update([
         'remaining' => 0,
         'status' => 'settled',
      ]);

      return redirect()->route('debts.show', $debt)
         ->with('success', __('Đã hoàn tất khoản nợ!'));
   }
}
