<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
   /**
    * Display a listing of the wallets.
    */
   public function index()
   {
      $wallets = Wallet::where('user_id', Auth::id())
         ->orderBy('is_default', 'desc')
         ->orderBy('name')
         ->get();

      return view('wallets.index', compact('wallets'));
   }

   /**
    * Show the form for creating a new wallet.
    */
   public function create()
   {
      return view('wallets.create');
   }

   /**
    * Store a newly created wallet.
    */
   public function store(Request $request)
   {
      $validated = $request->validate([
         'name' => 'required|string|max:255',
         'type' => 'required|in:cash,bank,e-wallet',
         'balance' => 'nullable|numeric|min:0',
         'currency' => 'required|string|max:3',
         'icon' => 'nullable|string|max:50',
         'color' => 'nullable|string|max:7',
      ]);

      $validated['user_id'] = Auth::id();
      $validated['balance'] = $validated['balance'] ?? 0;

      // Check if this is the first wallet, make it default
      $hasWallets = Wallet::where('user_id', Auth::id())->exists();
      $validated['is_default'] = !$hasWallets;

      Wallet::create($validated);

      return redirect()->route('wallets.index')
         ->with('success', 'Ví đã được tạo thành công!');
   }

   /**
    * Show the form for editing the wallet.
    */
   public function edit(Wallet $wallet)
   {
      $this->authorize('update', $wallet);

      return view('wallets.edit', compact('wallet'));
   }

   /**
    * Update the specified wallet.
    */
   public function update(Request $request, Wallet $wallet)
   {
      $this->authorize('update', $wallet);

      $validated = $request->validate([
         'name' => 'required|string|max:255',
         'type' => 'required|in:cash,bank,e-wallet',
         'balance' => 'nullable|numeric|min:0',
         'currency' => 'required|string|max:3',
         'icon' => 'nullable|string|max:50',
         'color' => 'nullable|string|max:7',
         'is_default' => 'boolean',
      ]);

      // If setting as default, remove default from other wallets
      if (!empty($validated['is_default'])) {
         Wallet::where('user_id', Auth::id())
            ->where('id', '!=', $wallet->id)
            ->update(['is_default' => false]);
      }

      $wallet->update($validated);

      return redirect()->route('wallets.index')
         ->with('success', 'Ví đã được cập nhật!');
   }

   /**
    * Remove the specified wallet.
    */
   public function destroy(Wallet $wallet)
   {
      $this->authorize('delete', $wallet);

      // Check if wallet has transactions
      if ($wallet->transactions()->exists()) {
         return redirect()->route('wallets.index')
            ->with('error', 'Không thể xóa ví vì còn giao dịch liên quan!');
      }

      $wallet->delete();

      return redirect()->route('wallets.index')
         ->with('success', 'Ví đã được xóa!');
   }
}
