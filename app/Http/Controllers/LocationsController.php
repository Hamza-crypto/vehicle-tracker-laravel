<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocationsController extends Controller
{
    public function index()
    {

        $locations = Location::all();
        $locations2 = (new VehicleController())->get_locations();

        return view('pages.location.index', get_defined_vars());
    }

    public function create()
    {

        return view('pages.location.add');
    }

    public function store(Request $request)
    {

        Location::create(
            $request->all()
        );

        Session::flash('success', __('Successfully Added'));

        return redirect()->back();
    }

    public function destroy(Location $location)
    {
        $location->delete();
        Session::flash('success', __('Successfully Deleted'));

        return back();
    }

    public function add_new_location($location)
    {
        Location::create(
            ['location' => $location]
        );

    }
}
