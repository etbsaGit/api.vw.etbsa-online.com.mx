<?php

namespace App\Http\Requests\Intranet\Quote;

use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreQuoteRequest extends FormRequest
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
            'expiration_date' => ['required', 'date'],
            'lead_time' => ['required', 'integer'],
            'comments' => ['nullable', 'string', 'max:191'],
            'amount' => ['required', 'numeric'],
            'follow_up_id' => ['required', 'integer', 'exists:follow_ups,id'],
            'inventory_id' => ['required', 'integer', 'exists:inventories,id'],
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'status_id' => ['required', 'integer', 'exists:statuses,id'],
            'type_id' => ['required', 'integer', 'exists:types,id'],
            'percentage' => ['nullable', 'integer'],
            'bono' => ['nullable', 'numeric'],
            'additionals' => ['nullable','array'],
            'additionals.*.name' => ['required', 'string', 'max:191'],
            'additionals.*.description' => ['nullable', 'string'],
            'additionals.*.price' => ['required', 'numeric'],
            'additionals.*.cost' => ['required', 'numeric'],
            'images' => ['nullable','array'],
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
