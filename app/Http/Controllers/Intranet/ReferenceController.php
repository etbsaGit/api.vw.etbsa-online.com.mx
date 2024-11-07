<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Reference;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Reference\PutReferenceRequest;
use App\Http\Requests\Intranet\Reference\StoreReferenceRequest;
use App\Models\Intranet\Customer;

class ReferenceController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(Reference::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReferenceRequest $request)
    {
        $reference = Reference::create($request->validated());
        return $this->respondCreated($reference);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reference $reference)
    {
        return $this->respond($reference);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(PutReferenceRequest $request, Reference $reference)
    {
        $reference->update($request->validated());
        return $this->respond($reference);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reference $reference)
    {
        $reference->delete();
        return $this->respondSuccess();
    }

    public function getPerCustomer(Customer $customer)
    {
        $references = Reference::where('customer_id', $customer->id)->get();
        return $this->respond($references);
    }
}
