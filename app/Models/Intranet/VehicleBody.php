<?php

namespace App\Models\Intranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleBody extends Model
{
    use HasFactory;

    protected $fillable = [
        'configuration',
        'type_id',
    ];

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'vehicle_body_id');
    }
}
