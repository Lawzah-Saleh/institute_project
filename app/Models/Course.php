<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';


    protected $fillable = ['department_id','course_name', 'duration','description', 'state'];

    public function latestActivePrice()
    {
        return $this->hasOne(CoursePrice::class, 'course_id')->where('state', 1)->latestOfMany();
    }


    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_students');
    }

    public function sessions()
{
    return $this->hasMany(CourseSession::class);
}
public function invoices()
{

    return $this->hasMany(Invoice::class, 'course_id');
}


}
