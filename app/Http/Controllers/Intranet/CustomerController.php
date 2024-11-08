<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Type;
use App\Models\Intranet\State;
use App\Imports\CustomerImport;
use App\Models\Intranet\Customer;
use App\Models\Intranet\Employee;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Customer\PutCustomerRequest;
use App\Http\Requests\Intranet\Customer\StoreCustomerRequest;

class CustomerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $customer = Customer::filterCustomers($filters)->with('municipality', 'state', 'type','employees.agency','references')->paginate(10);
        return $this->respond($customer);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->validated());
        return $this->respondCreated($customer);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return $this->respond($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());
        return $this->respond($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return $this->respondSuccess();
    }

    public function getOptions()
    {

        $personaMoralType = Type::where('name', 'moral')->where('type_key', 'sat')->first();

        $data = [
            'customers' => Customer::where('type_id', $personaMoralType->id)->orderBy('name')->get(),
            'states' => State::all(),
            'types' => Type::where('type_key', 'sat')->get(),
        ];

        return $this->respond($data);
    }

    public function insetExcel(Request $request)
    {
        // Validar que el archivo sea un archivo .xlsx
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        // Obtener el archivo cargado
        $file = $request->file('file');

        // Importar el archivo .xlsx usando el importador
        Excel::import(new CustomerImport, $file);

        return $this->respond("Clientes importados con exito");
    }

    public function getEmployeesPerAgency()
    {
        // Obtener el usuario autenticado
        $user = auth()->user();

        // Si el usuario es un admin, no filtrar por agencia
        if ($user->hasRole('Admin')) {
            $employees = Employee::with('customers')->get();
        } else {
            // Obtener la agencia del usuario
            $agencyId = $user->employee->agency_id;
            $employees = Employee::where('agency_id', $agencyId)->with('customers')->get();
        }

        // Preparar los datos para la respuesta
        $data = [
            'employees' => $employees,
        ];

        // Retornar la respuesta con los empleados
        return $this->respond($data);
    }


    public function postCustomersEmployee(Request $request)
    {
        // Validación de datos (opcional, dependiendo de tus necesidades)
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'customersAsync' => 'nullable|array',
            'customersAsync.*' => 'nullable|exists:customers,id'
        ]);

        // Obtener el empleado por el ID
        $employee = Employee::findOrFail($request->employee_id);

        // Realizar el sync de los clientes
        $employee->customers()->sync($request->customersAsync);

        return $this->respondSuccess();
    }
}
