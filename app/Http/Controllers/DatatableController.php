<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleMetas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DatatableController extends Controller
{
    public function vehicles(Request $request)
    {

        $totalData = Vehicle::filters($request->all())->count();

        // Widget for Orders index page
        $totalOrderCount = ['accepted' => 0, 'pending' => 0, 'declined' => 0];

        $totalFiltered = $totalData;

        $start = $request->length == -1 ? 0 : $request->start;
        $limit = $request->length == -1 ? $totalData : $request->length;

        //Put the columns in an array for which you want to apply the sorting,
        // index should be in accordance with the column index in the table
        $dbColumns = [
            0 => "id",
            3 => "left_location",
            5 => "date_paid",
            6 => "purchase_lot",
            7 => "auction_lot",

            // sorting by meta value
            8 => "days_in_yard",
            9 => "claim_number"
        ];

        $orderColumnIndex = $request->input('order.0.column');

        $orderDbColumn = $dbColumns[$orderColumnIndex];
        $orderDirection = $request->input('order.0.dir');


        if (empty($request->input('search.value'))) {
            $vehicles = Vehicle::with('metas')->filters($request->all());

            $vehicles = $vehicles->orderBy($orderDbColumn, $orderDirection);

            $vehicles = $vehicles->offset($start)->limit($limit)->get();

        } else {
            $search = $request->input('search.value');
            $vehicles = Vehicle::with('metas')->filters($request->all());
            $vehicles = $vehicles->where(function ($q1) use ($search) {
                $q1->where('vin', 'LIKE', "%$search%")
                    ->orWhere('purchase_lot', 'LIKE', "%$search%")
                    ->orWhere('auction_lot', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%"); // ILIKE only used for Postgress

            })
                ->get();

            $totalFiltered = count($vehicles);
            $vehicles = $vehicles->skip($start)->take($limit);
        }

//
        $data = [];
        $user_role = Auth::user()->role;
        foreach ($vehicles as &$vehicle) {
            $vehicle->null = "";
            $vehicle->DT_RowId = $vehicle->id;
            $vehicle->created_at_new = $vehicle->created_at->diffForHumans();
            $edit = '<a href="' . route('vehicles.edit', $vehicle->id) . '" class="btn" style="display: inline" target="_blank"><i class="fa fa-edit text-info"></i></a>';
            //$edit = '';
            $alertTitle = __("Are you sure you want to delete vehicle with VIN ") . ' ' . $vehicle->vin;
            $delete = '
                    <form method="post" action="' . route('vehicles.destroy', $vehicle->id) . '" style="display: inline"
                        onsubmit="return confirmSubmission(this, \'' . $alertTitle . '\')">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn text-danger"><i class="fa fa-trash"></i></button>
                    </form>
                    ';

            $vehicle->description = sprintf("<a data-toggle='modal' data-target='#modal-vehicle-detail' data-id='%s'>%s</a>", $vehicle->id, $vehicle->description);

            $vehicle->purchase_lot = sprintf("<a href='https://www.copart.com/lot/%s' target='_blank'>%s</a>", $vehicle->purchase_lot, $vehicle->purchase_lot);
            $vehicle->auction_lot = sprintf("<a href='https://www.copart.com/lot/%s' target='_blank'>%s</a>", $vehicle->auction_lot, $vehicle->auction_lot);

            $vehicle->invoice_amount = $vehicle->invoice_amount != null ? "$" . $vehicle->invoice_amount : '';
            $vehicle->date_paid = sprintf("<span> %s</span>", $vehicle->date_paid);
            $vehicle->actions .= $edit . $delete;
            if ($user_role == 'yard_manager') $vehicle->actions = $edit;

            //Get meta data
            $vehicle_metas = collect($vehicle->metas);
            $vehicle->claim_number = $vehicle_metas->where('meta_key', 'claim_number')->pluck('meta_value')->first();
            $vehicle->days_in_yard = $vehicle_metas->where('meta_key', 'days_in_yard')->pluck('meta_value')->first();
            $vehicle->status = $vehicle_metas->where('meta_key', 'status')->pluck('meta_value')->first();

            $data[] = $vehicle;

        }


        $extraInfo = [
            'total_orders_count' => $totalFiltered,
            'orders_status_accepted' => $totalOrderCount['accepted'],
            'orders_status_pending' => $totalOrderCount['pending'],
            'orders_status_declined' => $totalOrderCount['declined'],
            'user_rate' => ''
        ];


        $data = [
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            'data' => $data,
            'extra_info' => $extraInfo
        ];

        return response()->json($data);


    }

    public function getVehicleDetails(Vehicle $vehicle)
    {
            if ($this->isMobileDev()) {
                return $this->generateHtmlForMobile($vehicle);
            } else {
                return $this->generateHtml($vehicle);
            }
    }

    public function generateHtml(Vehicle $vehicle)
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

        //if any of the meta keys is missing, add it to the array with empty value
        foreach ($meta_keys as $meta_key) {
            if (!$vehicle_metas->has($meta_key)) {
                $vehicle_metas->put($meta_key, '');
            }
        }

        $html = '            <div class="modal-body m-3">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th style="width:40%;">Key</th>
                                                <th style="width:25%">Value</th>
                                            </tr>
                                            </thead>
                                            <tbody>';
        $vehicleArray = $vehicle->toArray();

        $array_keys = array_keys($vehicleArray);
        $values = array_values($vehicleArray);
        $count = count($array_keys);

        $locations = Vehicle::select('location')->distinct()->orderBy('location', 'asc')->get()->pluck('location');
        $statuses = VehicleMetas::select('meta_value')
            ->where('meta_key', 'status')
           // ->where('meta_value', '!=', 'Sold') //excluding sold status
            ->groupBy('meta_value')
            ->orderBy('meta_value')
            ->get()
            ->pluck('meta_value');

        $odometer = VehicleMetas::select('meta_value')
            ->where('meta_key', 'odometer')
            ->groupBy('meta_value')
            ->orderByRaw('CAST(meta_value AS DECIMAL(10,2))')
            ->get()
            ->pluck('meta_value');

        $primary_damage = VehicleMetas::select('meta_value')
            ->where('meta_key', 'primary_damage')
            ->groupBy('meta_value')
            ->orderBy('meta_value')
            ->get()
            ->pluck('meta_value');

        $secondary_damage = VehicleMetas::select('meta_value')
            ->where('meta_key', 'secondary_damage')
            ->whereNotNull('meta_value')
            ->groupBy('meta_value')
            ->orderBy('meta_value')
            ->get()
            ->pluck('meta_value');

        $keys = VehicleMetas::select('meta_value')
            ->where('meta_key', 'keys')
            ->whereNotNull('meta_value')
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


        $extra_data = [
            'location' => $locations,
            'status' => $statuses,
            'odometer' => $odometer,
            'primary_damage' => $primary_damage,
            'keys' => $keys,
            'drivability_rating' => $drivability_rating,
        ];


        for ($i = 0; $i < $count; $i += 2) {
            $first_key = $i;
            $second_key = $i + 1 >= $count ? $i : $i + 1;
            $html .= '<tr>';

            $html .= '<div class="row">'; //row started

            $html .= '<div class="col-6">';
            $html = $this->getHtmlTwo_TD($array_keys[$first_key], $html, $values[$first_key], $extra_data);
            $html .= '</div>'; //col-6 close

            $html .= '<div class="col-6">';
            $html = $this->getHtmlTwo_TD($array_keys[$second_key], $html, $values[$second_key], $extra_data);
            $html .= '</div>'; //col-6 close

            $html .= '</div>'; //row close

            $html .= '</tr>';
        }

        $first_subset = array_slice($meta_keys, 0, 6);
        $second_subset = array_slice($meta_keys, 6,);

        $count = count($first_subset);

        for ($i = 0; $i < $count; $i += 2) {
            $first_key = $i;
            $second_key = $i + 1 >= $count ? $i : $i + 1;

            $html .= '<tr>';
            $html .= '<div class="row r1">'; //row started

            $html .= '<div class="col-6 col1">';
            $html = $this->getMetaHtmlTwo_TD($first_subset[$first_key], $html, $vehicle_metas[$first_subset[$first_key]], $extra_data);
            $html .= '</div>'; //col-6 close

            $html .= '<div class="col-6 col2">';
            $html = $this->getMetaHtmlTwo_TD($first_subset[$second_key], $html, $vehicle_metas[$first_subset[$second_key]], $extra_data);
            $html .= '</div>'; //col-6 close

            $html .= '</div>'; //row close

            $html .= '</tr>';
        }

        $count = count($second_subset);

        for ($i = 0; $i < $count; $i += 2) {
            $first_key = $i;
            $second_key = $i + 1 >= $count ? $i : $i + 1;

            $html .= '<tr>';
            $html .= '<div class="row">'; //row started

            $html .= '<div class="col-6">';
            $html = $this->getMetaHtmlTwo_TD($second_subset[$first_key], $html, $vehicle_metas[$second_subset[$first_key]] ?? '');
            $html .= '</div>'; //col-6 close

            //if($i == 4) break; it will remove 2nd column of last row e.g. duplicated column

            $html .= '<div class="col-6">';
            $html = $this->getMetaHtmlTwo_TD($second_subset[$second_key], $html, $vehicle_metas[$second_subset[$second_key]] ?? '');
            $html .= '</div>'; //col-6 close

            $html .= '</div>'; //row close

            $html .= '</tr>';
        }

        $html .= ' </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>';

        return response()->json(['html' => $html]);
    }

    public function generateHtmlForMobile(Vehicle $vehicle)
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

        $html = '            <div class="modal-body m-3">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th style="width:40%;">Key</th>
                                                <th style="width:25%">Value</th>
                                            </tr>
                                            </thead>
                                            <tbody>';
        $vehicleArray = $vehicle->toArray();

        foreach ($vehicleArray as $key => $value) {
            if ($key == 'updated_at') {
                continue;
            }
            $html .= '<tr>';
            $html .= '<td style="padding: 0rem !important;">' . strtoupper($key) . '</td>';
            $html .= '<td style="padding: 0rem !important;">';
            $html .= '<input type="text" class="form-control';
            if (in_array($key, ['left_location', 'date_paid'])) {
                $html .= ' daterange';
            }
            $html .= '"';
            $html .= ' name="' . $key . '"';
            $html .= ' value="' . $value . '"';
            $html .= ' placeholder="' . $value . '"';
            if (in_array($key, ['id', 'created_at'])) {
                $html .= ' readonly';
            }
            $html .= '>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        foreach ($meta_keys as $key) {
            $html .= '<tr>';
            $html .= '<td style="padding: 0rem !important;">' . strtoupper($key) . '</td>';
            $html .= '<td style="padding: 0rem !important;">';
            $html .= '<input type="text" class="form-control"';
            $html .= ' name="' . $key . '"';
            $html .= ' value="' . ($vehicle_metas[$key] ?? '') . '"';
            $html .= ' placeholder="' . ($vehicle_metas[$key] ?? '') . '"';
            $html .= '>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= ' </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>';

        return response()->json(['html' => $html]);
    }

    public function getHtmlTwo_TD($string, string $html, $values, $extra_data = []): string
    {
        $html .= '<td>' . strtoupper($string) . '</td>';
        $html .= '<td>';

        $dropdowns = ['location'];

        if (in_array($string, $dropdowns)) {
            $html .= '<select class="form-control select2"';
            $html .= ' name="' . $string . '"';
            $html .= ' value="' . $values . '"';
            $html .= '>';

            foreach ($extra_data[$string] as $location) {
                $html .= '<option value="' . $location . '"';
                if ($location == $values) $html .= ' selected';
                $html .= '>';
                $html .= $location . '</option>';
            }

            $html .= '</select>';


        } else {
            $html .= '<input type="text" class="form-control"';
            $html .= ' name="' . $string . '"';
            $html .= ' value="' . $values . '"';
            $html .= ' placeholder="' . $values . '"';
            $html .= ' style="width:auto"';
            $html .= '>';
        }

        $html .= '</td>';
        return $html;
    }

    public function getMetaHtmlTwo_TD($key, string $html, $vehicle_metas, $extra_data = []): string
    {
        $html .= '<td>' . strtoupper($key) . '</td>';
        $html .= '<td>';

        $dropdowns = [ 'status', 'odometer', 'primary_damage','keys',  'drivability_rating'];

        if (in_array($key, $dropdowns)) {

            $html .= '<select class="form-control select2"';
            $html .= ' name="' . $key . '"';
            $html .= ' value="' . $vehicle_metas . '"';
            $html .= '>';

            if($key == 'status'){
                $html .= '<option value="-100">Select Status</option>';
            }
            elseif($key == 'odometer'){
                $html .= '<option value="-100">Select Mileage</option>';
            }
            elseif($key == 'primary_damage'){
                $html .= '<option value="-100">Select Damage</option>';
            }
            elseif($key == 'secondary_damage'){
                $html .= '<option value="-100">Select Damage</option>';
            }
            elseif($key == 'drivability_rating'){
                $html .= '<option value="-100">Select Engine</option>';
            }
            elseif($key == 'keys'){
                $html .= '<option value="-100">Select Key</option>';
            }
            foreach ($extra_data[$key] as $location) {
                $html .= '<option value="' . $location . '"';
                if ($location == $vehicle_metas) $html .= ' selected';
                $html .= '>';
                $html .= $location . '</option>';
            }

            $html .= '</select>';


        }
        else{
            $html .= '<input type="text" class="form-control"';
            $html .= ' name="' . $key . '"';
            $html .= ' value="' . ($vehicle_metas ?? '') . '"';
            $html .= ' placeholder="' . ($vehicle_metas ?? '') . '"';
            $html .= '>';
        }

        $html .= '</td>';
        return $html;
    }

    function isMobileDev(): bool
    {
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $user_ag = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/(Mobile|Android|Tablet|GoBrowser|[0-9]x[0-9]*|uZardWeb\/|Mini|Doris\/|Skyfire\/|iPhone|Fennec\/|Maemo|Iris\/|CLDC\-|Mobi\/)/uis', $user_ag)) {
                return true;
            };
        };
        return false;
    }

    function next_vehicle_id(){
       $vehicle = new Vehicle();
       $vehicle->vin = '';
       $vehicle->save();
       return $vehicle->id;
    }
}
