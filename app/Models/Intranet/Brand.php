<?php

namespace App\Models\Intranet;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
    ];

    protected $appends = ['logopath'];

    public function logopath(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->logo ? Storage::disk('s3')->url($this->logo) : null
        );
    }

    protected function defaultPathFolder(): Attribute
    {
        return Attribute::make(
            get: fn () => "intranet/brands/id_" . $this->id,
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($brand) {
            Storage::disk('s3')->delete($brand->logo);
        });
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'brand_id');
    }
}
