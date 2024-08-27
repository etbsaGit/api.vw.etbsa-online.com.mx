<?php

namespace App\Http\Requests\Intranet\Inventory;

use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreInventoryRequest extends FormRequest
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
            'serial_number' => ['required', 'string', 'unique:inventories', 'max:191'],
            'economical_number' => ['required', 'string', 'max:191'],
            'inventory_number' => ['required', 'string', 'max:191'],
            'invoice' => ['required', 'string', 'max:191'],
            'invoice_date' => ['required', 'date'],
            'year' => ['required', 'integer', 'digits:4'],
            'p_r' => ['nullable', 'string', 'max:191'],
            'comments' => ['nullable', 'string', 'max:191'],
            'status_id' => ['required', 'integer', 'exists:statuses,id'],
            'type_id' => ['required', 'integer', 'exists:types,id'],
            'agency_id' => ['required', 'integer', 'exists:agencies,id'],
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'vehicle_body_id' => ['nullable', 'integer', 'exists:vehicle_bodies,id'],
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
