<?php

echo "<pre>";

define('path_Utilidades',   '/opt/lampp/utilidades/');
include_once (path_Utilidades 	. 'php/clases/class_sql.php');

$db =$_SESSION['db'];

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/". $db .".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();
	
@mkdir(dirname(__DIR__, 1) . '/documentos_electronicos/solicitud_firma/' . $_REQUEST['ID_M_SOLICITUDES'] .'/', 0777);
	

/******************** ITEM ******************************/
$RUTA = dirname(__DIR__, 1) . "/documentos_electronicos/solicitud_firma/" . $_REQUEST['ID_M_SOLICITUDES'] . '/' . $_FILES["ARCHIVO"]["name"];
move_uploaded_file($_FILES["ARCHIVO"]["tmp_name"], $RUTA);


    $sql = "INSERT INTO D_ARCHIVOS(";
	$sql.= "IDX,";	
	$sql.= "TABLA,";
	$sql.= "RUTA";
	$sql.= ") VALUES (";
	$sql.= "'". $_REQUEST['ID_M_SOLICITUDES'] ."',";
	$sql.= "'M_SOLICITUDES',";
	$sql.= "'". $RUTA ."'";
	$sql.= ")";
    $query->sql =  $sql;
    $query->ejecuta_query();
    if($query->erro_msg!='')
    {
		echo $query->erro_msg;
    	die('Error: ' . $sql);
    }

echo " OK\n";
?>