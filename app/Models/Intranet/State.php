<?php

namespace App\Models\Intranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abbreviation'
    ];

    public function municipalities()
    {
        return $this->hasMany(Municipality::class, 'state_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'state_id');
    }
}
