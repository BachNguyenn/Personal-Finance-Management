<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Family extends Model
{
   protected $fillable = [
      'name',
      'invite_code',
      'created_by',
   ];

   protected static function boot()
   {
      parent::boot();

      static::creating(function ($family) {
         if (empty($family->invite_code)) {
            $family->invite_code = strtoupper(Str::random(8));
         }
      });
   }

   public function creator(): BelongsTo
   {
      return $this->belongsTo(User::class, 'created_by');
   }

   public function members(): HasMany
   {
      return $this->hasMany(FamilyMember::class);
   }

   public function wallets(): HasMany
   {
      return $this->hasMany(Wallet::class);
   }

   /**
    * Get all users in this family.
    */
   public function users()
   {
      return $this->hasManyThrough(User::class, FamilyMember::class, 'family_id', 'id', 'id', 'user_id');
   }

   /**
    * Check if a user is a member of this family.
    */
   public function hasMember($userId): bool
   {
      return $this->members()->where('user_id', $userId)->exists();
   }

   /**
    * Get the role of a user in this family.
    */
   public function getMemberRole($userId): ?string
   {
      $member = $this->members()->where('user_id', $userId)->first();
      return $member ? $member->role : null;
   }

   /**
    * Check if a user is an admin of this family.
    */
   public function isAdmin($userId): bool
   {
      return $this->getMemberRole($userId) === 'admin';
   }

   /**
    * Regenerate invite code.
    */
   public function regenerateInviteCode(): void
   {
      $this->update(['invite_code' => strtoupper(Str::random(8))]);
   }
}
