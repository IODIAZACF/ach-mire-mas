#!/usr/bin/php

<?php
require_once __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;
// Find your Account Sid and Auth Token at twilio.com/console
// DANGER! This is insecure. See http://twil.io/secure
$sid    = "AC02f5f0bec44d8f309703d482947bca30";
$token  = "f48c44df7a3472ae5dc1d1f401f34208";
$twilio = new Client($sid, $token);

$origen  = '19177462042';

$file = $argv[1];
if(isset($argv[2])){
	$xsms['To'] 	= $argv[1];
	$xsms['Body'] 	= $argv[2];
	$sms = json_decode(json_encode($xsms));
	file_put_contents(__DIR__ . '/mensajes/sms/error/' . $file , json_encode($xsms));
}else{
	rename(__DIR__ . '/mensajes/sms/pendientes/' . $file, __DIR__ . '/mensajes/sms/error/' . $file);
	$sms = json_decode(file_get_contents(__DIR__ . '/mensajes/sms/error/' . $file));	
}		  

//$sms->To = '593960163222';
$message = $twilio->messages
			  ->create("+" . $sms->To,
					   [
						   "from" => "+" . $origen,
						   "body" => utf8_encode($sms->Body) 
					   ]
			  );
			  
unlink(__DIR__ . '/mensajes/sms/error/' . $file);	
$resp['estatus'] ='OK';
$resp['id'] =$message->sid;
echo json_encode($resp);
exit(1);

?>