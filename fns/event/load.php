<?php

function event($data) {

    $output = array();
    $output["job"] = $data["job"];

    if (isset($data["event"]) && !empty($data["event"])) {
        $loadfnfile = 'fns/event/'.$data["event"].'.php';
        if (file_exists($loadfnfile)) {
            include($loadfnfile);
        }
    }
    echo json_encode($output);
    return $output;
}

?>