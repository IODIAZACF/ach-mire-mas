#!/usr/bin/php

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include dirname(__FILE__) . '/config.php';
include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');
while(1){
	/*---------------------INICIO DE LECTURA DE MENSAJES----------*/
	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/". DB .".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();

	$cmd = 'twurl -X GET /1.1/direct_messages/events/list.json';
	$resp = exec($cmd);
	$mensajes = json_decode($resp);
	file_put_contents(dirname(__FILE__) . '/log/twitter_events_list.txt', print_r($mensajes, true));
	for($i=0;$i<sizeof($mensajes->events);$i++)
	{
		$msg = $mensajes->events[$i];
		/* ELIMINAR MENSAJES */
		/*
		$cmd = 'twurl -X DELETE "/1.1/direct_messages/events/destroy.json?id='. $msg->id .'"';
		echo $cmd . "\n";
		$resp = system($cmd);
		continue;
		*/
		//if($msg->message_create->sender_id == TWITTER_COUNT_ID) continue; 

		$fecha = new DateTime();
		$fecha->setTimestamp($msg->created_timestamp);
		
		echo "Hora              : " . $msg->created_timestamp . "\n";
		echo "identificador     : " . $msg->message_create->sender_id . "\n";
		echo "mensaje           : " . $msg->message_create->message_data->text .  "\n";
		echo "xHora             : " . $msg->created_timestamp . "\n";
		
		echo "--------------------------------------------------------------------------" . "\n";
		$UID  			= md5($msg->message_create->sender_id . $msg->id);
		
		
		$query->sql ="SELECT * FROM D_WHATSAPP WHERE MENSAJE_ID='".$UID ."'";
		$query->ejecuta_query();
		if(!$query->next_record()){	
			
			$VIA			= $msg->message_create->sender_id == TWITTER_COUNT_ID ? 'OUT' : 'IN';
			$IDENTIFICADOR 	= $msg->message_create->sender_id;		
			$cmd = 'twurl /1.1/users/show.json?id='. $msg->message_create->sender_id;
			$resp = exec($cmd);
			$user_info = json_decode($resp);
			//print_r($user_info); 
			
			$NOMBRE_USUARIO = $user_info->name;
			$DESCRIPCION 	= $user_info->description;
			$CANAL_USUARIO  = $user_info->screen_name;					
			$MENSAJE 		= trim($msg->message_create->message_data->text);			
					
			$sql   = "INSERT INTO D_WHATSAPP (";
			$sql  .= "VIA,"; 
			$sql  .= "IDENTIFICADOR,";
			$sql  .= "NICK, ";
			$sql  .= "MENSAJE,";
			$sql  .= "ORIGEN,";
			$sql  .= "ESTATUS,";
			$sql  .= "CANAL,";
			$sql  .= "CANAL_USUARIO,";
			$sql  .= "DESCRIPCION,";
			$sql  .= "MENSAJE_ID";
			$sql  .= ") VALUES(";
			$sql  .= "'"  . $VIA . "'";
			$sql  .= ",'" . $IDENTIFICADOR 	. "'";
			$sql  .= ",'" . $NOMBRE_USUARIO . "'";
			$sql  .= ",'" . $MENSAJE 		. "'";
			$sql  .= ",'" . "LUISMAN011" 	. "'";
			$sql  .= ",'" . "ENT" 			. "'";
			$sql  .= ",'" . "TWITTER"		. "'";
			$sql  .= ",'" . $CANAL_USUARIO	. "'";
			$sql  .= ",'" . $DESCRIPCION	. "'";
			$sql  .= ",'" . $UID 			. "'";
			$sql  .= ")";
			echo "\n\n" . $sql . "\n\n"; 
			$query->sql = $sql;
			$query->ejecuta_query();
			if(strlen($query->erro_msg)>0)
			{
				echo "\nERROR:\n";
				echo $query->sql . "\n";
				echo $query->erro_msg . "\n";
			}else
			{
				$cmd = 'twurl -X DELETE "/1.1/direct_messages/events/destroy.json?id='. $msg->id .'"';
				echo $cmd . "\n";
				$resp = system($cmd);
			}

			
		}			
	}
	$query->free_result();
	$query->close();
	unset($query);
	$espera = 90;
	echo "\n\n" . date("h:i:s") . "\n\n";
	for($j=1;$j<$espera;$j++){
		echo $j . " ";
		sleep(1);
	}
}

?>