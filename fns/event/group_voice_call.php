<?php

$user_id = Registry::load('current_user')->id;
$group_id = 0;

if (isset($data['group_id'])) {
    $group_id = filter_var($data["group_id"], FILTER_SANITIZE_NUMBER_INT);
}

if (!empty($group_id) && !empty($user_id)) {
    if (isset($data["job"])) {
        if ($data["job"] === "start") {
            setCachedData("group_meeting_" . $group_id, 1);

            $system_message = [
                'message' => 'group_meeting_started'
            ];
            $system_message = json_encode($system_message);
            DB::connect()->insert("group_messages", [
                "system_message" => 1,
                "original_message" => 'system_message',
                "filtered_message" => $system_message,
                "group_id" => $group_id,
                "user_id" => Registry::load('current_user')->id,
                "created_on" => Registry::load('current_user')->time_stamp,
                "updated_on" => Registry::load('current_user')->time_stamp,
            ]);
        } else if ($data["job"] === "end") {
            setCachedData("group_meeting_" . $group_id, 0);
        } else if ($data["job"] === "verify") {
            $output["result"] = getCachedData("group_meeting_" . $group_id);
            if ($output["result"] == null) $output["result"] = 0;

            // check user_group_role
            $columns = ['group_members.group_role_id'];
            $where["AND"] = ["group_members.user_id" => $user_id, "group_members.group_id" => $group_id];
            $where["LIMIT"] = 1;
    
            $user_group_role_id = DB::connect()->select('group_members', $columns, $where);
            if (count($user_group_role_id) > 0) $user_group_role_id =  $user_group_role_id[0]["group_role_id"];
            else $user_group_role_id = 0;
            
            $output["user_group_role_id"] = $user_group_role_id;
        }
    }
}
