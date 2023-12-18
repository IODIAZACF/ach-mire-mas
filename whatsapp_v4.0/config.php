<?php
/*DATOS EMPRESA*/
define('DB'			, 'protecseguros');
define('EMPRESA'	, 'protecseguros');

/*DATOS RUTAS LOCALES */
define('UTIL_PATH'	, '/opt/lampp/');
define('SISTEMA'	, dirname(__DIR__));

/*DATOS URLS Y SERVIDORES */
define('HTTPS_URL'	, 'https://21c7434d.ngrok.io');
define('PUSH_SERVER'	, '192.168.1.150');
define('PUSH_PORT'		, 8887);

/*DATOS API FACEBBOK*/
$FB_CONFIG = array(
    'verify_token' => '424639',
    'token' => 'EAAH8PpuZBkTsBAApOxBbisZC4bSQ2jvPcKe3M4p9PgHdttJ0gqI1gvMZCaZCOXGSQEMFVYJXL2DyxcQ8QK86Hhyd3IY8X5v7tZCCPkR7E3xeS9t4GMFMlsrcxjOUhwDXGQdCUOamNzp5wGcC9D3YvAUZAyqZCZCvdHpLKlfsBrQMM0enT9inb3Nf'
);

/*DATOS API TWITTER sistemas24*/
define('TWITTER_CONSUMER_KEY' 		, 'DZ5sqFj7oYtxQrzwwtB6ROAvB');
define('TWITTER_CONSUMER_SECRET' 	, 'yMCSXrNzyZDT5h8E3WGA0MSajp28tcxFO7thuZK7aHLPuh8PIi');
define('TWITTER_ACCESS_TOKEN'		, '160898285-leLYY0CcnIydfucobu3c8X3FvDZ8GyNOXb9zbDuI');
define('TWITTER_ACCESS_TOKEN_SECRET', '4RkjsrRlcGmq2fqKI4mffR5KhiTb9iK9k08SzKjHYsIiW');
define('TWITTER_COUNT_ID'			, '160898285');

/*DATOS API WHATSAPP TWILIO*/
define('WHATSAPP_TWILIO_SID'		, 'AC9112339121f3d259723e90d97a22c49b');
define('WHATSAPP_TWILIO_TOKEN' 		, '564dd03bc46f46584d166108c6010c21');
define('WHATSAPP_TWILIO_ORIGEN' 	, '18452445899');
define('WHATSAPP_API' 	, 'baileys');

/*DATOS TELEGRAM*/
//configurar setWebhook
//https://api.telegram.org/bot5204388486:AAGA2Bal-knOBu1HdJkUNSwIOXcEMSMbRFw/setWebhook?url=https://desarrollo.sistemas24.com/protecseguros/notificaciones_monitor/telegram_receptor.php
define('TELEGRAM_BOT'		, '@PROTECSEGUROS_BOT');
define('TELEGRAM_TOKEN'		, '5204388486:AAGA2Bal-knOBu1HdJkUNSwIOXcEMSMbRFw');

/*DATOS WWW*/
//configurar setWebhook
//https://api.telegram.org/bot5204388486:AAGA2Bal-knOBu1HdJkUNSwIOXcEMSMbRFw/setWebhook?url=https://desarrollo.sistemas24.com/protecseguros/notificaciones_monitor/telegram_receptor.php
define('WWW_DOMINIO'		, 'SISTEMAS24.COM');
define('WWW_USER'	, 'admin');
define('WWW_KEY' 	, 'rune7780');
define('WWW_PATH' 	, 'http://127.0.0.1/protecseguros');


define('MAIL_DIR', '/opt/lampp/Maildir/');
@mkdir(MAIL_DIR,0777,true);


function eSQL($sql_query)
{	
	$query2 = new sql();
	$query2->DBHost     = "127.0.0.1";
	$query2->DBDatabase = "/opt/lampp/firebird/db/". DB .".gdb";
	$query2->DBUser     = "SYSDBA";
	$query2->DBPassword = "masterkey";
	$query2->Initialize();
	
	$ejecutar=1;
	$i=0;
	$query2->sql = $sql_query . ' /* '. date('Y-m-d H:i:s') .' */' ;	
	
	//echo "\n\n". $query2->sql . "\n\n";	
	while($ejecutar)
	{
		$i++;
		$ejecutar=0;
		$resp ='';
		$query2->ejecuta_query();
		//echo "ejecutando...";
		if(strlen($query2->erro_msg)>0)
		{
			$ejecutar=1;
			//echo "\nERROR:\n";
			//echo $query2->sql . "\n";
			//echo $query2->erro_msg . "\n";
			//echo "\nEjecutando $i de 5 :\n";
			$query2->erro_msg = '';
			$resp = $query2->erro_msg;
			sleep(3);
		}
		if($i>=5) $ejecutar=0;
	}
	//echo "Listo\n";	
	$query2->close();
    unset($query2);
	return $resp;
}

function MDY($fecha) {

	$arr = explode('-', $fecha);
	return $arr[2].'/'.$arr[1].'/'.$arr[0];		
}

function quitar_tildes($cadena) {
	$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
	$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
	$texto = str_replace($no_permitidas, $permitidas ,$cadena);
	return $texto;
}




function Msg_push($data){	
	$registro = array(
		'title' => 'Nuevo Mensaje',
		'msg' => 'Mensaje de SERVIDOR PUSH',
		'msg.en' => 'Message from PUSH SERVER',
		'msg.script' => $_SERVER['SCRIPT_NAME']);
		
	foreach($data as $campo=>$valor){
		$registro['data.' . $campo] = $valor;
		
	}
	
	$pushdHost = '127.0.0.1'; 	// IP O DNS DEL SERVIDOR PUSH
	$pushdPort = 8887; 			// PUERTO DEL SERVIDOR PUSH
	$eventName = 'chat';
	
	$msg = gzcompress('POST /event/' . urldecode($eventName) . '?' . http_build_query($registro));
	$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
	socket_sendto($socket, $msg, strlen($msg), 0, $pushdHost, $pushdPort);
	socket_close($socket);
	
	
	
}