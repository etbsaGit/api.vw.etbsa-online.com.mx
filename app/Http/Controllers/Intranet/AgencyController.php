<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Agency;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Agency\PutAgencyRequest;
use App\Http\Requests\Intranet\Agency\StoreAgencyRequest;

class AgencyController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(Agency::with('municipality','state')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAgencyRequest $request)
    {
        $agency = Agency::create($request->validated());
        return $this->respondCreated($agency);
    }

    /**
     * Display the specified resource.
     */
    public function show(Agency $agency)
    {
        return $this->respond($agency);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutAgencyRequest $request, Agency $agency)
    {
        $agency->update($request->validated());
        return $this->respond($agency);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agency $agency)
    {
        $agency->delete();
        return $this->respondSuccess();
    }
}
