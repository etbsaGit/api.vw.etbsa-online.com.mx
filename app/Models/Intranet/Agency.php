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
}
