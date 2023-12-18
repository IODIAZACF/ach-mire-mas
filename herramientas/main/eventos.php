<?php 

if (file_exists('eventos.js')) {
	$data = file_get_contents('eventos.js');
} else {
	die();
}


$registro = array(
	'title' => 'Nuevo Mensaje',
	'msg' => 'Mensaje de SERVIDOR PUSH',
	'msg.en' => 'Message from PUSH SERVER',
	'msg.script' => $_SERVER['SCRIPT_NAME'],
	'msg.db' => 'protecseguros',
	'msg.comando' => $data
	
);

$pushdHost = '127.0.0.1'; 	// IP O DNS DEL SERVIDOR PUSH
$pushdPort = 8887; 			// PUERTO DEL SERVIDOR PUSH
$eventName = 'main';

$msg = gzcompress('POST /event/' . urldecode($eventName) . '?' . http_build_query($registro));
$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_sendto($socket, $msg, strlen($msg), 0, $pushdHost, $pushdPort);
socket_close($socket);




?>