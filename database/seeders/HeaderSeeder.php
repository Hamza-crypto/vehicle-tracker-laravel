<?php

namespace Database\Seeders;

use Database\Factories\CardFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeaderSeeder extends Seeder
{
    public function run()
    {
        //copart Buy
        $requiredColumns = [
            'vin' => 'VIN',
            'purchase_lot' => 'Lot/Inv #',
            'location' => 'Location',
            'description' => 'Description',
            'left_location' => 'Left Location',
            'date_paid' => 'Date Paid',
            'invoice_amount' => 'Invoice Amount',
        ];

        foreach ($requiredColumns as $key => $value) {
            $bulk_insert_data[] =
                [
                    'filename' => 'copart_buy',
                    'database_field' => $key,
                    'csv_header' => $value
                ];
        }

        //IaaI Buy v1
        $requiredColumns = [
            'vin' => 'VIN',
            'purchase_lot' => 'Stock',
            'location' => 'Branch',
            'year' => 'Year',
            'make' => 'Make',
            'model' => 'Model',
            'left_location' => 'Date Picked Up',
            'date_paid' => 'Date Paid',
            'invoice_amount' => 'Total Amount'
        ];

        foreach ($requiredColumns as $key => $value) {
            $bulk_insert_data[] =
                [
                    'filename' => 'iaai_buy',
                    'database_field' => $key,
                    'csv_header' => $value
                ];
        }

        //Copart Inventory: Step 2
        $requiredColumns = [
            'vin' => 'VIN',
            'location' => 'Physical Location',
            'auction_lot' => 'Lot #',
            'claim_number' => 'Claim #',
            'description' => 'Description',

            'status' => 'Status',
            'primary_damage' => 'Primary Damage',
            'secondary_damage' => 'Secondary Damage',
            'keys' => 'Keys',
            'drivability_rating' => 'Drivability Rating',
            'odometer' => 'Odometer',
            'odometer_brand' => 'Odometer Brand',
            'sale_title_type' => 'Sale Title Type',
            'sale_title_state' => 'Sale Title State',
            'days_in_yard' => 'Days in Yard',
        ];

        foreach ($requiredColumns as $key => $value) {
            $bulk_insert_data[] =
                [
                    'filename' => 'copart_inventory',
                    'database_field' => $key,
                    'csv_header' => $value
                ];
        }

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


                $requiredColumns = [
            'lot' => 'Lot #',
            'sale_date' =>'Sale Date',
            'sale_price' =>'Sale Price',
            'status' =>'Status'
        ];


        foreach ($requiredColumns as $key => $value) {
            $bulk_insert_data[] =
                [
                    'filename' => 'copart_sale',
                    'database_field' => $key,
                    'csv_header' => $value
                ];
        }



        foreach (array_chunk($bulk_insert_data, 1000) as $chunk) {
            DB::table('csv_headers')->insert($chunk);
        }
    }
}