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
        $makes = [];
        $models = [];

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

    /*
     * Step 1:
     */

    function strToHex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $ord = ord($string[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0' . $hexCode, -2);
        }
        return strToUpper($hex);
    }

    public function import_buy_copart_csv(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $csvFile = array_map('str_getcsv', file($path));

        $headers = $csvFile[0];
        unset($csvFile[0]);

        $requiredColumns = [
            "VIN",
            "Lot/Inv #",
            "Location",
            "Description",
            "Left Location",
            "Date Paid",
            "Invoice Amount"
        ];
        $positions = [];

        // Find positions of required columns in the first row
        foreach ($requiredColumns as $columnName) {
            $position = array_search($columnName, $headers);
            if ($position === false) {
                Session::flash('error', "CSV file header is not correct");
                return view('pages.vehicle.buy.upload')->with('csv_header', $requiredColumns);
            }
            $positions[$columnName] = $position;
        }
        $vehicles_vins = Vehicle::pluck('vin')->toArray();

        foreach ($csvFile as $row) {
            $vin = $row[$positions['VIN']];

            if (empty($vin)) {
                continue;
            }

            if (!in_array($vin, $vehicles_vins)) {
                $vehicle = new Vehicle();
                $vehicle->vin = $vin;
                $vehicle->purchase_lot = $row[$positions['Lot/Inv #']];
                $vehicle->location = $row[$positions['Location']];
                $vehicle->description = $row[$positions['Description']]; //year_make_model
                $vehicle->left_location = Carbon::parse($row[$positions['Left Location']])->format('Y-m-d');
                $vehicle->date_paid = Carbon::parse($row[$positions['Date Paid']])->format('Y-m-d');
                $vehicle->invoice_amount = $this->format_amount($row[$positions['Invoice Amount']]);
                $vehicle->save();
            }
        }

        Session::flash('success', "Successfully inserted");
        return redirect()->route('upload.create.buy');

    }

    public function import_buy_iaai_csv(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $csvFile = array_map('str_getcsv', file($path));

        $headers = $csvFile[0];

        $requiredColumns = [
            "VIN",
            "Stock",
            "Stock#",
            "Branch",
            "Description",
            "Year",
            "Make",
            "Model",
            "Date Picked Up",
            "Date Paid",
            "Total Paid",
            "Total Amount",
            "Item#"
        ];

        if (!in_array("Item#", $headers)) {
            unset($requiredColumns[12]);
        }

        if (in_array("Description", $headers)) {
            unset($requiredColumns[5]);
            unset($requiredColumns[6]);
            unset($requiredColumns[7]);
        } else {
            unset($requiredColumns[4]);
        }

        if (in_array("Stock", $headers)) {
            unset($requiredColumns[2]);
        } else {
            unset($requiredColumns[1]);
        }

        if (in_array("Total Paid", $headers)) {
            unset($requiredColumns[11]);
        } else {
            unset($requiredColumns[10]);
        }
        $positions = [];

        // Find positions of required columns in the first row
        foreach ($requiredColumns as $columnName) {
            $position = array_search($columnName, $headers);
            if ($position === false) {
                Session::flash('error', "CSV file header is not correct");
                return view('pages.vehicle.buy.upload')->with('csv_header', $requiredColumns);
            }
            $positions[$columnName] = $position;
        }

        $vehicles_vins = Vehicle::pluck('vin')->toArray();

        foreach ($csvFile as $row) {

            $vin = $row[$positions['VIN']];

            if (empty($vin) || empty($row)) continue;

            if (!in_array($vin, $vehicles_vins)) {
                $vehicle = new Vehicle();
                $vehicle->vin = $vin;
                $vehicle->purchase_lot = isset($positions['Stock']) ? $row[$positions['Stock']] : $row[$positions['Stock#']];
                $vehicle->location = $row[$positions['Branch']];
                $vehicle->description = isset($positions['Description']) ?  $row[$positions['Description']] : sprintf("%s %s %s",$row[$positions['Year']], $row[$positions['Make']], $row[$positions['Model']] ) ; //year_make_model
                $vehicle->left_location = Carbon::parse($row[$positions['Date Picked Up']])->format('Y-m-d');
                $vehicle->date_paid = Carbon::parse($row[$positions['Date Paid']])->format('Y-m-d');
                $vehicle->invoice_amount = isset($positions['Total Amount']) ? $this->format_amount($row[$positions['Total Amount']]) : $this->format_amount($row[$positions['Total Paid']]);
                $vehicle->save();
            }

        }

        Session::flash('success', "Successfully inserted");
        return redirect()->route('upload.create.buy');
    }

    /*
     * Step 2:
     */

    public function import_inventory_copart_csv(Request $request) //step 2
    {
        $path = $request->file('csv_file')->getRealPath();
        $csvFile = array_map('str_getcsv', file($path));

        $headers = $csvFile[0];
        unset($csvFile[0]);

        $requiredColumns = [
            "VIN",
            "Lot #",
            "Location",
            "Description",
            "Claim #",
            "Status",
            "Primary Damage",
            "Keys",
            "Drivability Rating",
            "Odometer",
            "Odometer Brand",
            "Days in Yard",
            "Secondary Damage",
            "Sale Title State",
            "Sale Title Type"
        ];

        $positions = [];

        // Find positions of required columns in the first row
        foreach ($requiredColumns as $columnName) {
            $position = array_search($columnName, $headers);
            if ($position === false) {
                 Session::flash('error', "CSV file header is not correct");
                return view('pages.vehicle.inventory.upload')->with('csv_header', $requiredColumns);
            }
            $positions[$columnName] = $position;
        }
dd($positions);
        $vehicles_vins = Vehicle::pluck('vin')->toArray();

//        $today = now();
//        $past_auction_date = [];

        $count = 0;
        foreach ($csvFile as $row) {

            $vin = $row[$positions['VIN']];

            /*
             * Check if auction date is past date
             */
//            $auction_date = Carbon::parse($row[23]);
//            if ($auction_date < $today) {
//                $past_auction_date[] = ['vin' => $vin, 'auction_date' => $auction_date->format('Y-m-d')];
//
//                $count++;
//                if ($count > 10) {
//                    return view('pages.vehicle.inventory.upload')->with('past_auction_date', $past_auction_date);
//                }
//                continue;
//            }
//            return view('pages.vehicle.inventory.upload')->with('past_auction_date', $past_auction_date);
            if (!in_array($vin, $vehicles_vins)) {
                //insert new vehicle
                $vehicle = new Vehicle();
                $vehicle->vin = $vin;
                $vehicle->auction_lot = $row[$positions['Lot #']];
                $vehicle->location = $row[$positions['Location']];
                $vehicle->description = $row[$positions['Description']]; //year_make_model
                $vehicle->save();
                $this->insert_vehicle_metas($row, $vehicle->id, $positions);
            } else {
                //update vehicle
                $vehicle = Vehicle::where('vin', $vin)->first();
                //delete old vehicle metas
                VehicleMetas::where('vehicle_id', $vehicle->id)->delete();
                $vehicle->auction_lot = $row[$positions['Lot #']];
                $vehicle->location = $row[$positions['Location']];
                $vehicle->save();
                $this->insert_vehicle_metas($row, $vehicle->id, $positions);

            }

        }

        Session::flash('success', "Successfully inserted");
        return view('pages.vehicle.inventory.upload');
    }

    public function insert_vehicle_metas($row, $vehicle_id, $positions)
    {
        $necessary_meta_fields = [
            'claim_number' => $row[$positions['Claim #']],
            'status' => $row[$positions['Status']],
            'primary_damage' => $row[$positions['Primary Damage']],
            'keys' => $row[$positions['Keys']],
            'drivability_rating' => $row[$positions['Drivability Rating']],
            'odometer' => $row[$positions['Odometer']],
            'odometer_brand' => $row[$positions['Odometer Brand']],
            'days_in_yard' => $row[$positions['Days in Yard']],
        ];
        if (!empty($row[6])) {
            $necessary_meta_fields['secondary_damage'] = $row[6];
        }
        if (!empty($row[20])) {
            $necessary_meta_fields['sale_title_type'] = $row[20];
        }
        if (!empty($row[19])) {
            $necessary_meta_fields['sale_title_state'] = $row[19];
        }

        $metas = [];
        foreach ($necessary_meta_fields as $key => $value) {
            $metas[] = [
                'meta_key' => $key,
                'meta_value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            VehicleMetas::updateOrCreate(
                ['vehicle_id' => $vehicle_id, 'meta_key' => $key],
                [
                    'meta_value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);


        }
//        DB::table('vehicle_metas')->insert($metas);
    }

    /*
     * Step 3:
     */

    public function import_sold_copart_csv(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        $csv_header_fields = [];
        foreach ($data[0] as $value) {
            $csv_header_fields[] = $value;
        }


        $required_header = [
            "Lot #",
            "Claim #",
            "Status",
            "Location",
            "Sale Date",
            "Description",
            "Title State",
            "Title Type",
            "Odometer",
            "Odometer Brand",
            "Primary Damage",
            "Loss Type",
            "Keys",
            "Drivability Rating",
            "ACV",
            "Repair Cost",
            "Sale Price",
            "Return %"
        ];

        foreach ($data[0] as $value) {
            $value = trim($value);
            if (!in_array($value, $required_header)) {
                Session::flash('error', "CSV file header is not correct");
                return view('pages.vehicle.sold.upload')->with('csv_header', $required_header);
            }
        }


        unset($data[0]); // Remove header
        $auction_lot = Vehicle::whereNotNull('auction_lot')->pluck('auction_lot')->toArray();
        $purchase_lot = Vehicle::whereNotNull('purchase_lot')->pluck('purchase_lot')->toArray();

        $vehicles_not_found = [];
        foreach ($data as $row) {
            $lot = $row[0];

            if (in_array($lot, $auction_lot) || in_array($lot, $purchase_lot)) {
                $vehicle = Vehicle::where('auction_lot', $lot)->orWhere('purchase_lot', $lot)->first();
                VehicleMetas::updateOrCreate(
                    ['vehicle_id' => $vehicle->id, 'meta_key' => 'sale_date'],
                    [
                        'meta_value' => Carbon::parse($row[4])->format('Y-m-d') //sale_date
                    ]);
                VehicleMetas::updateOrCreate(
                    ['vehicle_id' => $vehicle->id, 'meta_key' => 'sale_price'],
                    [
                        'meta_value' => $row[16] //sale_price
                    ]);
                VehicleMetas::updateOrCreate(
                    ['vehicle_id' => $vehicle->id, 'meta_key' => 'status'],
                    [
                        'meta_value' => 'SOLD' //sale_price
                    ]);
            } else {
                $vehicles_not_found[] = ['lot' => $lot];
            }

        }

        Session::flash('success', "Successfully updated");
        return view('pages.vehicle.sold.upload')->with('vehicles_not_found', $vehicles_not_found);

    }


    public function edit(Vehicle $vehicle)
    {
        $vehicle_metas = VehicleMetas::where('vehicle_id', $vehicle->id)->get();
        return view('pages.vehicle.detail', get_defined_vars());
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
            ->where('meta_value', '!=', '')
            ->groupBy('meta_value')->get();
    }

    public function get_years()
    {
        $year = [];
        for ($i = 1950; $i < 2024; $i++) {
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
        if (!$vehicle) {
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
