<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use App\Models\Order;
use App\Models\Screenshot;
use App\Models\Tag;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleMetas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('metas')->get();
        dd($vehicles);
        $makes = $this->get_makes();
        unset($makes[0]);

        $models = $this->get_models();
        unset($models[0]);
        $statuses = VehicleMetas::select('meta_value')->where('meta_key', 'status')->groupBy('meta_value')->get()->toArray() ;

        return view('pages.vehicle.index', compact('models', 'makes', 'statuses'));
    }


    public function create_upload()
    {
        return view('pages.vehicle.upload');
    }

    public function create()
    {
        return view('pages.vehicle.add');
    }

    public function store(Request $request)
    {

    }


    public function import_buy_csv(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        $csv_header_fields = [];
        foreach ($data[0] as $value) {
            $csv_header_fields[] = $value;
        }

        unset($data[0]);

        $vehicles_vins = Vehicle::pluck('vin')->toArray();

        foreach ($data as $row) {

            if (!in_array($row[array_search("VIN", $csv_header_fields)], $vehicles_vins)) {
                $vehicle = new Vehicle();
                $vehicle->invoice = $row[array_search("Invoice Date", $csv_header_fields)];
                $vehicle->lot = $row[array_search("Lot/Inv #", $csv_header_fields)];
                $vehicle->vin = $row[array_search("VIN", $csv_header_fields)];
                $vehicle->year = $row[array_search("Year", $csv_header_fields)];
                $vehicle->make = $row[array_search("Make", $csv_header_fields)];
                $vehicle->model = $row[array_search("Model", $csv_header_fields)];
                $vehicle->save();

                $buy_price = $row[array_search("Invoice Amount", $csv_header_fields)];
                $location = $row[array_search("Location", $csv_header_fields)];

                $vehicle->metas()->updateOrCreate(['meta_key' => 'location'], ['meta_value' => $location]);
                $vehicle->metas()->updateOrCreate(['meta_key' => 'buy_price'], ['meta_value' => $buy_price]);

            }
        }
        return back();
    }

    public function import_sale_csv(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        $csv_header_fields = [];
        foreach ($data[0] as $value) {
            $csv_header_fields[] = $value;
        }

        unset($data[0]);

        $vehicles_vins = Vehicle::pluck('vin')->toArray();

        foreach ($data as $row) {

            if (in_array($row[array_search("VIN", $csv_header_fields)], $vehicles_vins)) {
                $vin = $row[array_search("VIN", $csv_header_fields)];

                $vehicle = Vehicle::where('vin', $vin)->first();

                $status = $row[array_search("Status", $csv_header_fields)];
                $location = $row[array_search("Location", $csv_header_fields)];

                $vehicle->metas()->updateOrCreate(['meta_key' => 'status'], ['meta_value' => $status]);
                $vehicle->metas()->updateOrCreate(['meta_key' => 'location'], ['meta_value' => $location]);
            }
        }
        return back();
    }


    public function import_inventory_csv(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        $csv_header_fields = [];
        foreach ($data[0] as $value) {
            $csv_header_fields[] = $value;
        }

        unset($data[0]);

        $vehicles_vins = Vehicle::pluck('vin')->toArray();

        foreach ($data as $row) {

            if (!in_array($row[array_search("VIN", $csv_header_fields)], $vehicles_vins)) {
                $vehicle = new Vehicle();
                $vehicle->invoice = $row[array_search("Invoice Date", $csv_header_fields)];
                $vehicle->lot = $row[array_search("Lot/Inv #", $csv_header_fields)];
                $vehicle->vin = $row[array_search("VIN", $csv_header_fields)];
                $vehicle->year = $row[array_search("Year", $csv_header_fields)];
                $vehicle->make = $row[array_search("Make", $csv_header_fields)];
                $vehicle->model = $row[array_search("Model", $csv_header_fields)];
                $vehicle->buy_price = $row[array_search("Invoice Amount", $csv_header_fields)];
                $vehicle->save();
            }
        }
        return back();
    }


    public function show(Vehicle $vehicle)
    {
        //
    }


    public function edit(Vehicle $vehicle)
    {
        //
    }


    public function update(Request $request, Vehicle $vehicle)
    {
        //
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        Session::flash('success', __('Successfully Deleted'));
        return redirect()->back();
    }

    public function get_makes()
    {
        return Vehicle::select('make')->groupBy('make')->get()->toArray();
    }

    public function get_models()
    {
        return Vehicle::select('model')->groupBy('model')->get()->toArray() ;
    }
}
