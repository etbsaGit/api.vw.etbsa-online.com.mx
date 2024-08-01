<?php

namespace App\Models\Intranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status_key'
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class, 'status_id');
    }
}
