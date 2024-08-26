<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Type;
use App\Models\Intranet\Status;
use App\Models\Intranet\Inventory;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Inventory\PutInventoryRequest;
use App\Http\Requests\Intranet\Inventory\StoreInventoryRequest;
use App\Models\Intranet\Agency;
use App\Models\Intranet\Vehicle;
use App\Models\Intranet\VehicleBody;

class InventoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $inventories = Inventory::filterInventories($filters)->with('status','type','agency','vehicle','vehicleBody','vehicleBody.type')->paginate(10);
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
            'vehicleBodies' => VehicleBody::all(),
        ];

        return $this->respond($data);
    }
}
