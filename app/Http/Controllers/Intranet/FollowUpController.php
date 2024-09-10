<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Type;
use App\Models\Intranet\Status;
use App\Models\Intranet\Vehicle;
use App\Models\Intranet\Customer;
use App\Models\Intranet\Employee;
use App\Models\Intranet\FollowUp;
use App\Models\Intranet\Position;
use App\Models\Intranet\Inventory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\FollowUp\NextFollowUpRequest;
use App\Http\Requests\Intranet\FollowUp\StoreFollowUpRequest;
use App\Http\Requests\Intranet\FollowUp\AddFeedBackFollowUpRequest;

class FollowUpController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $user = Auth::user();
        $employee = $user->employee;

        // Inicializar la consulta de FollowUps
        $followUpsQuery = FollowUp::filterFollowUp($filters)
            ->with(
                'customer.municipality',
                'customer.state',
                'employee.user',
                'employee.type',
                'employee.position',
                'employee.agency',
                'employee.department',
                'vehicle',
                'status',
                'origin',
                'percentage',
                'children',
                'children.percentage',
            );

        // Aplicar el filtro para follow_up_id IS NULL
        $followUpsQuery->whereNull('follow_up_id');

        // Verificar si el empleado está asociado al usuario
        if ($employee) {
            // Obtener el ID de la posición de 'Vendedor' (ajusta según tu lógica para obtener este ID)
            $vendedorPositionId = Position::where('name', 'Vendedor')->value('id');

            // Verificar el rol del empleado y ajustar la consulta en consecuencia
            if ($employee->position_id === $vendedorPositionId) {
                // Si el usuario es vendedor, filtrar por employee_id del vendedor
                $followUpsQuery->where('employee_id', $employee->id);
            }
        }

        // Obtener los resultados paginados
        $followUps = $followUpsQuery->paginate(10);

        return $this->respond($followUps);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFollowUpRequest $request)
    {
        // Valida y extrae los datos del request
        $validatedData = $request->validated();

        // Extrae los datos de next_follow
        $next_follow = $validatedData['next_follow'] ?? [];

        // Crea el primer FollowUp con los datos validados
        $firstFollowUp = FollowUp::create($validatedData);

        // Prepara los datos para el segundo FollowUp
        $secondFollowUpData = $validatedData;
        $secondFollowUpData['follow_up_id'] = $firstFollowUp->follow_up_id ?? $firstFollowUp->id;
        $secondFollowUpData['date'] = $next_follow['date'];
        $secondFollowUpData['percentage_id'] = $next_follow['percentage_id'];
        $secondFollowUpData['comments'] = $next_follow['comments'];   // Añade el ID del primer FollowUp

        $secondFollowUp = FollowUp::create($secondFollowUpData);

        return $this->respond($firstFollowUp->load('children'));
    }




    /**
     * Display the specified resource.
     */
    public function show(FollowUp $followUp)
    {
        $followUp = $followUp->load([
            'customer.municipality',
            'customer.state',
            'customer.agent',
            'employee.user',
            'employee.type',
            'employee.position',
            'employee.agency',
            'employee.department',
            'vehicle',
            'status',
            'origin',
            'percentage',
            'children' => function ($query) {
                $query->orderBy('date', 'desc'); // Ordenar por fecha del más viejo al más reciente
            },
            'children.percentage',
        ]);

        return $this->respond($followUp);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(AddFeedBackFollowUpRequest $request, FollowUp $followUp)
    {
        $validatedData = $request->validated();

        $followUp->update($validatedData);

        return $this->respond($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FollowUp $followUp)
    {
        //
    }

    public function getOptions()
    {
        $data = [
            'customers' => Customer::all(),
            'employees' => Employee::all(),
            'vehicles' => Vehicle::all(),
            'statuses' => Status::where('status_key', 'followUp')->get(),
            'origins' => Type::where('type_key', 'origin')->get(),
            'percentages' => Type::where('type_key', 'percentage')->get(),
        ];

        return $this->respond($data);
    }

    public function nextFollowUp(NextFollowUpRequest $request, FollowUp $followUp)
    {
        $firstFollowUp = FollowUp::create($request->validated());
        return $this->respond($firstFollowUp);
    }

    public function saleLost(FollowUp $followUp)
    {
        // Obtén el status con el nombre 'Venta perdida'
        $status = Status::where('name', 'Venta perdida')->first();

        // Verifica si se encontró el status
        if ($status === null) {
            return response()->json(['error' => 'El status "Venta perdida" no se encuentra en la base de datos.'], 404);
        }

        // Actualiza el status_id del FollowUp
        $followUp->status_id = $status->id;
        $followUp->save();

        // Asume que 'children' es la relación que contiene los registros relacionados
        // Actualiza el status_id para todos los registros relacionados
        $followUp->children()->each(function ($child) use ($status) {
            $child->status_id = $status->id;
            $child->save();
        });

        return $this->respondSuccess();
    }

    public function saleWin(FollowUp $followUp)
    {
        // Obtén el status con el nombre 'Venta perdida'
        $status = Status::where('name', 'Venta ganada')->first();

        // Verifica si se encontró el status
        if ($status === null) {
            return response()->json(['error' => 'El status "Venta ganada" no se encuentra en la base de datos.'], 404);
        }

        // Actualiza el status_id del FollowUp
        $followUp->status_id = $status->id;
        $followUp->save();

        // Asume que 'children' es la relación que contiene los registros relacionados
        // Actualiza el status_id para todos los registros relacionados
        $followUp->children()->each(function ($child) use ($status) {
            $child->status_id = $status->id;
            $child->save();
        });

        return $this->respondSuccess();
    }
}
