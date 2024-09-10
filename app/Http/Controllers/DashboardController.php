<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {

        $vehicles_with_days_in_yard = Cache::remember('vehicles_with_days_in_yard', 0, function () { //1440 = 1 hour
            return Vehicle::join('vehicle_metas as days_in_yard_meta', function ($join) {
                $join->on('vehicles.ID', '=', 'days_in_yard_meta.vehicle_id')
                    ->where('days_in_yard_meta.meta_key', '=', 'days_in_yard');
            })
                    ->leftJoin('vehicle_metas as status_meta', function ($join) {
                        $join->on('vehicles.ID', '=', 'status_meta.vehicle_id')
                            ->where('status_meta.meta_key', '=', 'status');
                    })
                    ->select(['vehicles.id', 'vin', 'description', 'days_in_yard_meta.meta_value'])
                    ->where(function ($query) {
                        $query->whereNull('status_meta.meta_value')
                            ->orWhere('status_meta.meta_value', '!=', 'SOLD');
                    })
                    ->orderByRaw('CAST(days_in_yard_meta.meta_value AS UNSIGNED) DESC')
                    ->limit(30)
                    ->get();
        });


        $vehicles_sold = Cache::remember('vehicles_sold', 1440, function () {
            return Vehicle::join('vehicle_metas', 'vehicles.ID', '=', 'vehicle_metas.vehicle_id')
            ->select(['vehicles.id','vin', 'description'])
            ->where('meta_key', 'status')
            ->where('meta_value', 'SOLD')
            ->orderBy('vehicle_metas.id', 'DESC')
            ->limit(30)
            ->get();
        });


        $last_30_inserted = Cache::remember('last_30_inserted', 3600, function () {
            return Vehicle::latest()->take(30)->get();
        });


        $last_30_updated = Cache::remember('last_30_updated', 3600, function () {
            return Vehicle::latest('updated_at')->take(30)->get();
        });

        return view('pages.dashboard.index', get_defined_vars());

    }
}
