<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Position;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Position\PutPositionRequest;
use App\Http\Requests\Intranet\Position\StorePositionRequest;

class PositionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(Position::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePositionRequest $request)
    {
        $position = Position::create($request->validated());
        return $this->respondCreated($position);
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
        return $this->respond($position);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutPositionRequest $request, Position $position)
    {
        $position->update($request->validated());
        return $this->respond($position);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        $position->delete();
        return $this->respondSuccess();
    }
}
