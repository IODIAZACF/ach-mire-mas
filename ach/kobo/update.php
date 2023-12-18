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

$sql =   "SELECT REFERENCIA, ID_M_FORMULARIOS, ID, XROTULO, ID_P_FORMULARIOS FROM P_FORMULARIOS  ORDER BY ID_M_FORMULARIOS, ID ASC";
$query->sql = $sql;
$query->ejecuta_query();

$form = array();
while($query->next_record()){
	$fid = $query->Record['ID_M_FORMULARIOS'];
	$pid = $query->Record['ID_P_FORMULARIOS'];
	$ref = $query->Record['REFERENCIA'];
	
	
	$nref = array_pop( explode('/', $ref ) );
	
	echo PHP_EOL . $ref .PHP_EOL;
	echo PHP_EOL . $nref .PHP_EOL;
	
	$uid = $nref;
	
	if( !isset( $form[ $fid ][ $uid ] )) {
		$form[ $fid ][ $uid ]['PRIMER_ID']	= $pid;
	}else{
		$form[ $fid ][ $uid ]['ELIMINAR'][]	= $pid;
	}
	
	$form[ $fid ][ $uid ]['ID_M_FORMULARIOS']	= $fid;
	$form[ $fid ][ $uid ]['REFERENCIA']		= $nref;
	$form[ $fid ][ $uid ]['XROTULO']		= $uid;
	
}

file_put_contents ( __DIR__ .'/p_formularios.sql',"");

foreach( $form as $fid => $preg ){
	foreach ( $preg  as $ref){
		print_r ( $ref );
		$sql = "UPDATE P_FORMULARIOS SET REFERENCIA='". $ref['REFERENCIA'] ."' WHERE ID_M_FORMULARIOS='". $ref['ID_M_FORMULARIOS'] ."' AND ID_P_FORMULARIOS='". $ref['PRIMER_ID'] ."';/*" .  trim($ref['XROTULO'])  . "*/";
		file_put_contents ( __DIR__ .'/p_formularios.sql', $sql . PHP_EOL, FILE_APPEND );

		if( isset ( $ref['ELIMINAR'] ) ){
			foreach ( $ref['ELIMINAR']  as $id => $xid){
				
				$sql = "UPDATE M_KOBO_RESPUESTAS SET ID_P_FORMULARIOS = '". $ref['PRIMER_ID'] ."' WHERE ID_M_FORMULARIOS='". $ref['ID_M_FORMULARIOS'] ."' AND ID_P_FORMULARIOS='". $xid ."';/*" .  trim($ref['XROTULO'])  . "*/";
				file_put_contents ( __DIR__ .'/p_formularios.sql', $sql . PHP_EOL, FILE_APPEND );
				
				$sql = "DELETE FROM P_FORMULARIOS  WHERE ID_M_FORMULARIOS='". $ref['ID_M_FORMULARIOS'] ."' AND ID_P_FORMULARIOS='". $xid ."';/*" .  trim($ref['XROTULO'])  . "*/";
				file_put_contents ( __DIR__ .'/p_formularios.sql', $sql . PHP_EOL, FILE_APPEND );
			}			
		}
		file_put_contents ( __DIR__ .'/p_formularios.sql',  PHP_EOL . PHP_EOL , FILE_APPEND );
	}	
	
}

file_put_contents ( __DIR__ .'/p_formularios.txt', print_r ( $form, true) );


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