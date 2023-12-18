<?php
/*DATOS EMPRESA*/
define('DB'			, 'demo24');
define('EMPRESA'	, 'demo24');

/*DATOS RUTAS LOCALES */
define('UTIL_PATH'	, '/opt/lampp/');
define('SISTEMA'	, dirname(__DIR__));

/*DATOS URLS Y SERVIDORES */
define('PUSH_SERVER'	, '192.168.5.130');
define('PUSH_PORT'		, 8887);

$verify_token = '424639';
$token = '';

defined('Server_Path') 		or define('Server_Path',			dirname(__DIR__ ,1));

/*DATOS API FACEBBOK*/
define('FB_VERIFY_TOKEN'	, '424639');
define('FB_TOKEN' 			, 'EAAltcmBpDHUBAL1DWe8wv4u8r0D15BNMw1QTMPzoDsxSCmMo2OvXeezWP06n1ZBtiRGtjmnRNMdBc0s3Yt0NybJFOGXfU9Qhy427ZAeLoMx7IZBpgbKH2ssW4TrBs13wdLGnAQ3lZCAWzM9rI8F6I9YJxuZBKTeg3ge2pm8hkdAZDZD');
define('FB_ORIGEN' 			, 'sistemas24');


/*DATOS API TWITTER*/
define('TWITTER_CONSUMER_KEY' 		, 'c6k8N9cVNmekZKJ8qU3MKb3Te');
define('TWITTER_CONSUMER_SECRET' 	, '75Fuy1d09XVDdA5Vd4xuBn0MQRYft66NR4pjV24xQIBhdHimLY');
define('TWITTER_ACCESS_TOKEN'		, '160898285-RJ2fhu3myNRmsE7A6ioF8dtA4uOQET9waaT6gUWl');
define('TWITTER_ACCESS_TOKEN_SECRET', '5IYOSvzKTePSaQIQLmxil8XsdUXt6JD4nrFZwswXQ47r0');
define('TWITTER_COUNT_ID'			, '160898285');

/*DATOS API WHATSAPP TWILIO*/
define('WHATSAPP_TWILIO_SID'		, 'AC9112339121f3d259723e90d97a22c49b');
define('WHATSAPP_TWILIO_TOKEN' 		, '564dd03bc46f46584d166108c6010c21');
define('WHATSAPP_TWILIO_ORIGEN' 	, '14155238886');

/*DATOS API TELEGRAM*/
define('TELEGRAM_TOKEN'		, '1178837406:AAGh88U82MSdW3UCbEPBRZKs01b67gRyW68');
define('TELEGRAM_ORIGEN' 	, '@sistemas24bot');
define('TELEGRA_URL' 		, 'http://t.me/Sistemas24bot');
//configurar setWebhook
//https://api.telegram.org/bot1178837406:AAGh88U82MSdW3UCbEPBRZKs01b67gRyW68/setWebhook?url=https://vps24.dyndns.info/demo24/notificaciones_monitor/telegram_receptor.php

/*DATOS API livehelperchat*/
define('WWW_USER'	, 'admin');
define('WWW_KEY' 	, 'rune7780');
define('WWW_PATH' 	, 'demo24/web_soporte');


define('MAIL_DIR', '/opt/lampp/Maildir/');

@mkdir(MAIL_DIR,0777,true);
@mkdir(__DIR__ . '/log', 0777, true);



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
	
	echo "\n\n". $query2->sql . "\n\n";	
	while($ejecutar)
	{
		$i++;
		$ejecutar=0;
		$resp ='';
		$query2->beginTransaction();
		$query2->ejecuta_query();
		echo "ejecutando...";
		if(strlen($query2->erro_msg)>0)
		{
			$ejecutar=1;
			echo "\nERROR:\n";
			echo $query2->sql . "\n";
			echo $query2->erro_msg . "\n";
			echo "\nEjecutando $i de 5 :\n";
			$query2->erro_msg = '';
			$resp = $query2->erro_msg;
			sleep(3);
		}
		if($i>=5) $ejecutar=0;
	}
	echo "Listo\n";	
	$query2->commit();
	$query2->close();
    unset($query2);
	return $resp;
}

function exec_SQL($sql_query)
{	
	$query2 = new sql();
	$query2->DBHost     = "127.0.0.1";
	$query2->DBDatabase = "/opt/lampp/firebird/db/". DB .".gdb";
	$query2->DBUser     = "SYSDBA";
	$query2->DBPassword = "masterkey";
	$query2->Initialize();
	$resp = 0;
	$ejecutar=1;
	$i=0;
	$query2->sql = $sql_query . ' /* '. date('Y-m-d H:i:s') .' */' ;	
	
	echo "\n". $query2->sql . "\n";	
	while($ejecutar)
	{
		$i++;
		$ejecutar=0;
		$query2->beginTransaction();
		$query2->ejecuta_query();
		echo "ejecutando...";
		if(strlen($query2->erro_msg)>0)
		{
			$ejecutar=1;
			echo "\nERROR:\n";
			echo $query2->sql . "\n";
			echo $query2->erro_msg . "\n";
			echo "\nEjecutando $i de 5 :\n";
			$query2->erro_msg = '';
			sleep(3);
		}
		if($i>=5) $ejecutar=0;
	}
	echo "Listo\n";	
	$query2->commit();
	$resp = $query2->Reg_Afect;
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
