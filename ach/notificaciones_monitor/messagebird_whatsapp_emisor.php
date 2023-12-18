#!/usr/bin/php

<?php
system('clear');
system("printf '\033[3J'");

include dirname(__FILE__) . '/config.php';

//$origen  = $_REQUEST['origen'];
$origen  = '447418310508';
$db = 'pagotoday';

include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');

include_once (UTIL_PATH . 'utilidades/php/clases/class_ini.php');
include_once (UTIL_PATH . 'utilidades/php/clases/comun.php'); 
//require('simple_html_dom.php');

require __DIR__ . '/vendor/autoload.php';
$messageBird = new \MessageBird\Client('cQnSwbFuJBZ6sON0z2Ph83eVm', null, [\MessageBird\Client::ENABLE_CONVERSATIONSAPI_WHATSAPP_SANDBOX]);

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

$sql = "SELECT * FROM D_WHATSAPP WHERE ESTATUS='PEN' AND ORIGEN='" . $origen . "' AND IDENTIFICADOR IN ('593960163222')";
//$sql = "SELECT * FROM D_WHATSAPP WHERE id_d_whatsapp='001102'";

$query->sql = $sql;
$query->ejecuta_query();

if($query->next_record())
{
	$tmp['ID'] 				= $query->Record['ID_D_WHATSAPP'];
	$tmp['IDENTIFICADOR'] 	= $query->Record['IDENTIFICADOR'];
	$tmp['RESPUESTA'] 		= $query->Record['RESPUESTA'];
	$tmp['URL'] 			= $query->Record['URL'];
	$tmp['MENSAJE'] 		= $query->Record['XMENSAJE'];
	
	$registro['tabla']['registro'] = $tmp;
	
	//echo json_encode(utf8ize($registro));

	//echo $query->Record['XMENSAJE'];

	$content = new \MessageBird\Objects\Conversation\Content();
	$message = new \MessageBird\Objects\Conversation\Message();
	
	switch ($query->Record['RESPUESTA']) {
		case 'MENSAJE':
			$message->type = 'text';
			$content->text = utf8_encode($query->Record['XMENSAJE']);// 'Hello world2';
			break;
		case 'IMAGEN':
			// https://sistemas24.dyndns.info/pagotoday/control_transferencias/base64_img.php?imagen=0012164;0012096			
			$xurl =  explode('=', $query->Record['URL']);			
			$t = explode(';', $xurl[1]);
			//$t[0] = '0012165';
			//$t[1] = '0012098';			
			$message->type = \MessageBird\Objects\Conversation\Content::TYPE_IMAGE; // 'image'
			$content->image = array(
				'caption' => utf8_encode($query->Record['XMENSAJE']),
				'url' => 'https://sistemas24.dyndns.info/pagotoday/imagenes/transferencias/'. $t[0] .'/'. $t[1]  .'.jpg'
			);
			break;
	}

	$message->channelId = '8eecc931db28425c9ae08e186e77aaad';
	$message->content = $content;
	//$message->to = $query->Record['IDENTIFICADOR'] ; //'593960163222'; // Channel-specific, e.g. MSISDN for SMS.
	$message->to = '593960163222'; // Channel-specific, e.g. MSISDN for SMS.
	

	try {
		$conversation = $messageBird->conversations->start($message);
		var_dump($conversation);
		$sql2 ="UPDATE D_WHATSAPP SET ESTATUS = 'EP' WHERE ID_D_WHATSAPP= '" .  $query->Record['ID_D_WHATSAPP'] . "'";
		$query2->sql = $sql2;
		$query2->ejecuta_query();
		
		file_put_contents(__DIR__ .'/log/send_'. $query->Record['IDENTIFICADOR']  .'.txt', print_r(json_decode($conversation), true), FILE_APPEND);
		
	} catch (\Exception $e) {
		echo sprintf("%s: %s", get_class($e), $e->getMessage());
		file_put_contents(__DIR__ .'/log/err_'. $query->Record['IDENTIFICADOR']  .'.txt', print_r($message, true));
		//print_r($message);
	}

	
	
} else {
	$tmp['ID'] = '0'; 
	$registro['tabla']['registro'] = $tmp;
	echo json_encode($registro);
	
}

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}
//echo "{'numero':'0979000333' , 'mensaje' : 'prueba de mensaje'}";
https://wa.me/447418310508?text=join%20prance%20primarily 

?>