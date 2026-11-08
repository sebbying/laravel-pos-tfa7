<?php
namespace App\Http\Controllers;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class CustomerController extends Controller
{
    public function index(): View
    {
        return view('customers.index', ['customers' => Customer::orderBy('id', 'desc')->paginate(10)]);
    }
    public function create(): View { return view('customers.create'); }
    public function store(CustomerRequest $request): RedirectResponse
    {
        Customer::create($request->validated());
        return to_route('customers.index')->with('success', 'Customer created successfully.');
    }
    public function edit(Customer $customer): View { return view('customers.edit', compact('customer')); }
    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());
        return to_route('customers.index')->with('success', 'Customer updated successfully.');
    }
}
