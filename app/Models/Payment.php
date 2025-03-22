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


    public function student()
    {
        return $this->belongsTo(Student::class);
    }


    public function session()
    {
        return $this->belongsTo(CourseSession::class, 'session_id');
    }


    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }



    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }


    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }


    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }


    public function isCompleted()
    {
        return $this->status === 'completed';
    }
    public function source()
{
    return $this->belongsTo(PaymentSource::class, 'payment_source_id');
}

}
