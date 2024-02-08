<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearVehicle extends Command
{
    protected $signature = 'add-runlist-header';
    protected $description = 'It add csv headers for runlist';

    public function handle()
    {

       //Run List Headers
        $requiredColumns = [
            'item_number' => 'Item #',
            'lot_number' => 'Lot #',
            'claim_number' => 'Claim #',
            'description' =>'Description',
            'number_of_runs' =>'# of Runs'
        ];


        foreach ($requiredColumns as $key => $value) {
            $bulk_insert_data[] =
                [
                    'filename' => 'run_list',
                    'database_field' => $key,
                    'csv_header' => $value
                ];
        }

        foreach (array_chunk($bulk_insert_data, 1000) as $chunk) {
            DB::table('csv_headers')->insert($chunk);
        }

        echo "Headers added successfully.";

        return Command::SUCCESS;
    }
}