<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
    use HasFactory;

    protected $table = 'degrees';

    protected $fillable = [
        'student_id',
        'course_session_id',
        'practical_degree',
        'final_degree',
        'attendance_degree',
        'total_degree',
        'state',
    ];

    protected $casts = [
        'practical_degree' => 'decimal:2',
        'final_degree' => 'decimal:2',
        'attendance_degree' => 'decimal:2',
        'total_degree' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }


    public function session()
    {
        return $this->belongsTo(CourseSession::class, 'course_session_id');
    }


    public function calculateTotal()
    {
        $this->total_degree = $this->practical_degree + $this->final_degree + $this->attendance_degree;
        $this->save();
    }


    public function determineStatus($passingMark = 50)
    {
        $this->status = $this->total_degree >= $passingMark ? 'pass' : 'fail';
        $this->save();
    }


    public function scopePassed($query)
    {
        return $query->where('status', 'pass');
    }


    public function scopeFailed($query)
    {
        return $query->where('status', 'fail');
    }


    public function isPassed()
    {
        return $this->status === 'pass';
    }
}
