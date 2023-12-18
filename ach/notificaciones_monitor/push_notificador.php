#!/usr/bin/php

<?php
system('clear');
system("printf '\033[3J'");
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//header("Access-Control-Allow-Origin:*");
//$param = file_get_contents('php://input');

include __DIR__  . '/config.php';
include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');
include_once (UTIL_PATH . 'utilidades/php/clases/class_ini.php');
include_once (UTIL_PATH . 'utilidades/php/clases/comun.php'); 

$mensaje = '--INICIANDO_PUSH--';

while(1){
	$equery = new sql();
	$equery->DBHost     = "127.0.0.1";
	$equery->DBDatabase = "/opt/lampp/firebird/db/" . DB . ".gdb";
	$equery->DBUser     = "SYSDBA";
	$equery->DBPassword = "masterkey";
	$equery->Initialize();
	
	/*Arreglo con la data a enviar*/
	$registro = array(
	'title' => 'TITULO DEL MENSAJE',
	'msg' => 'Mensaje de SERVIDOR PUSH',
	'msg.en' => 'Message from PUSH SERVER',
	'data.FECHA' => date("d/m/Y"),
	'data.NOMBRE' => date("d/m/Y"),
	'data.HORA'  => date("H:i:s"),
	'data.MENSAJE' => $mensaje);

	push('chat', $registro);

	
	echo date("Y-m-d H:i:s") . " -> Esperando....\n";
	$equery->wait('NEW_MENSAJE');
	$mensaje = '--NUEVO_MENSAJE--';
	
	$equery->close();
	unset($equery);	
}

function push($suscripcion, $registro){

	$pushdHost = '127.0.0.1'; // IP O DNS DEL SERVIDOR PUSH
	$pushdPort = 8887; // PUERTO DEL SERVIDOR PUSH
	$eventName = $suscripcion;


	$msg = gzcompress('POST /event/' . urldecode($eventName) . '?' . http_build_query($registro));
	$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
	socket_sendto($socket, $msg, strlen($msg), 0, $pushdHost, $pushdPort);
	socket_close($socket);

}

?>