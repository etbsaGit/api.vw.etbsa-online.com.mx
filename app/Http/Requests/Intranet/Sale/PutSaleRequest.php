<?php

namespace App\Http\Requests\Intranet\Sale;

use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class PutSaleRequest extends FormRequest
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
            'id_sale' => [
                'required',
                'string',
                'max:191',
                Rule::unique('sales')->where(function ($query) {
                    return $query->where('cancel', 0);
                })->ignore($this->route("sale")->id)
            ],
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric'],
            'inventory_id' => [
                'required',
                'integer',
                'exists:inventories,id',
                Rule::unique('sales')->where(function ($query) {
                    return $query->where('cancel', 0);
                })->ignore($this->route("sale")->id)
            ],
            'status_id' => ['required', 'integer', 'exists:statuses,id'],
            'sales_channel_id' => ['required', 'integer', 'exists:types,id'],
            'type_id' => ['required', 'integer', 'exists:types,id'],
            'agency_id' => ['required', 'integer', 'exists:agencies,id'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'comments' => ['nullable', 'string', 'max:191'],
            'cancellation_reason' => ['nullable', 'string', 'max:191'],
            'cancellation_folio' => ['nullable', 'string', 'max:191'],
            'cancellation_date' => ['nullable', 'date'],
            'cancel' => ['required', 'boolean'],
            'quote_id' => [
                'nullable',
                'integer',
                Rule::unique('sales')->where(function ($query) {
                    return $query->where('cancel', 0);
                })->ignore($this->route("sale")->id)
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
