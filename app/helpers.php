<?php

function order_activity($title , $order) {
    $order->activities()->create([
        "order_id" => $order->id,
        "title" => $title,

    ]);
}
