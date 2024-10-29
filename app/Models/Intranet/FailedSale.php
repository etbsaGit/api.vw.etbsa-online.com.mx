<?php

namespace App\Models\Intranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'follow_up_id',
        'type_id',
        'comments',
    ];

    public function followUp()
    {
        return $this->belongsTo(FollowUp::class, 'follow_up_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
}
