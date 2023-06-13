<?php

namespace Database\Seeders;

use Database\Factories\CardFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeaderSeeder extends Seeder
{
    public function run()
    {
        $requiredColumns = [
            'VIN',
            'Lot/Inv #',
            'Location',
            'Description',
            'Left Location',
            'Date Paid',
            'Invoice Amount',
        ];

        foreach ($requiredColumns as $value) {
            $bulk_insert_data[] =
                [
                    'filename' => 'copart_buy',
                    'header' => $value
                ];
        }

        $requiredColumns = [
            'VIN',
            'Stock',
            'Stock#',
            'Branch',
            'Description',
            'Year',
            'Make',
            'Model',
            'Date Picked Up',
            'Date Paid',
            'Total Paid',
            'Total Amount',
            'Item#',
        ];

        foreach ($requiredColumns as $value) {
            $bulk_insert_data[] =
                [
                    'filename' => 'iaai_buy',
                    'header' => $value
                ];
        }


        $requiredColumns = [
            'Lot #',
            'Claim #',
            'Status',
            'Description',
            'VIN',
            'Primary Damage',
            'Secondary Damage',
            'Keys',
            'Drivability Rating',
            'Odometer',
            'Odometer Brand',
            'Sale Title State',
            'Sale Title Type',
            'Location',
            'Days in Yard',
        ];
        foreach ($requiredColumns as $value) {
            $bulk_insert_data[] =
                [
                    'filename' => 'copart_inventory',
                    'header' => $value
                ];
        }


        $requiredColumns = [
            'Lot #',
            'Claim #',
            'Status',
            'Location',
            'Sale Date',
            'Description',
            'Title State',
            'Title Type',
            'Odometer',
            'Odometer Brand',
            'Primary Damage',
            'Loss Type',
            'Keys',
            'Drivability Rating',
            'ACV',
            'Repair Cost',
            'Sale Price',
            'Return %',
        ];

        foreach ($requiredColumns as $value) {
            $bulk_insert_data[] =
                [
                    'filename' => 'copart_sale',
                    'header' => $value
                ];
        }

        foreach (array_chunk($bulk_insert_data, 1000) as $chunk) {
            DB::table('csv_headers')->insert($chunk);
        }





    }
}
