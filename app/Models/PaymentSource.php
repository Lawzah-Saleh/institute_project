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
    // مصدر الدفع يمكن أن يكون مرتبطًا بعدة دفعات
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
