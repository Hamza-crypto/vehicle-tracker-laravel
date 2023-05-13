<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use Illuminate\Console\Command;

class ClearVehicle extends Command
{
    protected $signature = 'vehicle:clear';

    protected $description = 'It removes unnecessary vehicle data';

    public function handle()
    {

        $vehicles = Vehicle::whereNull('invoice_amount')->delete();

        echo "Deleted {$vehicles} vehicles";

        return Command::SUCCESS;
    }
}
