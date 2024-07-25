<?php

namespace App\Models\Intranet;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    use Scopes;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'quantity',
        'active',
        'featured',
        'type_id',
        'brand_id'
    ];

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

    public function prices()
    {
        return $this->hasMany(Price::class, 'vehicle_id');
    }
}
