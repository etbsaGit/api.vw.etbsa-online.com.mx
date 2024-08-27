<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Scopes
{
    public function scopeFilter(Builder $query, array $filters)
    {
        foreach ($filters as $key => $value) {
            if ($value !== null) {
                $query->where($key, $value);
            }
        }
    }

    public function scopeFilterPage(Builder $query, array $filters)
    {
        foreach ($filters as $key => $value) {
            if ($value !== null && $key !== 'page') {
                if ($key === 'search') {
                    $query->where(function ($query) use ($value) {
                        $query->where('sku', 'LIKE', '%' . $value . '%')
                            ->orWhere('name', 'LIKE', '%' . $value . '%')
                            ->orWhere('description', 'LIKE', '%' . $value . '%');
                    });
                } else {
                    $query->where($key, $value);
                }
            }
        }
        return $query;
    }

    public function scopeFilterCustomers(Builder $query, array $filters)
    {
        foreach ($filters as $key => $value) {
            if ($value !== null && $key !== 'page') {
                if ($key === 'search') {
                    $query->where(function ($query) use ($value) {
                        $query->where('name', 'LIKE', '%' . $value . '%')
                            ->orWhere('rfc', 'LIKE', '%' . $value . '%')
                            ->orWhere('phone', 'LIKE', '%' . $value . '%')
                            ->orWhere('email', 'LIKE', '%' . $value . '%');
                    });
                } else {
                    $query->where($key, $value);
                }
            }
        }
        return $query;
    }

    public function scopeFilterEmployees(Builder $query, array $filters)
    {
        foreach ($filters as $key => $value) {
            if ($value !== null && $key !== 'page') {
                if ($key === 'search') {
                    $query->where(function ($query) use ($value) {
                        $query->where('first_name', 'LIKE', '%' . $value . '%')
                            ->orWhere('middle_name', 'LIKE', '%' . $value . '%')
                            ->orWhere('paternal_surname', 'LIKE', '%' . $value . '%')
                            ->orWhere('maternal_surname', 'LIKE', '%' . $value . '%')
                            ->orWhere('rfc', 'LIKE', '%' . $value . '%')
                            ->orWhere('sales_key', 'LIKE', '%' . $value . '%')
                            ->orWhere('phone', 'LIKE', '%' . $value . '%');
                    });
                } else {
                    $query->where($key, $value);
                }
            }
        }
        return $query;
    }

    public function scopeFilterSales(Builder $query, array $filters)
    {
        foreach ($filters as $key => $value) {
            if ($value !== null && $key !== 'page') {
                if ($key === 'search') {
                    $query->where(function ($query) use ($value) {
                        $query->where('id_sale', 'LIKE', '%' . $value . '%');
                    });
                } else {
                    $query->where($key, $value);
                }
            }
        }
        return $query;
    }

    public function scopeFilterInventories(Builder $query, array $filters)
    {
        foreach ($filters as $key => $value) {
            if ($value !== null && $key !== 'page') {
                if ($key === 'search') {
                    $query->where(function ($query) use ($value) {
                        $query->where('serial_number', 'LIKE', '%' . $value . '%')
                            ->orWhere('economical_number', 'LIKE', '%' . $value . '%')
                            ->orWhere('inventory_number', 'LIKE', '%' . $value . '%')
                            ->orWhere('invoice', 'LIKE', '%' . $value . '%');
                    });
                } else {
                    $query->where($key, $value);
                }
            }
        }
        return $query;
    }
}
