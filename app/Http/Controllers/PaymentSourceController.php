<?php

namespace App\Http\Controllers;

use App\Models\PaymentSource;
use Illuminate\Http\Request;

class PaymentSourceController extends Controller
{
    /**
     * Display a listing of the payment sources.
     */
    public function index()
    {
        // Get all active payment sources
        $paymentSources = PaymentSource::all();

        return view('admin.pages.payment_sources.index', compact('paymentSources'));
    }

    /**
     * Show the form for creating a new payment source.
     */
    public function create()
    {
        return view('admin.pages.payment_sources.create');
    }

    /**
     * Store a newly created payment source in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        PaymentSource::create($request->all());

        return redirect()->route('payment_sources.index')
            ->with('success', 'Payment Source created successfully.');
    }


    /**
     * Show the form for editing the specified payment source.
     */
    public function edit(PaymentSource $paymentSource)
    {
        return view('admin.pages.payment_sources.edit', compact('paymentSource'));
    }

    /**
     * Update the specified payment source in storage.
     */
    public function update(Request $request, PaymentSource $paymentSource)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $paymentSource->update($request->all());

        return redirect()->route('payment_sources.index')
            ->with('success', 'Payment Source updated successfully.');
    }

    /**
     * Remove the specified payment source from storage.
     */
    public function destroy(PaymentSource $paymentSource)
    {
        $paymentSource->delete();

        return redirect()->route('payment_sources.index')
            ->with('success', 'Payment Source deleted successfully.');
    }
}
