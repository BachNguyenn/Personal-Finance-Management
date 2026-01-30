<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Transaction extends Model
{
   protected $fillable = [
      'user_id',
      'wallet_id',
      'category_id',
      'type',
      'amount',
      'transaction_date',
      'description',
      'note',
      'attachment',
      'to_wallet_id',
   ];

   protected $casts = [
      'amount' => 'decimal:2',
      'transaction_date' => 'date',
   ];

   /**
    * Get the user that owns the transaction.
    */
   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class);
   }

   /**
    * Get the wallet for this transaction.
    */
   public function wallet(): BelongsTo
   {
      return $this->belongsTo(Wallet::class);
   }

   /**
    * Get the destination wallet for transfers.
    */
   public function toWallet(): BelongsTo
   {
      return $this->belongsTo(Wallet::class, 'to_wallet_id');
   }

   /**
    * Get the category for this transaction.
    */
   public function category(): BelongsTo
   {
      return $this->belongsTo(Category::class);
   }

   /**
    * Scope to filter income transactions.
    */
   public function scopeIncome(Builder $query): Builder
   {
      return $query->where('type', 'income');
   }

   /**
    * Scope to filter expense transactions.
    */
   public function scopeExpense(Builder $query): Builder
   {
      return $query->where('type', 'expense');
   }

   /**
    * Scope to filter transfer transactions.
    */
   public function scopeTransfer(Builder $query): Builder
   {
      return $query->where('type', 'transfer');
   }

   /**
    * Scope to filter transactions for current month.
    */
   public function scopeThisMonth(Builder $query): Builder
   {
      return $query->whereMonth('transaction_date', Carbon::now()->month)
         ->whereYear('transaction_date', Carbon::now()->year);
   }

   /**
    * Scope to filter transactions between dates.
    */
   public function scopeBetweenDates(Builder $query, $startDate, $endDate): Builder
   {
      return $query->whereBetween('transaction_date', [$startDate, $endDate]);
   }

   /**
    * Scope to filter transactions for a specific year.
    */
   public function scopeYear(Builder $query, int $year): Builder
   {
      return $query->whereYear('transaction_date', $year);
   }

   /**
    * Scope to filter transactions for a specific month.
    */
   public function scopeMonth(Builder $query, int $month): Builder
   {
      return $query->whereMonth('transaction_date', $month);
   }
}
