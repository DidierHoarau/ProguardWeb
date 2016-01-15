<?php
    // Get Input JSON
    $data = json_decode(file_get_contents('php://input'), true);

    // Init Variables
    $TMP_PATH = "tmp";

    // Load Config
    $config_string = file_get_contents("../config/config.json");
    $config_json = json_decode($config_string, true);

    // Copy files to temp folder
    // TODO

    // Execute Command
    exec( $config_json['SDK_PATH']."/tools/proguard/bin/retrace.sh -verbose ".$TMP_PATH."/mapping.txt ".$TMP_PATH."/trace.txt" , $output , $return_var );

    // Build JSON and return
    $res_out="";
    foreach ($output as $value) {
        $res_out.=$value."\n";
    }
    $res_array = array ( "retrace_output" => $res_out , "return_code" => $return_var );
    $res_json = json_encode($res_array);
    echo $res_json;
?>