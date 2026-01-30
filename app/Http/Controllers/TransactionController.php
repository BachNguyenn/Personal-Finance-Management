<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
   /**
    * Display a listing of transactions.
    */
   public function index(Request $request)
   {
      $query = Transaction::where('user_id', Auth::id())
         ->with(['category', 'wallet']);

      // Filter by type
      if ($request->filled('type')) {
         $query->where('type', $request->type);
      }

      // Filter by category
      if ($request->filled('category_id')) {
         $query->where('category_id', $request->category_id);
      }

      // Filter by wallet
      if ($request->filled('wallet_id')) {
         $query->where('wallet_id', $request->wallet_id);
      }

      // Filter by date range
      if ($request->filled('start_date')) {
         $query->where('transaction_date', '>=', $request->start_date);
      }
      if ($request->filled('end_date')) {
         $query->where('transaction_date', '<=', $request->end_date);
      }

      // Search by description
      if ($request->filled('search')) {
         $query->where('description', 'like', '%' . $request->search . '%');
      }

      $transactions = $query->orderBy('transaction_date', 'desc')
         ->orderBy('created_at', 'desc')
         ->paginate(20)
         ->withQueryString();

      $categories = Category::where('user_id', Auth::id())->get();
      $wallets = Wallet::where('user_id', Auth::id())->get();

      return view('transactions.index', compact('transactions', 'categories', 'wallets'));
   }

   /**
    * Show the form for creating a new transaction.
    */
   public function create(Request $request)
   {
      $type = $request->query('type', 'expense');

      $categories = Category::where('user_id', Auth::id())
         ->where('type', $type)
         ->get();

      $wallets = Wallet::where('user_id', Auth::id())->get();

      return view('transactions.create', compact('type', 'categories', 'wallets'));
   }

   /**
    * Store a newly created transaction.
    */
   public function store(Request $request)
   {
      $validated = $request->validate([
         'wallet_id' => 'required|exists:wallets,id',
         'category_id' => 'nullable|exists:categories,id',
         'type' => 'required|in:income,expense,transfer',
         'amount' => 'required|numeric|min:0.01',
         'transaction_date' => 'required|date',
         'description' => 'nullable|string|max:255',
         'note' => 'nullable|string',
         'attachment' => 'nullable|image|max:2048',
         'to_wallet_id' => 'required_if:type,transfer|nullable|exists:wallets,id|different:wallet_id',
      ]);

      $validated['user_id'] = Auth::id();

      // Handle attachment upload
      if ($request->hasFile('attachment')) {
         $validated['attachment'] = $request->file('attachment')
            ->store('transactions', 'public');
      }

      // Verify wallet belongs to user
      $wallet = Wallet::where('id', $validated['wallet_id'])
         ->where('user_id', Auth::id())
         ->firstOrFail();

      // Create transaction
      $transaction = Transaction::create($validated);

      // Update wallet balance
      if ($validated['type'] === 'income') {
         $wallet->increment('balance', $validated['amount']);
      } elseif ($validated['type'] === 'expense') {
         $wallet->decrement('balance', $validated['amount']);
      } elseif ($validated['type'] === 'transfer') {
         $wallet->decrement('balance', $validated['amount']);

         $toWallet = Wallet::where('id', $validated['to_wallet_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();
         $toWallet->increment('balance', $validated['amount']);
      }

      return redirect()->route('transactions.index')
         ->with('success', 'Giao dịch đã được tạo thành công!');
   }

   /**
    * Show the form for editing the transaction.
    */
   public function edit(Transaction $transaction)
   {
      $this->authorize('update', $transaction);

      $categories = Category::where('user_id', Auth::id())
         ->where('type', $transaction->type)
         ->get();

      $wallets = Wallet::where('user_id', Auth::id())->get();

      return view('transactions.edit', compact('transaction', 'categories', 'wallets'));
   }

   /**
    * Update the specified transaction.
    */
   public function update(Request $request, Transaction $transaction)
   {
      $this->authorize('update', $transaction);

      $validated = $request->validate([
         'wallet_id' => 'required|exists:wallets,id',
         'category_id' => 'nullable|exists:categories,id',
         'amount' => 'required|numeric|min:0.01',
         'transaction_date' => 'required|date',
         'description' => 'nullable|string|max:255',
         'note' => 'nullable|string',
         'attachment' => 'nullable|image|max:2048',
      ]);

      // Verify wallet belongs to user
      $newWallet = Wallet::where('id', $validated['wallet_id'])
         ->where('user_id', Auth::id())
         ->firstOrFail();

      // Revert old wallet balance
      $oldWallet = $transaction->wallet;
      if ($transaction->type === 'income') {
         $oldWallet->decrement('balance', $transaction->amount);
      } elseif ($transaction->type === 'expense') {
         $oldWallet->increment('balance', $transaction->amount);
      }

      // Handle attachment upload
      if ($request->hasFile('attachment')) {
         // Delete old attachment
         if ($transaction->attachment) {
            Storage::disk('public')->delete($transaction->attachment);
         }
         $validated['attachment'] = $request->file('attachment')
            ->store('transactions', 'public');
      }

      // Update transaction
      $transaction->update($validated);

      // Apply new wallet balance
      if ($transaction->type === 'income') {
         $newWallet->increment('balance', $validated['amount']);
      } elseif ($transaction->type === 'expense') {
         $newWallet->decrement('balance', $validated['amount']);
      }

      return redirect()->route('transactions.index')
         ->with('success', 'Giao dịch đã được cập nhật!');
   }

   /**
    * Remove the specified transaction.
    */
   public function destroy(Transaction $transaction)
   {
      $this->authorize('delete', $transaction);

      // Revert wallet balance
      $wallet = $transaction->wallet;
      if ($transaction->type === 'income') {
         $wallet->decrement('balance', $transaction->amount);
      } elseif ($transaction->type === 'expense') {
         $wallet->increment('balance', $transaction->amount);
      } elseif ($transaction->type === 'transfer' && $transaction->toWallet) {
         $wallet->increment('balance', $transaction->amount);
         $transaction->toWallet->decrement('balance', $transaction->amount);
      }

      // Delete attachment
      if ($transaction->attachment) {
         Storage::disk('public')->delete($transaction->attachment);
      }

      $transaction->delete();

      return redirect()->route('transactions.index')
         ->with('success', 'Giao dịch đã được xóa!');
   }
}
