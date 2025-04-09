<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentStatementExport implements FromCollection, WithHeadings
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
            'رقم الفاتورة',
            'اسم الطالب',
            'المبلغ المدفوع',
            'تاريخ الدفع',
        ];
    }
}

