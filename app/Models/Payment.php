<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'session_id', 'status', 'payment_date', 'amount', 'invoice_id'
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
}

