<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Type;
use App\Models\Intranet\Feature;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Feature\StoreFeatureRequest;

class FeatureController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener los tipos con la relación 'features' cargada
        $types = Type::where('type_key', 'features')->with('features')->get();

        // Retornar la respuesta
        return $this->respond($types);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeatureRequest $request)
    {
        $feature = Feature::create($request->validated());
        return $this->respondCreated($feature);
    }

    /**
     * Display the specified resource.
     */
    public function show(Feature $feature)
    {
        return $this->respond($feature);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreFeatureRequest $request, Feature $feature)
    {
        $feature->update($request->validated());
        return $this->respond($feature);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feature $feature)
    {
        $feature->delete();
        return $this->respondSuccess();
    }

    public function getPerType($type)
    {
        $features = Feature::where('type_id', $type)->get();
        return $this->respond($features);
    }
}
