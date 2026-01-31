<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
   protected $fillable = [
      'user_id',
      'family_id',
      'name',
      'type',
      'balance',
      'currency',
      'icon',
      'color',
      'is_default',
   ];

   protected $casts = [
      'balance' => 'decimal:2',
      'is_default' => 'boolean',
   ];

   /**
    * Get the user that owns the wallet.
    */
   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class);
   }

   /**
    * Get the family this wallet is shared with.
    */
   public function family(): BelongsTo
   {
      return $this->belongsTo(Family::class);
   }

   /**
    * Get the transactions for this wallet.
    */
   public function transactions(): HasMany
   {
      return $this->hasMany(Transaction::class);
   }

   /**
    * Get incoming transfers to this wallet.
    */
   public function incomingTransfers(): HasMany
   {
      return $this->hasMany(Transaction::class, 'to_wallet_id');
   }

   /**
    * Update balance based on transaction.
    */
   public function updateBalance(float $amount, string $type): void
   {
      if ($type === 'income') {
         $this->balance += $amount;
      } elseif ($type === 'expense') {
         $this->balance -= $amount;
      }
      $this->save();
   }

   /**
    * Check if wallet is shared with a family.
    */
   public function isShared(): bool
   {
      return $this->family_id !== null;
   }

   /**
    * Scope for shared wallets.
    */
   public function scopeShared($query)
   {
      return $query->whereNotNull('family_id');
   }

   /**
    * Scope for personal (unshared) wallets.
    */
   public function scopePersonal($query)
   {
      return $query->whereNull('family_id');
   }
}

