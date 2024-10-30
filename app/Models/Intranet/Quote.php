<?php

namespace App\Models\Intranet;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'expiration_date',
        'lead_time',
        'comments',
        'path',
        'amount',
        'follow_up_id',
        'inventory_id',
        'employee_id',
        'customer_id',
        'status_id',
        'type_id',
        'percentage',
        'bono',
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
            get: fn() => "intranet/customer/id_" . $this->followUp->customer->id . "/quotes",
        );
    }

    public function followUp()
    {
        return $this->belongsTo(FollowUp::class, 'follow_up_id');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function additionals()
    {
        return $this->hasMany(Additional::class, 'quote_id');
    }
}
