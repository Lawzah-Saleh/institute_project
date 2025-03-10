<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEvaluation extends Model
{
    use HasFactory;

    protected $table = 'course_evaluations'; // اسم الجدول في قاعدة البيانات

    protected $fillable = [
        'student_id',
        'course_session_id',
        'rating',
        'feedback',
        'date',
    ];

    protected $casts = [
        'rating' => 'integer',
        'date' => 'date',
    ];


    public function student()
    {
        return $this->belongsTo(Student::class);
    }


    public function courseSession()
    {
        return $this->belongsTo(CourseSession::class);
    }


}
