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

    public function shopify_call($token, $shop, $api_endpoint, $query = array(), $method = 'GET', $request_headers = array())
    {
        $url = sprintf('https://%s.myshopify.com%s', $shop, $api_endpoint);
        if (!is_null($query) && in_array($method, array('GET', 'DELETE'))) $url = $url . "?" . http_build_query($query);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, TRUE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        $request_headers[] = '';
        $request_headers[] = 'Content-Type: application/json';
        if (!is_null($token)) $request_headers[] = sprintf('X-Shopify-Access-Token: %s', $token);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);

        if ($method != 'GET' && in_array($method, array('POST', 'PUT'))) {
            if (is_array($query)) $query = json_encode($query);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
        }

        $response = curl_exec($curl);
        $error_number = curl_errno($curl);
        $error_message = curl_error($curl);
        curl_close($curl);

        if ($error_number) {
            return $error_message;
        } else {
            $response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);
            $headers = array();
            $header_data = explode("\n", $response[0]);
            $headers['status'] = $header_data[0];
            array_shift($header_data);
            foreach ($header_data as $part) {
                $h = explode(":", $part, 2);
                $headers[trim($h[0])] = trim($h[1]);
            }

            return array('headers' => $headers, 'data' => $response[1]);
        }
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

        $expected_header = [
            "Invoice",
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
            "Invoice Amount",
        ];

        dump($csv_header_fields, $expected_header);
        //find difference between arrays and tell which type of difference it is

        $diff = array_diff($expected_header, $csv_header_fields);
//        dd($diff);

//        if ($csv_header_fields != $expected_header) {
//            Session::flash('error', "File is not matching with criteria");
//            return back()->withInput($request->all() + ['invalid' => $expected_header]);
//        }

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
                $vehicle->description = $row[array_search("Description", $csv_header_fields)]; //year_make_model
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

        $expected_header = [
            0 => "Stock",
            1 => "Branch",
            2 => "VIN",
            3 => "Year",
            4 => "Make",
            5 => "Model",
            6 => "Date Won",
            7 => "Date Paid",
            8 => "Date Picked Up",
            9 => "Bid Amount",
            10 => "Buyer Fee",
            11 => "Internet Fee",
            12 => "Premium Vehicle Report Fee",
            13 => "Fedex Fee",
            14 => "Late Fee",
            15 => "Yard Fee",
            16 => "Other Fees",
            17 => "Service Fee",
            18 => "Sales Tax",
            19 => "Storage Fee",
            20 => "Tow Fee",
            21 => "Total Amount",
            22 => "Series",
            23 => "Color",
            24 => "Transportation & Shipping Fee"
        ];

        $diff = array_diff($expected_header, $csv_header_fields);
//        dump($diff);
//        dd($csv_header_fields,$expected_header );
//        if ($csv_header_fields != $expected_header) {
//            Session::flash('error', "File is not matching with criteria");
//            return back()->withInput($request->all() + ['invalid' => $expected_header]);
//        }

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

                $year = $row[array_search("Year", $csv_header_fields)];
                $make = $row[array_search("Make", $csv_header_fields)];
                $model = $row[array_search("Model", $csv_header_fields)];

                $vehicle->description = sprintf('%s %s %s', $year, $make, $model);
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

    public function import_inventory_copart_csv(Request $request) //step 2
    {
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        $csv_header_fields = [];
        foreach ($data[0] as $value) {
            $csv_header_fields[] = $value;
        }

        unset($data[0]); // Remove header

        $expected_header = [
            0 => "Lot #",
            1 => "Claim #",
            2 => "Status",
            3 => "Description",
            4 => "VIN",
            5 => "Primary Damage",
            6 => "Secondary Damage",
            7 => "Keys",
            8 => "Drivability Rating",
            9 => "Engine",
            10 => "Drive",
            11 => "Missing Parts",
            12 => "Seller",
            13 => "Adjuster",
            14 => "Cert Received Date",
            15 => "Odometer",
            16 => "Odometer Brand",
            17 => "Original Title State",
            18 => "Original Title Type",
            19 => "Sale Title State",
            20 => "Sale Title Type",
            21 => "Title Reviewed",
            22 => "Location",
            23 => "Auction Date",
            24 => "Item # ",
            25 => "Row Location",
            26 => "# of Runs",
            27 => "Days in Yard",
            28 => "Advance Charges",
            29 => "ACV",
            30 => "Repair Cost",
            31 => "Current Bid",
            32 => "Reserve",
            33 => "Reserve Amount",
            34 => "Reserve %",
            35 => "Reviewed",
        ];

//        if ($csv_header_fields != $expected_header) {
//            Session::flash('error', "File is not matching with criteria");
//            return back()->withInput($request->all() + ['invalid' => $expected_header]);
//        }

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
                $vehicle->vin = $vin;
                $vehicle->description = $row[array_search("Description", $csv_header_fields)];
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

            $vehicle->metas()->updateOrCreate(['meta_key' => 'auction_stk'], ['meta_value' => $lot]);

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

        $expected_header = [
            0 => "Lot #",
            1 => "Claim #",
            2 => "Status",
            3 => "Location",
            4 => "Sale Date",
            5 => "Description",
            6 => "Title State ",
            7 => "Title Type",
            8 => "Odometer",
            9 => "Odometer Brand",
            10 => "Primary Damage",
            11 => "Loss Type",
            12 => "Keys",
            13 => "Drivability Rating",
            14 => "ACV",
            15 => "Repair Cost",
            16 => "Sale Price",
            17 => "Return %",
        ];

        if ($csv_header_fields != $expected_header) {
            Session::flash('error', "File is not matching with criteria");
            return back()->withInput($request->all() + ['invalid' => $expected_header]);
        }

        $vehicles_lot = Vehicle::pluck('lot')->toArray();

        foreach ($data as $row) {
            $status = $row[array_search("Status", $csv_header_fields)];
            if ($status == 'WAITING FOR BUYER PAYMENT') continue;

            $lot = $row[array_search("Lot #", $csv_header_fields)];

            $sale_price = $row[array_search("Sale Price", $csv_header_fields)];
            $auction_date = $row[array_search("Sale Date", $csv_header_fields)];
            $auction_date = Carbon::parse($auction_date)->format('Y-m-d');

            if (in_array($lot, $vehicles_lot)) {
                $vehicle = Vehicle::where('lot', $lot)->first();

                $vehicle->metas()->updateOrCreate(['meta_key' => 'status'], ['meta_value' => 'sold']);
                $vehicle->metas()->updateOrCreate(['meta_key' => 'sale_price'], ['meta_value' => $sale_price]);
                $vehicle->metas()->updateOrCreate(['meta_key' => 'auction_date'], ['meta_value' => $auction_date]);

            }

        }

        Session::flash('success', "Successfully updated");

        return back();

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
