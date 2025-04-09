<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Student;
use App\Models\Payment;
use App\Models\PaymentSource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * عرض قائمة الحوافظ.
     */
    public function index()
    {
        $invoices = Invoice::with(['student', 'payment'])->latest()->get();
        return view('admin.pages.invoices.index', compact('invoices'));
    }

    /**
     * عرض نموذج إنشاء حافظة جديدة.
     */
    public function create()
    {
        $students = Student::with('payments')->get();
        return view('admin.pages.invoices.create', compact('students'));
    }

    /**
     * حفظ حافظة جديدة.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'payment_id' => 'required|exists:payments,id',
            'amount' => 'required|numeric|min:1',
            'invoice_details' => 'required|string|max:500',
            'due_date' => 'nullable|date',
        ]);

        Invoice::create([
            'student_id' => $request->student_id,
            'payment_id' => $request->payment_id,
            'payment_sources_id' => null,
            'amount' => $request->amount,
            'status' => 0, // لم يتم الدفع
            'invoice_number' => 'INV-' . time(),
            'invoice_details' => $request->invoice_details,
            'due_date' => $request->due_date,
        ]);

        return redirect()->back()->with('success', '✅ تم إضافة الرسوم (الحافظة) بنجاح.');
    }


    /**
     * عرض تفاصيل حافظة.
     */
    public function show($id)
    {
        $invoice = Invoice::with(['student', 'payment'])->findOrFail($id);
        return view('admin.pages.invoices.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = Invoice::with(['student', 'payment', 'paymentSource'])->findOrFail($id);
        $sources = PaymentSource::where('status', 'active')->get();

        return view('admin.pages.invoices.edit', compact('invoice', 'sources'));
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'invoice_details' => 'required|string',
            'due_date' => 'required|date',
            'payment_sources_id' => 'nullable|exists:payment_sources,id',
        ]);

        $invoice->update([
            'amount' => $request->amount,
            'invoice_details' => $request->invoice_details,
            'due_date' => $request->due_date,
            'payment_sources_id' => $request->payment_sources_id,
        ]);

        return redirect()->route('admin.student.payment.details', $invoice->student_id)
                         ->with('success', 'تم تعديل الفاتورة بنجاح ✅');
    }

    /**
     * حذف الحافظة.
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->back()->with('success', 'تم حذف الحافظة بنجاح');
    }
}
