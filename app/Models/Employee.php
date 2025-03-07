<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Employee extends Model
{
    protected $fillable = [
        'user_id', 'name_en', 'name_ar', 'phone', 'address', 'gender',
        'Day_birth', 'place_birth', 'image', 'email', 'emptype', 'state'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // سكوب لجلب المدرسين فقط
    public function scopeTeachers($query)
    {
        return $query->where('emptype', 'teacher');
    }
    public function courseSessions()
    {
        return $this->hasMany(CourseSession::class, 'employee_id');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id')
                    ->wherePivot('model_type', 'App\Models\User'); // Ensure it connects via User
    }

}
