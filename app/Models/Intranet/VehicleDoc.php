<?php

namespace App\Models\Intranet;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleDoc extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'type_id',
        'name',
        'extension',
        'path'
    ];

    protected $appends = ['realpath'];

    public function realpath(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path ? Storage::disk('s3')->url($this->path) : null
        );
    }

    protected function defaultPathFolder(): Attribute
    {
        return Attribute::make(
            get: fn () => "intranet/vehicles/id_" . $this->vehicle_id,
        );
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
}
