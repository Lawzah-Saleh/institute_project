<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Payment;
use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;

class PaidStudentsExport implements FromCollection
{
    protected $departmentId;
    protected $courseId;
    protected $sessionId;

    public function __construct($departmentId, $courseId, $sessionId)
    {
        $this->departmentId = $departmentId;
        $this->courseId = $courseId;
        $this->sessionId = $sessionId;
    }

    public function collection()
    {
        // Get the students filtered by department, course, and session
        $students = Student::query()
                            ->where('department_id', $this->departmentId)
                            ->where('course_id', $this->courseId)
                            ->where('session_id', $this->sessionId)
                            ->get();

        $reportData = collect();

        // Loop through each student to get their payment and invoice details
        foreach ($students as $student) {
            // Sum of payments for this student
            $totalPaid = Payment::where('student_id', $student->id)->sum('amount');

            // Total amount from invoices for this student
            $totalAmount = Invoice::where('student_id', $student->id)->sum('amount');

            // Remaining amount (totalAmount - totalPaid)
            $remainingAmount = $totalAmount - $totalPaid;

            // Add the student data to the report collection
            $reportData->push([
                'student_id' => $student->id,
                'student_name_ar' => $student->student_name_ar,
                'student_name_en' => $student->student_name_en,
                'email' => $student->email,
                'total_paid' => number_format($totalPaid, 2),
                'total_amount' => number_format($totalAmount, 2),
                'remaining_amount' => number_format($remainingAmount, 2),
            ]);
        }

        return $reportData;
    }
}
