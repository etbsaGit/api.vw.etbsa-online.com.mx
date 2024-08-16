<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\StorePermissionRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(Permission::get());
    }

        /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        $permission = Permission::create($request->validated());
        return $this->respondCreated($permission);
    }

        /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        return $this->respond($permission);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePermissionRequest $request, Permission $permission)
    {
        $permission->update($request->validated());
        return $this->respond($permission);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return $this->respondSuccess();
    }
}
