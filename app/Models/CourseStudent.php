<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseStudent extends Model
{
    use HasFactory;

    protected $table = 'course_students'; // اسم الجدول في قاعدة البيانات

    protected $fillable = [
        'student_id',
        'course_id',
        'study_time',
        'status',
        'register_at',
    ];

    protected $casts = [
        'register_at' => 'datetime',
    ];


    public function student()
    {
        return $this->belongsTo(Student::class);
    }


    public function course()
    {
        return $this->belongsTo(Course::class);
    }




}
