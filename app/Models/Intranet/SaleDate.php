<?php

namespace App\Models\Intranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDate extends Model
{
    use HasFactory;

    protected $fillable = [
       'date',
       'type_id',
       'sale_id',
       'comments'
    ];

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
