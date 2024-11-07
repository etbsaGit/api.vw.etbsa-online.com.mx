<?php

namespace App\Http\Requests\Intranet\FollowUp;

use Illuminate\Http\Response;
use App\Models\Intranet\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreFollowUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $status = Status::where('name', 'Activo')->first();

        // Asegúrate de que el status fue encontrado
        if ($status === null) {
            // Manejo de error si no se encuentra el status "Activo"
            throw new \Exception('El status "Activo" no se encuentra en la base de datos.');
        }

        // Asigna el ID del status encontrado a `status_id` en los datos de validación
        $this->merge([
            'status_id' => $status->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'date' => ['required', 'date'],
            'comments' => ['nullable', 'string', 'max:191'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'vehicle_id' => ['nullable', 'integer', 'exists:vehicles,id'],
            'status_id' => ['required', 'integer', 'exists:statuses,id'],
            'origin_id' => ['required', 'integer', 'exists:types,id'],
            'percentage_id' => ['required', 'integer', 'exists:types,id'],
            'reference_id' => ['nullable', 'integer', 'exists:references,id'],
            'next_follow' => ['required', 'array'],
            'next_follow.date' => ['required', 'date'],
            'next_follow.percentage_id' => ['required', 'integer', 'exists:types,id'],
            'next_follow.comments' => ['required', 'string', 'max:191'],
        ];
    }

    function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            $response = new Response($validator->errors(), 422);
            throw new ValidationException($validator, $response);
        }
    }
}
