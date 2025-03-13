<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CourseSession extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'employee_id', 'start_date', 'end_date', 'start_time', 'end_time', 'daily_hours', 'state'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // حساب تاريخ الانتهاء تلقائيًا عند الحفظ
    public static function calculateEndDate($courseId, $startDate, $dailyHours)
    {
        $course = Course::find($courseId);
        if (!$course || !$dailyHours || $dailyHours <= 0) {
            return $startDate; // إرجاع نفس التاريخ في حال الخطأ
        }

        $totalHours = $course->duration;
        $daysNeeded = ceil($totalHours / $dailyHours);

        $start = Carbon::parse($startDate);
        $holidays = Holiday::pluck('date')->toArray(); // جلب أيام الإجازات من قاعدة البيانات

        $currentDate = $start->copy();
        $addedDays = 0;

        while ($addedDays < $daysNeeded) {
            $currentDate->addDay();

            // التأكد من أن اليوم ليس إجازة وليس يوم الجمعة
            if (!in_array($currentDate->toDateString(), $holidays) && $currentDate->dayOfWeek !== Carbon::FRIDAY) {
                $addedDays++;
            }
        }

        return $currentDate->toDateString();
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_students', 'course_session_id', 'student_id');
    }
    

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'session_id');
    }
    
    



}
