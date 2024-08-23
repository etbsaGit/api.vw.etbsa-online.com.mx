<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Department;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Department\StoreDepartmentRequest;

class DepartmentController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(Department::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        $department = Department::create($request->validated());
        return $this->respondCreated($department);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return $this->respond($department);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreDepartmentRequest $request, Department $department)
    {
        $department->update($request->validated());
        return $this->respond($department);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return $this->respondSuccess();
    }
}
