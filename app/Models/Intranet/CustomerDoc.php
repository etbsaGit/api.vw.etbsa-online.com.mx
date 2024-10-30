<?php

namespace App\Models\Intranet;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerDoc extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'extension',
        'path',
        'expiration_date',
        'comments',
        'type_id',
        'customer_id',
    ];

    protected $appends = ['realpath'];

    public function realpath(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->path ? Storage::disk('s3')->url($this->path) : null
        );
    }

    protected function defaultPathFolder(): Attribute
    {
        return Attribute::make(
            get: fn() => "intranet/customer/id_" . $this->customer->id . "/docs",
        );
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
