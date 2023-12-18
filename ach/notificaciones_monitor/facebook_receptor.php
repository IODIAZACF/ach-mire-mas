<?php

include __DIR__  . '/config.php';
@mkdir(__DIR__ . '/callback/', 0777, true);


include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');
include_once (UTIL_PATH . 'utilidades/php/clases/class_ini.php');
include_once (UTIL_PATH . 'utilidades/php/clases/comun.php'); 

require_once(__DIR__  . '/vendor/autoload.php');

use pimax\FbBotApp;
use pimax\Menu\MenuItem;
use pimax\Menu\LocalizedMenu;
use pimax\Messages\Message;
use pimax\Messages\MessageButton;
use pimax\Messages\StructuredMessage;
use pimax\Messages\MessageElement;
use pimax\Messages\MessageReceiptElement;
use pimax\Messages\Address;
use pimax\Messages\Summary;
use pimax\Messages\Adjustment;
use pimax\Messages\AccountLink;
use pimax\Messages\ImageMessage;
use pimax\Messages\QuickReply;
use pimax\Messages\QuickReplyButton;
use pimax\Messages\SenderAction;


// Make Bot Instance
$bot = new FbBotApp(FB_TOKEN);



if (!empty($_REQUEST['local'])) {

    $message = new ImageMessage(1585388421775947, dirname(__FILE__).'/fb4d_logo-2x.png');

    $message_data = $message->getData();
    $message_data['message']['attachment']['payload']['url'] = 'fb4d_logo-2x.png';

    echo '<pre>', print_r($message->getData()), '</pre>';

    $res = $bot->send($message);

    echo '<pre>', print_r($res), '</pre>';
}

// Receive something
if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == FB_VERIFY_TOKEN) {

    // Webhook setup request
    echo $_REQUEST['hub_challenge'];
} else {

	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/" . DB . ".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();


    // Other event
    $data = json_decode(file_get_contents("php://input"), true, 512, JSON_BIGINT_AS_STRING);
	file_put_contents(__DIR__ .'/log/facebook_data.txt', print_r($data, true), FILE_APPEND);
	file_put_contents(__DIR__ .'/log/facebook_data.txt', "\n\n", FILE_APPEND);
	
    if (!empty($data['entry'][0]['messaging'])) {

        foreach ($data['entry'][0]['messaging'] as $message) {
			
			file_put_contents(__DIR__ .'/log/facebook_message.txt', print_r($message, true), FILE_APPEND);
			file_put_contents(__DIR__ .'/log/facebook_message.txt', "\n\n", FILE_APPEND);			

            if (!empty($message['delivery'])) {
				
				foreach($message['delivery']['mids'] as $c => $MessageSid){
					$nf = glob(__DIR__ . '/callback/facebook_' . $message['sender']['id']  .'_*');
					$t = sizeof($nf) +1;
					$sec = str_pad($t, 7, "0", STR_PAD_LEFT);
					$xMessage['id'] = $MessageSid;
					$xMessage['estatus'] = 'delivery';
					file_put_contents(__DIR__ . '/callback/facebook_' .  $message['sender']['id']  . '_' . $sec, json_encode($xMessage));
					file_put_contents(__DIR__ . '/log/query_sql.txt', print_r( $query, true));
				}						
                continue;
            }

            if (!empty($message['read'])) {
				$nf = glob(__DIR__ . '/callback/facebook_' . $message['sender']['id']  .'_*');
				$t = sizeof($nf) +1;
				$sec = str_pad($t, 7, "0", STR_PAD_LEFT);
				$xMessage['sender_id'] = $message['sender']['id'];
				$xMessage['estatus'] = 'read';
				file_put_contents(__DIR__ . '/callback/facebook_' .  $message['sender']['id']  . '_' . $sec, json_encode($xMessage));
				file_put_contents(__DIR__ . '/log/query_sql.txt', print_r( $query, true));
                continue;
            }

            if (($message['message']['is_echo'] == "true")) {
				
				
                continue;
			}
			
			$mensaje 	= 'NULL';
			$url 		= 'NULL';
			$latitud 	= 'NULL';
			$longitud 	= 'NULL';


			$mensaje = str_replace("'", "''", quitar_tildes($message['message']['text'] ));


			$API_CREACION_FECHA = 'NULL';
			$API_CREACION_HORA  = 'NULL';
			$API_UPDATE_FECHA = 'NULL';
			$API_UPDATE_HORA  = 'NULL';

			$via  	 = $_REQUEST['via'] ? $_REQUEST['via'] : 'IN';
			$id_m_usuarios  = $_REQUEST['id_m_usuarios'] 	? "'" . $_REQUEST['id_m_usuarios'] . "'" 	: 'NULL';
			$identificador  = $message['sender']['id'];

			
			$user = $bot->userProfile($message['sender']['id']);


			$canal  		= "'FACEBOOK'";
			$nick  			= $user->getFirstName() 		? "'" . strtoupper(utf8_decode($user->getFirstName()) . ' '.  utf8_decode($user->getLastName()))  . "'" : 'NULL';
			$canal_usuario 	= $_REQUEST['canal_usuario'] 	? "'" . $_REQUEST['canal_usuario'] . "'" 	: 'NULL';
			$descripcion	= $_REQUEST['descripcion'] 		? "'" . $_REQUEST['descripcion'] . "'" 		: 'NULL';

			if($via=='IN'){
				$estatus='RECEIVED'; 
			} else {
				$estatus='PENDIENTE';
			}
			
			$COMANDO = 'NULL';
			if(substr($mensaje, 0, 1)=='/'){
				$COMANDO = "'" . strtoupper(trim(substr($mensaje, 1))). "'";
			}			
			

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
			$sql .= "'" . $message['message']['mid'] ."',";
			$sql .= "'" . FB_ORIGEN . "',";
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

			file_put_contents(__DIR__ . '/log/facebook_receptor_sql.txt', $sql . "\n", FILE_APPEND);
			$query->sql=$sql;
			$query->ejecuta_query();

			file_put_contents(__DIR__ . '/log/facebook_query_sql.txt', print_r( $query, true));			

        }
    }
}