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

    public function channel()
    {
        return $this->hasMany(Sale::class, 'sales_channel_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'type_id');
    }

    public function dates()
    {
        return $this->hasMany(SaleDate::class, 'type_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'type_id');
    }

    public function targets()
    {
        return $this->hasMany(Target::class, 'type_id');
    }

    public function vehicleBodies()
    {
        return $this->hasMany(VehicleBody::class, 'type_id');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'type_id');
    }

    public function origin()
    {
        return $this->hasMany(FollowUp::class, 'origin_id');
    }

    public function percentage()
    {
        return $this->hasMany(FollowUp::class, 'percentage_id');
    }

    public function failedSale()
    {
        return $this->hasMany(FailedSale::class, 'type_id');
    }
}
