#!/usr/bin/php

<?php
system('clear');
system("printf '\033[3J'");
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//header("Access-Control-Allow-Origin:*");
//$param = file_get_contents('php://input');

include __DIR__  . '/config.php';
include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');
include_once (UTIL_PATH . 'utilidades/php/clases/class_ini.php');
include_once (UTIL_PATH . 'utilidades/php/clases/comun.php'); 


while(1){
	unset($xFiles);
	$xFiles = glob(__DIR__ .'/callback/*');

	for($i=0;$i<sizeof($xFiles);$i++)
	{
		$xFile = basename($xFiles[$i]);
		$canal =  explode('_', $xFile)[0];
		$sql ='';
		echo "Proceando...  $xFile\n";
		if($canal=='whatsapp'){
			$message = json_decode(file_get_contents($xFiles[$i]));
			//print_r($message);	
			$sql = "UPDATE D_CHAT SET ESTATUS='". strtoupper($message->MessageStatus) ."' WHERE  MENSAJE_ID='". $message->MessageSid ."'";
		}
		
		if($canal=='facebook'){
			$message = json_decode(file_get_contents($xFiles[$i]));
			if($message->estatus=='delivery'){
				$sql = "UPDATE D_CHAT SET ESTATUS='". strtoupper($message->estatus) ."' WHERE  MENSAJE_ID='". $message->id ."'";				
			}
			if($message->estatus=='read'){
				$sql = "UPDATE D_CHAT SET ESTATUS='READ' WHERE  IDENTIFICADOR='". $message->sender_id ."' AND (ESTATUS='DELIVERY' OR ESTATUS='READ')";
			}
		}
		if($sql!=''){
			$resp = exec_SQL($sql);
			echo "Re SQL $resp\n";
			if($resp){
				@unlink($xFiles[$i]);
			}			
		}	
	}
	//sleep(2);
	echo "Esperando cambios...\n";
	exec('watch -d -t -g ls -lR '. __DIR__ .'/callback');
}


//print_r($xFiles);


?>