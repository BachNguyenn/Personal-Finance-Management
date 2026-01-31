<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetAlert extends Model
{
   protected $fillable = [
      'budget_id',
      'user_id',
      'percentage_used',
      'spent_amount',
      'alert_type',
      'is_read',
   ];

   protected $casts = [
      'spent_amount' => 'decimal:2',
      'is_read' => 'boolean',
   ];

   public function budget(): BelongsTo
   {
      return $this->belongsTo(Budget::class);
   }

   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class);
   }

   // Scopes
   public function scopeUnread($query)
   {
      return $query->where('is_read', false);
   }

   public function scopeForUser($query, $userId)
   {
      return $query->where('user_id', $userId);
   }
}
