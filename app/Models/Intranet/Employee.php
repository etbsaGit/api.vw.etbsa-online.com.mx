<?php

namespace App\Models\Intranet;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    use Scopes;

    protected $fillable = [
        'first_name',
        'middle_name',
        'paternal_surname',
        'maternal_surname',
        'rfc',
        'agency_id'
    ];

    protected $appends = ['fullName'];

    public function getfullNameAttribute()
    {
        return $this->first_name . ' ' . $this->middle_name . ' ' . $this->paternal_surname . ' ' . $this->maternal_surname;
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }
}
