<?php

namespace App\Http\Controllers;

use App\Models\CSVHeader;
use App\Models\VehicleMetas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CSVHeaderController extends Controller
{
    function index(Request $request)
    {
        $headers = [];
        return view('pages.headers.index', get_defined_vars());
    }

    function store(Request $request)
    {
        $data = $request->all();
        $filetype = $data['filetype'];
        unset($data['filetype']);
        unset($data['_token']);

        foreach ($data as $key => $value) {
            CSVHeader::updateOrCreate(
                ['filename' => $filetype, 'database_field' => $key],
                [
                    'csv_header' => $value
                ]);
        }
        Session::flash('success', 'Successfully updated');
        return view('pages.headers.index');
    }


    function destroy(Request $request)
    {
        $header = CSVHeader::where('id', $request->id)->delete();
        return response()->json(['success' => true, 'message' => 'Header deleted successfully.']);
    }

    function showFieldMapping(Request $request)
    {
        $filetype = $request->filetype;
        $db_fields = $this->get_csv_headers($filetype);

        $path = $request->file('csv_file')->getRealPath();
        $csvFile = array_map('str_getcsv', file($path));

        $headers = $csvFile[0];

        return view('pages.headers.index', get_defined_vars());
    }

    public function get_csv_headers($filename)
    {
        return CSVHeader::select('database_field', 'csv_header')->where('filename', $filename)->pluck('csv_header','database_field');
    }
}
