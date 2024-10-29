<?php

namespace App\Models\Intranet;

use Carbon\Carbon;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    use SoftDeletes;

    use Scopes;

    protected $fillable = [
        'serial_number',
        'economical_number',
        'inventory_number',
        'invoice',
        'invoice_date',
        'year',
        'p_r',
        'comments',
        'priority',

        'status_id',
        'type_id',
        'agency_id',
        'vehicle_id',
    ];

    protected $appends = ['days_in_inventory', 'm_y', 'model', 'lineaPrice','bono'];

    public function getLineaPriceAttribute()
    {
        // Obtiene el tipo "Linea" como un único modelo
        $lineType = Type::where('name', 'Lista')->first(); // Usa first() para obtener un único registro

        // Verifica si se encontró el tipo
        if ($lineType) {
            // Filtra los precios donde el type_id es el correspondiente
            $linePrice = $this->prices->where('type_id', $lineType->id)->first();
            return $linePrice ? $linePrice->price : null; // Devuelve el precio o null si no existe
        }

        return null; // Devuelve null si no se encontró el tipo
    }

    public function getBonoAttribute()
    {
        // Obtiene el tipo "Linea" como un único modelo
        $lineType = Type::where('name', 'Bono')->first(); // Usa first() para obtener un único registro

        // Verifica si se encontró el tipo
        if ($lineType) {
            // Filtra los precios donde el type_id es el correspondiente
            $linePrice = $this->prices->where('type_id', $lineType->id)->first();
            return $linePrice ? $linePrice->price : null; // Devuelve el precio o null si no existe
        }

        return null; // Devuelve null si no se encontró el tipo
    }


    public function model(): Attribute
    {
        return new Attribute(
            get: function () {

                return $this->vehicle->name .' '. $this->serial_number;
            }
        );
    }

    public function daysInInventory(): Attribute
    {
        return new Attribute(
            get: function () {
                // Obtener la fecha actual
                $today = Carbon::now();

                // Obtener la fecha de la factura
                $invoiceDate = $this->invoice_date;

                // Calcular la diferencia en días
                return $invoiceDate ? $today->diffInDays($invoiceDate) : null;
            }
        );
    }

    public function mY(): Attribute
    {
        return new Attribute(
            get: function () {
                // Obtener el valor de serial_number
                $serialNumber = $this->serial_number;

                // Verificar si serial_number tiene al menos 10 caracteres
                if (strlen($serialNumber) >= 10) {
                    // Retornar el décimo carácter (índice 9)
                    return $serialNumber[9];
                }

                // Si no tiene al menos 10 caracteres, retornar null o un valor por defecto
                return null;
            }
        );
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function prices()
    {
        return $this->hasMany(Price::class, 'inventory_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'inventory_id');
    }

    public function quote()
    {
        return $this->hasMany(Quote::class, 'inventory_id');
    }
}
