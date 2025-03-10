<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;



class Employee extends Model
{
    protected $fillable = [
        'name_en', 'name_ar', 'phones', 'address', 'gender', 'birth_date', 
        'birth_place', 'image', 'email', 'emptype', 'state','user_id'
    ];

    protected $casts = [
        'phones' => 'array',
        'state' => 'boolean', 
    ];
    public function qualifications()
    {
        return $this->hasMany(Qualification::class);
    }

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
    public function getTeacherSessions(Request $request)
    {
        $employeeId = auth()->user()->employee->id; // جلب ID الموظف المرتبط بالمستخدم

        $sessions = CourseSession::where('teacher_id', $employeeId)
            ->with('students')
            ->get();

        return response()->json($sessions);
    }


}
