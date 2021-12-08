<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class DatatableController extends Controller
{

    public function orders(Request $request)
    {
        $request_user_id = $request->user;

        if ($request_user_id != -100 && $request_user_id != null) {
            $request_user_rate = User::find($request_user_id)->rate();
        } else {
            $request_user_rate = 89;
        }

        $user = Auth()->user();
        $role = Auth()->user()->role;

        if ($role != 'admin') {
            $request_user_rate = $user->rate();
        }

        $totalData = Vehicle::filters($request->all())->count();

        // Widget for Orders index page
        $totalOrderCount = ['accepted' => 0, 'pending' => 0, 'declined' => 0];

        $totalFiltered = $totalData;

        $start = $request->length == -1 ? 0 : $request->start;
        $limit = $request->length == -1 ? $totalData : $request->length;



        if (empty($request->input('search.value'))) {
            $vehicles = Vehicle::filters($request->all());

            //$totalOrders = $vehicles->get(); //if any filter is selected , then count according to that filter

            $vehicles = $vehicles->offset($start)->limit($limit)->get();

        } else {
            $search = $request->input('search.value');
            $vehicles = Vehicle::filters($request->all());
            $vehicles = $vehicles->where(function ($q1) use ($search) {
                $q1->where('id', 'LIKE', "%$search%")
                    ->orWhere('vin', 'LIKE', "%$search%")
                    ->orWhere('lot', 'LIKE', "%$search%");
            })
            ->get();

            $totalOrders = $vehicles; //if any filter is selected , then count according to that filter


            $totalFiltered = count($vehicles);
            $vehicles = $vehicles->skip($start)->take($limit);
        }

        //dd($vehicles->toArray());
//        foreach ($totalOrders as $vehicle) {
//            $totalOrderCount[$vehicle->status] = ($totalOrderCount[$vehicle->status] ?? 0) + 1;
//        }

        $data = [];

        foreach ($vehicles as &$vehicle) {
            $vehicle->DT_RowId = $vehicle->id;
            $vehicle->null = "";

            //$vehicle->invoice_date = $vehicle->invoice_date;

            $vehicle->created_at_new = $vehicle->created_at->diffForHumans();

            $edit = '<a href="' . route('vehicles.edit', $vehicle->id) . '" class="btn" style="display: inline"><i class="fa fa-edit text-info"></i></a>';
            $alertTitle = __("Are you sure you want to delete vehicle with VIN ") . ' ' . $vehicle->vin;
            $delete = '
                    <form method="post" action="' . route('vehicles.destroy', $vehicle->id) . '" style="display: inline"
                        onsubmit="return confirmSubmission(this, \'' . $alertTitle . '\')">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn text-danger" href="' . route('orders.destroy', $vehicle->id) . '"><i class="fa fa-trash"></i></button>
                    </form>
                    ';

           $vehicle->actions .= $edit;
           $vehicle->actions .= $delete;

           $data[] = $vehicle;
        }


        $extraInfo = [
            'total_orders_count' => $totalFiltered,
            'orders_status_accepted' => $totalOrderCount['accepted'],
            'orders_status_pending' => $totalOrderCount['pending'],
            'orders_status_declined' => $totalOrderCount['declined'],
            'user_rate' => $request_user_rate
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


}
