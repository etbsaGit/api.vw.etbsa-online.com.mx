<?php

namespace App\Models\Intranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'state_id'
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'municipality_id');
    }

    public function agencies()
    {
        return $this->hasMany(Agency::class, 'municipality_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'p_employee_municipalities', 'municipality_id', 'employee_id');
    }

}
