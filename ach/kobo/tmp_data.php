#!/usr/bin/php

<?php
define('DB', 'ach');

$xpath = '/opt/lampp/utilidades';
$xtmp  = '/opt/tmp/';

include_once ($xpath . '/php/clases/class_sql.php');

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/" . DB . ".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();


$data =  glob(__DIR__ .'/tmp_data/*');
foreach($data as $id=> $archivo){
	$recibe = file_get_contents($archivo);
	$param = json_decode($recibe);
	
	$sql = "SELECT * FROM M_FORMULARIOS WHERE UID='". $param->_xform_id_string ."' AND ID_M_FORMULARIOS IN ('0011', '0012','0013')";
	$query->sql = $sql;
	$query->ejecuta_query();
	if($query->next_record()){
		$sql = "SELECT  * FROM M_KOBO_FORMULARIOS where fuid='".  $param->_xform_id_string ."' and uid ='". $param->_id ."'";
		$query->sql = $sql;
		$query->ejecuta_query();
		if(!$query->next_record()){
			
			foreach($param as $pregunta => $valor){
				if(is_array($valor) || is_object($valor)) continue;

				$valor = str_replace('á', 'a', $valor);	
				$valor = str_replace('é', 'e', $valor);	
				$valor = str_replace('í', 'i', $valor);	
				$valor = str_replace('ó', 'o', $valor);	
				$valor = str_replace('ú', 'u', $valor);	

				$valor = str_replace('Á', 'A', $valor);	
				$valor = str_replace('É', 'E', $valor);	
				$valor = str_replace('Í', 'I', $valor);	
				$valor = str_replace('Ó', 'O', $valor);	
				$valor = str_replace('Ú', 'U', $valor);
				
				$sql =   "SELECT * FROM TMP_RESPUESTAS  WHERE UID = '". $param->_id . "' AND REFERENCIA='/data/".  $pregunta ."'";
				$query->sql = $sql;
				$query->ejecuta_query();
				if(!$query->next_record()){	
					$sql = 	"INSERT INTO TMP_RESPUESTAS (";
					$sql.= 	"FUID";
					$sql.= 	",REFERENCIA";
					$sql.= 	",UID";
					$sql.= 	",VALOR_C";
					$sql.=	",ESTATUS";   
					$sql.=	") VALUES (";
					$sql.=	"'".  $param->_xform_id_string ."'";
					$sql.=	",'/data/".  $pregunta ."'";	
					$sql.=	",'".  $param->_id ."'";
					$sql.=	",'".  utf8_decode($valor) ."'";	
					$sql.=	",'". 'ACT' ."'";	
					$sql.=	")";

					$tSQL[] = $sql;
				}else{
					die($sql);
				}
			}
			foreach($tSQL as $sql){
				exec_SQL($sql);
			}

			$sql = "UPDATE TMP_RESPUESTAS SET CAMPO1='X' WHERE FUID='". $param->_xform_id_string ."' AND ID_M_FORMULARIOS IS NULL";
			exec_SQL($sql);
		}
	}

}
function exec_SQL($sql_query){
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



?>