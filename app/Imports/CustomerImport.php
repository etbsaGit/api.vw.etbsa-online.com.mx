<?php

namespace App\Imports;

use App\Models\Intranet\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Convertir espacios en blanco a NULL
        $idCustomer = $this->sanitizeField($row['id_customer']);
        $name = $this->sanitizeField($row['name']);
        $street = $this->sanitizeField($row['street']);
        $phone = $this->sanitizeField($row['phone']);
        $landline = $this->sanitizeField($row['landline']);
        $email = $this->sanitizeField($row['email']);

        // Verificar si el id_customer es NULL o vacío, y si es así, omitir el registro
        if (is_null($idCustomer)) {
            return null; // Ignorar registros donde 'id_customer' es NULL o vacío
        }

        // Buscar un cliente existente con el mismo id_customer
        $existingCustomer = Customer::where('id_customer', $idCustomer)->first();

        if ($existingCustomer) {
            // Actualizar el cliente existente
            $existingCustomer->update([
                'name'     => $name,
                'street'   => $street,
                'phone'    => $phone,
                'landline' => $landline,
                'email'    => $email,
            ]);

            return null; // No es necesario retornar un modelo para actualización
        } else {
            // Crear un nuevo cliente si no existe
            return new Customer([
                'id_customer' => $idCustomer,
                'name'        => $name,
                'street'      => $street,
                'phone'       => $phone,
                'landline'    => $landline,
                'email'       => $email,
            ]);
        }
    }

    /**
     * Limpia los campos para convertir espacios en blanco a NULL.
     *
     * @param mixed $value
     * @return mixed
     */
    private function sanitizeField($value)
    {
        // Convertir espacios en blanco a NULL
        return trim($value) === '' ? null : $value;
    }
}
