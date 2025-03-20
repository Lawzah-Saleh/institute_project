<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseStudent;

class Student extends Model
{
    protected $table = 'students'; // Ensure this is the actual table name in DB

    protected $fillable = [
        'user_id',
        'student_name_en',
        'student_name_ar',
        'image',
        'phone',
        'gender',
        'qualification',
        'birth_date',
        'birth_place',
        'address',
        'email',
        'state',
    ];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->hasOneThrough(Department::class, Course::class, 'id', 'id', 'course_id', 'department_id');
    }
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_students', 'student_id', 'course_id');
    }

    public function sessions()
    {
        return $this->belongsToMany(CourseSession::class, 'attendances')
                    ->withPivot('attendance_status')
                    ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // public function courseSessions()
    // {
    //     return $this->belongsToMany(CourseSessionStudent::class);
    // }
    public function courseSessionStudents()
    {
        return $this->belongsToMany(CourseSession::class, 'course_session_students');
    }


    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'student_id');
    }



}
