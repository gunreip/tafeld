<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function create()
    {
        return view('customers.create');
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Datei-Upload behandeln
        if ($request->hasFile('personalausweis_dokument')) {
            $data['personalausweis_dokument'] = $request->file('personalausweis_dokument')
                ->store('uploads/customers', 'public');
        }

        // Kundennummer automatisch vergeben, falls leer
        if (empty($data['customer_number'])) {
            $data['customer_number'] = 'C' . str_pad(Customer::count() + 1, 5, '0', STR_PAD_LEFT);
        }

        $customer = Customer::create($data);

        return redirect()
            ->route('customers.create')
            ->with('success', "Kunde {$customer->first_name} {$customer->last_name} wurde erfolgreich angelegt.");
    }
}
