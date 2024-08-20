<?php

namespace App\Http\Controllers\Intranet;

use App\Models\User;
use App\Traits\UploadFiles;
use Illuminate\Http\Request;
use App\Models\Intranet\Type;
use App\Models\Intranet\Agency;
use App\Models\Intranet\Employee;
use App\Models\Intranet\Position;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Employee\AttachMunicipalitiesRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Intranet\Employee\PutEmployeeRequest;
use App\Http\Requests\Intranet\Employee\StoreEmployeeRequest;

class EmployeeController extends ApiController
{
    use UploadFiles;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $employees = Employee::filterEmployees($filters)->with('agency','user','type','position')->paginate(10);
        return $this->respond($employees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $employee = Employee::create($request->validated());

        if (!is_null($request['base64'])) {
            $relativePath  = $this->saveImage($request['base64'], $employee->default_path_folder);
            $request['base64'] = $relativePath;
            $updateData = ['picture' => $relativePath];
            $employee->update($updateData);
        }

        // Verificar si el campo email no es nulo y crear un usuario
        if (!is_null($request['email'])) {
            $email = $request['email'];
            $name = explode('@', $email)[0]; // Obtener la parte antes del @

            // Crear un nuevo usuario con contraseña encriptada
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('vw123'), // Encriptar la contraseña
            ]);

            if (!$employee->user) {
                $employee->user()->associate($user);
                $employee->save();
            }
        }

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

        if (!is_null($request['base64'])) {
            if ($employee->picture) {
                Storage::disk('s3')->delete($employee->picture);
            }
            $relativePath  = $this->saveImage($request['base64'], $employee->default_path_folder);
            $request['base64'] = $relativePath;
            $updateData = ['picture' => $relativePath];
            $employee->update($updateData);
        }

        // Verificar si el usuario asociado al empleado existe y actualizar si el email es diferente
        if ($employee->user && !is_null($request['email'])) {
            $currentEmail = $employee->user->email;
            $newEmail = $request['email'];

            if ($currentEmail !== $newEmail) {
                $name = explode('@', $newEmail)[0]; // Obtener la parte antes del @

                // Actualizar el usuario con el nuevo email y nombre
                $employee->user->update([
                    'name' => $name,
                    'email' => $newEmail,
                ]);
            }
        }
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
            'positions' => Position::all(),
            'types' => Type::where('type_key', 'employee')->get(),
        ];

        return $this->respond($data);
    }

    public function attachMunicipalities(AttachMunicipalitiesRequest $request, Employee $employee)
    {
        // Obtén el array de IDs de municipios del request
        $municipalityIds = $request->input('municipalities');

        // Asocia los municipios al empleado
        $employee->municipalities()->sync($municipalityIds);

        return $this->respondSuccess();
    }

    public function getMunicipalities(Employee $employee)
    {
        $municipalities = $employee->municipalities;

        return $this->respond($municipalities->load('state'));
    }
}
