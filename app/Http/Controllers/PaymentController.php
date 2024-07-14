<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', Payment::class);
        $payments = Payment::all();
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $this->authorize('create', Payment::class);
        return view('payments.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Payment::class);

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_type' => 'required|string|max:255',
            'payment_status' => 'required|string|max:255',
        ]);

        Payment::create($request->all());

        return redirect()->route('payments.index');
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $this->authorize('update', $payment);
        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_type' => 'required|string|max:255',
            'payment_status' => 'required|string|max:255',
        ]);

        $payment->update($request->all());

        return redirect()->route('payments.index');
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);
        $payment->delete();

        return redirect()->route('payments.index');
    }
}
