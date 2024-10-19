<?php

namespace App\Models\Intranet;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    use Scopes;

    protected $fillable = [
        'id_sale',
        'amount',
        'comments',
        'inventory_id',
        'status_id',
        'sales_channel_id',
        'type_id',
        'agency_id',
        'customer_id',
        'employee_id',
        'date',
        'cancellation_reason',
        'cancel',
        'cancellation_folio',
        'cancellation_date',
    ];

    protected $appends = ['inventory_with_trashed'];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    // Accessor para obtener el inventario incluyendo los soft deletes
    public function inventoryWithTrashed(): Attribute
    {
        return new Attribute(
            get: function () {
                return Inventory::withTrashed()->with('vehicle')->find($this->inventory_id);
            }
        );
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
