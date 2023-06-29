<?php

function zego($data) {
    $zego = $data["zego"];

    if ($zego == "token") {
        $appId = $data["appID"];
        $userId = $data["userID"];
        $secret = $data["secret"];
        $payload = $data["payload"];
    
        include 'fns/zego/ZegoServerAssistant.php';
        include 'fns/zego/ZegoErrorCodes.php';
        include 'fns/zego/ZegoAssistantToken.php';

        $token = ZegoServerAssistant::generateToken04($appId,$userId,$secret,3600,$payload);
        if( $token->code == ZegoErrorCodes::success ){
          echo json_encode($token);
        }
    }
    else if($zego == "role") {
      $roomID = $data['roomID'];
      $userID = $data['userID'];

      $columns = ['group_members.group_role_id'];
      $where["AND"] = ["group_members.user_id" => $userID, "group_members.group_id" => $roomID];
      $where["LIMIT"] = 1;

      $user_group_role_id = DB::connect()->select('group_members', $columns, $where);
      if (count($user_group_role_id) > 0) $user_group_role_id =  $user_group_role_id[0]["group_role_id"];
      else $user_group_role_id = 0;
      
      $output["user_group_role_id"] = $user_group_role_id;
      echo json_encode($output);
    }
}