<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//header("Access-Control-Allow-Origin:*");
$xparam = file_get_contents('php://input');
$param =$_REQUEST;

	file_put_contents(__DIR__ .'/log/sms_in_json.txt', print_r($xparam, true), FILE_APPEND);
	file_put_contents(__DIR__ .'/log/sms_in_json.txt', print_r($_POST, true), FILE_APPEND);
	file_put_contents(__DIR__ .'/log/sms_in_json.txt', print_r($_GET, true), FILE_APPEND);
	file_put_contents(__DIR__ .'/log/sms_in_json.txt', "\n\n", FILE_APPEND);
//if( $e->message->direction=='sent') die();



die();
include __DIR__  . '/config.php';

include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');
include_once (UTIL_PATH . 'utilidades/php/clases/class_ini.php');
include_once (UTIL_PATH . 'utilidades/php/clases/comun.php'); 


$origen  = str_replace(['+', '-'], '', filter_var($param['To'], FILTER_SANITIZE_NUMBER_INT));

$mensaje 	= 'NULL';
$url 		= 'NULL';
$latitud 	= 'NULL';
$longitud 	= 'NULL';

$tipo = $param['MediaContentType0'];

$mensaje = str_replace("'", "''", quitar_tildes($param['Body']));

$API_CREACION_FECHA = 'NULL';
$API_CREACION_HORA  = 'NULL';
$API_UPDATE_FECHA = 'NULL';
$API_UPDATE_HORA  = 'NULL';

$via  	 = $_REQUEST['via'] ? $_REQUEST['via'] : 'IN';
$id_m_usuarios  = $_REQUEST['id_m_usuarios'] 	? "'" . $_REQUEST['id_m_usuarios'] . "'" 	: 'NULL';
$identificador  = str_replace(['+', '-'], '', filter_var($param['From'], FILTER_SANITIZE_NUMBER_INT));


$canal  		= "'WHATSAPP'";// 			? "'" . $_REQUEST['canal'] . "'" 			: 'null';
$nick  			= $_REQUEST['nick'] 			? "'" . $_REQUEST['nick'] . "'" 			: 'NULL';
$canal_usuario 	= $_REQUEST['canal_usuario'] 	? "'" . $_REQUEST['canal_usuario'] . "'" 	: 'NULL';
$descripcion	= $_REQUEST['descripcion'] 		? "'" . $_REQUEST['descripcion'] . "'" 		: 'NULL';

if($via=='IN'){
	$estatus='REC'; 
} else {
	$estatus='PEN';
}

//if($mensaje=='') die('error');

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/" . DB . ".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();

if(!isset($param['MessageStatus'])){
	$ID_D_WHATSAPP = $query->Next_ID('D_WHATSAPP');
	$sql = "INSERT INTO D_WHATSAPP (MENSAJE_ID, ORIGEN, MENSAJE, ESTATUS, VIA,ID_M_USUARIOS, IDENTIFICADOR,CANAL,NICK,CANAL_USUARIO,DESCRIPCION, LATITUD, LONGITUD, API_CREACION_FECHA, API_CREACION_HORA,API_UPDATE_FECHA,API_UPDATE_HORA,URL) VALUES ('". $param['MessageSid'] ."', '" . $origen . "','" . $mensaje . "', '" . $estatus . "', '". $via ."', ". $id_m_usuarios .", ". $identificador .",". $canal .",". $nick .",". $canal_usuario .",". $descripcion .", ". $latitud .", ". $longitud . ", ". $API_CREACION_FECHA . ", " . $API_CREACION_HORA ."," . $API_UPDATE_FECHA . "," . $API_UPDATE_HORA . "," . $url. ")";	
}else{
	$sql = "UPDATE D_WHATSAPP SET ESTATUS='". strtoupper($param['MessageStatus']) ."' WHERE  MENSAJE_ID='". $param['MessageSid'] ."'";
}

file_put_contents(__DIR__ . '/log/receptor_sql.txt', $sql . "\n", FILE_APPEND);
$query->sql=$sql;
$query->ejecuta_query();

file_put_contents(__DIR__ . '/log/query_sql.txt', print_r( $query, true));

if(strlen($query->regi['ERROR'])){
	echo $query->regi['ERROR']."<br/>\n";
	die("HA OCURRIDO UN ERROR DE SQL<br/>\n");
}

?>