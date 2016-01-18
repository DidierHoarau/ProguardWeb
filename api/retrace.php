<?php
    // Get Input JSON
    $data = json_decode(file_get_contents('php://input'), true);

    // Init Variables
    $tmp_path = "tmp/".rand();

    // Load Config
    $config_string = file_get_contents("../config/config.json");
    $config_json = json_decode($config_string, true);

    // Copy files to temp folder
    $res_bool = mkdir($tmp_path);
    $res_bool = copy($config_json['mapping_path']."/".$data['app'] , $tmp_path."/".$data['app']);
    $res_bool = file_put_contents($tmp_path."/trace.txt", $data['trace'], LOCK_EX);
    $p1 = new PharData($tmp_path."/".$data['app']);
    $p1->decompress();
    $p2 = new PharData($tmp_path."/".dirGetFileWithExt($tmp_path."/", ".tar"));
    $p2->extractTo($tmp_path);
    $tmp_mapping_path = dirFindFile($tmp_path,"mapping.txt");

    // Execute Command
    exec( $config_json['sdk_path']."/tools/proguard/bin/retrace.sh -verbose ".$tmp_mapping_path." ".$tmp_path."/trace.txt" , $output , $return_var );

    // Clean
    delTree($tmp_path);

    // Build JSON and return
    $res_out="";
    foreach ($output as $value) {
        $res_out.=$value."\n";
    }
    $res_array = array ( "retrace_output" => $res_out , "return_code" => $return_var );
    $res_json = json_encode($res_array);
    echo $res_json;




    /** Functions *********************/

    /**
     * Reccursively deletes a directory
     */
    function delTree($dir) { 
        $files = array_diff(scandir($dir), array('.','..')); 
        foreach ($files as $file) { 
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
        } 
        return rmdir($dir); 
    }

    /**
     * Return the file in a directory with a given ext
     */
    function dirGetFileWithExt($dir,$ext) { 
        $files = array_diff(scandir($dir), array('.','..')); 
        foreach ($files as $file) {
            if (substr($file, strlen($file)-strlen($ext),strlen($ext))==$ext) {
                return $file;
            } 
        } 
        return "";
    } 

    /**
     * Reccursively find a file in a directory
     */
    function dirFindFile($dir,$name) { 
        $files = array_diff(scandir($dir), array('.','..')); 
        foreach ($files as $file) {
            if (is_dir("$dir/$file")) {
                $looksub = dirFindFile("$dir/$file",$name);
                if ($looksub!="") {
                    return $looksub;
                }
            } elseif ($file==$name) {
                return "$dir/$file";
            }
        }
        return ""; 
    }

?>