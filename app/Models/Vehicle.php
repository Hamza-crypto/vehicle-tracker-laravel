<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [

    ];

    protected $hidden = [
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    protected $dates = [
        'created_at',
    ];

    public function getInvoiceDateAttribute($date)
    {
        $date = Carbon::parse($date);

        return $date->format('Y-m-d');
    }

    public function metas()
    {
        return $this->hasMany(VehicleMetas::class);
    }

    public function invoice_amount()
    {
        return $this->hasOne(VehicleMetas::class)
            ->where('meta_key', 'invoice_amount');
    }

    public function location()
    {
        return $this->hasOne(VehicleMetas::class)
            ->where('meta_key', 'location');
    }

    public function date_pickup()
    {
        return $this->hasOne(VehicleMetas::class)
            ->where('meta_key', 'pickup_date');
    }

    public function auction_stk()
    {
        return $this->hasOne(VehicleMetas::class)
            ->where('meta_key', 'auction_stk');
    }

    public function auction_date()
    {
        return $this->hasOne(VehicleMetas::class)
            ->where('meta_key', 'auction_date');
    }

    public function sale_price()
    {
        return $this->hasOne(VehicleMetas::class)
            ->where('meta_key', 'sale_price');
    }

    public function days_in_yard()
    {
        return $this->hasOne(VehicleMetas::class)
            ->where('meta_key', 'days_in_yard');
    }

    public function number_of_runs()
    {
        return $this->hasOne(VehicleMetas::class)
            ->where('meta_key', 'number_of_runs');
    }

    public function scopeFilters($query, $request)
    {
        if (isset($request['make']) && $request['make'] != -100 && $request['make'] != 'undefined') {
            $query->where('make', $request['make']);
        }

        if (isset($request['model']) && $request['model'] != -100 && $request['model'] != 'undefined') {
            $query->where('model', $request['model']);
        }

        if (isset($request['status']) && $request['status'] != -100) {

            $search = $request['status'];
            $query->whereHas('metas', function ($q1) use ($search) {
                $q1->where('meta_value', 'LIKE', "%$search%");
            });

        }

        if (isset($request['daterange'])) {
            $dateRange = explode(' - ', $request['daterange']);
            $query->whereBetween('created_at', [Carbon::parse($dateRange[0])->format('Y-m-d'), Carbon::parse($dateRange[1])->format('Y-m-d')]);
        }

        $query->whereDoesntHave('metas', function ($q1) {
            $q1->where('meta_key', 'status')
                ->where('meta_value', 'Sold');
        });

        $query->orderBy('id', 'desc');
    }
}
