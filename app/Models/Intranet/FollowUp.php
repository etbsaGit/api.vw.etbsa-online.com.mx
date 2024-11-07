<?php

namespace App\Models\Intranet;

use Carbon\Carbon;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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

        'quote_pdf',
        'reference_id',
    ];

    protected $appends = ['lastPercentage', 'lastVehicle', 'daysRemaining','qpdf'];

    public function qpdf(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->quote_pdf ? Storage::disk('s3')->url($this->quote_pdf) : null
        );
    }

    protected function defaultPathFolder(): Attribute
    {
        return Attribute::make(
            get: fn () => "intranet/followUp/id_" . $this->follow_up_id,
        );
    }

    public function getDaysRemainingAttribute()
    {
        $latestChild = $this->children()->orderBy('date', 'desc')->first();
        $statusActivo = Status::where('name','Activo')->first();

        if (!$latestChild || !$latestChild->date || $this->status_id != $statusActivo->id) {
            return null;
        }

        $latestChildDate = Carbon::parse($latestChild->date);

        $currentDate = Carbon::now();

        $daysRemaining = $currentDate->diffInDays($latestChildDate);

        if ($latestChildDate < $currentDate) {
            $daysRemaining = -$daysRemaining;
        } else {
            $daysRemaining += 1;
        }

        return $daysRemaining;
    }


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

    public function reference()
    {
        return $this->belongsTo(Reference::class, 'reference_id');
    }

    public function parent()
    {
        return $this->belongsTo(FollowUp::class, 'follow_up_id');
    }

    public function children()
    {
        return $this->hasMany(FollowUp::class, 'follow_up_id');
    }

    public function failedSale()
    {
        return $this->hasOne(FailedSale::class, 'follow_up_id');
    }

    public function quote()
    {
        return $this->hasMany(Quote::class, 'follow_up_id');
    }
}
