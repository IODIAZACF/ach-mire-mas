#!/usr/bin/php

<?php	
set_time_limit(0);
system('clear');
system("printf '\033[3J'");

defined('Debug') 			or define('Debug',			isset($_REQUEST['debug']) ? $_REQUEST['debug'] : 0 );
defined('Server_Path') 		or define('Server_Path',			dirname(__DIR__ ,1));
defined('path_Utilidades') 	or define('path_Utilidades',		'/opt/lampp/utilidades/');
defined('DB') 				or define('DB',	basename(Server_Path));

include_once (path_Utilidades 	. 'php/clases/class_sql.php');	

@mkdir(Email_Spool . '/', 0777, true);
@chmod(Email_Spool . '/', 0777);


echo "Iniciando Lectura ...\n";

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/". DB .".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();

$xRecord = array();
$sql = "SELECT * FROM D_CORREO WHERE ESTATUS='CONFIRMADO'";
$query->sql = $sql;
$query->ejecuta_query();
while($query->next_record()){
	$xRecord[] = $query->Record;
}
for($i=0;$i<sizeof($xRecord);$i++){
	$Record = $xRecord[$i];	
	echo "Enviando...." . $Record['ID_D_CORREO'] . ' --> ' . $Record['DESTINATARIO'] ;
	exec(__DIR__ . '/correo_crear.php '. $Record['ID_D_CORREO'], $resp, $estatus);
	//echo PHP_EOL . __DIR__ . '/correo_crear.php '. $Record['ID_D_CORREO'] . PHP_EOL;
	//print_r (  $resp ) ;
	//die();

	if($estatus=='1'){
		$message = json_decode($resp[1]);
		$sql ="UPDATE D_CORREO SET ESTATUS = 'PENDIENTE' , COMENTARIOS=NULL WHERE ID_D_CORREO= '" .  $Record['ID_D_CORREO'] . "'";
		echo "---> OK \n";
	}else{
		$sql ="UPDATE D_CORREO SET ESTATUS = 'ERROR', COMENTARIOS='ERROR AL GENERAR EL CORREO ' WHERE ID_D_CORREO= '" .  $Record['ID_D_CORREO'] . "'";
		echo "---> ERROR \n";
	}
	eSQL($sql);
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
