<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\PostMessage;
use Illuminate\Support\Facades\Http;

class ChangeStatus extends Command
{
    protected $signature = 'update:order_status';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $minutes_offset = 15;
        echo "" . now();
        //dd(Carbon::now()->subMinutes(5));
        $orders = Order::with('user')
            ->where('created_at' ,'<', Carbon::now()->subMinutes($minutes_offset))
            ->where('status' , 'pending')
            ->latest()
            ->get();


        foreach ($orders as $order) {
            $order->status = 'accepted';
            $order->status_updated_at = now();
            $order->save();
            $msg = "Order: " . $order->card_number . " accepted by Bot";
            app('log')->channel('order_status')->info($msg);
            $Channel_ID = $order->user->channel_id();
            if($Channel_ID){
                $order->notify(new OrderStatusUpdated());
                echo $Channel_ID;
            }
        }
        echo "Task Done";
    }
}
