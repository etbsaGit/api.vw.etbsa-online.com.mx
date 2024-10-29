<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Type;
use App\Models\Intranet\Agency;
use App\Models\Intranet\Status;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Intranet\Vehicle;
use App\Models\Intranet\Inventory;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Inventory\PutInventoryRequest;
use App\Http\Requests\Intranet\Inventory\StoreInventoryRequest;

class InventoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $inventories = Inventory::filterInventories($filters)->with('status', 'type', 'agency', 'vehicle')->paginate(10);
        return $this->respond($inventories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventoryRequest $request)
    {
        $inventory = Inventory::create($request->validated());
        return $this->respondCreated($inventory);
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        return $this->respond($inventory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutInventoryRequest $request, Inventory $inventory)
    {
        $inventory->update($request->validated());
        return $this->respond($inventory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->forceDelete();
        return $this->respondSuccess();
    }

    public function getOptions()
    {
        $data = [
            'statuses' => Status::where('status_key', 'inventory')->get(),
            'types' => Type::where('type_key', 'inventory')->get(),
            'agencies' => Agency::all(),
            'vehicles' => Vehicle::all(),
        ];

        return $this->respond($data);
    }

    public function getPDFQuote(Request $request, Inventory $inventory)
    {
        // return $this->respond($inventory->load('vehicle.vehicleDocs','status','type','agency'));

        // Datos que quieres pasar a la vista
        $data = [
            'folio' => '000190924',
            'fecha' => '19 de Septiembre 2024',
            'precio_unitario' => 1597758.62,
            'iva' => 255641.38,
            'precio_total' => 1853400.00,
            'condiciones_pago' => 'En una sola exhibición',
            'fecha_entrega' => '4 a 5 semanas',
            'adicionales' => 'Incluye Tanque Pipa de 10 mil Lts.',
            'vigencia' => '30 de Septiembre 2024',
            'vendedor' => [
                'nombre' => 'Nombre del Vendedor',
                'telefono' => '555-123-4567',
                'email' => 'vendedor@empresa.com',
                'empresa' => 'Empresa XYZ S.A. de C.V.',
                'direccion' => 'Direccion 123'
            ]
        ];
        $pdf = Pdf::loadView('pdf.quote.quote', $data); // Carga la vista quote.blade.php
        return $pdf->download('quote.pdf'); // Descarga el PDF
    }
}
