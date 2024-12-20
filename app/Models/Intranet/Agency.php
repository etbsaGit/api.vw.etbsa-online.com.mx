<?php

namespace App\Models\Intranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'district',
        'zip_code',
        'municipality_id',
        'state_id',
    ];

    protected $appends = ['fullAddress'];

    public function getFullAddressAttribute()
    {
        return $this->address . ' ' . $this->district . ' ' . $this->zip_code . ' ' . $this->municipality->name . ' ' . $this->state->name;
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'agency_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'agency_id');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'agency_id');
    }
}
