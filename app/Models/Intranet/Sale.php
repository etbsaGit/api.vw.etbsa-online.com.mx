<?php

namespace App\Models\Intranet;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    use Scopes;

    protected $fillable = [
        'id_sale',
        'series_vehicle',
        'year_vehicle',
        'comments',
        'vehicle_id',
        'status_id',
        'sales_channel_id',
        'type_id',
        'agency_id',
        'customer_id',
        'employee_id',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function salesChannel()
    {
        return $this->belongsTo(Type::class, 'sales_channel_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function dates()
    {
        return $this->hasMany(SaleDate::class, 'sale_id');
    }
}
