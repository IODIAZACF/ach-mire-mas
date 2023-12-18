#!/usr/bin/php

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include dirname(__FILE__) . '/config.php';

$recipient_id 	= $argv[1];
$text			= $argv[2];
$parameters = array(
	'event' => array(
		'type' => "message_create",
		'message_create' => array(
			'target' => array(
				'recipient_id' => $recipient_id
			),
			'message_data' => array(
				'text' => $text
			)
		)
	)
);
$cmd = "twurl -A 'Content-type: application/json' -X POST /1.1/direct_messages/events/new.json -d '". json_encode($parameters) ."'";
$resp = exec($cmd);
$mensajes = json_decode($resp);
file_put_contents(dirname(__FILE__) . '/log/twitter_events_new.txt', print_r($mensajes, true));
if(isset($mensajes->event)){
	$UID  			= md5(TWITTER_COUNT_ID . $mensajes->event->id);
	echo $UID;
}
else{
	echo "ERROR";
	file_put_contents(dirname(__FILE__) . '/log/'. uniqid('error_') .'_twitter_events_new.txt', print_r($mensajes, true));

}	
?>