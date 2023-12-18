#!/usr/bin/php

<?php
system('clear');
system("printf '\033[3J'");

include dirname(__FILE__) . '/config.php';

//$origen  = $_REQUEST['origen'];
$origen  = '14155238886';
$db = 'pagotoday';

include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');

include_once (UTIL_PATH . 'utilidades/php/clases/class_ini.php');
include_once (UTIL_PATH . 'utilidades/php/clases/comun.php'); 
//require('simple_html_dom.php');


@mkdir(__DIR__ . '/mensajes/sms/pendientes', 0777, true);
@mkdir(__DIR__ . '/mensajes/sms/error', 0777, true);

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/" . DB . ".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();

$sql = "SELECT * FROM D_SMS WHERE ESTATUS='PEN'";
$query->sql = $sql;
$query->ejecuta_query();
$xRecord = array();
while($query->next_record()){
	$xRecord[] = $query->Record;	
}
//print_r($xRecord);
for($i=0;$i<sizeof($xRecord);$i++)
{
	$Record = $xRecord[$i];	
	echo "Enviando...." . $Record['ID_D_SMS'];
	if($Record['MENSAJE']==''){
		$sql2 ="UPDATE D_SMS SET ESTATUS = 'ERR' WHERE ID_D_SMS= '" .  $Record['ID_D_SMS'] . "'";
		echo "---> ERROR SIN MENSAJE \n";
	}else{
		$sms['To']   = $Record['NUMERO'];
		$sms['Body'] = utf8_encode($Record['MENSAJE']);
		$mensaje =  json_encode($sms,JSON_PRETTY_PRINT);
		file_put_contents(__DIR__ . '/mensajes/sms/pendientes/' . $Record['ID_D_SMS'] , $mensaje);
		exec(__DIR__ . '/sms_send.php '. $Record['ID_D_SMS'], $resp, $estatus);
		if($estatus=='1'){
			$message = json_decode($resp[1]);
			$sql2 ="UPDATE D_SMS SET ESTATUS = 'ENV', RESPUESTA='OK', MENSAJE_ID='". $message->id ."' WHERE ID_D_SMS= '" .  $Record['ID_D_SMS'] . "'";					
			echo "---> OK \n";
		}else{
			$sql2 ="UPDATE D_SMS SET ESTATUS = 'ERR' WHERE ID_D_SMS= '" .  $Record['ID_D_SMS'] . "'";								
			echo "---> ERROR \n";
		}
	}
	
	$query->sql = $sql2;
	$query->ejecuta_query();	
	
}

?>