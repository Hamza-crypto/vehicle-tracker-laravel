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

        $keys = array_keys($vehicleArray);
        $values = array_values($vehicleArray);
        $count = count($keys);

        for ($i = 0; $i < $count; $i += 2) {
            $first_key = $i;
            $second_key = $i + 1 >= $count ? $i : $i + 1;
            $html .= '<tr>';

            $html .= '<div class="row">'; //row started

            $html .= '<div class="col-6">';
            $html = $this->getHtmlTwo_TD($keys[$first_key], $html, $values[$first_key]);
            $html .= '</div>'; //col-6 close

            $html .= '<div class="col-6">';
            $html = $this->getHtmlTwo_TD($keys[$second_key], $html, $values[$second_key]);
            $html .= '</div>'; //col-6 close

            $html .= '</div>'; //row close

            $html .= '</tr>';
        }

        $first_subset = array_slice($meta_keys, 0, 6);
        $second_subset = array_slice($meta_keys, 6,);

        $count = count($first_subset);
        for ($i = 0; $i < 6; $i += 2) {
            $first_key = $i;
            $second_key = $i + 1 >= $count ? $i : $i + 1;

            $html .= '<tr>';
            $html .= '<div class="row r1">'; //row started

            $html .= '<div class="col-6 col1">';
            $html = $this->getMetaHtmlTwo_TD($first_subset[$first_key], $html, $vehicle_metas[$first_subset[$first_key]]);
            $html .= '</div>'; //col-6 close

            $html .= '<div class="col-6 col2">';
            $html = $this->getMetaHtmlTwo_TD($first_subset[$second_key], $html, $vehicle_metas[$first_subset[$second_key]]);
            $html .= '</div>'; //col-6 close

            $html .= '</div>'; //row close

            $html .= '</tr>';
        }

        $count = count($second_subset);
        for ($i = 0; $i < 5; $i += 2) {
            $first_key = $i;
            $second_key = $i + 1 >= $count ? $i : $i + 1;

            $html .= '<tr>';
            $html .= '<div class="row">'; //row started

            $html .= '<div class="col-6">';
            $html = $this->getMetaHtmlTwo_TD($second_subset[$first_key], $html, $vehicle_metas[$second_subset[$first_key]] ?? '');
            $html .= '</div>'; //col-6 close

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

    public function getHtmlTwo_TD($string, string $html, $values): string
    {
        $html .= '<td>' . strtoupper($string) . '</td>';
        $html .= '<td>';
        $html .= '<input type="text" class="form-control"';
        $html .= ' name="' . $string . '"';
        $html .= ' value="' . $values . '"';
        $html .= ' placeholder="' . $values . '"';
        $html .= ' style="width:auto"';
        $html .= '>';
        $html .= '</td>';
        return $html;
    }

    public function getMetaHtmlTwo_TD($key, string $html, $vehicle_metas): string
    {
        $html .= '<td>' . strtoupper($key) . '</td>';
        $html .= '<td>';
        $html .= '<input type="text" class="form-control"';
        $html .= ' name="' . $key . '"';
        $html .= ' value="' . ($vehicle_metas ?? '') . '"';
        $html .= ' placeholder="' . ($vehicle_metas ?? '') . '"';
        $html .= '>';
        $html .= '</td>';
        return $html;
    }

    function isMobileDev()
    {
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $user_ag = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/(Mobile|Android|Tablet|GoBrowser|[0-9]x[0-9]*|uZardWeb\/|Mini|Doris\/|Skyfire\/|iPhone|Fennec\/|Maemo|Iris\/|CLDC\-|Mobi\/)/uis', $user_ag)) {
                return true;
            };
        };
        return false;
    }


}
