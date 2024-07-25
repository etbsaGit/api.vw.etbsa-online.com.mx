<?php

namespace App\Models\Intranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleFeature extends Model
{
    use HasFactory;

    protected $table = 'vehicles_features';

    protected $fillable = [
       'vehicle_id',
       'feature_id',
       'value'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class, 'feature_id');
    }
}
