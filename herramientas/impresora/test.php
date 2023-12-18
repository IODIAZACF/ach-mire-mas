<?php

session_start();

header("content-type: text/plain; charset=utf-8");

$sql = $_REQUEST["sql"];
$db = "/opt/lampp/firebird/db/comercialapolo.gdb";

if ($sql) {
    $cnx = ibase_connect("localhost:$db", "SYSDBA", "masterkey", 'ISO8859_1');
    
    $q = ibase_query($cnx, $sql);
    echo ibase_errmsg();

    $obj=array("data" => array());
    while ($r = ibase_fetch_assoc($q)){
        array_push($obj["data"], $r);
    }

    $obj["data"] = utf8_converter($obj["data"]);

    print_r(json_encode($obj));
    
    echo json_last_error_msg();
}

function utf8_converter($array)
{
    array_walk_recursive($array, function(&$item, $key){
        if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
        }
    }); 
    return $array;
}

?>