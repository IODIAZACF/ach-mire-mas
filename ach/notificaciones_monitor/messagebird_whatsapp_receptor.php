<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
header("Access-Control-Allow-Origin:*");
$param = file_get_contents('php://input');

if($param!=''){
	file_put_contents(__DIR__ .'/log/recibe_json.txt', $param);
}else{
	$param = file_get_contents(__DIR__ . '/log/recibe_json.txt');	
}

$e = json_decode($param);

file_put_contents(__DIR__ .'/log/evento_'. $e->contact->msisdn  .'.txt', print_r(json_decode($param), true), FILE_APPEND);

if( $e->message->direction=='sent') die();

include __DIR__  . '/config.php';

include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');
include_once (UTIL_PATH . 'utilidades/php/clases/class_ini.php');
include_once (UTIL_PATH . 'utilidades/php/clases/comun.php'); 


$origen  = str_replace(['+', '-'], '', filter_var($e->message->to, FILTER_SANITIZE_NUMBER_INT));

$mensaje 	= 'NULL';
$url 		= 'NULL';
$latitud 	= 'NULL';
$longitud 	= 'NULL';

$tipo = $e->message->type;

switch ($e->message->type) {
    case 'text':
		$mensaje = str_replace("'", "''", quitar_tildes($e->message->content->text));
        break;
    case 'image':
        if(isset($e->message->content->image->caption)) $mensaje = str_replace("'", "''", quitar_tildes($e->message->content->image->caption, "'"));
		$url = "'" . str_replace("'", "''", quitar_tildes($e->message->content->image->url, "'")) . "'";
        break;
    case 'file':
        $url = "'" . str_replace("'", "''", quitar_tildes($e->message->content->file->url, "'")) . "'";
		if(isset($e->message->content->image->caption)) $mensaje = str_replace("'", "''", quitar_tildes($e->message->content->file->caption, "'"));
        break;
    case 'audio':
        $url = "'" . str_replace("'", "''", quitar_tildes($e->message->content->audio->url, "'")) . "'";
        break;
    case 'video':
        if(isset($e->message->content->video->caption)) $mensaje = str_replace("'", "''", quitar_tildes($e->message->content->video->caption, "'"));
		$url = "'" . str_replace("'", "''", quitar_tildes($e->message->content->video->url, "'")) . "'";
        break;
    case 'location':        
		$latitud = "'" . $e->message->content->location->latitude . "'";
		$longitud = "'" . $e->message->content->location->longitude . "'";
        break;
}

$API_CREACION_FECHA = 'NULL';
$API_CREACION_HORA  = 'NULL';
$API_UPDATE_FECHA = 'NULL';
$API_UPDATE_HORA  = 'NULL';

$via  	 = $_REQUEST['via'] ? $_REQUEST['via'] : 'IN';
$id_m_usuarios  = $_REQUEST['id_m_usuarios'] 	? "'" . $_REQUEST['id_m_usuarios'] . "'" 	: 'NULL';
$identificador  = str_replace(['+', '-'], '', filter_var($e->message->from, FILTER_SANITIZE_NUMBER_INT));


$canal  		= "'WHATSAPP'";// 			? "'" . $_REQUEST['canal'] . "'" 			: 'null';
$nick  			= $_REQUEST['nick'] 			? "'" . $_REQUEST['nick'] . "'" 			: 'NULL';
$canal_usuario 	= $_REQUEST['canal_usuario'] 	? "'" . $_REQUEST['canal_usuario'] . "'" 	: 'NULL';
$descripcion	= $_REQUEST['descripcion'] 		? "'" . $_REQUEST['descripcion'] . "'" 		: 'NULL';

if ($via=='IN'){
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

$sql = "INSERT INTO D_WHATSAPP (ORIGEN, MENSAJE, ESTATUS, VIA,ID_M_USUARIOS, IDENTIFICADOR,CANAL,NICK,CANAL_USUARIO,DESCRIPCION, LATITUD, LONGITUD, API_CREACION_FECHA, API_CREACION_HORA,API_UPDATE_FECHA,API_UPDATE_HORA,URL) VALUES ('" . $origen . "','" . $mensaje . "', '" . $estatus . "', '". $via ."', ". $id_m_usuarios .", ". $identificador .",". $canal .",". $nick .",". $canal_usuario .",". $descripcion .", ". $latitud .", ". $longitud . ", ". $API_CREACION_FECHA . ", " . $API_CREACION_HORA ."," . $API_UPDATE_FECHA . "," . $API_UPDATE_HORA . "," . $url. ")";

file_put_contents(__DIR__ . '/log/receptor_sql.txt', $sql . "\n", FILE_APPEND);
$query->sql=$sql;
$query->ejecuta_query();

file_put_contents(__DIR__ . '/log/query_sql.txt', print_r( $query, true));

if(strlen($query->regi['ERROR'])){
	echo $query->regi['ERROR']."<br/>\n";
	die("HA OCURRIDO UN ERROR DE SQL<br/>\n");
}

?>