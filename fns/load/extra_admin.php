<?php

if (Registry::load('current_user')->site_role_attribute == "administrators") {

    $url = "https://www.example.com"; // URL to redirect to

    echo '<script type="text/javascript">';
    echo 'window.open("' . $url . '", "_blank");'; // Open new tab with the specified URL
    echo 'window.focus();'; // Set focus on the new tab
    echo '</script>';

}

?>