<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('dashboard.customers.index', compact('customers'));
    }

    // Show the form for creating a new customer
    public function create()
    {
        return view('dashboard.customers.create');
    }

    // Store a newly created customer in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'nullable|email|unique:customers',
            'phone' => 'nullable',
            'notes' => 'nullable',
        ]);

        Customer::create($validatedData);
        return response()->json();
    }

    // Display the specified customer
    public function show( $customer)
    {
        $customer = Customer::findOrFail($customer);
        return view('dashboard.customers.show', compact('customer'));
    }

    // Show the form for editing the specified customer
    public function edit( $customer)
    {
        $customer = Customer::findOrFail($customer);
        return view('dashboard.customers.create', compact('customer'));
    }

    // Update the specified customer in the database
    public function update(Request $request,  $customer)
    {
        $customer = Customer::findOrFail($customer);

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable',
            'notes' => 'nullable',
        ]);

        $customer->update($validatedData);
        return response()->json();
    }

    // Remove the specified customer from the database
    public function destroy( $customer)
    {
        $customer = Customer::findOrFail($customer);
        $customer->delete();
        return response()->json();
    }
}
