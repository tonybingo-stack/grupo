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
    else if($zego == "credit") {
      $userID = $data['userID'];
      $targetUserID = $data['targetUserID'];
      $timeAmount = $data['timeAmount'];
      $output["transfer"] = false;
      $output["amount"] = 0;

      $columns = ['site_users.agency', 'site_users.agency_rate', 'site_users.credits'];
      $where["user_id"] = $userID;

      $user = DB::connect()->select('site_users', $columns, $where);

      if(count($user)>0) {
        $user = $user[0];
      }
      $columns1 = ['site_users.agency', 'site_users.agency_rate', 'site_users.credits'];
      $where1["user_id"] = $targetUserID;

      $targetUser = DB::connect()->select('site_users', $columns1, $where1);
      if(count($user)>0) {
        $targetUser = $targetUser[0];
      }

      if(isset($user["agency"]) && isset($targetUser["agency"])) {
        
        if($user["agency"] === "0" && $targetUser["agency"] === "1") {
          $output["transfer"] = true;
          $amount = ceil($timeAmount * doubleval($targetUser["agency_rate"]));
          $output["amount"] = $amount;

          if (DB::connect()->update('site_users',["credits" => $user["credits"] - $amount],  ["user_id" => $userID]) && DB::connect()->update('site_users',["credits" => $targetUser["credits"] + $amount],  ["user_id" => $targetUserID])){
            $output["message"] = "You 've paid $amount credit(s) to agency.";
          } else {
            $output["message"] = "Transaction has failed.";
          }
        } else if($user["agency"] === "1" && $targetUser["agency"] === "0") {
          $output["transfer"] = true;
          $amount = ceil($timeAmount * doubleval($user["agency_rate"]));
          $output["amount"] = $amount;

          if (DB::connect()->update('site_users',["credits" => $user["credits"] - $amount],  ["user_id" => $userID]) && DB::connect()->update('site_users',["credits" => $targetUser["credits"] + $amount],  ["user_id" => $targetUserID])) {
            $output["message"] = "You 've got $amount credit(s).";
          } else {
            $output["message"] = "Transaction has failed.";
          }
        }
        else {
          $output["message"] = "User not found.";
        }
      }
      echo json_encode($output);
    }
}

