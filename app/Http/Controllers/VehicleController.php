<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Vehicle;
use App\Models\VehicleMetas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class VehicleController extends Controller
{
    public function __construct()
    {
        $this->middleware('vehicle_manager')->only('destroy'); // Vehicle manager and higher roles can delete
        $this->middleware('yard_manager')->only('update'); // Yard manager and higher roles can edit
    }

    public function index()
    {
        $makes = [];
        $models = [];

        $statuses = VehicleMetas::select('meta_value')
            ->where('meta_key', 'status')
            ->where('meta_value', '!=', 'Sold') //excluding sold status
            ->groupBy('meta_value')
            ->orderBy('meta_value')
            ->get()
            ->pluck('meta_value');

        $drivability_rating = VehicleMetas::select('meta_value')
            ->where('meta_key', 'drivability_rating')
            ->whereNotNull('meta_value')
            ->groupBy('meta_value')
            ->orderBy('meta_value')
            ->get()
            ->pluck('meta_value');

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
        return view('pages.vehicle.add');
    }

    public function store(Request $request)
    {
        $vehicle = $this->insert_in_db($request);

        return redirect()->route('vehicles.edit', $vehicle->id);
    }

    /*
     * Step 1:
     */

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
        unset($csvFile[0]);

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

            if (!isset($positions['Item#']) || empty($row[$positions['Item#']])){ //if item# is not present in csv file
                continue;
            }

            $vin = $row[$positions['VIN']];
            if (empty($vin)) continue;

            if (!in_array($vin, $vehicles_vins)) {
                $vehicle = new Vehicle();
                $vehicle->vin = $vin;
                $vehicle->purchase_lot = isset($positions['Stock']) ? $row[$positions['Stock']] : $row[$positions['Stock#']];
                $vehicle->location = $row[$positions['Branch']];
                $vehicle->description = isset($positions['Description']) ? $row[$positions['Description']] : sprintf("%s %s %s", $row[$positions['Year']], $row[$positions['Make']], $row[$positions['Model']]); //year_make_model
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
            "Lot #",
            "Claim #",
            "Status",
            "Description",
            "VIN",
            "Primary Damage",
            "Secondary Damage",
            "Keys",
            "Drivability Rating",
            "Odometer",
            "Odometer Brand",
            "Sale Title State",
            "Sale Title Type",
            "Location",
            "Days in Yard",
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
        return redirect()->route('upload.create.inventory');
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
            $necessary_meta_fields['secondary_damage'] = $row[$positions['Secondary Damage']];
        }
        if (!empty($row[20])) {
            $necessary_meta_fields['sale_title_type'] = $row[$positions['Sale Title State']];
        }
        if (!empty($row[19])) {
            $necessary_meta_fields['sale_title_state'] = $row[$positions['Sale Title Type']];
        }

        $metas = [];
        $now = now();
        foreach ($necessary_meta_fields as $key => $value) {
            $metas[] = [
                'vehicle_id' => $vehicle_id,
                'meta_key' => $key,
                'meta_value' => trim($value),
                'created_at' => $now,
                'updated_at' => $now,
            ];

        }
        DB::table('vehicle_metas')->insert($metas);
    }

    /*
     * Step 3:
     */

    public function import_sold_copart_csv(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

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
        $vehicle_metas = VehicleMetas::where('vehicle_id', $vehicle->id)->get()->mapWithKeys(function ($item) {
        return [$item['meta_key'] => $item['meta_value']];
    });

        $meta_keys = [
            'claim_number',
            'status',
            'primary_damage',
            'keys',
            'drivability_rating',
            'odometer',
            'odometer_brand',
            'days_in_yard',
            'secondary_damage',
            'sale_title_state',
            'sale_title_type'
        ];


        $locations = Vehicle::select('location')->distinct()->orderBy('location', 'asc')->get()->pluck('location');

        return view('pages.vehicle.detail', get_defined_vars());
    }


    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'vin' => 'required',
            'location' => 'required',
            'description' => 'required'
        ]);

        $vehicle->vin = $request->vin;
        $vehicle->purchase_lot = $request->purchase_lot;
        $vehicle->auction_lot = $request->auction_lot;
        $vehicle->location = $request->location;
        $vehicle->description = $request->description;
        $vehicle->left_location = $request->left_location;
        $vehicle->date_paid = $request->date_paid;
        $vehicle->invoice_amount = $request->invoice_amount;
        $vehicle->save();

        $meta_keys = [
            'claim_number',
            'status',
            'primary_damage',
            'keys',
            'drivability_rating',
            'odometer',
            'odometer_brand',
            'days_in_yard',
            'secondary_damage',
            'sale_title_state',
            'sale_title_type'
        ];

        foreach ($meta_keys as $key) {

            if(empty($request->$key) || $request->$key == '-100') continue;

            VehicleMetas::updateOrCreate(
                ['vehicle_id' => $vehicle->id, 'meta_key' => $key],
                [
                    'meta_value' => $request->$key
                ]);
        }

        return response()->json(['message' => 'Vehicle updated successfully']);
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->metas()->delete();
        $vehicle->delete();
        Session::flash('success', __('Successfully Deleted'));
        return redirect()->back();
    }

    public function delete_multiple_vehicles(Request $request)
    {
        $ids = $request->input('ids');
        $vehicles = Vehicle::whereIn('id', $ids)->get();

        foreach ($vehicles as $vehicle) {
            $vehicle->metas()->delete();
            $vehicle->delete();
        }

        return response()->json(['message' => count($vehicles) . ' vehicles have been deleted.']);
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

        $vehicle->date_paid = $request->invoice_date;
        $vehicle->invoice_amount = $request->invoice_amount;
        $vehicle->purchase_lot = $request->purchase_lot;
        $vehicle->auction_lot = $request->auction_lot;
        $vehicle->vin = $request->vin;
        $vehicle->description = sprintf("%s %s %s", $request->year, $request->make, $request->model);
        $vehicle->location = $request->location;
        $vehicle->left_location = $request->left_location;
        $vehicle->save();

        $vehicle->metas()->updateOrCreate(['meta_key' => 'location'], ['meta_value' => $request->location]);
        $vehicle->metas()->updateOrCreate(['meta_key' => 'invoice_amount'], ['meta_value' => $request->invoice_amount]);
        return $vehicle;
//        $vehicle->metas()->updateOrCreate(['meta_key' => 'pickup_date'], ['meta_value' => $request->pickup_date]);
    }
}
