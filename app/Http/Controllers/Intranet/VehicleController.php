<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Type;
use App\Models\Intranet\Brand;
use App\Models\Intranet\Vehicle;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Vehicle\PutVehicleRequest;
use App\Http\Requests\Intranet\Vehicle\StoreVehicleRequest;

class VehicleController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $vehicles = Vehicle::filterPage($filters)->with('brand','type')->paginate(10);
        return $this->respond($vehicles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request)
    {
        $vehicle = Vehicle::create($request->validated());
        return $this->respondCreated($vehicle);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        return $this->respond($vehicle);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());
        return $this->respond($vehicle);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return $this->respondSuccess();
    }

    public function getOptions()
    {
        $data = [
            'brands' => Brand::all(),
            'types' => Type::where('type_key', 'vehicle')->get(),
        ];

        return $this->respond($data);
    }
}
