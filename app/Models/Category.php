<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
   protected $fillable = [
      'user_id',
      'name',
      'type',
      'icon',
      'color',
      'parent_id',
      'keywords',
      'position',
   ];

   protected $casts = [
      'keywords' => 'array',
   ];

   /**
    * Get the user that owns the category.
    */
   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class);
   }

   /**
    * Get the parent category.
    */
   public function parent(): BelongsTo
   {
      return $this->belongsTo(Category::class, 'parent_id');
   }

   /**
    * Get the child categories.
    */
   public function children(): HasMany
   {
      return $this->hasMany(Category::class, 'parent_id');
   }

   /**
    * Get the transactions for this category.
    */
   public function transactions(): HasMany
   {
      return $this->hasMany(Transaction::class);
   }

   /**
    * Scope to filter income categories.
    */
   public function scopeIncome($query)
   {
      return $query->where('type', 'income');
   }

   /**
    * Scope to filter expense categories.
    */
   public function scopeExpense($query)
   {
      return $query->where('type', 'expense');
   }
}
