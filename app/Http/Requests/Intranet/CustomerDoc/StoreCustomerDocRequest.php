<?php

namespace App\Http\Requests\Intranet\CustomerDoc;

use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreCustomerDocRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'extension' => ['required', 'string', 'max:255'],
            'expiration_date' => ['nullable','date'],
            'comments' => ['nullable', 'string', 'max:255'],
            'base64' => ['required', 'string'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
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
