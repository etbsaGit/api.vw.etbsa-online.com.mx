<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Status;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Status\StoreStatusRequest;

class StatusController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los tipos ordenados por type_key
        $statuses = Status::orderBy('status_key')->get();

        // Agrupar por 'status_key' y devolver los resultados como un arreglo asociativo
        $statusesArray = $statuses->groupBy('status_key');

        // Retornar la respuesta con el arreglo
        return $this->respond($statusesArray);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStatusRequest $request)
    {
        $status = Status::create($request->validated());
        return $this->respondCreated($status);
    }

    /**
     * Display the specified resource.
     */
    public function show(Status $status)
    {
        return $this->respond($status);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreStatusRequest $request, Status $status)
    {
        $status->update($request->validated());
        return $this->respond($status);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Status $status)
    {
        $status->delete();
        return $this->respondSuccess();
    }

    public function getPerKey($key)
    {
        $statuses = Status::where('status_key', $key)->get();

        return $this->respond($statuses);
    }
}
