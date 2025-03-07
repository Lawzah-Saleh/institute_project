<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    // تأكيد ارتباط النموذج بالجدول "departments"
    protected $table = 'departments';

    // الأعمدة القابلة للتعبئة
    protected $fillable = ['department_name', 'department_info', 'state'];
    // public function students()
    // {
    //     return $this->belongsToMany(Student::class, 'department_student');
    // }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }




}
