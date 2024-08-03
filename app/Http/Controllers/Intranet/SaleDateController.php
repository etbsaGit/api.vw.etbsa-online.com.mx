<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\SaleDate;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\SaleDate\StoreSaleDateRequest;

class SaleDateController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(SaleDate::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleDateRequest $request)
    {
        $data = $request->validated();

        // Verificar si ya existe una combinación vehicle_id y feature_id
        if (SaleDate::where('type_id', $data['type_id'])
            ->where('sale_id', $data['sale_id'])
            ->exists()
        ) {
            return response()->json(['caracteristica' => 'Tipo de fecha ya asignada'], 422);
        }

        // Si no existe, crear el nuevo registro
        $vehicleFeature = SaleDate::create($data);

        return $this->respondCreated($vehicleFeature);
    }

    /**
     * Display the specified resource.
     */
    public function show(SaleDate $saleDate)
    {
        return $this->respond($saleDate);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSaleDateRequest $request, SaleDate $saleDate)
    {
        $data = $request->validated();

        // Verificar si ya existe una combinación vehicle_id y feature_id
        // Excluyendo el registro actual de la verificación
        if (SaleDate::where('type_id', $data['type_id'])
            ->where('sale_id', $data['sale_id'])
            ->where('id', '!=', $saleDate->id)
            ->exists()
        ) {
            return response()->json(['error' => 'Tipo de fecha ya asignada'], 422);
        }

        // Si no existe una combinación duplicada, proceder con la actualización
        $saleDate->update($data);

        return $this->respond($saleDate);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaleDate $saleDate)
    {
        $saleDate->delete();
        return $this->respondSuccess();
    }

    public function getPerSale($id)
    {
        $dates = SaleDate::where('sale_id', $id)->with('type')->get();
        return $this->respond($dates);
    }

}
