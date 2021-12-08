<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Vehicle;
use App\Models\VehicleMetas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class VehicleController extends Controller
{
    public function index()
    {
//        $vehicles = Vehicle::with('metas')->get();
//        foreach ($vehicles as $vehicle){
//            dd($vehicle->invoice_amount);
//        }
        $makes = $this->get_makes();
//        unset($makes[0]);

        $models = $this->get_models();
 //       unset($models[0]);
        $statuses = VehicleMetas::select('meta_value')->where('meta_key', 'status')->groupBy('meta_value')->get()->toArray();

        return view('pages.vehicle.index', compact('models', 'makes', 'statuses'));
    }


    public function create_upload_buy()
    {
        return view('pages.vehicle.buy.upload');
    }

    public function create_upload_inventory()
    {
        return view('pages.vehicle.inventory.upload');
    }

    public function create_upload_sold()
    {
        return view('pages.vehicle.sold.upload');
    }

    public function create()
    {
        $makes = $this->get_makes();
        $models = $this->get_models();
        $years = $this->get_years();
        $locations = $this->get_locations();
        $locations2 = Location::all();
        return view('pages.vehicle.add', get_defined_vars());
    }

    public function store(Request $request)
    {
        $this->insert_in_db($request);


        Session::flash('success', "Vehicle inserted successfully");
        return back();
    }


    public function import_buy_copart_csv(Request $request)
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

            $vin = $row[array_search("VIN", $csv_header_fields)];

            if (empty($vin)) continue;

            $invoice_amount = $this->format_amount($row[array_search("Invoice Amount", $csv_header_fields)]);
            $location = $row[array_search("Location", $csv_header_fields)];
            $date_picked_up = $row[array_search("Left Location", $csv_header_fields)];


            if (!in_array($vin, $vehicles_vins)) {
                $vehicle = new Vehicle();
                $vehicle->invoice_date = $row[array_search("Invoice Date", $csv_header_fields)];
                $vehicle->lot = $row[array_search("Lot/Inv #", $csv_header_fields)];
                $vehicle->vin = $vin;
                $vehicle->year = $row[array_search("Year", $csv_header_fields)];
                $vehicle->make = explode(' ', $row[array_search("Description", $csv_header_fields)])[1];
                $vehicle->model = $row[array_search("Model", $csv_header_fields)];
                $vehicle->save();

            } else {
                $vehicle = Vehicle::where('vin', $vin)->first();
            }

            $date_picked_up = Carbon::parse($date_picked_up)->format('Y-m-d');
            $vehicle->metas()->updateOrCreate(['meta_key' => 'invoice_amount'], ['meta_value' => $invoice_amount]);
            $vehicle->metas()->updateOrCreate(['meta_key' => 'location'], ['meta_value' => $location]);
            $vehicle->metas()->updateOrCreate(['meta_key' => 'pickup_date'], ['meta_value' => $date_picked_up]);
        }

        Session::flash('success', "Successfully inserted");

        return back();
    }

    public function import_buy_iaai_csv(Request $request)
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

            $vin = $row[array_search("VIN", $csv_header_fields)];

            if (empty($vin)) continue;

            $invoice_amount = $this->format_amount($row[array_search("Total Amount", $csv_header_fields)]); // Invoice Amount = Total Amount

            $location = $row[array_search("Branch", $csv_header_fields)]; // Location = Branch
            $date_picked_up = $row[array_search("Date Picked Up", $csv_header_fields)]; // Left Location = Date Picked Up

            if (!in_array($vin, $vehicles_vins)) {
                $vehicle = new Vehicle();
                $vehicle->invoice_date = $row[array_search("Date Won", $csv_header_fields)]; //Invoice Date = Date Won
                $vehicle->lot = $row[array_search("Stock", $csv_header_fields)]; // Lot/Inv #  = Stock
                $vehicle->vin = $vin;
                $vehicle->year = $row[array_search("Year", $csv_header_fields)];
                $vehicle->make = $row[array_search("Make", $csv_header_fields)];
                $vehicle->model = $row[array_search("Model", $csv_header_fields)];
                $vehicle->save();

            } else {
                $vehicle = Vehicle::where('vin', $vin)->first();
            }

            $date_picked_up = Carbon::parse($date_picked_up)->format('Y-m-d');
            $vehicle->metas()->updateOrCreate(['meta_key' => 'invoice_amount'], ['meta_value' => $invoice_amount]);
            $vehicle->metas()->updateOrCreate(['meta_key' => 'location'], ['meta_value' => $location]);
            $vehicle->metas()->updateOrCreate(['meta_key' => 'pickup_date'], ['meta_value' => $date_picked_up]);
        }

        Session::flash('success', "Successfully inserted");

        return back();
    }

    public function import_inventory_copart_csv(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        $csv_header_fields = [];
        foreach ($data[0] as $value) {
            $csv_header_fields[] = $value;
        }

        unset($data[0]); // Remove header

        $vehicles_vins = Vehicle::pluck('vin')->toArray();

        foreach ($data as $row) {

            $vin = $row[array_search("VIN", $csv_header_fields)];

            if (empty($vin)) continue;

            $lot = $row[array_search("Lot #", $csv_header_fields)];
            $claim_number = $row[array_search("Claim #", $csv_header_fields)];
            $location = $row[array_search("Location", $csv_header_fields)];
            $auction_date = $row[array_search("Auction Date", $csv_header_fields)];
            $number_of_runs = $row[array_search("# of Runs", $csv_header_fields)];
            $days_in_yard = $row[array_search("Days in Yard", $csv_header_fields)];

            if (!in_array($vin, $vehicles_vins)) {
                $vehicle = new Vehicle();
                $vehicle->lot = $lot;
                $vehicle->vin = $vin;
                $vehicle->year = explode(' ', $row[array_search("Description", $csv_header_fields)])[0];
                $vehicle->make = explode(' ', $row[array_search("Description", $csv_header_fields)])[1];

                $vehicle->save();

            } else {
                $vehicle = Vehicle::where('vin', $vin)->first();
            }

            $auction_date = Carbon::parse($auction_date)->format('Y-m-d');
            $vehicle->metas()->updateOrCreate(['meta_key' => 'location'], ['meta_value' => $location]);
            $vehicle->metas()->updateOrCreate(['meta_key' => 'claim_number'], ['meta_value' => $claim_number]);
            $vehicle->metas()->updateOrCreate(['meta_key' => 'auction_date'], ['meta_value' => $auction_date]);
            $vehicle->metas()->updateOrCreate(['meta_key' => 'number_of_runs'], ['meta_value' => $number_of_runs]);
            $vehicle->metas()->updateOrCreate(['meta_key' => 'days_in_yard'], ['meta_value' => $days_in_yard]);
        }

        Session::flash('success', "Successfully inserted");
        return back();
    }

    public function import_sold_copart_csv(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        $csv_header_fields = [];
        foreach ($data[0] as $value) {
            $csv_header_fields[] = $value;
        }

        unset($data[0]); // Remove header

        $vehicles_lot = Vehicle::pluck('lot')->toArray();

        foreach ($data as $row) {
            $status = $row[array_search("Status", $csv_header_fields)];
            if ($status == 'WAITING FOR BUYER PAYMENT') continue;

            $lot = $row[array_search("Lot #", $csv_header_fields)];

            $sale_price = $row[array_search("Sale Price", $csv_header_fields)];
            $sale_date = $row[array_search("Sale Date", $csv_header_fields)];
            $sale_date = Carbon::parse($sale_date)->format('Y-m-d');

            if (in_array($lot, $vehicles_lot)) {
                $vehicle = Vehicle::where('lot', $lot)->first();

                $vehicle->metas()->updateOrCreate(['meta_key' => 'status'], ['meta_value' => 'sold']);
                $vehicle->metas()->updateOrCreate(['meta_key' => 'sale_price'], ['meta_value' => $sale_price]);
                $vehicle->metas()->updateOrCreate(['meta_key' => 'sold_at'], ['meta_value' => $sale_date]);

            }

        }

        Session::flash('success', "Successfully updated");

        return back();

    }


    public function show(Vehicle $vehicle)
    {
        //
    }


    public function edit(Vehicle $vehicle)
    {
        $makes = $this->get_makes();
        $models = $this->get_models();
        $years = $this->get_years();

        $locations = $this->get_locations();
        $locations2 = Location::all();

        return view('pages.vehicle.edit', get_defined_vars());
    }


    public function update(Request $request, Vehicle $vehicle)
    {
        $this->insert_in_db($request, $vehicle);

        Session::flash('success', "Vehicle updated successfully");
        return back();
    }

    public function destroy(Vehicle $vehicle)
    {
        foreach ($vehicle->metas as $meta) {
            $meta->delete();
        }

        $vehicle->delete();
        Session::flash('success', __('Successfully Deleted'));
        return redirect()->back();
    }

    public function get_makes()
    {
        return Vehicle::select('make')
            ->where('make', '!=', '')
            ->groupBy('make')->get();
    }

    public function get_models()
    {
        return Vehicle::select('model')
            ->where('model', '!=', '')
            ->groupBy('model')->get();
    }

    public function get_locations()
    {
        return VehicleMetas::select('meta_value')
            ->where('meta_key', 'location')
            ->where('meta_value',  '!=', '')
            ->groupBy('meta_value')->get();
    }

    public function get_years()
    {
        $year = [];
       for ($i = 1950; $i<2023; $i++){
           $year[] = $i;
       }

       return $year;

    }

    public function format_amount($amount)
    {
        $invoice_amount = str_replace('$', '', $amount);
        $invoice_amount = str_replace('USD', '', $invoice_amount);
        $invoice_amount = str_replace(',', '', $invoice_amount);
        return $invoice_amount;
    }

    public function insert_in_db($request, $vehicle = null)
    {
        if(!$vehicle){
            $vehicle = new Vehicle();
        }


        $vehicle->invoice_date = $request->invoice_date;
        $vehicle->lot = $request->lot;
        $vehicle->vin = $request->vin;
        $vehicle->year = $request->year;
        $vehicle->make = $request->make;
        $vehicle->model = $request->model;
        $vehicle->save();

        $vehicle->metas()->updateOrCreate(['meta_key' => 'location'], ['meta_value' => $request->location]);
        $vehicle->metas()->updateOrCreate(['meta_key' => 'pickup_date'], ['meta_value' => $request->pickup_date]);
        $vehicle->metas()->updateOrCreate(['meta_key' => 'invoice_amount'], ['meta_value' => $request->invoice_amount]);
    }
}
