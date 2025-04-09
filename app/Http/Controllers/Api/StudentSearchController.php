<?php
namespace App\Http\Controllers\Api;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        $students = Student::where('student_name_ar', 'like', "%$query%")
            ->orWhere('student_name_en', 'like', "%$query%")
            ->withSum('invoices', 'amount')
            ->withSum('payments', 'total_amount')
            ->limit(10)
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->student_name_ar,
                    'email' => $student->email,
                    'total_due' => $student->invoices_sum_amount ?? 0,
                    'paid_amount' => $student->payments_sum_total_amount ?? 0,
                ];
            });

        return response()->json($students);
    }
}
