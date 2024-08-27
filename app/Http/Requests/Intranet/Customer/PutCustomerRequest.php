<?php

namespace App\Http\Requests\Intranet\Customer;

use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class PutCustomerRequest extends FormRequest
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
            "id_customer" => ['nullable', 'integer', Rule::unique('customers')->ignore($this->route("customer")->id)],
            "name" => ['required', 'string', 'max:255'],
            "rfc" => ['nullable', 'string', 'min:13', 'max:13', Rule::unique('customers')->ignore($this->route("customer")->id)],
            "curp" => ['nullable', 'string', 'min:18', 'max:18', Rule::unique('customers')->ignore($this->route("customer")->id)],
            "phone" => ['nullable', 'numeric', 'digits:10', Rule::unique('customers')->ignore($this->route("customer")->id)],
            "landline" => ['nullable', 'numeric', 'digits:10', Rule::unique('customers')->ignore($this->route("customer")->id)],
            'email' => ['nullable', 'email', Rule::unique('customers')->ignore($this->route("customer")->id)],
            "street" => ['nullable', 'string', 'max:255'],
            "district" => ['nullable', 'string', 'max:255'],
            "zip_code" => ['nullable', 'numeric', 'digits:5'],

            'state_id' => ['nullable', 'integer', 'exists:states,id'],
            'municipality_id' => ['nullable', 'integer', 'exists:municipalities,id'],
            'type_id' => ['required', 'integer', 'exists:types,id'],
            'agent_id' => ['nullable', 'integer', 'exists:customers,id'],
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
