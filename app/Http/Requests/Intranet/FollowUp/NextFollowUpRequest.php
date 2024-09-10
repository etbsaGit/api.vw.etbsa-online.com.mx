<?php

namespace App\Http\Requests\Intranet\FollowUp;

use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class NextFollowUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // Asigna el ID del status encontrado a `status_id` en los datos de validación
        $this->merge([
            'title' => $this->route("followUp")->title,
            'customer_id' => $this->route("followUp")->customer_id,
            'employee_id' => $this->route("followUp")->employee_id,
            'status_id' => $this->route("followUp")->status_id,
            'origin_id' => $this->route("followUp")->origin_id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'date' => ['required','date'],
            'comments' => ['required','string','max:191'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'vehicle_id' => ['nullable', 'integer', 'exists:vehicles,id'],
            'status_id' => ['required', 'integer', 'exists:statuses,id'],
            'origin_id' => ['required', 'integer', 'exists:types,id'],
            'percentage_id' => ['required','integer','exists:types,id'],
            'follow_up_id' => ['required','integer','exists:follow_ups,id'],
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
