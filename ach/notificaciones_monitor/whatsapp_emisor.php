#!/usr/bin/php

<?php
system('clear');
system("printf '\033[3J'");

include __DIR__  . '/config.php';

include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');

include_once (UTIL_PATH . 'utilidades/php/clases/class_ini.php');
include_once (UTIL_PATH . 'utilidades/php/clases/comun.php'); 

@mkdir(__DIR__ . '/mensajes/whatsapp/pendientes', 0777, true);
@mkdir(__DIR__ . '/mensajes/whatsapp/error', 0777, true);
@mkdir(__DIR__ . '/mensajes/whatsapp/enviados', 0777, true);



while(1){	

	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/" . DB . ".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();
		
	$sql = "SELECT * FROM D_CHAT WHERE ESTATUS='PENDIENTE' AND ORIGEN='" . WHATSAPP_TWILIO_ORIGEN . "' AND CANAL='WHATSAPP' AND FECHA_ENVIO <= CAST('NOW' AS TIMESTAMP)";
	$query->sql = $sql;
	$query->ejecuta_query();
	$xRecord = array();
	while($query->next_record()){
		$xRecord[] = $query->Record;	
	}
	
	$query->commit();
	$query->close();
	unset($query);
	
	//print_r($xRecord);
	for($i=0;$i<sizeof($xRecord);$i++)
	{
		$Record = $xRecord[$i];	
		echo "Enviando...." . $Record['ID_D_CHAT'];
		if($Record['XMENSAJE']==''){
			$sql2 ="UPDATE D_CHAT SET ESTATUS = 'ERROR' WHERE ID_D_CHAT= '" .  $Record['ID_D_CHAT'] . "'";
			echo "---> ERROR SIN MENSAJE \n";
		}else{
			$sms['Tipo'] = $Record['RESPUESTA'];	
			$sms['To']   = $Record['IDENTIFICADOR'];
			$sms['Url'] =  $Record['URL'];
			$sms['Body'] = utf8_encode($Record['XMENSAJE']);
			$mensaje =  json_encode($sms,JSON_PRETTY_PRINT);
			file_put_contents(__DIR__ . '/mensajes/whatsapp/pendientes/' . $Record['ID_D_CHAT'] , $mensaje);
			unset($resp);
			$estatus = null;
			exec(__DIR__ . '/whatsapp_send.php '. $Record['ID_D_CHAT'], $resp, $estatus);
			if($estatus=='1'){
				$message = json_decode($resp[1]);
				$sql2 ="UPDATE D_CHAT SET ESTATUS = 'ENVIADO', MENSAJE_ID='". $message->id ."' WHERE ID_D_CHAT= '" .  $Record['ID_D_CHAT'] . "'";					
				echo "---> OK \n";
			}else{
				$sql2 ="UPDATE D_CHAT SET ESTATUS = 'ERROR' WHERE ID_D_CHAT= '" .  $Record['ID_D_CHAT'] . "'";								
				echo "---> ERROR \n";
			}
		}		
		eSQL($sql2);
	}	
	
	$equery = new sql();
	$equery->DBHost     = "127.0.0.1";
	$equery->DBDatabase = "/opt/lampp/firebird/db/" . DB . ".gdb";
	$equery->DBUser     = "SYSDBA";
	$equery->DBPassword = "masterkey";
	$equery->Initialize();
	
	$equery->wait('NEW_MENSAJE_WHATSAPP');	
	
	$equery->close();
	unset($equery);
}

?>