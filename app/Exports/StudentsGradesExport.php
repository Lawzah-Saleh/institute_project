<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\CourseSession;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsGradesExport implements FromCollection, WithHeadings
{
    protected $students;

    public function __construct($students)
    {
        $this->students = $students;
    }

    public function collection()
    {
        $data = [];
        
        foreach ($this->students as $student) {
            foreach ($student->sessions as $session) {
                $data[] = [
                    'رقم الطالب' => $student->id,
                    'اسم الطالب' => $student->student_name_ar . ' (' . $student->student_name_en . ')',
                    'درجة الحضور' => $session->pivot->attendance_score ?? '0',
                    'الدرجة الكلية' => $session->pivot->final_score ?? '0',
                    'التقدير' => $this->getGrade($session->pivot->final_score)
                ];
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'رقم الطالب',
            'اسم الطالب',
            'درجة الحضور (من 10)',
            'الدرجة الكلية',
            'التقدير'
        ];
    }

    // Method to calculate the grade
    public function getGrade($finalScore)
    {
        if ($finalScore >= 90) return 'ممتاز';
        if ($finalScore >= 80) return 'جيد جداً';
        if ($finalScore >= 70) return 'جيد';
        if ($finalScore >= 60) return 'مقبول';
        return 'راسب';
    }
}
