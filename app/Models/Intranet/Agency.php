<?php

namespace App\Models\Intranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'agency_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'agency_id');
    }
}
