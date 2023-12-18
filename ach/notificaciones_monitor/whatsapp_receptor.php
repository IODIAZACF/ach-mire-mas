<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//header("Access-Control-Allow-Origin:*");
//$param = file_get_contents('php://input');

include __DIR__  . '/config.php';
@mkdir(__DIR__ . '/callback/', 0777, true);
$param =$_REQUEST;

if($param!=''){
	file_put_contents(__DIR__ .'/log/recibe_json.txt', print_r($param, true), FILE_APPEND);
	file_put_contents(__DIR__ .'/log/recibe_json.txt', "\n\n", FILE_APPEND);
}else{
	$param = file_get_contents(__DIR__ . '/log/recibe_json.txt');	
}

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
	$estatus= strtoupper($param['SmsStatus']); 
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


if(!isset($param['MessageStatus'])){
	$ID_D_CHAT = $query->Next_ID('D_CHAT');
	$sql = "INSERT INTO D_CHAT (";
	$sql.= "MENSAJE_ID ,"; 
	$sql.= "ORIGEN ,"; 
	$sql.= "MENSAJE,"; 
	$sql.= "COMANDO, ";	
	$sql.= "ESTATUS,"; 
	$sql.= "VIA,";
	$sql.= "ID_M_USUARIOS, ";
	$sql.= "IDENTIFICADOR,";
	$sql.= "CANAL,";
	$sql.= "NICK,";
	$sql.= "CANAL_USUARIO,";
	$sql.= "DESCRIPCION, ";
	$sql.= "LATITUD, ";
	$sql.= "LONGITUD, ";
	$sql.= "API_CREACION_FECHA, ";
	$sql.= "API_CREACION_HORA,";
	$sql.= "API_UPDATE_FECHA,";
	$sql.= "API_UPDATE_HORA,";
	$sql.= "URL";
	$sql.= ") VALUES (";
	$sql.= "'". $param['MessageSid'] ."', ";
	$sql.= "'" . $origen . "',";
	$sql.= "'" . $mensaje . "', ";
	$sql.= ""  . $COMANDO . ",";
	$sql.= "'" . $estatus . "', ";
	$sql.= "'" . $via ."', ";
	$sql.= ""  . $id_m_usuarios .", ";
	$sql.= "'"  . $identificador ."',";
	$sql.= ""  . $canal .",";
	$sql.= ""  . $nick .",";
	$sql.= ""  . $canal_usuario .",";
	$sql.= ""  . $descripcion .", ";
	$sql.= ""  . $latitud .", ";
	$sql.= ""  . $longitud . ", ";
	$sql.= ""  . $API_CREACION_FECHA . ", ";
	$sql.= ""  . $API_CREACION_HORA .",";
	$sql.= ""  . $API_UPDATE_FECHA . ",";
	$sql.= ""  . $API_UPDATE_HORA . ",";
	$sql.= ""  . $url. "";
	$sql.= ")";
	
	file_put_contents(__DIR__ . '/log/receptor_sql.txt', $sql . "\n", FILE_APPEND);
	$query->sql=$sql;
	$query->ejecuta_query();	

}else{
/*
	$sql = "UPDATE D_CHAT SET ESTATUS='". strtoupper($param['MessageStatus']) ."' WHERE  MENSAJE_ID='". $param['MessageSid'] ."'";
	file_put_contents(__DIR__ . '/log/callback.txt', print_r( $param, true) . "\n", FILE_APPEND);
	$query->sql=$sql;
	$query->ejecuta_query();
	if(!$query->Reg_Afect){	
*/
		$nf = glob(__DIR__ . '/callback/whatsapp_' . $param['MessageSid'] .'_*');
		$t = sizeof($nf) +1;
		$sec = str_pad($t, 7, "0", STR_PAD_LEFT);
		file_put_contents(__DIR__ . '/callback/whatsapp_' .  $param['MessageSid'] . '_' . $sec, json_encode($_REQUEST));
		file_put_contents(__DIR__ . '/log/query_sql.txt', print_r( $query, true));
//	}	
}



if(strlen($query->regi['ERROR'])){
	echo $query->regi['ERROR']."<br/>\n";
	die("HA OCURRIDO UN ERROR DE SQL<br/>\n");
}

?>