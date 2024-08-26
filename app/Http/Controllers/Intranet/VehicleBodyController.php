<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\VehicleBody;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\VehicleBody\StoreVehicleBodyRequest;

class VehicleBodyController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(VehicleBody::with('type')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleBodyRequest $request)
    {
        $vehicleBody = VehicleBody::create($request->validated());
        return $this->respondCreated($vehicleBody);
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleBody $vehicleBody)
    {
        return $this->respond($vehicleBody);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreVehicleBodyRequest $request, VehicleBody $vehicleBody)
    {
        $vehicleBody->update($request->validated());
        return $this->respond($vehicleBody);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleBody $vehicleBody)
    {
        $vehicleBody->delete();
        return $this->respondSuccess();
    }
}
