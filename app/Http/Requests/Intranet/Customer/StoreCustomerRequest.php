<?php

namespace App\Http\Requests\Intranet\Customer;

use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreCustomerRequest extends FormRequest
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
            "id_customer" => ['nullable', 'integer', 'unique:customers,id_customer'],
            "name" => ['required', 'string', 'max:255'],
            "rfc" => ['nullable', 'string', 'min:13', 'max:13', 'unique:customers,rfc'],
            "curp" => ['nullable', 'string', 'min:18', 'max:18', 'unique:customers,curp'],
            "phone" => ['nullable', 'numeric', 'digits:10', 'unique:customers,phone'],
            "landline" => ['nullable', 'numeric', 'digits:10', 'unique:customers,landline'],
            'email' => ['nullable', 'email', 'unique:customers,email'],
            "calle" => ['nullable', 'string', 'max:255'],
            "district" => ['nullable', 'string', 'max:255'],
            "zip_code" => ['nullable', 'numeric', 'digits:5'],

            'state_id' => ['nullable', 'integer', 'exists:states,id'],
            'municipality_id' => ['nullable', 'integer', 'exists:municipalities,id'],
            'type_id' => ['required', 'integer', 'exists:types,id'],
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
