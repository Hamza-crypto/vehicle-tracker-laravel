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

    function strToHex($string){
        $hex = '';
        for ($i=0; $i<strlen($string); $i++){
            $ord = ord($string[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0'.$hexCode, -2);
        }
        return strToUpper($hex);
    }
    public function import_buy_copart_csv(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        $required_header = [
            "Invoice Date",
            "Item #",
            "Lot/Inv #",
            "Year",
            "Make",
            "Model",
            "VIN",
            "Location",
            "Description",
            "Left Location",
            "Date Paid",
            "Invoice Amount"
        ];

        foreach ($data[0] as $value) {
            $value = trim($value);
            if (strpos($value, 'Invoice Date') !== false) {
                continue;
            }
            if(!in_array($value, $required_header)){
                Session::flash('error', "CSV file header is not correct");
                return view('pages.vehicle.buy.upload')->with('csv_header', $required_header);
            }
        }

        unset($data[0]);

        $vehicles_vins = Vehicle::pluck('vin')->toArray();

        foreach ($data as $row) {
            $vin = $row[6];

            if (empty($vin)) {
                continue;
            }

            if (!in_array($vin, $vehicles_vins)) {
                $vehicle = new Vehicle();
                $vehicle->vin = $vin;
                $vehicle->purchase_lot = $row[2];
                $vehicle->location = $row[7];
                $vehicle->description = $row[8]; //year_make_model
                $vehicle->left_location = Carbon::parse($row[9])->format('Y-m-d');
                $vehicle->date_paid = Carbon::parse($row[10])->format('Y-m-d');
                $vehicle->invoice_amount = $this->format_amount($row[11]);
                $vehicle->save();
            }

        }

        Session::flash('success', "Successfully inserted");
        return redirect()->route('upload.create.buy');

    }

    public function import_buy_iaai_csv(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        $csv_header_fields = [];
        foreach ($data[0] as $value) {
            $csv_header_fields[] = $value;
        }

        $required_header = [
            "Stock#",
            "Lane",
            "Item#",
            "VIN",
            "Date Won",
            "Date Paid",
            "Date Picked Up",
            "Payment Reference",
            "Receipt#",
            "Description",
            "Series",
            "Color",
            "Bid Type",
            "Bidder",
            "Branch",
            "Bid Amount",
            "Fees & Tax",
            "Total Paid"
        ];

//        foreach ($data[0] as $value) {
//            $value = trim($value);
//            if (strpos($value, 'Stock') !== false) {
//                continue;
//            }
//            if(!in_array($value, $required_header)){
//                Session::flash('error', "CSV file header is not correct");
//                return view('pages.vehicle.buy.upload')->with('csv_header', $required_header);
//            }
//        }


        unset($data[0]);

        $vehicles_vins = Vehicle::pluck('vin')->toArray();

        foreach ($data as $row) {

            $vin = $row[3];

            if (empty($vin) || empty($row[2])) continue;

            if (!in_array($vin, $vehicles_vins)) {
                $vehicle = new Vehicle();
                $vehicle->vin = $vin;
                $vehicle->purchase_lot = $row[0];
                $vehicle->location = $row[14];
                $vehicle->description = $row[9]; //year_make_model
                $vehicle->left_location = Carbon::parse($row[6])->format('Y-m-d');
                $vehicle->date_paid = Carbon::parse($row[5])->format('Y-m-d');
                $vehicle->invoice_amount = $this->format_amount($row[17]);
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
        $data = array_map('str_getcsv', file($path));

        $csv_header_fields = [];
        foreach ($data[0] as $value) {
            $csv_header_fields[] = $value;
        }

        $required_header = [
            "Lot #",
            "Claim #",
            "Status",
            "Description",
            "VIN",
            "Primary Damage",
            "Secondary Damage",
            "Keys",
            "Drivability Rating",
            "Engine",
            "Drive",
            "Missing Parts",
            "Seller",
            "Adjuster",
            "Cert Received Date",
            "Odometer",
            "Odometer Brand",
            "Original Title State",
            "Original Title Type",
            "Sale Title State",
            "Sale Title Type",
            "Title Reviewed",
            "Location",
            "Auction Date",
            "Item # ",
            "Row Location",
            "# of Runs",
            "Days in Yard",
            "Advance Charges",
            "ACV",
            "Repair Cost",
            "Current Bid",
            "Reserve",
            "Reserve Amount",
            "Reserve %",
            "Reviewed",
            "Auction Comment"
        ];

//        foreach ($data[0] as $value) {
//            $value = trim($value);
//            if(!in_array($value, $required_header)){
//                Session::flash('error', "CSV file header is not correct");
//                return view('pages.vehicle.inventory.upload')->with('csv_header', $required_header);
//            }
//        }


        unset($data[0]); // Remove header

        $vehicles_vins = Vehicle::pluck('vin')->toArray();

        $today = now();
        $past_auction_date = [];
        $count = 0;
        foreach ($data as $row) {

            $vin = $row[4];

            $auction_date = Carbon::parse($row[23]);
            if($auction_date < $today){
                $past_auction_date[] = ['vin' => $vin, 'auction_date' => $auction_date->format('Y-m-d')];

                $count++;
                if($count > 10) {
                    return view('pages.vehicle.inventory.upload')->with('past_auction_date', $past_auction_date);
                }
                continue;
            }

            if (!in_array($vin, $vehicles_vins)) {
                //insert new vehicle
                $vehicle = new Vehicle();
                $vehicle->vin = $vin;
                $vehicle->auction_lot = $row[0];
                $vehicle->location = $row[22];
                $vehicle->description = $row[3]; //year_make_model
                $vehicle->save();
                $this->insert_vehicle_metas($row, $vehicle->id);
            } else {
                //update vehicle
                $vehicle = Vehicle::where('vin', $vin)->first();
                //delete old vehicle metas
                VehicleMetas::where('vehicle_id', $vehicle->id)->delete();
                $vehicle->auction_lot = $row[0];
                $vehicle->location = $row[22];
                $vehicle->save();
                $this->insert_vehicle_metas($row, $vehicle->id);

            }

        }


        Session::flash('success', "Successfully inserted");
        return view('pages.vehicle.inventory.upload')->with('past_auction_date', $past_auction_date);
    }

    public function insert_vehicle_metas($row, $vehicle_id)
    {
        $necessary_meta_fields = [
            'claim_number' => $row[1],
            'status' => $row[2],
            'primary_damage' => $row[5],
            'keys' => $row[7],
            'drivability_rating' => $row[8],
            'odometer' => $row[15],
            'odometer_brand' => $row[16],
            'days_in_yard' => $row[27],
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
            if(!in_array($value, $required_header)){
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

            if ( in_array($lot, $auction_lot ) || in_array($lot, $purchase_lot ) ) {
                $vehicle = Vehicle::where('auction_lot', $lot)->orWhere('purchase_lot' , $lot)->first();
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
