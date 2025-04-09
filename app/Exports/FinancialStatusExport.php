<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinancialStatusExport implements FromCollection, WithHeadings
{
    protected $students;

    public function __construct($students)
    {
        $this->students = $students;
    }

    public function collection()
    {
        // تحضير البيانات لتصديرها
        return $this->students->map(function ($student) {
            return [
                'الاسم' => $student->student_name_ar,
                'البريد الإلكتروني' => $student->email,
                'المبلغ المدفوع' => $student->payments_sum_total_amount,
                'المبلغ المستحق' => $student->invoices_sum_amount,
                'المبلغ المتبقي' => $student->invoices_sum_amount - $student->payments_sum_total_amount,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'الاسم',
            'البريد الإلكتروني',
            'المبلغ المدفوع',
            'المبلغ المستحق',
            'المبلغ المتبقي',
        ];
    }
}
