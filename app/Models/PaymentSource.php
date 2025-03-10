<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSource extends Model
{
    use HasFactory;

    protected $table = 'payment_sources'; // اسم الجدول

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Scope to get only active payment sources.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get only inactive payment sources.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Check if the payment source is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
}
