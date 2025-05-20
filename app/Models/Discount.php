<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'code',
        'type', // percentage or fixed
        'value',
        'start_date',
        'end_date',
        'min_booking_amount',
        'max_discount_amount',
        'is_active',
        'usage_limit',
        'used_count'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'value' => 'decimal:2',
        'min_booking_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'usage_limit' => 'integer',
        'used_count' => 'integer'
    ];

    public function isValid()
    {
        return $this->is_active &&
            now()->between($this->start_date, $this->end_date) &&
            ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }

    public function calculateDiscount($amount)
    {
        if (!$this->isValid() || $amount < $this->min_booking_amount) {
            return 0;
        }

        $discount = $this->type === 'percentage' 
            ? ($amount * $this->value / 100)
            : $this->value;

        if ($this->max_discount_amount !== null) {
            $discount = min($discount, $this->max_discount_amount);
        }

        return $discount;
    }
} 