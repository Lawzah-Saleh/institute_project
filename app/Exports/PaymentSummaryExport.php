<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentSummaryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $payments;

    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    public function collection()
    {
        return $this->payments;
    }

    public function headings(): array
    {
        return [
            'رقم الطالب',
            'اسم الطالب',
            'البريد الإلكتروني',
            'المبلغ المدفوع',
            'تاريخ الدفع',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->student->id,
            $payment->student->student_name_ar,
            $payment->student->email,
            number_format($payment->total_amount, 2),
            $payment->created_at->format('Y-m-d'),
        ];
    }
}
