<?php

namespace App\Models\Intranet;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    use Scopes;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'active',
        'featured',
        'type_id',
        'brand_id'
    ];

    protected $appends = ['quantity'];

    public function quantity(): Attribute
    {
        return new Attribute(
            get: function () {
                // Contar el número de objetos en la relación inventories
                return $this->inventories()->count();
            }
        );
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function features()
    {
        return $this->hasOne(VehicleFeature::class, 'vehicle_id');
    }

    public function vehicleDocs()
    {
        return $this->hasMany(VehicleDoc::class, 'vehicle_id');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'vehicle_id');
    }
}
