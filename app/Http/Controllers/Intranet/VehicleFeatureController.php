<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Type;
use App\Http\Controllers\ApiController;
use App\Models\Intranet\VehicleFeature;
use App\Http\Requests\Intranet\VehicleFeature\StoreVehicleFeatureRequest;

class VehicleFeatureController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(VehicleFeature::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleFeatureRequest $request)
    {
        $data = $request->validated();

        // Verificar si ya existe una combinación vehicle_id y feature_id
        if (VehicleFeature::where('vehicle_id', $data['vehicle_id'])
            ->where('feature_id', $data['feature_id'])
            ->exists()
        ) {
            return response()->json(['caracteristica' => 'El vehiculo ya tiene la caracteristica'], 422);
        }

        // Si no existe, crear el nuevo registro
        $vehicleFeature = VehicleFeature::create($data);

        return $this->respondCreated($vehicleFeature);
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleFeature $vehicleFeature)
    {
        return $this->respond($vehicleFeature);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreVehicleFeatureRequest $request, VehicleFeature $vehicleFeature)
    {
        $vehicleFeature->update($request->validated());
        return $this->respond($vehicleFeature);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleFeature $vehicleFeature)
    {
        $vehicleFeature->delete();
        return $this->respondSuccess();
    }

    public function getPerVehicle($id)
    {
        $types = Type::where('type_key', 'features')
            ->with(['features' => function ($query) use ($id) {
                $query->whereHas('vehicles', function ($query) use ($id) {
                    $query->where('vehicle_id', $id);
                })->with(['vehicles' => function ($query) use ($id) {
                    $query->where('vehicle_id', $id);
                }]);
            }])
            ->get();

        return $this->respond($types);
    }
}
