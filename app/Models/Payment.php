<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'student_id',
        'session_id',
        'invoice_id',
        'status',
        'payment_date',
        'amount',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];
    // الدفع مرتبط بالطالب
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // الدفع مرتبط بالفاتورة
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // الدفع مرتبط بمصدر الدفع
    public function paymentSource()
    {
        return $this->belongsTo(PaymentSource::class, 'payment_sources_id');
    }

}
