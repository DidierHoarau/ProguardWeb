<?php
    // Load Config
    $config_string = file_get_contents("../config/config.json");
    $config_json = json_decode($config_string, true);

    // List dir
    $res_array = array();
    $dh  = opendir($config_json['mapping_path']);
    while (false !== ($filename = readdir($dh))) {
        if ($filename!="." and $filename!="..") {
            array_push($res_array, $filename);            
        }
    }

    // Build JSON and return
    $res_json = json_encode($res_array);
    echo $res_json;
?>