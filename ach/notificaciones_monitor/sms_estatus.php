<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//header("Access-Control-Allow-Origin:*");
$xparam = file_get_contents('php://input');
$param =$_REQUEST;

	file_put_contents(__DIR__ .'/log/recibe_sms_json.txt', print_r($param, true), FILE_APPEND);
	file_put_contents(__DIR__ .'/log/recibe_sms_json.txt', print_r($xparam, true), FILE_APPEND);
	file_put_contents(__DIR__ .'/log/recibe_sms_json.txt', "\n\n", FILE_APPEND);

	
die();
	

?>