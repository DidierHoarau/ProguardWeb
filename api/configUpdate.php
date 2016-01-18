<?php

    // Get Input JSON
    $data = json_decode(file_get_contents('php://input'), true);

    // Validation
    // TODO

    // Dump in config file
    file_put_contents('../config/config.json', json_encode($data), LOCK_EX);

    // Return if success
    // TODO
    $res_array = array ( "return_code" => 0 );
    $res_json = json_encode($data);
    echo $res_json;
?>