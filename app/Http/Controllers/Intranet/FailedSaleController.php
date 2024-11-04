<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Quote;
use App\Models\Intranet\Status;
use App\Models\Intranet\FollowUp;
use App\Models\Intranet\FailedSale;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\FailedSale\StoreFailedSaleRequest;

class FailedSaleController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(FailedSale::with('followUp', 'type')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFailedSaleRequest $request)
    {
        $failedSale = FailedSale::create($request->validated());

        // Obtén el status con el nombre 'Venta perdida'
        $status = Status::where('name', 'Venta perdida')->first();

        // Obten el followUp
        $followUp = FollowUp::where('id', $failedSale->follow_up_id)->first();

        // Verifica si se encontró el status
        if ($status === null) {
            return response()->json(['error' => 'El status "Venta perdida" no se encuentra en la base de datos.'], 404);
        }

        // Actualiza el status_id del FollowUp
        $followUp->status_id = $status->id;
        $followUp->save();

        // Obtén el status con el nombre 'Venta perdida'
        $statusPerdida = Status::where('status_key', 'quote')->where('name', 'Perdida')->first();

        // Actualiza el status_id para todas las quotes relacionadas
        Quote::where('follow_up_id', $followUp->id)->update(['status_id' => $statusPerdida->id]);

        // Asume que 'children' es la relación que contiene los registros relacionados
        // Actualiza el status_id para todos los registros relacionados
        $followUp->children()->each(function ($child) use ($status) {
            $child->status_id = $status->id;
            $child->save();
        });

        return $this->respondCreated($failedSale);
    }

    /**
     * Display the specified resource.
     */
    public function show(FailedSale $failedSale)
    {
        return $this->respond($failedSale->load('followUp', 'type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreFailedSaleRequest $request, FailedSale $failedSale)
    {
        $failedSale->update($request->validated());

        // Obtén el status con el nombre 'Venta perdida'
        $status = Status::where('name', 'Venta perdida')->first();

        // Obten el followUp
        $followUp = FollowUp::where('id', $failedSale->follow_up_id)->first();

        // Verifica si se encontró el status
        if ($status === null) {
            return response()->json(['error' => 'El status "Venta perdida" no se encuentra en la base de datos.'], 404);
        }

        // Actualiza el status_id del FollowUp
        $followUp->status_id = $status->id;
        $followUp->save();

        // Asume que 'children' es la relación que contiene los registros relacionados
        // Actualiza el status_id para todos los registros relacionados
        $followUp->children()->each(function ($child) use ($status) {
            $child->status_id = $status->id;
            $child->save();
        });
        return $this->respond($failedSale);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FailedSale $failedSale)
    {
        $failedSale->delete();
        return $this->respondSuccess();
    }
}
