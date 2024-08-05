<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(Role::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::create($request->validated());
        return $this->respondCreated($role);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return $this->respond($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRoleRequest $request, Role $role)
    {
        $role->update($request->validated());
        return $this->respond($role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return $this->respondSuccess();
    }
}
