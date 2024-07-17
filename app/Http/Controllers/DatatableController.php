<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleMetas;
use App\Models\VehicleNote;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DatatableController extends Controller
{
    public function vehicles(Request $request)
    {
        $user_role = Auth::user()->role;

        $vehiclesQuery = Vehicle::with('metas')->filters($request->all());

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $vehiclesQuery->where(function ($q) use ($search) {
                $q->where('vin', 'LIKE', "%$search%")
                    ->orWhere('purchase_lot', 'LIKE', "%$search%")
                    ->orWhere('auction_lot', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%");
            });
        }

        $totalData = $vehiclesQuery->count();
        $totalFiltered = $totalData;

        $start = $request->length == -1 ? 0 : $request->start;
        $limit = $request->length == -1 ? $totalData : $request->length;

        if($user_role == 'admin'){
            $dbColumns = [
            0 => 'id',
            3 => 'left_location',
            5 => 'date_paid',
            6 => 'purchase_lot',
            7 => 'auction_lot',
            8 => 'days_in_yard',
        ];
        }
        else{
            $dbColumns = [
            0 => 'id',
            2 => 'left_location',
            4 => 'date_paid',
            5 => 'purchase_lot',
            6 => 'auction_lot',
            7 => 'days_in_yard',
        ];
        }


        $orderColumnIndex = $request->input('order.0.column');
        $orderDbColumn = $dbColumns[$orderColumnIndex];
        $orderDirection = $request->input('order.0.dir');

        $vehicles = $vehiclesQuery->orderBy($orderDbColumn, $orderDirection)
            ->offset($start)
            ->limit($limit)
            ->get();

        $data = [];


        foreach ($vehicles as $vehicle) {
            $vehicle->null = '';
            $vehicle->DT_RowId = $vehicle->id;
            $vehicle->created_at_new = $vehicle->created_at->diffForHumans();
            $edit = '<a href="' . route('vehicles.edit', $vehicle->id) . '" class="btn" style="display: inline" target="_blank"><i class="fa fa-edit text-info"></i></a>';
            $edit = '';
            $alertTitle = __('Are you sure you want to delete vehicle with VIN ') . ' ' . $vehicle->vin;
            $delete = '
                <form method="post" action="' . route('vehicles.destroy', $vehicle->id) . '" style="display: inline"
                    onsubmit="return confirmSubmission(this, \'' . $alertTitle . '\')">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn text-danger"><i class="fa fa-trash"></i></button>
                </form>
                ';

            $vehicle->description = sprintf("<a data-toggle='modal' data-target='#modal-vehicle-detail' data-id='%s' style='color: #3f80ea;'>%s</a>", $vehicle->id, $vehicle->description);

            if ($vehicle->source == 'iaai') {
                $vehicle->purchase_lot = sprintf("<a href='https://www.iaai.com/PurchaseHistory' target='_blank' style='color: red'>%s</a>", $vehicle->purchase_lot);
            } else {
                $vehicle->purchase_lot = sprintf("<a href='https://www.copart.com/lot/%s' target='_blank'>%s</a>", $vehicle->purchase_lot, $vehicle->purchase_lot);
            }
            //Auction lot will always redirect to Copart
            $vehicle->auction_lot = sprintf("<a href='https://www.copart.com/lot/%s' target='_blank'>%s</a>", $vehicle->auction_lot, $vehicle->auction_lot);

            $vehicle->invoice_amount = $vehicle->invoice_amount != null ? '$' . $vehicle->invoice_amount : '';
            $vehicle->date_paid = sprintf('<span> %s</span>', $vehicle->date_paid);
            $vehicle->actions .= $edit . $delete;
            // if ($user_role != 'admin') {
                // $vehicle->actions = "";
            // }

            $vehicle_metas = collect($vehicle->metas);
            $vehicle->claim_number = $vehicle_metas->where('meta_key', 'claim_number')->pluck('meta_value')->first();
            $vehicle->status = $vehicle_metas->where('meta_key', 'status')->pluck('meta_value')->first();

            $data[] = $vehicle;
        }

        $data = [
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ];

        return response()->json($data);
    }


    public function getVehicleIdsSortedByMeta($metaKey, $sortOrder = 'asc', $vehicles, $type = 'date', $start = 0, $limit = 10)
    {
        if($type == 'date'){
            $sort_type = "STR_TO_DATE(vehicle_metas.meta_value, '%Y-%m-%d') $sortOrder";
        }
        elseif($type == 'price'){
            $sort_type = "CAST(vehicle_metas.meta_value AS UNSIGNED) $sortOrder";
        }

        // Fetch vehicle IDs sorted by the given meta key
        $sorted_ids = \DB::table('vehicles')
            ->join('vehicle_metas', function ($join) use ($metaKey) {
                $join->on('vehicles.id', '=', 'vehicle_metas.vehicle_id')
                    ->where('vehicle_metas.meta_key', '=', $metaKey);
            })
            ->orderByRaw($sort_type)
            ->offset($start)
            ->limit($limit)
            ->pluck('vehicles.id')
            ->toArray();

        $rawOrder = 'FIELD(id, ' . implode(',', $sorted_ids) . ')';
        $vehicles->whereIn('id', $sorted_ids)->orderByRaw($rawOrder);

        return $vehicles;
    }

    public function vehicles_sold(Request $request)
    {
        $totalData = Vehicle::sold($request->all())->count();

        // Widget for Orders index page
        $totalOrderCount = ['accepted' => 0, 'pending' => 0, 'declined' => 0];

        $totalFiltered = $totalData;

        $start = $request->length == -1 ? 0 : $request->start;
        $limit = $request->length == -1 ? $totalData : $request->length;

        //Put the columns in an array for which you want to apply the sorting,
        // index should be in accordance with the column index in the table
        $dbColumns = [
            0 => 'id',
            3 => 'auction_lot',
            4 => 'sale_date',
            5 => 'invoice_amount',
            6 => 'sale_price',
            7 => 'days_in_yard'
        ];

        $orderColumnIndex = $request->input('order.0.column');

        $orderDbColumn = $dbColumns[$orderColumnIndex];
        $orderDirection = $request->input('order.0.dir');


        if (empty($request->input('search.value'))) {

            $vehicles = Vehicle::with('metas')->sold($request->all());

            if($orderDbColumn == 'sale_date'){
                $vehicles = $this->getVehicleIdsSortedByMeta($orderDbColumn, $orderDirection, $vehicles, 'date', $start, $limit);
            }
            elseif($orderDbColumn == 'sale_price'){
                 $vehicles = $this->getVehicleIdsSortedByMeta($orderDbColumn, $orderDirection, $vehicles, 'price', $start, $limit);
            }
            else{
                $vehicles = $vehicles->orderBy($orderDbColumn, $orderDirection);
            }

            $vehicles = $vehicles->offset($start)->limit($limit)->get();


        } else {
            $search = $request->input('search.value');
            $vehicles = Vehicle::with('metas')->sold($request->all());

            if($orderDbColumn == 'sale_date'){
                $vehicles = $this->getVehicleIdsSortedByMeta($orderDbColumn, $orderDirection, $vehicles, 'date');
            }
            elseif($orderDbColumn == 'sale_price'){
                 $vehicles = $this->getVehicleIdsSortedByMeta($orderDbColumn, $orderDirection, $vehicles, 'price');
            }

            $vehicles = $vehicles->where(function ($q1) use ($search) {
                $q1->where('vin', 'LIKE', "%$search%")
                    ->orWhere('purchase_lot', 'LIKE', "%$search%")
                    ->orWhere('auction_lot', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%"); // ILIKE only used for Postgress

            })->get();

            $totalFiltered = count($vehicles);
            $vehicles = $vehicles->skip($start)->take($limit);
        }


        $data = [];
        $user_role = Auth::user()->role;
        foreach ($vehicles as &$vehicle) {

            $vehicle->null = '';
            $vehicle->DT_RowId = $vehicle->id;
            $vehicle->created_at_new = "";
            $edit = '<a href="' . route('vehicles.edit', $vehicle->id) . '" class="btn" style="display: inline" target="_blank"><i class="fa fa-edit text-info"></i></a>';
            $edit = '';
            $alertTitle = __('Are you sure you want to delete vehicle with VIN ') . ' ' . $vehicle->vin;

            $delete = '
                    <form method="post" action="' . route('vehicles.destroy', $vehicle->id) . '" style="display: inline"
                        onsubmit="return confirmSubmission(this, \'' . $alertTitle . '\')">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn text-danger"><i class="fa fa-trash"></i></button>
                    </form>
                    ';

            $vehicle->description = sprintf("<a data-toggle='modal' data-target='#modal-vehicle-detail' data-id='%s'>%s</a>", $vehicle->id, $vehicle->description);

            $vehicle->auction_lot = sprintf("<a href='https://www.copart.com/lot/%s' target='_blank'>%s</a>", $vehicle->auction_lot, $vehicle->auction_lot);

            $vehicle->invoice_amount = $vehicle->invoice_amount != null ? '$' . $vehicle->invoice_amount : '';

            $vehicle->actions .= $edit . $delete;
            if ($user_role == 'yard_manager') {
                $vehicle->actions = $edit;
            }

            //Get meta data
            $vehicle_metas = collect($vehicle->metas);
            $vehicle->sale_date = $vehicle_metas->where('meta_key', 'sale_date')->pluck('meta_value')->first();
            $vehicle->sale_price = $vehicle_metas->where('meta_key', 'sale_price')->pluck('meta_value')->first();
            $vehicle->days_in_yard = $vehicle_metas->where('meta_key', 'days_in_yard')->pluck('meta_value')->first();

            // Check if a sale date was found and format it
            // if ( $vehicle->sale_date) {
            //      $vehicle->sale_date = Carbon::parse( $vehicle->sale_date)->format('m/d/Y');
            // }
            $data[] = $vehicle;
        }

        $extraInfo = [
            'total_orders_count' => $totalFiltered,
            'orders_status_accepted' => $totalOrderCount['accepted'],
            'orders_status_pending' => $totalOrderCount['pending'],
            'orders_status_declined' => $totalOrderCount['declined'],
            'user_rate' => '',
        ];

        $data = [
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
            'extra_info' => $extraInfo,
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
            'sale_title_type',
            'sale_price',
        ];

        //if any of the meta keys is missing, add it to the array with empty value
        foreach ($meta_keys as $meta_key) {
            if (! $vehicle_metas->has($meta_key)) {
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
        unset($vehicleArray['days_in_yard']); // because we are already getting days_in_yard from meta table

        $array_keys = array_keys($vehicleArray);
        $values = array_values($vehicleArray);
        $count = count($array_keys);

        $locations = Vehicle::select('location')->distinct()->orderBy('location', 'asc')->get()->pluck('location');
        // $statuses = VehicleMetas::select('meta_value')
        //     ->where('meta_key', 'status')
        //    // ->where('meta_value', '!=', 'Sold') //excluding sold status
        //     ->groupBy('meta_value')
        //     ->orderBy('meta_value')
        //     ->get()
        //     ->pluck('meta_value');

        $statuses = Vehicle::STATUSES;

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
        $second_subset = array_slice($meta_keys, 6);

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

            // if($i == 4) break; //it will remove 2nd column of last row e.g. duplicated column

            $html .= '<div class="col-6">';
            $html = $this->getMetaHtmlTwo_TD($second_subset[$second_key], $html, $vehicle_metas[$second_subset[$second_key]] ?? '');
            $html .= '</div>'; //col-6 close

            $html .= '</div>'; //row close

            $html .= '</tr>';
        }

        $html = $this->getVehicleNotes($html, $vehicle);

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
            'sale_title_type',
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
                $value = now()->format('Y-m-d'); //if date is empty, then datapicker gives NAN for all values in datepicker widget

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
        $date_ranges = ['left_location', 'date_paid'];

        if (in_array($string, $dropdowns)) {
            $html .= '<select class="form-control select2"';
            $html .= ' name="' . $string . '"';
            $html .= ' value="' . $values . '"';
            $html .= '>';

            foreach ($extra_data[$string] as $location) {
                $html .= '<option value="' . $location . '"';
                if ($location == $values) {
                    $html .= ' selected';
                }
                $html .= '>';
                $html .= $location . '</option>';
            }

            $html .= '</select>';

        } else {
            $html .= '<input type="text" class="form-control';
            if (in_array($string, $date_ranges)) {
                $html .= ' daterange';
                //if date is empty, then datapicker gives NAN for all values in datepicker widget
                if(is_null($values)) {
                    $values = now()->format('Y-m-d');
                }

            }
            $html .= '"';

            if ($string == 'created_at') {
                $html .= ' disabled';
            }

            $html .= ' name="' . $string . '"';
            $html .= ' value="' . $values . '"';
            if ($string == 'source') {
                $html .= ' placeholder="copart | iaai"';
            } else {
                $html .= ' placeholder="' . $values . '"';
            }

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

        $dropdowns = ['status', 'primary_damage', 'keys',  'drivability_rating'];

        if (in_array($key, $dropdowns)) {

            $html .= '<select class="form-control select2"';
            $html .= ' name="' . $key . '"';
            $html .= ' value="' . $vehicle_metas . '"';
            $html .= '>';

            if ($key == 'status') {
                $html .= '<option value="-100">Select Status</option>';
            } elseif ($key == 'primary_damage') {
                $html .= '<option value="-100">Select Damage</option>';
            } elseif ($key == 'secondary_damage') {
                $html .= '<option value="-100">Select Damage</option>';
            } elseif ($key == 'drivability_rating') {
                $html .= '<option value="-100">Select Engine</option>';
            } elseif ($key == 'keys') {
                $html .= '<option value="-100">Select Key</option>';
            }
            foreach ($extra_data[$key] as $location) {
                $html .= '<option value="' . $location . '"';
                if ($location == $vehicle_metas) {
                    $html .= ' selected';
                }
                $html .= '>';
                $html .= $location . '</option>';
            }

            $html .= '</select>';

        } else {

            /**
             * Making Mileage field of type number instead of text
             */
            if ($key == "odometer") {
                $html .= '<input type="number" class="form-control"';
            } else {
                $html .= '<input type="text" class="form-control"';
            }

            $html .= ' name="' . $key . '"';
            $html .= ' value="' . ($vehicle_metas ?? '') . '"';
            $html .= ' placeholder="' . ($vehicle_metas ?? '') . '"';
            $html .= '>';
        }

        $html .= '</td>';

        return $html;
    }

    public function isMobileDev(): bool
    {
        if (! empty($_SERVER['HTTP_USER_AGENT'])) {
            $user_ag = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/(Mobile|Android|Tablet|GoBrowser|[0-9]x[0-9]*|uZardWeb\/|Mini|Doris\/|Skyfire\/|iPhone|Fennec\/|Maemo|Iris\/|CLDC\-|Mobi\/)/uis', $user_ag)) {
                return true;
            }
        }

        return false;
    }

    public function next_vehicle_id()
    {
        $vehicle = new Vehicle();
        $vehicle->vin = '';
        $vehicle->save();

        return $vehicle->id;
    }

    public function getVehicleNotes($html, $vehicle)
    {
        //Notes Heading
        $html .= '<tr>';
        $html .= '<div class="row">'; //row started
        $html .= '<div class="col-6">';

        $html .= '<td>';
        $html .= '<strong>Notes</strong>';
        $html .= '</td>';

        $html .= '</div>';
        $html .= '</div >'; //row ended
        $html .= '</tr>';
        //Notes Heading End

        $notes = VehicleNote::where('vehicle_id', $vehicle->id)->get();

        $show_blank_note = true;
        foreach ($notes as $note) {
            $html = $this->singleNoteRow($html, $note);
            if(Auth::id() == $note->user_id) {
                $show_blank_note = false;
            }
        }

        //if user role is not 'viewer', then show blank note row
        if($show_blank_note) {
            if(Auth::user()->role != 'viewer') {
                $html = $this->blankSingleNoteRow($html);
            }
        }




        return $html;

    }

    public function singleNoteRow($html, $note)
    {
        $html .= '<tr>';
        $html .= '<div class="row">'; //row started
        $html .= '<div class="col-6">';

        $html .= '<td>';
        $html .= sprintf("Added by: <strong>%s</strong> <br> %s", get_username($note->user_id ?? ''), $note->created_at ? $note->created_at->format("d/m/Y H:i:s") : '');
        $html .= '</td>';

        $html .= '<td colspan=3>';
        $html .= '<textarea class="form-control form-control-lg" name="notes[]"';

        //readonly
        if(Auth::id() != $note->user_id) {
            $html .= ' disabled';
        }

        $html .= '>';
        $html .= $note->note  ?? null;
        $html .= '</textarea>';
        $html .= '</td>';

        $html .= '</div>';
        $html .= '</div >'; //row ended
        $html .= '</tr>';

        return $html;
    }

    public function blankSingleNoteRow($html)
    {
        $html .= '<tr>';
        $html .= '<div class="row">'; //row started
        $html .= '<div class="col-6">';

        $html .= '<td>';
        $html .= 'Add New Note';
        $html .= '</td>';

        $html .= '<td colspan=3>';
        $html .= '<textarea class="form-control form-control-lg" name="notes[]">';
        $html .= '</textarea>';
        $html .= '</td>';

        $html .= '</div>';
        $html .= '</div >'; //row ended
        $html .= '</tr>';

        return $html;
    }
}