<?php
header("Access-Control-Allow-Origin:*");

include dirname(__FILE__) . '/config.php';
include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');
include_once (UTIL_PATH . 'utilidades/php/clases/class_ini.php');
include_once (UTIL_PATH . 'utilidades/php/clases/comun.php'); 

//file_put_contents('./query.txt', print_r( $_REQUEST, true));

$origen  = $_REQUEST['origen'];
$mensaje = addcslashes(quitar_tildes($_REQUEST['mensaje']), "'");
$via  	 = $_REQUEST['via'] ? $_REQUEST['via'] : 'IN';
$id_m_usuarios  = $_REQUEST['id_m_usuarios'] 	? "'" . $_REQUEST['id_m_usuarios'] . "'" 	: 'NULL';
$identificador  = $_REQUEST['identificador'] 	? "'" . $_REQUEST['identificador'] . "'" 	: 'NULL';
$canal  		= $_REQUEST['canal'] 			? "'" . $_REQUEST['canal'] . "'" 			: 'NULL';
$nick  			= $_REQUEST['nick'] 			? "'" . $_REQUEST['nick'] . "'" 			: 'NULL';
$canal_usuario 	= $_REQUEST['canal_usuario'] 	? "'" . $_REQUEST['canal_usuario'] . "'" 	: 'NULL';
$descripcion	= $_REQUEST['descripcion'] 		? "'" . $_REQUEST['descripcion'] . "'" 		: 'NULL';

if ($via=='IN'){
	$estatus='REC'; 
} else {
	$estatus='PEN';
}

if($mensaje=='') die('error');

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/" . DB . ".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();

$sql = "INSERT INTO D_WHATSAPP (ORIGEN, MENSAJE, ESTATUS, VIA,ID_M_USUARIOS, IDENTIFICADOR,CANAL,NICK,CANAL_USUARIO,DESCRIPCION) VALUES ('" . $origen . "','" . $mensaje . "', '" . $estatus . "', '". $via ."', ". $id_m_usuarios .", ". $identificador .",". $canal .",". $nick .",". $canal_usuario .",". $descripcion .")";

//file_put_contents(__DIR__ .'', $persona, FILE_APPEND | LOCK_EX);
file_put_contents(dirname(__FILE__) . '/log/receptor_sql.txt', $sql . "\n", FILE_APPEND);
$query->sql=$sql;
$query->ejecuta_query();
//file_put_contents(dirname(__FILE__) . '/log/SQL_receptor_sql.txt',  print($query, true), FILE_APPEND);

file_put_contents('./query_sql.txt', print_r( $query, true));

if(strlen($query->regi['ERROR'])){
	echo $query->regi['ERROR']."<br/>\n";
	die("HA OCURRIDO UN ERROR DE SQL<br/>\n");
}
?>