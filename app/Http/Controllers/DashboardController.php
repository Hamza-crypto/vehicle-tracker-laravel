<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Carbon\Carbon;
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
                    ->select(['vehicles.id', 'vin', 'auction_lot', 'description', 'days_in_yard_meta.meta_value'])
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
            ->select(['vehicles.id','vin', 'description', 'auction_lot'])
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
            $last_30_updated = \DB::select("
            SELECT v.* FROM `vehicles` v
            LEFT JOIN `vehicle_metas` vm ON v.id = vm.vehicle_id AND vm.meta_key = 'status'
            WHERE (vm.meta_value IS NULL OR vm.meta_value != 'SOLD')
            ORDER BY v.updated_at DESC
            LIMIT 30;
                ");

            foreach ($last_30_updated as $vehicle) {
                $vehicle->updated_at = Carbon::parse($vehicle->updated_at)->format('Y-m-d H:i:s');
                $vehicle->human_readable_format = Carbon::parse($vehicle->updated_at)->diffForHumans();
            }

            return collect($last_30_updated);
        });


        // $vehiclesWithRecentNotes = Vehicle::join('vehicle_notes', 'vehicles.id', '=', 'vehicle_notes.vehicle_id')
        //     ->orderBy('vehicle_notes.updated_at', 'desc') // Order by most recently updated notes
        //     ->take(3) // Limit to the last 30 vehicles
        //     ->select('vehicles.id', 'vehicles.vin', 'vehicles.description', 'vehicle_notes.updated_at as note_updated_at', 'vehicle_notes.note as note_content')
        //     ->get();

        // dd($vehiclesWithRecentNotes->toArray());
        return view('pages.dashboard.index', get_defined_vars());

    }
}
