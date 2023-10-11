<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $vehicles_with_days_in_yard = Cache::remember('vehicles_with_days_in_yard', 1440, function () { //1440 = 1 hour
            return Vehicle::join('vehicle_metas', 'vehicles.ID', '=', 'vehicle_metas.vehicle_id')
            ->select(['vin', 'meta_value'])
            ->where('meta_key', 'days_in_yard')
            ->orderByRaw('CAST(meta_value AS UNSIGNED) DESC')
            ->limit(30)
            ->get();
        });

        $vehicles_sold = Cache::remember('vehicles_sold', 1440, function () {
            return Vehicle::join('vehicle_metas', 'vehicles.ID', '=', 'vehicle_metas.vehicle_id')
            ->where('meta_key', 'status')
            ->where('meta_value', 'SOLD')
            ->orderBy('vehicle_metas.id', 'DESC')
            ->limit(30)
            ->get();
        });

        // $vehicles_with_days_in_yard = Vehicle::join('vehicle_metas', 'vehicles.ID', '=', 'vehicle_metas.vehicle_id')
        // ->select(['vin', 'meta_value'])
        // ->where('meta_key', 'days_in_yard')
        // ->orderByRaw('CAST(meta_value AS UNSIGNED) DESC')
        // ->limit(30)
        // ->get();

        // $vehicles_sold = Vehicle::join('vehicle_metas', 'vehicles.ID', '=', 'vehicle_metas.vehicle_id')
        // // ->select(['vin', 'meta_value'])
        // ->where('meta_key', 'status')
        // ->where('meta_value', 'SOLD')
        // ->orderBy('vehicle_metas.id', 'DESC')
        // ->limit(30)
        // ->get();



        return view('pages.dashboard.index', get_defined_vars());


    }
}
