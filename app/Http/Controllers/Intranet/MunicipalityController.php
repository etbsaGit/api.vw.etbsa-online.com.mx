<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Municipality;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Municipality\PutMunicipalityRequest;
use App\Http\Requests\Intranet\Municipality\StoreMunicipalityRequest;

class MunicipalityController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(Municipality::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMunicipalityRequest $request)
    {
        $municipality = Municipality::create($request->validated());
        return $this->respondCreated($municipality);
    }

    /**
     * Display the specified resource.
     */
    public function show(Municipality $municipality)
    {
        return $this->respond($municipality);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutMunicipalityRequest $request, Municipality $municipality)
    {
        $municipality->update($request->validated());
        return $this->respond($municipality);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Municipality $municipality)
    {
        $municipality->delete();
        return $this->respondSuccess();
    }

    public function getPerState($id)
    {
        $municipalities = Municipality::where('state_id', $id)->get();
        return $this->respond($municipalities);
    }
}
