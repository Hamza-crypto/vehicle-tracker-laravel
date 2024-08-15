<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleMetas;
use App\Models\VehicleNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class VinOcrController extends Controller
{
    public function showForm()
    {
        return view('pages.vinocr.form');
    }

    public function processImage(Request $request)
    {
        $baseURL = env('VINOCR_BASE_URL');
        $accessCode = env('VINOCR_TOKEN');
        $vinDecode = "TRUE";
        $format = 'JSON';
        $saveImage = "TRUE";

        $url = url("$baseURL?accesscode=$accessCode&saveimage=$saveImage&vindecode=$vinDecode&format=$format");
        $imgInput = $request->file('file');

        $response = Http::timeout(150)
        ->attach(
            'Image File',
            file_get_contents($imgInput?->path()),
            $imgInput->getClientOriginalName()
        )
        ->post($url);

        if($response['status'] == 'FAILED') {
            Session::flash('error', $response['message']);
            return redirect()->back();
        }


        // $jsonResponse = $response->json();
        // $response = [
        //   "service" => "vinocr",
        //   "version" => "2.0",
        //   "date" => "1/16/2024 6:40:36 PM",
        //   "status" => "SUCCESS",
        //   "vin_captured" => "1FTFW1E55MFA068662a",
        //   "vindecode" => [
        //         "status" => "SUCCESS",
        //         "make" => "Abc",
        //         "model" => "Focus",
        //         "year" => 2016,
        //   ],
        //   "left" => 53,
        //   "top" => 202,
        //   "width" => 247,
        //   "height" => 33,
        // ];

        app('log')->channel('vinocr')->info($response);

        if(! isset($response['vin_captured'])) {
            return;
        }
        $vin = $response['vin_captured'];
        $vin = preg_replace('/\s+/', '', trim($vin));

        $vehicle = Vehicle::where('vin', $vin)->first();
        if($vehicle) {

            VehicleMetas::updateOrCreate(
                ['vehicle_id' => $vehicle->id, 'meta_key' => 'status'],
                ['meta_value' => 'Intake']
            );
            $vehicle_metas = $vehicle->metas;
            $statuses = Vehicle::getStatuses();
            return view('pages.vinocr.detail1', compact('vehicle', 'vehicle_metas', 'statuses'));

        } else {
            $vehicle = new Vehicle();
            $vehicle->vin = $vin;

            if (isset($response['vindecode'])) {
                $description = $response['vindecode'];
                $des_string = '';

                if (isset($description['year'])) {
                    $des_string .= $description['year'];
                }

                // Check for the presence of "make" and "model" keys before appending them to the description
                if (isset($description['make'])) {
                    $des_string .= ' ' . $description['make'];
                }

                if (isset($description['model'])) {
                    $des_string .= ' ' . $description['model'];
                }
                $vehicle->description = $des_string;
                // $vehicle->save();
                $vehicle->id = Vehicle::max('id') + 1;
            }

            $statuses = Vehicle::getStatuses();
            return view('pages.vinocr.detail2', compact('vehicle', 'statuses'));
        }

    }

    public function update_detail_1(Request $request, Vehicle $vehicle)
    {
        if ($request->location != -1) {
            $vehicle->location = $request->location;
            $vehicle->save();
        }

        $vehicle->metas()->updateOrCreate(['meta_key' => 'keys'], ['meta_value' => $request->keys]);
        $vehicle->metas()->updateOrCreate(['meta_key' => 'status'], ['meta_value' => $request->status]);


        if($request->note != null) {

            VehicleNote::updateOrCreate(
                ['vehicle_id' => $vehicle->id, 'user_id' => Auth::id()],
                [
                'note' => $request->note,
            ]
            );
        }

        Session::flash('success', 'Successfully Updated');
        return redirect()->route('vinocr.showform');
    }

    public function update_detail_2(Request $request)
    {
        $vehicle = new Vehicle();
        $vehicle->vin = $request->vin;
        $vehicle->description = $request->description;

        $vehicle->save();

        // Check if the location is not equal to -1 before updating
        if ($request->location != -1) {
            $updateFields['location'] = $request->location;
            $vehicle->fill($updateFields)->save();
        }

        $metaData = [
        'keys' => $request->keys,
        'status' => $request->status,
        'odometer' => $request->mileage,
    ];

        foreach ($metaData as $metaKey => $metaValue) {
            $vehicle->metas()->updateOrCreate(
                ['vehicle_id' => $vehicle->id, 'meta_key' => $metaKey],
                ['meta_value' => $metaValue]
            );
        }

        if($request->note != null) {

            VehicleNote::updateOrCreate(
                ['vehicle_id' => $vehicle->id, 'user_id' => Auth::id()],
                [
                'note' => $request->note,
            ]
            );
        }

        Session::flash('success', 'Successfully Added');
        return redirect()->route('vinocr.showform');

    }
}
