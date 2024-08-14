<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Customer::class);
        $customers = Customer::all();
        return response()->json(['customers' => $customers]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Customer::class);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'is_guest' => 'required|boolean',
        ]);

        $customer = Customer::create($request->all());

        return response()->json(['customer' => $customer], 201);
    }

    public function show(Customer $customer): JsonResponse
    {
        $this->authorize('view', $customer);
        return response()->json(['customer' => $customer]);
    }

    public function update(Request $request, Customer $customer): JsonResponse
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

        return response()->json(['customer' => $customer]);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $this->authorize('delete', $customer);
        $customer->delete();

        return response()->json(null, 204);
    }
}
