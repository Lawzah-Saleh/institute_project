<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'student_id',
        'session_id',
        'employee_id',
        'attendance_date',
        'status',
    ];

    protected $casts = [
        'attendance_date' => 'datetime',
        'status' => 'boolean',
    ];


    public function student()
    {
        return $this->belongsTo(Student::class);
    }

 
    public function session()
    {
        return $this->belongsTo(CourseSession::class);
    }

  
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

  
    public function scopePresent($query)
    {
        return $query->where('status', true);
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', false);
    }


    public function isPresent()
    {
        return $this->status === true;
    }
}
