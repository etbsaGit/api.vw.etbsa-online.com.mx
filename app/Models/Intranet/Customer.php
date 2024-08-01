<?php

namespace App\Models\Intranet;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    use Scopes;

    protected $fillable = [
        'id_customer',
        'name',
        'rfc',
        'curp',
        'phone',
        'landline',
        'email',
        'street',
        'district',
        'zip_code',
        'municipality_id',
        'state_id',
        'type_id',
    ];

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'customer_id');
    }
}
