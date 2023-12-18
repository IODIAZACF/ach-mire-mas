<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

$param = file_get_contents("php://input");
$param = json_decode($param);

file_put_contents(__DIR__ .'/'. basename(__FILE__, '.php') .'.txt', "------ INPUT ------\n", FILE_APPEND);
file_put_contents(__DIR__ .'/'. basename(__FILE__, '.php') .'.txt', print_r($param, true), FILE_APPEND);

$param = $_REQUEST;
file_put_contents(__DIR__ .'/'. basename(__FILE__, '.php') .'.txt', "------ _REQUEST ------\n", FILE_APPEND);
file_put_contents(__DIR__ .'/'. basename(__FILE__, '.php') .'.txt', print_r($param, true), FILE_APPEND);


?>