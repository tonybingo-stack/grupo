<?php

$user_id = Registry::load('current_user')->id;

if (!empty($user_id)) {
    $system_message = null;
    if(isset($data["start"])) {
        $system_message = [
            'message' => 'phone_call_started'
        ];
    } else {
        $system_message = [
            'message' => 'phone_call_finished'
        ]; 
    }

    $system_message = json_encode($system_message);

    // DB::connect()->insert("private_conversations", [
    //     "initiator_user_id" => $current_user_id,
    //     "recipient_user_id" => $data["user_id"],
    //     "created_on" => Registry::load('current_user')->time_stamp,
    //     "updated_on" => Registry::load('current_user')->time_stamp,
    // ]);

}
