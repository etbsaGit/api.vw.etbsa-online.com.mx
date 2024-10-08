<?php

namespace App\Models\Intranet;

use App\Models\User;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    use Scopes;

    protected $fillable = [
        'first_name',
        'middle_name',
        'paternal_surname',
        'maternal_surname',
        'rfc',
        'agency_id',
        'sales_key',
        'phone',
        'picture',
        'qrpath',
        'user_id',
        'type_id',
        'position_id',
        'department_id'
    ];

    protected $appends = ['shortName','fullName','pic','qr'];

    public function pic(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->picture ? Storage::disk('s3')->url($this->picture) : null
        );
    }

    public function qr(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->qrpath ? Storage::disk('s3')->url($this->qrpath) : null
        );
    }

    protected function defaultPathFolder(): Attribute
    {
        return Attribute::make(
            get: fn () => "intranet/employee/id_" . $this->id,
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($employee) {
            Storage::disk('s3')->delete($employee->picture);
            Storage::disk('s3')->delete($employee->qrpath);
        });
    }

    public function getfullNameAttribute()
    {
        return $this->first_name . ' ' . $this->middle_name . ' ' . $this->paternal_surname . ' ' . $this->maternal_surname;
    }

    public function getShortNameAttribute()
    {
        return $this->first_name . ' ' . $this->paternal_surname;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'employee_id');
    }

    public function municipalities()
    {
        return $this->belongsToMany(Municipality::class, 'p_employee_municipalities', 'employee_id', 'municipality_id');
    }

    public function targets()
    {
        return $this->hasMany(Target::class, 'employee_id');
    }

    public function followUp()
    {
        return $this->hasMany(FollowUp::class, 'employee_id');
    }

    public function quote()
    {
        return $this->hasMany(Quote::class, 'employee_id');
    }
}
