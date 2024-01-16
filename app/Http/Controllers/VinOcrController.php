<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleMetas;
use Illuminate\Http\Request;

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

        // $response = Http::attach(
        //     'Image File',
        //     file_get_contents($imgInput?->path()),
        //     $imgInput->getClientOriginalName()
        // )->post($url);

        // if($response['status'] == 'FAILED'){
        //     Session::flash('error', $response['message']);
        //     return redirect()->back();
        // }


        // $jsonResponse = $response->json();


        $response = [
          "service" => "vinocr",
          "version" => "2.0",
          "date" => "1/16/2024 6:40:36 PM",
          "status" => "SUCCESS",
          "vin_captured" => "1FADP3L93GL227299",
          "vindecode" => [
                "status" => "SUCCESS",
                "make" => "Abc",
                "model" => "Focus",
                "year" => 2016,
          ],
          "left" => 53,
          "top" => 202,
          "width" => 247,
          "height" => 33,
        ];

        app('log')->channel('vinocr')->info($response);

        if(! isset($response['vin_captured'])) {
            return;
        }
        $vin = $response['vin_captured'];

        $vehicle = Vehicle::where('vin', $vin)->first();
        if($vehicle) {
            return view('pages.vinocr.detail1', compact('vehicle'));

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
            }

            $vehicle->save();
            VehicleMetas::updateOrCreate(
                ['vehicle_id' => $vehicle->id, 'meta_key' => 'status'],
                ['meta_value' => 'Intake']
            );
        }

    }
}
