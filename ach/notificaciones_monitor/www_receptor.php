<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
include __DIR__  . '/config.php';

$param = file_get_contents("php://input");
$param = json_decode($param);

file_put_contents(__DIR__ .'/log/www.txt', print_r($param, true), FILE_APPEND);
file_put_contents(__DIR__ .'/log/www.txt', "\n\n", FILE_APPEND);


include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');
include_once (UTIL_PATH . 'utilidades/php/clases/class_ini.php');
include_once (UTIL_PATH . 'utilidades/php/clases/comun.php'); 


$mensaje 	= 'NULL';
$url 		= 'NULL';
$latitud 	= 'NULL';
$longitud 	= 'NULL';


$mensaje = str_replace("'", "''", quitar_tildes(utf8_decode($param->msg)));


$API_CREACION_FECHA = 'NULL';
$API_CREACION_HORA  = 'NULL';
$API_UPDATE_FECHA = 'NULL';
$API_UPDATE_HORA  = 'NULL';

$via  	 = $_REQUEST['via'] ? $_REQUEST['via'] : 'IN';
$id_m_usuarios  = $_REQUEST['id_m_usuarios'] 	? "'" . $_REQUEST['id_m_usuarios'] . "'" 	: 'NULL';
$identificador  = $param->chat_id;


$canal  		= "'WWW'";// 			? "'" . $_REQUEST['canal'] . "'" 			: 'null';
$nick  			= $param->nick 	? "'" . strtoupper(utf8_decode($param->nick))  . "'" 			: 'NULL';
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
$query->sql = "SELECT ID_D_CHAT FROM D_CHAT WHERE CANAL='TELEGRAM' AND MENSAJE_ID='". $param->message->message_id ."'";
$query->ejecuta_query();
if($query->next_record()){
	file_put_contents(__DIR__ .'/log/tele_repe.txt', $query->sql . "\n" , FILE_APPEND);
	die();
}
*/
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
$sql .= "'" . $param->id ."',";
$sql .= "'" . 'sistemas24.com' . "',";
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

file_put_contents(__DIR__ . '/log/www_receptor_sql.txt', $sql . "\n", FILE_APPEND);
$query->sql=$sql;
$query->ejecuta_query();

file_put_contents(__DIR__ . '/log/www_query_sql.txt', print_r( $query, true));


?>