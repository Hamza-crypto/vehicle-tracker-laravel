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


    public static function countVehicles($status)
    {
        $sold_vehicles = Vehicle::whereHas('metas', function ($query) use ($status) {
            $query->where('meta_key', 'status')
                ->where('meta_value', $status);
        })->count();

        return $sold_vehicles;
    }

    public static function countAllVehicles()
    {
        $sold_vehicles = Vehicle::whereDoesntHave('metas', function ($query) {
            $query->where('meta_key', 'status')
                ->where('meta_value', 'SOLD');
        })->count();

        return $sold_vehicles;
    }

    public function number_of_runs()
    {
        return $this->hasOne(VehicleMetas::class)
            ->where('meta_key', 'number_of_runs');
    }

    public function scopeFilters($query, $request)
    {
        $query = $this->mainFilters($query, $request);

        $query = $this->allVehicles($query);

    }

    public function scopeSold($query, $request)
    {
        $query = $this->mainFilters($query, $request);

        $query = $this->soldVehicles($query);

    }

    public function mainFilters($query, $request)
    {

        if (isset($request['status']) && $request['status'] != -100) {

            $search = $request['status'];
            $query->whereHas('metas', function ($q1) use ($search) {
                $q1->where('meta_value', 'LIKE', "%$search%");
            });

        }

        if (isset($request['left_location'])) {
            $dateRange = explode(' - ', $request['left_location']);
            $query->whereBetween('left_location', [Carbon::parse($dateRange[0])->format('Y-m-d'), Carbon::parse($dateRange[1])->format('Y-m-d')]);
        }

        if (isset($request['location']) && $request['location'] != -100) {
            $query->where('location',  $request['location']);
        }

        if (isset($request['date_paid'])) {
            $dateRange = explode(' - ', $request['date_paid']);
            $query->whereBetween('date_paid', [Carbon::parse($dateRange[0])->format('Y-m-d'), Carbon::parse($dateRange[1])->format('Y-m-d')]);
        }

        // $query->orderBy('id', 'desc');

        return $query;
    }

    public function allVehicles($query)
    {

        $query->whereDoesntHave('metas', function ($q1) {
            $q1->where('meta_key', 'status')
                ->where('meta_value', 'SOLD');
        });

        return $query;
    }

    public function soldVehicles($query)
    {

        $query->whereHas('metas', function ($q1) {
            $q1->where('meta_key', 'status')
                ->where('meta_value', 'SOLD');
        });

        return $query;
    }

    public function scopeSort($query, $request)
    {
        if (!isset($request['sort'])) {
            return $query->orderBy('id', 'desc');
        }

        $sort = $request['sort'];
        $order = $request['order'];

        return $query->orderBy($sort, $order);
    }
}
