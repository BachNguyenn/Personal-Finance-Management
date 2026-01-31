<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyMember extends Model
{
   protected $fillable = [
      'family_id',
      'user_id',
      'role',
   ];

   public function family(): BelongsTo
   {
      return $this->belongsTo(Family::class);
   }

   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class);
   }

   /**
    * Check if this member is an admin.
    */
   public function isAdmin(): bool
   {
      return $this->role === 'admin';
   }

   /**
    * Check if this member can add transactions.
    */
   public function canAddTransactions(): bool
   {
      return in_array($this->role, ['admin', 'member']);
   }

   /**
    * Check if this member can manage other members.
    */
   public function canManageMembers(): bool
   {
      return $this->role === 'admin';
   }
}
