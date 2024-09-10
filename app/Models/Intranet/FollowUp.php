<?php

namespace App\Models\Intranet;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FollowUp extends Model
{
    use HasFactory;

    use Scopes;

    protected $fillable = [
        'title',
        'date',
        'comments',
        'feedback',

        'customer_id',
        'employee_id',
        'vehicle_id',
        'status_id',
        'origin_id',
        'percentage_id',
        'follow_up_id',
    ];

    protected $appends = ['lastPercentage','lastVehicle'];

    public function getLastPercentageAttribute()
    {
        $latestChild = $this->children()->orderBy('date', 'desc')->first();
        return $latestChild ? $latestChild->percentage : null;
    }

    public function getLastVehicleAttribute()
    {
        $latestChild = $this->children()->orderBy('date', 'desc')->first();
        return $latestChild ? $latestChild->vehicle : null;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function origin()
    {
        return $this->belongsTo(Type::class, 'origin_id');
    }

    public function percentage()
    {
        return $this->belongsTo(Type::class, 'percentage_id');
    }

    public function parent()
    {
        return $this->belongsTo(FollowUp::class, 'follow_up_id');
    }

    public function children()
    {
        return $this->hasMany(FollowUp::class, 'follow_up_id');
    }
}
