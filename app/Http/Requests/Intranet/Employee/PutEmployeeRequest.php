<?php

namespace App\Http\Requests\Intranet\Employee;

use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class PutEmployeeRequest extends FormRequest
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
            'rfc' => ['nullable', 'string', 'max:191', Rule::unique('employees')->ignore($this->route("employee")->id)],
            'agency_id' => ['required', 'integer', 'exists:agencies,id'],
            'sales_key' => ['nullable', 'string', 'max:191', Rule::unique('employees')->ignore($this->route("employee")->id)],
            'phone' => ['required', 'string', 'max:191', Rule::unique('employees')->ignore($this->route("employee")->id)],
            'base64' => ['nullable', 'string'],
            'base64qr' => ['nullable', 'string'],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($this->route("employee")->user_id)],
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
