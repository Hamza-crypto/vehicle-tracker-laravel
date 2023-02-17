<?php

namespace App\Http\Controllers;

use App\Models\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ElectionController extends Controller
{

    function index(Request $request)
    {
        return Election::filter($request)->paginate($request->per_page);
    }

    function update_election()
    {
        DB::table('elections')->truncate();

        $headers = [
            '0' => 'Election',
            '1' => 'Department',
            '2' => 'Year',
            '3' => 'Candidate Name',
            '4' => 'Party',
            '5' => 'First Round Total Votes',
            '6' => '1st Round Percentage of votes',
            '7' => '2nd Round Total Votes',
            '8' => '2nd Round Percentage of votes',
            '9' => '2nd Round Total Votes',
            '10' => '2nd Round Total Votes',
        ];

        $count = 0;
        $handle = fopen(public_path('election.csv'), "r");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($count == 0) {
                $count++;
                continue;
            }
            if ($data[4] == '') continue; //Candidate Name

            $election = new Election();
            $election->candidate = $data[4];
            $election->votes_first_round = str_replace(',', '', $data[6]);
            $election->percentage_first_round = $data[7];
            $election->votes_second_round = str_replace(',', '', $data[8]);;
            $election->percentage_second_round = $data[9];
            $election->party = $data[5];
            $election->department = $data[2];
            $election->year = $data[3];
            $election->election = $data[0];
            $election->save();
            $count++;

        }

    }
}
