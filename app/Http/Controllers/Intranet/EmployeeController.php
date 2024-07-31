<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Employee;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Employee\PutEmployeeRequest;
use App\Http\Requests\Intranet\Employee\StoreEmployeeRequest;
use App\Models\Intranet\Agency;

class EmployeeController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $employees = Employee::filterEmployees($filters)->with('agency')->paginate(10);
        return $this->respond($employees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $employee = Employee::create($request->validated());
        return $this->respondCreated($employee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return $this->respond($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());
        return $this->respond($employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return $this->respondSuccess();
    }

    public function getOptions()
    {
        $data = [
            'agencies' => Agency::all(),
        ];

        return $this->respond($data);
    }
}
