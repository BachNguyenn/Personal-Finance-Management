<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Debt extends Model
{
   protected $fillable = [
      'user_id',
      'person_name',
      'phone',
      'type',
      'amount',
      'remaining',
      'description',
      'debt_date',
      'due_date',
      'status',
   ];

   protected $casts = [
      'amount' => 'decimal:2',
      'remaining' => 'decimal:2',
      'debt_date' => 'date',
      'due_date' => 'date',
   ];

   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class);
   }

   public function payments(): HasMany
   {
      return $this->hasMany(DebtPayment::class)->orderBy('payment_date', 'desc');
   }

   // Scopes
   public function scopeLend($query)
   {
      return $query->where('type', 'lend');
   }

   public function scopeBorrow($query)
   {
      return $query->where('type', 'borrow');
   }

   public function scopePending($query)
   {
      return $query->whereIn('status', ['pending', 'partial']);
   }

   public function scopeSettled($query)
   {
      return $query->where('status', 'settled');
   }

   // Helpers
   public function isOverdue(): bool
   {
      return $this->due_date && $this->due_date->isPast() && $this->status !== 'settled';
   }

   public function getPaidAmount(): float
   {
      return $this->amount - $this->remaining;
   }

   public function getProgressPercentage(): float
   {
      if ($this->amount == 0)
         return 100;
      return round(($this->getPaidAmount() / $this->amount) * 100, 1);
   }
}
