<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances'; // تحديد الجدول المرتبط بالموديل

    protected $fillable = [
        'student_id',
        'session_id',
        'employee_id',
        'attendance_date',
        'status'
    ];

    // العلاقة مع الطلاب
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // العلاقة مع الجلسات
    public function session()
    {
        return $this->belongsTo(CourseSession::class, 'session_id');
    }

    // العلاقة مع الموظفين (المعلمين)
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
