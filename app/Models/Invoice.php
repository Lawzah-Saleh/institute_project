<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'amount', 'status', 'invoice_number',
        'invoice_details', 'due_date', 'paid_at'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function payments()
{
    return $this->hasMany(Payment::class, 'invoice_id');
}
public function course()
{
    return $this->belongsTo(Course::class, 'course_id');
}

}
