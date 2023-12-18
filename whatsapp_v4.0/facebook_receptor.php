<?php
include __DIR__  . '/config.php';
const VERIFY_TOKEN = "siempreLas36horas**";

include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');

$contenido = file_get_contents("php://input");

//$contenido = file_get_contents(__DIR__ . '/recibe_from_whatsapp.json');

$data = json_decode($contenido , true, 512, JSON_BIGINT_AS_STRING);
file_put_contents (__DIR__ . '/recibe_from_whatsapp.json', $contenido);
file_put_contents (__DIR__ . '/recibe_from_whatsapp.txt', print_r ( $data , true), FILE_APPEND);


if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == VERIFY_TOKEN) {
    echo $_REQUEST['hub_challenge'];
	die();
}

$conversacion_id = $data['entry'][0]['id'];
$msg = $data['entry'][0]['changes'][0]['value'];



$mensaje 				= 'NULL';
$url 					= 'NULL';
$latitud 				= 'NULL';
$longitud 				= 'NULL';
$descripcion			= 'NULL';
$mime					= 'NULL';
$API_CREACION_FECHA 	= "'" . date('Y-m-d', $msg['messages'][0]['timestamp'] ) . "'";
$API_CREACION_HORA  	= "'" . date('H:i:s', $msg['messages'][0]['timestamp'] ) . "'";
$API_UPDATE_FECHA 		= 'NULL';
$API_UPDATE_HORA  		= 'NULL';
$API_RESPONSE  			= 'whatsapp';
$MediaKey				= 'NULL';
$jpegThumbnail			= 'NULL';
//$url_profile			= isset($param['messages'][0]['key']['url_profile']) ? "'" . $param['messages'][0]['key']['url_profile'] . "'": 'NULL';
$url_profile			= 'NULL';
$canal  				= "'WHATSAPP'";
//$via  	 				= $param['messages'][0]['key']['fromMe']=='1' ?  "'OUT'" : "'IN'";
$via  	 				= "'IN'";
$id_m_usuarios  		= isset( $_REQUEST['id_m_usuarios'] )	? "'" . $_REQUEST['id_m_usuarios'] . "'" 	: 'NULL';
$estatus				= 'RECIBIDO'; 


switch( $msg['messages'][0]['type'] ){
	case 'text':
		$tipo = 'chat' ;
		$mensaje = $msg['messages'][0]['text']['body'];
		$mensaje = str_replace("'", "''", $mensaje);
	break;

}



include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');
include_once (UTIL_PATH . 'utilidades/php/clases/class_ini.php');
include_once (UTIL_PATH . 'utilidades/php/clases/comun.php'); 

$origen  				= $msg['metadata']['display_phone_number'];
$identificador 			= $msg['messages'][0]['from'];
$mensaje_id 			= $msg['messages'][0]['id'];
$nick  					= isset ( $msg['contacts'] ) ? utf8_decode( $msg['contacts'][0]['profile']['name'] ) : 'NULL';


$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/" . DB . ".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();

$query2 = new sql();
$query2->DBHost     = "127.0.0.1";
$query2->DBDatabase = "/opt/lampp/firebird/db/" . DB . ".gdb";
$query2->DBUser     = "SYSDBA";
$query2->DBPassword = "masterkey";
$query2->Initialize();

$COMANDO = 'NULL';
if(substr($mensaje, 0, 1)=='/'){
	$COMANDO = "'" . strtoupper(trim(substr($mensaje, 1))). "'";
}

// **************** temporal por Edsoan para evitar recibir mensajes sin origen ****************//
if( $origen == ''){
	$tmp['estatus'] = 'ERROR Mensaje sin Origen Controlar este error en Baily cuando se envia un mensaje el lo recibe tambien..!!!! (Edson)';
	echo json_encode($tmp);
	die();	
}


$ID_D_CHAT = '001'.$query->Next_ID('D_CHAT');
$sql = "INSERT INTO D_CHAT (";
$sql.= "SCRIPT_ORIGEN,";
$sql.= "TIPO,";
$sql.= "ID_D_CHAT ,"; 
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
$sql.= "API_RESPONSE,";
$sql.= "DESCRIPCION, ";
$sql.= "LATITUD, ";
$sql.= "LONGITUD, ";
$sql.= "API_CREACION_FECHA, ";
$sql.= "API_CREACION_HORA,";
$sql.= "API_UPDATE_FECHA,";
$sql.= "API_UPDATE_HORA,";
$sql.= "MEDIAKEY,";
$sql.= "JPEGTHUMBNAIL,";
$sql.= "URL_PROFILE,";
$sql.= "URL";
$sql.= ") VALUES (";
$sql.= "'". $_SERVER['SCRIPT_FILENAME'] . "',";
$sql.= "'". $tipo . "',";
$sql.= "'". $ID_D_CHAT ."', ";
$sql.= "'". $mensaje_id ."', ";
$sql.= "'" . $origen . "',";
$sql.= "'" . utf8_decode($mensaje) . "', ";
$sql.= ""  . $COMANDO . ",";
$sql.= "'" . $estatus . "', ";
$sql.= ""  . $via .", ";
$sql.= ""  . $id_m_usuarios .", ";
$sql.= "'" . $identificador ."',";
$sql.= ""  . $canal .",";
$sql.= "'" . $nick ."',";
$sql.= "'" . $API_RESPONSE ."',";
$sql.= ""  . $descripcion .", ";
$sql.= ""  . $latitud .", ";
$sql.= ""  . $longitud . ", ";
$sql.= ""  . $API_CREACION_FECHA . ", ";
$sql.= ""  . $API_CREACION_HORA .",";
$sql.= ""  . $API_UPDATE_FECHA . ",";
$sql.= ""  . $API_UPDATE_HORA . ",";
$sql.= ""  . $MediaKey . ",";
$sql.= ""  . $jpegThumbnail . ",";
$sql.= ""  . $url_profile . ",";
$sql.= ""  . $url. "";
$sql.= ")";

file_put_contents(__DIR__ . '/receptor_sql.txt', $sql . "\n", FILE_APPEND);

eSQL($sql);

$query->sql="SELECT * FROM D_CHAT WHERE ID_D_CHAT='". $ID_D_CHAT ."'";
$query->ejecuta_query();
$query->next_record();

$tmp['estatus'] = 'OK';
echo json_encode($tmp);
Msg_push($query->Record);


if(strlen($query->regi['ERROR'])){
	echo $query->regi['ERROR']."<br/>\n";
	die("HA OCURRIDO UN ERROR DE SQL<br/>\n");
}


function push_debug($data){	
	
	//exec("curl -d msg=Test%20message http://localhost:8887/event/chat");
	//die();
	
	$registro = array(
		'title' => 'Nuevo Mensaje',
		'msg' => 'Mensaje de SERVIDOR PUSH',
		'msg.en' => 'Message from PUSH SERVER',
		'msg.script' => $_SERVER['SCRIPT_NAME'],
		'data.json' => $data );
	
	$pushdHost = '127.0.0.1'; 	// IP O DNS DEL SERVIDOR PUSH
	$pushdPort = 8887; 			// PUERTO DEL SERVIDOR PUSH
	$eventName = 'chat';
	
	$msg = gzcompress('POST /event/' . urldecode($eventName) . '?' . http_build_query($registro));
	$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
	socket_sendto($socket, $msg, strlen($msg), 0, $pushdHost, $pushdPort);
	socket_close($socket);
	
}


?>