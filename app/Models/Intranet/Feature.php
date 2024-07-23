<?php

namespace App\Models\Intranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type_id'
    ];

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
}
