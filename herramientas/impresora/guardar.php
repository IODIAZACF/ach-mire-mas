<?php

$file = $_REQUEST["file"];
$fr3  = $_REQUEST["fr3"];

$dir = __DIR__."/../..";
$file = "$dir/$file.fr3";
$respaldo = __DIR__."/respaldo/$file";

/*if (file_exists($file)) {
    
    echo "$dir/respaldo";
    if (!mkdir("/respaldo")) {
        die("ERRDIR");
    }
    if (file_exists("$respaldo.fr3")) {
        $n="";
        $m=0;
        while (file_exists($respaldo."$n.fr3")) {
            $n=$m;
            $m++;
        }
        $res = copy("$respaldo.fr3", $respaldo."$n.fr3");
        if (!$res) die("ERRBKP");
    }
}*/
if (file_put_contents($file, $fr3)) echo "OK";
?>