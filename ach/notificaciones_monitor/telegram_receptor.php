<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
include __DIR__  . '/config.php';

$param = file_get_contents("php://input");
$param = json_decode($param);

@unlink(__DIR__ .'/log/end.txt');
file_put_contents(__DIR__ .'/log/telegram.txt', print_r($param, true), FILE_APPEND);
file_put_contents(__DIR__ .'/log/telegram.txt', "\n\n", FILE_APPEND);

include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');
include_once (UTIL_PATH . 'utilidades/php/clases/class_ini.php');
include_once (UTIL_PATH . 'utilidades/php/clases/comun.php'); 


$mensaje 	= 'NULL';
$url 		= 'NULL';
$latitud 	= 'NULL';
$longitud 	= 'NULL';


$mensaje = str_replace("'", "''", quitar_tildes($param->message->text));


$API_CREACION_FECHA = 'NULL';
$API_CREACION_HORA  = 'NULL';
$API_UPDATE_FECHA = 'NULL';
$API_UPDATE_HORA  = 'NULL';

$via  	 = $_REQUEST['via'] ? $_REQUEST['via'] : 'IN';
$id_m_usuarios  = $_REQUEST['id_m_usuarios'] 	? "'" . $_REQUEST['id_m_usuarios'] . "'" 	: 'NULL';
$identificador  = $param->message->chat->id;


$canal  		= "'TELEGRAM'";// 			? "'" . $_REQUEST['canal'] . "'" 			: 'null';
$nick  			= $param->message->chat->first_name 	? "'" . strtoupper(utf8_decode($param->message->chat->first_name)  . ' ' . utf8_decode($param->message->chat->last_name))  . "'" 			: 'NULL';
$canal_usuario 	= $_REQUEST['canal_usuario'] 	? "'" . $_REQUEST['canal_usuario'] . "'" 	: 'NULL';
$descripcion	= $_REQUEST['descripcion'] 		? "'" . $_REQUEST['descripcion'] . "'" 		: 'NULL';

if($via=='IN'){
	$estatus='RECEIVED'; 
} else {
	$estatus='PENDIENTE';
}

//if($mensaje=='') die('error');

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/" . DB . ".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();

$COMANDO = 'NULL';
if(substr($mensaje, 0, 1)=='/'){
	$COMANDO = "'" . strtoupper(trim(substr($mensaje, 1))). "'";
}
	
/*
if(substr($mensaje, 0, 7)=='/start '){
	$CODIGO = trim(substr($mensaje, 7));
	//$comando = 'BIENVENIDA';
	$query->sql = "UPDATE M_CLIENTES SET TELEGRAM_ID ='". $param->message->from->id ."' WHERE CODIGO2='" . $CODIGO . "'";
	$query->ejecuta_query();
	file_put_contents(__DIR__ .'/log/end.txt', $query->sql . "\n", FILE_APPEND);
}
*/

$query->sql = "SELECT ID_D_CHAT FROM D_CHAT WHERE CANAL='TELEGRAM' AND MENSAJE_ID='". $param->message->message_id ."'";
$query->ejecuta_query();
if($query->next_record()){
	file_put_contents(__DIR__ .'/log/tele_repe.txt', $query->sql . "\n" , FILE_APPEND);
	die();
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
$sql .= "'" . $param->message->message_id ."',";
$sql .= "'" . TELEGRAM_ORIGEN . "',";
$sql .= "'" . $mensaje . "',";
$sql .= ""  . $COMANDO . ",";
$sql .= "'" . $estatus . "', ";
$sql .= "'" . $via ."', ";
$sql .= ""  . $id_m_usuarios .",";
$sql .= "'"  . $identificador ."',";
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

file_put_contents(__DIR__ . '/log/telegram_receptor_sql.txt', $sql . "\n", FILE_APPEND);
$query->sql=$sql;
$query->ejecuta_query();

file_put_contents(__DIR__ . '/log/telegram_query_sql.txt', print_r( $query, true));


?>