#!/usr/bin/php

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include dirname(__FILE__) . '/config.php';
include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');
	$cmd = 'twurl -X GET /1.1/direct_messages/events/list.json';
	$resp = exec($cmd);
	$mensajes = json_decode($resp);
	file_put_contents(dirname(__FILE__) . '/log/twitter_events_list.txt', print_r($mensajes, true));
	for($i=0;$i<sizeof($mensajes->events);$i++)
	{
		$msg = $mensajes->events[$i];
		/* ELIMINAR MENSAJES */
		$cmd = 'twurl -X DELETE "/1.1/direct_messages/events/destroy.json?id='. $msg->id .'"';
		echo $cmd . "\n";
		$resp = system($cmd);
	}

	echo "Eliminados los mensajes de el twitter"
?>