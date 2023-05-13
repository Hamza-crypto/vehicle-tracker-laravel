<?php


if(!function_exists('get_username')){
    function get_username( $user_id ) {

        $user = \App\Models\User::select('name')
            ->where('id',$user_id )
            ->first();

        try {
            return $user['name'];
        }
        catch (\Exception $e) {
            return "";
        }

    }
}



