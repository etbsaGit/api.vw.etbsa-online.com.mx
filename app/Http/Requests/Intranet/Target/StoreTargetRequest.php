<?php

namespace App\Http\Requests\Intranet\Target;

use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreTargetRequest extends FormRequest
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
            'targets' => ['required', 'array'],
            'targets.*.value' => ['required', 'decimal:2'],
            'targets.*.quantity' => ['required', 'integer'],
            'targets.*.month' => ['required', 'integer'],
            'targets.*.year' => ['required', 'integer', 'digits:4'],
            'targets.*.comments' => ['nullable', 'string', 'max:191'],
            'targets.*.type_id' => ['required', 'integer', 'exists:types,id'],
            'targets.*.employee_id' => ['required', 'integer', 'exists:employees,id'],
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
