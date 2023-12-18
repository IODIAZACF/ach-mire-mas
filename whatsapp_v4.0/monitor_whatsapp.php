#!/usr/bin/php

<?php

define('DIR_DATA_DOWNLOAD', dirname(__DIR__,1) . '/whatsapp_download');

while(1){
	if( file_exists( __DIR__ . '/config.json' ) ) {
		$config =  json_decode( file_get_contents( __DIR__ . '/config.json' ), true );
		foreach($config['dispositivos'] as $numero => $dispositivo){
		//print_r ($dispositivo);
			$puerto				= $dispositivo['puerto'];
			$identificador 		= $dispositivo['identificador'];
			$estatus_servicio	= $dispositivo['estatus_servicio'];
			@mkdir( DIR_DATA_DOWNLOAD .'/' . $identificador, 0777, true);
			if($estatus_servicio=='CORRIENDO'){
				$res = estatus($puerto, $identificador);
				if($res['estatus']=='NO_RUN'){
					start_whatsapp($identificador, $puerto);
				}			
			}		
		}
	}
sleep(2);
}

function estatus($port, $identificador){
	$respuesta = array();
	$respuesta['estatus']='NO_RUN';
	/*
		ps -aux|grep "whatsapp" |grep -v grep | awk {'print $14 "|" $15 "|" $16 "|" $2 "|" $3 "|" $4'}
		/opt/lampp/htdocs/whatsapp/Example/whatsapp.ts|4001|593962184978|44760|0.0|2.9	
	*/	
	$command = 'ps -aux|grep "whatsapp" |grep -v grep | awk {\'print $14 "|" $15 "|" $16 "|" $2 "|" $3 "|" $4\'}';
	exec($command, $procesos);
	foreach($procesos as $proceso){
		$pID = explode('|',$proceso);
		if($pID[1]==$port && substr($pID[0],-3)=='.ts' && $pID[2]==$identificador){
			$respuesta['estatus']='IS_RUN';
			$respuesta['comando'] = $pID[0];
			$respuesta['port'] = $pID[1];
			$respuesta['identificador'] = $pID[1];
			$respuesta['pid'] = $pID[3];
			$respuesta['cpu'] = $pID[4];
			$respuesta['mem'] = $pID[5];
		}
	}
	return $respuesta;
}

function stop_whatsapp($pID, $port){
	$command = 'kill '. $pID;
	exec($command);
	sleep(1);
	$res = estatus($port);
	if($res['estatus']=='NO_RUN'){
		return true;
	}
	return false;
}

function start_whatsapp($identificador, $port){
	//$command = 'nohup node --inspect -r ts-node/register '. __DIR__ .'/Example/whatsapp.ts '. $port .' ' . $identificador .' > /dev/null 2>&1 & echo $!';
	$command = 'nohup node -r ts-node/register '. __DIR__ .'/Example/whatsapp.ts '. $port .' ' . $identificador .' > /dev/null 2>&1 & echo $!';
	exec($command);
	sleep(1);
}

?>
