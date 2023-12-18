#!/usr/bin/php

<?php

include __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

$telegram = new Telegram(TELEGRAM_TOKEN);
$IDENTIFICADOR = $argv[1];
if(isset($argv[2])){
	$xsms['To'] 	= $argv[1];
	$xsms['Body'] 	= $argv[2];
	$sms = json_decode(json_encode($xsms));
	file_put_contents(__DIR__ . '/mensajes/telegram/error/' . $IDENTIFICADOR , json_encode($xsms));
}else{
	rename(__DIR__ . '/mensajes/telegram/pendientes/' . $IDENTIFICADOR, __DIR__ . '/mensajes/telegram/error/' . $IDENTIFICADOR);
	$sms = json_decode(file_get_contents(__DIR__ . '/mensajes/telegram/error/' . $IDENTIFICADOR));	
}		  
if($sms->Tipo=='IMAGEN'){
	
	$id = md5_file(__DIR__ . '/mensajes/telegram/error/' . $IDENTIFICADOR);
	$xfile =  sys_get_temp_dir() . "/". $id . '.jpeg';	
	
	if(file_exists(Server_Path . '/'  . $sms->Url)){
		file_put_contents($xfile, file_get_contents(Server_Path . '/'  . $sms->Url));
	}else{
		if(file_exists($sms->Url)){
			file_put_contents($xfile, file_get_contents($sms->Url));	
		}
	}
	
	$img = new CURLFile(realpath($xfile));
	
    $content = ['chat_id' =>$sms->To, 'photo' => $img, 'caption' => $sms->Body];
    $result = $telegram->sendPhoto($content);
	@unlink($xfile);
	
}else{
	
	$chat_id =  $sms->To;
	$content = array('chat_id' => $chat_id, 'parse_mode' => 'markdown' , 'text' => $sms->Body);
	$result = $telegram->sendMessage($content);
	
}

/****************** test ********************/
	//rename(__DIR__ . '/mensajes/telegram/error/' . $IDENTIFICADOR, __DIR__ . '/mensajes/telegram/pendientes/' . $IDENTIFICADOR);
	//die();
/****************** test ********************/
if($result['ok'] =='1'){
	unlink(__DIR__ . '/mensajes/telegram/error/' . $IDENTIFICADOR);	
	file_put_contents(__DIR__ . '/mensajes/telegram/enviados/' . $IDENTIFICADOR , print_r( $result, true));
	$resp['estatus'] ='OK';
	$resp['id'] = "" . $result['result']['message_id'] . "";
	echo json_encode($resp);	
	exit(1);	
}else{
	print_r($result);
	exit(0);	
}
?>