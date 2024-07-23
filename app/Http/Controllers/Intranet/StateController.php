<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\State;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\State\PutStateRequest;
use App\Http\Requests\Intranet\State\StoreStateRequest;

class StateController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(State::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStateRequest $request)
    {
        $state = State::create($request->validated());
        return $this->respondCreated($state);
    }

    /**
     * Display the specified resource.
     */
    public function show(State $state)
    {
        return $this->respond($state);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutStateRequest $request, State $state)
    {
        $state->update($request->validated());
        return $this->respond($state);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(State $state)
    {
        $state->delete();
        return $this->respondSuccess();
    }
}
