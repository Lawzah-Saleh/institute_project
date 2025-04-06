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
        'course_id',
        'total_amount',  // المبلغ الكلي
        'status',
    ];

    // الدفع مرتبط بالطالب
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // الدفع مرتبط بالفاتورة
    public function invoice()
    {
        return $this->hasMany(Invoice::class);
    }

    // الدفع مرتبط بمصدر الدفع

        // تعريف العلاقة مع الكورس
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

}
