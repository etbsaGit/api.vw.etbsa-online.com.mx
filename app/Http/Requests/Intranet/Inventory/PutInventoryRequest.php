<?php

namespace App\Http\Requests\Intranet\Inventory;

use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class PutInventoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'serial_number' => ['required', 'string', 'max:191', Rule::unique('inventories')->ignore($this->route("inventory")->id)],
            'economical_number' => ['nullable', 'string', 'max:191'],
            'inventory_number' => ['nullable', 'string', 'max:191'],
            'invoice' => ['nullable', 'string', 'max:191'],
            'invoice_date' => ['nullable', 'date'],
            'year' => ['nullable', 'integer', 'digits:4'],
            'p_r' => ['nullable', 'string', 'max:191'],
            'comments' => ['nullable', 'string', 'max:191'],
            'status_id' => ['required', 'integer', 'exists:statuses,id'],
            'type_id' => ['nullable', 'integer', 'exists:types,id'],
            'agency_id' => ['required', 'integer', 'exists:agencies,id'],
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'priority' => [
                'nullable',
                'integer',
                'unique:inventories,priority,NULL,id,vehicle_id,' . $this->input('vehicle_id') // Validación única con condición
            ],
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
