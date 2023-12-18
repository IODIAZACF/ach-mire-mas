#!/usr/bin/php

<?php
include __DIR__ . '/config.php';

$IDENTIFICADOR = $argv[1];
if(isset($argv[2])){
	$xsms['To'] 	= $argv[1];
	$xsms['Body'] 	= $argv[2];
	$sms = json_decode(json_encode($xsms));
	file_put_contents(__DIR__ . '/mensajes/www/error/' . $IDENTIFICADOR , json_encode($xsms));
}else{
	rename(__DIR__ . '/mensajes/www/pendientes/' . $IDENTIFICADOR, __DIR__ . '/mensajes/www/error/' . $IDENTIFICADOR);
	$sms = json_decode(file_get_contents(__DIR__ . '/mensajes/www/error/' . $IDENTIFICADOR));	
}

if($sms->Tipo=='IMAGEN'){	
	$base64 = '';
	if(file_exists(Server_Path . '/'  . $sms->Url)){		
		$path = Server_Path . '/'  . $sms->Url;
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);		
	}else{
		if(file_exists($sms->Url)){
			$path = $sms->Url;
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);					
		}
	}
	if($base64!=''){
		$Body = '<img src="'. $base64 .'"><br>';
		$Body.= $sms->Body;
		$sms->Body = $Body;
	}
	file_put_contents(__DIR__ . '/mensajes/www/enviados/' . $IDENTIFICADOR . '.html' , $sms->Body);	
}		  

$curl = curl_init();

//echo base64_encode('admin:rune7780') . "\n";
curl_setopt_array($curl, array(
  //CURLOPT_URL => "http://127.0.0.1/". WWW_PATH ."/index.php/restapi/addmsgadmin",
  CURLOPT_URL => "http://127.0.0.1/". WWW_PATH ."/index.php/restapi/addmsgadmin",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_VERBOSE => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => array('chat_id' => $sms->To,'msg' => $sms->Body,'sender' => '1', 'meta_msg' => $sms->Body),
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic " . base64_encode(WWW_USER . ':' . WWW_KEY),
    "Cookie: PHPSESSID=kbluj0cpqap5m2jbbrsbos0g50"
  ),
));

$response = curl_exec($curl);
curl_close($curl);
$result = json_decode(($response));

if(!$result->error){	
	unlink(__DIR__ . '/mensajes/www/error/' . $IDENTIFICADOR);
	file_put_contents(__DIR__ . '/mensajes/www/enviados/' . $IDENTIFICADOR , print_r( $result, true));
	$resp['estatus'] ='OK';
	$resp['id'] = generateRandomString();
	echo json_encode($resp);
	exit(1);
}else{
	print_r($result);
	exit(0);	
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?> 