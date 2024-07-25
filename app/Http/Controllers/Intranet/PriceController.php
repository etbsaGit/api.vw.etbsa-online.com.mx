<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Price;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Price\StorePriceRequest;

class PriceController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(Price::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePriceRequest $request)
    {
        $data = $request->validated();

        // Verificar si ya existe una combinación vehicle_id y feature_id
        if (Price::where('vehicle_id', $data['vehicle_id'])
            ->where('type_id', $data['type_id'])
            ->exists()
        ) {
            return response()->json(['Precio' => 'El tipo de precio ya esta asignado'], 422);
        }

        // Si no existe, crear el nuevo registro
        $vehicleFeature = Price::create($data);

        return $this->respondCreated($vehicleFeature);
    }

    /**
     * Display the specified resource.
     */
    public function show(Price $price)
    {
        return $this->respond($price);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePriceRequest $request, Price $price)
    {
        $data = $request->validated();

        // Verificar si ya existe una combinación vehicle_id y feature_id
        // Excluyendo el registro actual de la verificación
        if (Price::where('vehicle_id', $data['vehicle_id'])
            ->where('type_id', $data['type_id'])
            ->where('id', '!=', $price->id)
            ->exists()
        ) {
            return response()->json(['error' => 'El tipo de precio ya esta asignado'], 422);
        }

        // Si no existe una combinación duplicada, proceder con la actualización
        $price->update($data);

        return $this->respond($price);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Price $price)
    {
        $price->delete();
        return $this->respondSuccess();
    }

    public function getPerVehicle($id)
    {
        $prices = Price::where('vehicle_id', $id)->with('type')->get();
        return $this->respond($prices);
    }
}
