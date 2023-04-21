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
            $edit = '';
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

            $vehicle->invoice_amount = $vehicle->invoice_amount != null  ? "$" . $vehicle->invoice_amount : '';
            $vehicle->date_paid = sprintf("<span> %s</span>", $vehicle->date_paid);
            $vehicle->actions .= $edit . $delete;
            if($user_role == 'yard_manager') $vehicle->actions = $edit;

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

        $html = '';
        $vehicleArray = $vehicle->toArray();

        foreach ($vehicleArray as $key => $value) {
            if ($key == 'updated_at') {
                continue;
            }
            $html .= '<tr>';
            $html .= '<td>' . strtoupper($key) . '</td>';
            $html .= '<td>';
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
            $html .= '<td>' . strtoupper($key) . '</td>';
            $html .= '<td>';
            $html .= '<input type="text" class="form-control"';
            $html .= ' name="' . $key . '"';
            $html .= ' value="' . ($vehicle_metas[$key] ?? '') . '"';
            $html .= ' placeholder="' . ($vehicle_metas[$key] ?? '') . '"';
            $html .= '>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        return response()->json(['html' => $html]);
    }


}
