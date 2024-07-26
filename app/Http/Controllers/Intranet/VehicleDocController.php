<?php

namespace App\Http\Controllers\Intranet;

use App\Traits\UploadFiles;
use Illuminate\Http\Request;
use App\Models\Intranet\VehicleDoc;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Intranet\VehicleDoc\StoreVehicleDocRequest;

class VehicleDocController extends ApiController
{
    use UploadFiles;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(VehicleDoc::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleDocRequest $request)
    {
        $vehicleDoc = VehicleDoc::create($request->validated());
        $relativePath  = $this->saveDoc($request['base64'], $vehicleDoc->default_path_folder);
        $updateData = ['path' => $relativePath];
        $vehicleDoc->update($updateData);
        return $this->respond($vehicleDoc);
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleDoc $vehicleDoc)
    {
        return $this->respond($vehicleDoc);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreVehicleDocRequest $request, VehicleDoc $vehicleDoc)
    {
        $vehicleDoc->update($request->validated());

        if (!is_null($request['base64'])) {
            if ($vehicleDoc->path) {
                Storage::disk('s3')->delete($vehicleDoc->path);
            }
            $relativePath  = $this->saveDoc($request['base64'], $vehicleDoc->default_path_folder);
            $updateData = ['path' => $relativePath];
            $vehicleDoc->update($updateData);
        }

        return $this->respond($vehicleDoc);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleDoc $vehicleDoc)
    {
        Storage::disk('s3')->delete($vehicleDoc->path);
        $vehicleDoc->delete();
        return $this->respondSuccess();
    }

    public function getPerVehicle($id)
    {
        $prices = VehicleDoc::where('vehicle_id', $id)->with('type')->get();
        return $this->respond($prices);
    }
}
