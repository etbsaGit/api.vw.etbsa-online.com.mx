<?php

namespace App\Models\Intranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Additional extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'cost',
        'quote_id',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }
}
