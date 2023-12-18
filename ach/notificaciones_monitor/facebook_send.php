#!/usr/bin/php

<?php
include __DIR__ . '/config.php';
require_once(__DIR__ . '/vendor/autoload.php');

use pimax\FbBotApp;
use pimax\Menu\MenuItem;
use pimax\Menu\LocalizedMenu;
use pimax\Messages\Message;
use pimax\Messages\MessageButton;
use pimax\Messages\StructuredMessage;
use pimax\Messages\MessageElement;
use pimax\Messages\MessageReceiptElement;
use pimax\Messages\Address;
use pimax\Messages\Summary;
use pimax\Messages\Adjustment;
use pimax\Messages\AccountLink;
use pimax\Messages\ImageMessage;
use pimax\Messages\QuickReply;
use pimax\Messages\QuickReplyButton;
use pimax\Messages\SenderAction;


$bot = new FbBotApp(FB_TOKEN);

$IDENTIFICADOR = $argv[1];
if(isset($argv[2])){
	$xsms['To'] 	= $argv[1];
	$xsms['Body'] 	= $argv[2];
	$sms = json_decode(json_encode($xsms));
	file_put_contents(__DIR__ . '/mensajes/facebook/error/' . $IDENTIFICADOR , json_encode($xsms));
}else{
	rename(__DIR__ . '/mensajes/facebook/pendientes/' . $IDENTIFICADOR, __DIR__ . '/mensajes/facebook/error/' . $IDENTIFICADOR);
	$sms = json_decode(file_get_contents(__DIR__ . '/mensajes/facebook/error/' . $IDENTIFICADOR));	
}		  

$result = $bot->send(new Message($sms->To , $sms->Body));
if(!isset($result['error'])){	
	unlink(__DIR__ . '/mensajes/facebook/error/' . $IDENTIFICADOR);
	file_put_contents(__DIR__ . '/mensajes/facebook/enviados/' . $IDENTIFICADOR , print_r( $result, true));
	$resp['estatus'] ='OK';
	$resp['id'] = "" . $result['message_id'] . "";
	echo json_encode($resp);
	exit(1);
}else{
	$resp['estatus'] ='ERROR';
	$resp['msg'] = "" . $result['error']['message'] . "";
	echo json_encode($resp);
	exit(0);	
}

?> 