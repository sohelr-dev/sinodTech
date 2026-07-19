<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'email', 'phone', 'last_purchase_at'];

    protected $casts = [
        'last_purchase_at' => 'datetime',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(CustomerAssignment::class);
    }

    public function promotionLogs(): HasMany
    {
        return $this->hasMany(PromotionLog::class);
    }

    public function isLost(int $days = 90): bool
    {
        if (!$this->last_purchase_at) {
            return true;
        }
        return $this->last_purchase_at->diffInDays(Carbon::now()) >= $days;
    }
}
