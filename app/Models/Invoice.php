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

     //Get the student related to this invoice.
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function paymentSource()
    {
        return $this->belongsTo(PaymentSource::class, 'payment_sources_id');
    }

    // Scope to get only paid invoices.
   
    public function scopePaid($query)
    {
        return $query->where('status', 1);
    }

     // Scope to get only unpaid invoices.
    public function scopeUnpaid($query)
    {
        return $query->where('status', 0);
    }

     // Mark the invoice as paid.
     public function isPaid()
    {
        return $this->status === true;
    }

     // Check if the invoice is overdue.
    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast() && !$this->status;
    }
}
