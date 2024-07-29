<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\State;
use App\Models\Intranet\Customer;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Customer\PutCustomerRequest;
use App\Http\Requests\Intranet\Customer\StoreCustomerRequest;
use App\Models\Intranet\Type;

class CustomerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $customer = Customer::filterCustomers($filters)->with('municipality','state','type')->paginate(10);
        return $this->respond($customer);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->validated());
        return $this->respondCreated($customer);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return $this->respond($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());
        return $this->respond($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return $this->respondSuccess();
    }

    public function getOptions()
    {
        $data = [
            'states' => State::all(),
            'types' => Type::where('type_key', 'sat')->get(),
        ];

        return $this->respond($data);
    }
}
