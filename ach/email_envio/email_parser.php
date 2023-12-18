<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

//header('Content-Type: text/html; charset=iso-8859-1');
header('Content-Type: text/html; charset=utf-8');
global $excepciones;

include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$query = new sql();

$sql ="SELECT * FROM CONFIGURACION";
$query->sql = $sql;
$query->ejecuta_query();
while($query->next_record())
{
	$Configuracion= $query->Record;
}

$sql ="SELECT * FROM D_CORREO WHERE ID_D_CORREO ='". $_REQUEST['ID_D_CORREO'] ."'";
$query->sql = $sql;
$query->ejecuta_query();
$query->next_record();

//echo "<pre>";
$template = $query->Record['ARCHIVO'];
$MENSAJE =  file_get_contents("./". $template .".html");
$msg = json_decode($query->Record['MENSAJE']);
if(is_object($msg))
{
	while (list($clave, $valor) = each($msg))
	{
		$MENSAJE =  str_replace('{' . $clave . '}', $valor, $MENSAJE);
		//echo $clave . "----->". $valor . "\n";
	}
	
	while (list($clave, $valor) = each($Configuracion))
	{
		$MENSAJE =  str_replace('{CONFIGURACION_' . $clave . '}', $valor, $MENSAJE);
	}
}

echo $MENSAJE;

?>
