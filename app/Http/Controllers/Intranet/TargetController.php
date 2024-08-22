<?php

namespace App\Http\Controllers\Intranet;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Intranet\Type;
use App\Models\Intranet\Agency;
use App\Models\Intranet\Target;
use App\Models\Intranet\Employee;
use App\Models\Intranet\Position;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Target\StoreTargetRequest;

class TargetController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(Target::with('type', 'employee')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTargetRequest $request)
    {
        $targets = $request['targets'];

        foreach ($targets as $targetData) {
            Target::create($targetData);
        }
        return $this->respondSuccess();
    }

    /**
     * Display the specified resource.
     */
    public function show(Target $target)
    {
        return $this->respond($target);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTargetRequest $request, Target $target)
    {
        $target->update($request->validated());
        return $this->respond($target);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Target $target)
    {
        $target->delete();
        return $this->respondSuccess();
    }

    public function getPerEmployee($id)
    {
        // Obtén el año actual
        $currentYear = Carbon::now()->year;

        // Obtén el año actual
        $currentMonth = Carbon::now()->month;

        // Consulta los targets para el empleado especificado y del año actual
        $targets = Target::where('employee_id', $id)
            ->where('year', $currentYear)
            ->where('month', $currentMonth)
            ->with('type')
            ->get();

        return $this->respond($targets);
    }

    public function getTarget($year)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener el ID de la posición 'Vendedor'
        $vendedorPositionId = Position::where('name', 'Vendedor')->value('id');

        if ($user->hasRole('Admin')) {
            // Obtener todas las agencias con empleados con el position_id del vendedor
            $employees = Agency::whereHas('employees', function ($query) use ($vendedorPositionId) {
                $query->where('position_id', $vendedorPositionId);
            })
                ->with([
                    'employees' => function ($query) use ($vendedorPositionId) {
                        $query->where('position_id', $vendedorPositionId);
                    },
                    'employees.targets' => function ($query) use ($year) {
                        $query->where('year', $year)
                            ->with('type'); // Cargar la relación 'type' en los targets
                    }
                ])
                ->get();

            return $this->respond($employees);
        } else {
            $sucursal = $user->employee->agency;

            // Obtener la agencia junto con los empleados con posición de 'Vendedor' y sus targets del año especificado
            $sucursalConEmpleados = Agency::where('id', $sucursal->id)
                ->with([
                    'employees' => function ($query) use ($vendedorPositionId) {
                        $query->where('position_id', $vendedorPositionId); // Filtra empleados por position_id
                    },
                    'employees.targets' => function ($query) use ($year) {
                        $query->where('year', $year)
                            ->with('type'); // Cargar la relación 'type' en los targets
                    }
                ])
                ->first();

            return $this->respond($sucursalConEmpleados);
        }
    }

    public function getTargetsEmployee($month, $year, Agency $agency)
    {
        // Obtener el ID de la posición 'Vendedor'
        $vendedorPositionId = Position::where('name', 'Vendedor')->value('id');

        // Obtener empleados con posición 'Vendedor' y sus targets filtrados por year y month
        $employees = $agency->employees()
            ->where('position_id', $vendedorPositionId)
            ->with(['targets' => function ($query) use ($year, $month) {
                $query->where('year', $year)
                    ->where('month', $month)
                    ->with('type'); // Cargar la relación 'type' en los targets; // Filtrar targets por año y mes
            }])
            ->get();


        $data = [
            'employees' => $employees,
            'types' => Type::where('type_key', 'target')->get(),
        ];

        return $this->respond($data);
    }
}
