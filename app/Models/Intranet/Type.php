<?php

namespace App\Models\Intranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type_key'
    ];

    public function features()
    {
        return $this->hasMany(Feature::class, 'type_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'type_id');
    }

    public function prices()
    {
        return $this->hasMany(Price::class, 'type_id');
    }

    public function vehicleDocs()
    {
        return $this->hasMany(VehicleDoc::class, 'type_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'type_id');
    }
}
