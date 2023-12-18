#!/usr/bin/php

<?php
system('clear');
system("printf '\033[3J'");

include __DIR__ . '/config.php';

include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');

include_once (UTIL_PATH . 'utilidades/php/clases/class_ini.php');
include_once (UTIL_PATH . 'utilidades/php/clases/comun.php'); 

@mkdir(__DIR__ . '/mensajes/facebook/pendientes', 0777, true);
@mkdir(__DIR__ . '/mensajes/facebook/error', 0777, true);
@mkdir(__DIR__ . '/mensajes/facebook/enviados', 0777, true);

while(1){	
	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/" . DB . ".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();

	$sql = "SELECT * FROM D_CHAT WHERE ESTATUS='PENDIENTE' AND UPPER(ORIGEN)='" . strtoupper(FB_ORIGEN) . "' AND CANAL='FACEBOOK'  AND FECHA_ENVIO <= CAST('NOW' AS TIMESTAMP)";;
	

	$query->sql = $sql;
	$query->ejecuta_query();
	$xRecord = array();
	while($query->next_record()){
		$xRecord[] = $query->Record;	
	}
	
	$query->commit();
	$query->close();
	unset($query);
	
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
			file_put_contents(__DIR__ . '/mensajes/facebook/pendientes/' . $Record['ID_D_CHAT'] , $mensaje);	
			unset($resp);
			$estatus = null;
			exec(__DIR__ . '/facebook_send.php '. $Record['ID_D_CHAT'], $resp, $estatus);
			if($estatus=='1'){			
				$message = json_decode($resp[1]);
				$sql2 ="UPDATE D_CHAT SET ESTATUS = 'ENVIADO', MENSAJE_ID='". $message->id ."' WHERE ID_D_CHAT= '" .  $Record['ID_D_CHAT'] . "'";					
				echo "---> OK \n";
				print_r($message);
			}else{
				$message = json_decode($resp[1]);
				$sql2 ="UPDATE D_CHAT SET ESTATUS = 'ERROR', MENSAJE_ERROR='".  utf8_decode($message->msg)  ."' WHERE ID_D_CHAT= '" .  $Record['ID_D_CHAT'] . "'";
				echo "---> ERROR \n";				
				//die($sql2);
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
	
	$equery->wait('NEW_MENSAJE_FACEBOOK');	
	
	$equery->close();
	unset($equery);
}
	
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
	
?> 