#!/usr/bin/php

<?php
system('clear');
system("printf '\033[3J'");

include __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');
include_once (UTIL_PATH . 'utilidades/php/clases/class_ini.php');
include_once (UTIL_PATH . 'utilidades/php/clases/comun.php'); 


use DG\Twitter\Twitter;

//require_once '../src/twitter.class.php';

// ENTER HERE YOUR CREDENTIALS (see readme.txt)
$twitter = new Twitter(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, TWITTER_ACCESS_TOKEN, TWITTER_ACCESS_TOKEN_SECRET);

// See https://dev.twitter.com/docs/api/1.1
$mensajes = $twitter->request('direct_messages/events/list', 'GET');


//print_r($mensajes);
$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/". DB .".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();

foreach ($mensajes->events as $msg){
	//print_r($msg);
	

	
	echo "Hora              : " . $msg->created_timestamp . "\n";
	echo "identificador     : " . $msg->message_create->sender_id . "\n";
	echo "mensaje           : " . $msg->message_create->message_data->text .  "\n";
	echo "xHora             : " . $msg->created_timestamp . "\n";
	
	echo "--------------------------------------------------------------------------" . "\n";
	
	$query->sql ="SELECT * FROM D_CHAT WHERE MENSAJE_ID='". $msg->id ."'";
	$query->ejecuta_query();
	if($query->next_record()){
		$resp = $mensajes = $twitter->request('direct_messages/events/destroy.json?id='. $msg->id, 'DELETE');
		print_r($resp);
		die();
		
		continue;
	}
		
	$mensaje 	= 'NULL';
	$url 		= 'NULL';
	$latitud 	= 'NULL';
	$longitud 	= 'NULL';


	$mensaje = str_replace("'", "''", quitar_tildes($msg->message_create->message_data->text));


	$API_CREACION_FECHA = 'NULL';
	$API_CREACION_HORA  = 'NULL';
	$API_UPDATE_FECHA = 'NULL';
	$API_UPDATE_HORA  = 'NULL';

	$via			= $msg->message_create->sender_id == TWITTER_COUNT_ID ? 'OUT' : 'IN';
	$id_m_usuarios  ='NULL';
	$identificador  = $msg->message_create->sender_id;

	$nick  			= "NULL";
	$canal  		= "'TIWTTER'";// 			? "'" . $_REQUEST['canal'] . "'" 			: 'null';
	$canal_usuario 	= 'NULL';
	$descripcion	= 'NULL';

	if($via=='IN'){
		$estatus='RECEIVED'; 
		$user_info = $mensajes = $twitter->request('users/show.json?id='. $msg->message_create->sender_id, 'GET');
		$nick  			= "'" . strtoupper(utf8_decode($user_info->name))  . "'";		
	} else {
		$estatus='PENDIENTE';
	}
	
	$COMANDO = 'NULL';
	if(substr($mensaje, 0, 1)=='/'){
		$COMANDO = "'" . strtoupper(trim(substr($mensaje, 1))). "'";
	}
	
	file_put_contents(__DIR__ .'/log/end.txt', __LINE__ , FILE_APPEND);
	$sql = "INSERT INTO D_CHAT (";
	$sql .= "MENSAJE_ID,";
	$sql .= "ORIGEN, ";
	$sql .= "MENSAJE, ";
	$sql .= "COMANDO, ";
	$sql .= "ESTATUS, ";
	$sql .= "VIA,";
	$sql .= "ID_M_USUARIOS, ";
	$sql .= "IDENTIFICADOR,";
	$sql .= "CANAL,";
	$sql .= "NICK,";
	$sql .= "CANAL_USUARIO,";
	$sql .= "DESCRIPCION, ";
	$sql .= "LATITUD, ";
	$sql .= "LONGITUD, ";
	$sql .= "API_CREACION_FECHA, ";
	$sql .= "API_CREACION_HORA,";
	$sql .= "API_UPDATE_FECHA,";
	$sql .= "API_UPDATE_HORA,";
	$sql .= "URL";
	$sql .= ") VALUES (";
	$sql .= "'" . $msg->id ."',";
	$sql .= "'" . TWITTER_COUNT_ID . "',";
	$sql .= "'" . $mensaje . "',";
	$sql .= ""  . $COMANDO . ",";
	$sql .= "'" . $estatus . "', ";
	$sql .= "'" . $via ."', ";
	$sql .= ""  . $id_m_usuarios .",";
	$sql .= "'" . $identificador ."',";
	$sql .= ""  . $canal .",";
	$sql .= ""  . $nick .",";
	$sql .= ""  . $canal_usuario .",";
	$sql .= ""  . $descripcion .",";
	$sql .= ""  . $latitud .",";
	$sql .= ""  . $longitud . ", ";
	$sql .= ""  . $API_CREACION_FECHA . ",";
	$sql .= "" . $API_CREACION_HORA .",";
	$sql .= "" . $API_UPDATE_FECHA . ",";
	$sql .= "" . $API_UPDATE_HORA . ",";
	$sql .= "" . $url . "";
	$sql .= ")";	
	
	file_put_contents(__DIR__ . '/log/twitter_receptor_sql.txt', $sql . "\n", FILE_APPEND);
	$query->sql=$sql;
	$query->ejecuta_query();
	if(strlen($query->erro_msg)>0)
	{
		echo "\nERROR:\n";
		echo $query->sql . "\n";
		echo $query->erro_msg . "\n";
	}else
	{
		$resp = $mensajes = $twitter->request('direct_messages/events/destroy.json?id='. $msg->id, 'DELETE');
		print_r($resp);
		die();
	}
	file_put_contents(__DIR__ . '/log/twitter_query_sql.txt', print_r( $query, true));
}

$query->free_result();
$query->close();
unset($query);
$espera = 90;
echo "\n\n" . date("h:i:s") . "\n\n";
for($j=1;$j<$espera;$j++){
	echo $j . " ";
	sleep(1);
}	

?>