<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'start_date',
        'end_date',
        'alert_threshold',
        'alert_enabled',
        'last_alert_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'alert_enabled' => 'boolean',
        'last_alert_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(BudgetAlert::class);
    }

    /**
     * Calculate the spent amount for this budget period.
     */
    public function getSpentAmount(): float
    {
        if (!$this->category) {
            return 0;
        }

        return $this->category->transactions()
            ->where('user_id', $this->user_id)
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$this->start_date, $this->end_date])
            ->sum('amount');
    }

    /**
     * Get the percentage of budget used.
     */
    public function getUsedPercentage(): float
    {
        if ($this->amount <= 0) {
            return 0;
        }

        return round(($this->getSpentAmount() / $this->amount) * 100, 1);
    }

    /**
     * Check if the budget is currently active.
     */
    public function isActive(): bool
    {
        $today = now()->startOfDay();
        return $today->between($this->start_date, $this->end_date);
    }
}
