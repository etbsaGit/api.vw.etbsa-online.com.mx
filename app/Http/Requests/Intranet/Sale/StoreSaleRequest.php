<?php

namespace App\Http\Requests\Intranet\Sale;

use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreSaleRequest extends FormRequest
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
            'id_sale' => ['required', 'string', 'unique:sales', 'max:191'],
            'inventory_id' => ['required', 'integer', 'exists:inventories,id', 'unique:sales'],
            'status_id' => ['required', 'integer', 'exists:statuses,id'],
            'sales_channel_id' => ['required', 'integer', 'exists:types,id'],
            'type_id' => ['required', 'integer', 'exists:types,id'],
            'agency_id' => ['required', 'integer', 'exists:agencies,id'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'comments' => ['nullable', 'string', 'max:191'],
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
