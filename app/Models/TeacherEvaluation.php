<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherEvaluation extends Model
{
    use HasFactory;

    protected $table = 'teacher_evaluations'; // Explicitly define table name if needed

    protected $fillable = [
        'student_id',
        'employee_id',
        'rating',
        'feedback',
        'date',
    ];

    protected $casts = [
        'date' => 'date', // Ensure 'date' is cast as a date object
    ];

    /**
     * Get the student who made the evaluation.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get the teacher (employee) being evaluated.
     */
    public function teacher()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Scope a query to filter evaluations by rating.
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }
}
