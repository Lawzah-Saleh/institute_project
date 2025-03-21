<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSessionStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_session_id',
        'student_id',
    ];

    public function session()
    {
        return $this->belongsTo(CourseSession::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id'); // Adjust the 'course_id' column if necessary
    }
}
