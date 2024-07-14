<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', Customer::class);
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $this->authorize('create', Customer::class);
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Customer::class);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'is_guest' => 'required|boolean',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index');
    }

    public function show(Customer $customer)
    {
        $this->authorize('view', $customer);
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $this->authorize('update', $customer);
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $this->authorize('update', $customer);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'is_guest' => 'required|boolean',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index');
    }

    public function destroy(Customer $customer)
    {
        $this->authorize('delete', $customer);
        $customer->delete();

        return redirect()->route('customers.index');
    }
}
