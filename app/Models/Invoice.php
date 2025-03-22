<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices'; // Explicitly define table name if needed

    protected $fillable = [
        'student_id',
        'payment_sources_id', // مصدر الدفع
        'amount',
        'status',
        'invoice_number',
        'invoice_details',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'status' => 'boolean',  // Ensure 'status' is cast as a boolean
        'due_date' => 'date',   // Convert 'due_date' to a Carbon date object
        'paid_at' => 'datetime' // Convert 'paid_at' to a Carbon datetime object
    ];

     public function student()
     {
         return $this->belongsTo(Student::class);
     }

     public function paymentSource()
     {
         return $this->belongsTo(PaymentSource::class);
     }
     public function items()
     {
         return $this->hasMany(InvoiceItem::class);
     }


     public function payments()
     {
         return $this->hasMany(Payment::class);
     }


}
