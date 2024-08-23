<?php

namespace App\Http\Requests\Intranet\Employee;

use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreEmployeeRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:191'],
            'middle_name' => ['nullable', 'string', 'max:191'],
            'paternal_surname' => ['required', 'string', 'max:191'],
            'maternal_surname' => ['required', 'string', 'max:191'],
            'rfc' => ['nullable', 'string', 'unique:employees,rfc', 'max:191'],
            'agency_id' => ['required', 'integer', 'exists:agencies,id'],
            'sales_key' => ['nullable', 'string', 'unique:employees,sales_key', 'max:191'],
            'phone' => ['required', 'string', 'unique:employees,phone', 'max:191'],
            'base64' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'type_id' => ['nullable', 'integer', 'exists:types,id'],
            'position_id' => ['nullable', 'integer', 'exists:positions,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
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
