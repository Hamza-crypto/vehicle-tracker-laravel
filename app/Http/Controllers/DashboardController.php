<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $type = request()->get('type');
        $limit = $type ? 300 : 15;

        if ($type) {
            switch ($type) {
                case 'days_in_yard':
                    $vehicles_with_days_in_yard = $this->getVehiclesWithDaysInYard($limit); // Use the required limit
                    return view('pages.dashboard.all_vehicles.days_in_yard', compact('vehicles_with_days_in_yard'));

                case 'sold':
                    $vehicles_sold = $this->getVehiclesSold($limit);
                    return view('pages.dashboard.all_vehicles.sold', compact('vehicles_sold'));

                case 'inserted':
                    $last_30_inserted = $this->getLastInserted($limit);
                    return view('pages.dashboard.all_vehicles.inserted', compact('last_30_inserted'));

                case 'updated':
                    $last_30_updated = $this->getLastUpdated($limit);
                    return view('pages.dashboard.all_vehicles.updated', compact('last_30_updated'));

                case 'notes':
                    $vehicles_with_notes = $this->getVehiclesWithNotes($limit);
                    return view('pages.dashboard.all_vehicles.notes', compact('vehicles_with_notes'));

                default:
                    abort(404, "Section not found.");
            }
        }

        $vehiclesWithRecentNotes = Vehicle::join('vehicle_notes', 'vehicles.id', '=', 'vehicle_notes.vehicle_id')
            ->orderBy('vehicle_notes.updated_at', 'desc') // Order by most recently updated notes
            ->count();

        // If no 'section' parameter, run all queries with default limits
        $vehicles_with_days_in_yard = $this->getVehiclesWithDaysInYard(10);
        $vehicles_sold = $this->getVehiclesSold(10);
        $last_30_inserted = $this->getLastInserted(10);
        $last_30_updated = $this->getLastUpdated(10);
        return view('pages.dashboard.index', get_defined_vars());

    }

    private function getLastInserted($limit)
    {
        return Cache::remember('last_30_inserted_' . $limit, 3600, function () use ($limit) {
            return Vehicle::latest()->take($limit)->get();
        });
    }

    private function getLastUpdated($limit)
    {
        return Cache::remember('last_30_updated_' . $limit, 3600, function () use ($limit) {
            $last_30_updated = \DB::select("
            SELECT v.* FROM `vehicles` v
            LEFT JOIN `vehicle_metas` vm ON v.id = vm.vehicle_id AND vm.meta_key = 'status'
            WHERE (vm.meta_value IS NULL OR vm.meta_value != 'SOLD')
            ORDER BY v.updated_at DESC
            LIMIT {$limit};
                ");

            foreach ($last_30_updated as $vehicle) {
                $vehicle->updated_at = Carbon::parse($vehicle->updated_at)->format('Y-m-d H:i:s');
                $vehicle->human_readable_format = Carbon::parse($vehicle->updated_at)->diffForHumans();
            }

            return collect($last_30_updated);
        });
    }

    private function getVehiclesWithDaysInYard($limit)
    {
        return Cache::remember('vehicles_with_days_in_yard_' . $limit, 3600, function () use ($limit) { //1440 = 1 hour
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
                    ->limit($limit)
                    ->get();
        });
    }

    private function getVehiclesSold($limit)
    {
        return Cache::remember('vehicles_sold_' . $limit, 1440, function () use ($limit) {
            return Vehicle::join('vehicle_metas', 'vehicles.ID', '=', 'vehicle_metas.vehicle_id')
            ->select(['vehicles.id','vin', 'description', 'auction_lot'])
            ->where('meta_key', 'status')
            ->where('meta_value', 'SOLD')
            ->orderBy('vehicle_metas.id', 'DESC')
            ->limit($limit)
            ->get();
        });
    }

    private function getVehiclesWithNotes($limit)
    {
        return Cache::remember('vehicles_with_notes_' . $limit, 300, function () use ($limit) {
            return Vehicle::join('vehicle_notes', 'vehicles.id', '=', 'vehicle_notes.vehicle_id')
            ->leftJoin('vehicle_metas as status_meta', function ($join) {
                $join->on('vehicles.id', '=', 'status_meta.vehicle_id')
                     ->where('status_meta.meta_key', '=', 'status');
            })
            ->select('vehicles.id', 'vehicles.vin', 'vehicles.description', 'vehicle_notes.updated_at as note_updated_at', 'vehicle_notes.note as note_content')
            ->where(function ($query) {
                $query->whereNull('status_meta.meta_value')
                      ->orWhere('status_meta.meta_value', '!=', 'SOLD');
            })
                ->orderBy('vehicle_notes.updated_at', 'desc')
                ->take($limit)
                ->get();
        });
    }
}
