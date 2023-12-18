#!/usr/bin/php

<?php
include_once ( __DIR__ . '/config.php');
system('clear');

while(1)
{	
	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();
	
	$query->sql ="SELECT * FROM V_SRI_DOCUMENTOS WHERE AUTORIZADO ='NO' AND INTENTO < 1 ORDER BY INTENTO ASC ROWS 50";	
	$query->ejecuta_query();
	$nc=1;
	while($query->next_record())
	{
		$Record[] = $query->Record;
	}
	echo "Listo\n";
    if(isset($Record))
    {
		$total_cc 			= sizeof($Record);
		$solicitud_actual 	= 0;
		for($i=0;$i<sizeof($Record);$i++)
		{
			echo "Autorizando...". $Record[$i]['TABLA'] . "   " . $Record[$i]['SRI_SECUENCIA'] ."\n";
			if($Record[$i]['TIPO']=='FAC'){
				$resp= system(__DIR__ . '/cmd_factura_electronica.php ' .  $Record[$i]['IDX'], $ret);
				sleep(1);
			}
			if($Record[$i]['TIPO']=='REM'){
				$resp= system(__DIR__. '/cmd_retenciones_electronica.php ' .  $Record[$i]['IDX'], $ret);
				sleep(1);
			}
			if($Record[$i]['TIPO']=='NCC'){
				$resp= system(__DIR__. '/cmd_nota_credito_electronica.php ' .  $Record[$i]['IDX'], $ret);
				sleep(1);
			}
			
		}
		unset($Record);	
	}	
    unset($query);
	die();
}
?>