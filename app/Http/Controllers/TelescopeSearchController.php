<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TelescopeSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('telescope_entries')->where('type', 'request');

        if ($request->has('method') && !empty($request->method)) {
            $query->WhereRaw("JSON_EXTRACT(`content`, '$.method') = ?", [$request->method]);
        }

        if ($request->has('resposne_status') && !empty($request->resposne_status)) {
            $query->WhereRaw("JSON_EXTRACT(`content`, '$.response_status') = ?", [(int) $request->resposne_status]);
        } else {
            $query->WhereRaw("JSON_EXTRACT(`content`, '$.response_status') = ?", [200]);
        }

        if ($request->has('uri') && !empty($request->uri)) {
            $query->WhereRaw("JSON_EXTRACT(`content`, '$.uri') = ?", [$request->uri]);
        }

        $results = $query->paginate(5);

        return view('telescope.search', compact('results'));
    }
}
