<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Additional;
use App\Http\Controllers\ApiController;

class AdditionalController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(Additional::with('quote')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $additional = Additional::create($request->validated());
        return $this->respondCreated($additional);
    }

    /**
     * Display the specified resource.
     */
    public function show(Additional $additional)
    {
        return $this->respond($additional);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Additional $additional)
    {
        $additional->update($request->validated());
        return $this->respond($additional);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Additional $additional)
    {
        $additional->delete();
        return $this->respondSuccess();
    }
}
