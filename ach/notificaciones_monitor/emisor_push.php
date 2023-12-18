#!/usr/bin/php

<?php
include dirname(__FILE__) . '/config.php';
include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');
system("clear && printf '\033[3J'");
$xRecord = array();

while(1)
{		
	enviar_mensajes();
	$fbquery = new sql();
	$fbquery->DBHost     = "127.0.0.1";
	$fbquery->DBDatabase = "/opt/lampp/firebird/db/". DB .".gdb";
	$fbquery->DBUser     = "SYSDBA";
	$fbquery->DBPassword = "masterkey";
	$fbquery->Initialize();
	
	echo " esperando...\n";
	
	$evento = $fbquery->wait('D_WHATSAPP');
	
	echo date("Y-m-d H:i:s") . " ----> " . $evento;
	$fbquery->close();
}

function enviar_mensajes()
{	
	$fbquery = new sql();
	$fbquery->DBHost     = "127.0.0.1";
	$fbquery->DBDatabase = "/opt/lampp/firebird/db/". DB .".gdb";
	$fbquery->DBUser     = "SYSDBA";
	$fbquery->DBPassword = "masterkey";
	$fbquery->Initialize();

	unset($xRecord);
	$fbquery->sql= "SELECT * FROM V_D_WHATSAPP_MONITOREO WHERE ESTATUS_PUSH='NUEVO'";
	$fbquery->ejecuta_query();
	
	while($fbquery->next_record())
	{	
		echo "  ". $fbquery->Record['MENSAJE'] . "\n";
		$xRecord[] = $fbquery->Record;
	}
	$fbquery->free_result();
	$fbquery->close();

	if(isset($xRecord))
	{ 
		//print_r($xRecord);
		for($i=0;$i<sizeof($xRecord);$i++)
		{		
			$Record = $xRecord[$i];
			$payload = array(
				'title' => 'CHAT-WHATSAPP',
				'msg' => 'Mensaje de SERVIDOR PUSH',
				'msg.en' => 'Message from PUSH SERVER'
			);			
			while (list($campo, $valor) = each($Record))
			{	
				$xcampo='data.'. $campo;
				$payload[$xcampo] = $valor;
			}
			$payload['data.NOMBRE_USUARIO'] 		= addcslashes(quitar_tildes($Record['NOMBRE_USUARIO']), "'");
			$payload['data.MENSAJE'] 				= addcslashes(quitar_tildes($Record['XMENSAJE']), "'");
			$payload['data.NOMBRE_USUARIO_HTML'] 	= htmlentities ($Record['NOMBRE_USUARIO']);
			$payload['data.MENSAJE_HTML'] 			= htmlentities ($Record['XMENSAJE']);
			$payload['data.FECHA']					= date("d/m/Y");
			$payload['data.HORA']  					= date("h:i:s a");

			print_r($payload);
			
			push('chat', $payload);
			$UID  = "NULL";
			switch ($Record['ORIGEN'])
			{				
				case 'WWW':					
					if($Record['VIA']=='IN'){
						//push($Record['IDENTIFICADOR'], $payload);
					}						
					if($Record['CANAL']=='CONSOLA'){
						push($Record['IDENTIFICADOR'], $payload);				
					}						
				break;
				case 'bot-sistemas24':
				case 'FBMESSENGER':
					if($Record['VIA']=='OUT'){
						$cmd = SISTEMA . '/notificaciones_monitor/facebook_emison.php "'. $Record['IDENTIFICADOR'] .'" "'. $payload['data.MENSAJE'] .'"';					                  
						echo $cmd . "\n";
						system($cmd);
					}						
				break;
				case 'TWITTER':
				case 'LUISMAN011':
					if($Record['VIA']=='OUT'){
						$cmd = SISTEMA . '/notificaciones_monitor/twitter_emisor.php "'. $Record['IDENTIFICADOR'] .'" "'. $payload['data.MENSAJE'] .'"';
						echo $cmd . "\n";
						$resp = system($cmd);
						if($resp!='ERROR'){
							$UID = "'" . $resp . "'";
						} 
					}						
				break;
				default:
					push($Record['IDENTIFICADOR'], $payload);				
			}				
			
			$sql ="UPDATE D_WHATSAPP SET CAMPO1=". $UID .",ESTATUS_PUSH = 'ENVIADO' WHERE ID_D_WHATSAPP= '" .  $Record['ID_D_WHATSAPP'] . "'";			
			eSQL($sql);
		}			
	}
}

function push($suscripcion, $payload){
	$msg = gzcompress('POST /event/' . urldecode($suscripcion) . '?' . http_build_query($payload));
	$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
	socket_sendto($socket, $msg, strlen($msg), 0, PUSH_SERVER, PUSH_PORT);
	socket_close($socket);	
}

?>