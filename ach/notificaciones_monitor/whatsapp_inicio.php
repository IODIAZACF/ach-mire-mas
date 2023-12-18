<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
header("Access-Control-Allow-Origin:*");
$param = file_get_contents('php://input');

file_put_contents(__DIR__ .'/log/inicio_json.txt', $param);
file_put_contents(__DIR__ .'/log/inicio_'. $e->contact->msisdn  .'.txt', print_r(json_decode($param), true), FILE_APPEND);

?>