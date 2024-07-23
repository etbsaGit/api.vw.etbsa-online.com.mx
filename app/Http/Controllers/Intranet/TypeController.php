<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Type;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Type\StoreTypeRequest;

class TypeController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(Type::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeRequest $request)
    {
        $type = Type::create($request->validated());
        return $this->respondCreated($type);
    }

    /**
     * Display the specified resource.
     */
    public function show(Type $type)
    {
        return $this->respond($type);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTypeRequest $request, Type $type)
    {
        $type->update($request->validated());
        return $this->respond($type);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        $type->delete();
        return $this->respondSuccess();
    }

    public function getPerKey($key)
    {
        $types = Type::where('type_key', $key)->get();

        return $this->respond($types);
    }
}
