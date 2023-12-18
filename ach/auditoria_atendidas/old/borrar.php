<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
require_once 'excel_reader2.php';

$archivo = getvar('archivo');
limpiar();

$query = new sql(0);
$sql = "DELETE FROM D_ADMINISTRACION WHERE CAMPO1='$archivo'";
$query->sql = $sql;
$query->ejecuta_query();

if(strlen($query->regi['ERROR']))
{
    echo $query->regi['ERROR'];
    die("OCURRIÓ UN ERROR AL INTENTAR BORRAR LOS REGISTROS<br/>\n");
}

//error_reporting(E_ALL ^ E_NOTICE);
echo 'Archivo Eliminado :' . $archivo;


function limpiar()
{
    $query = new sql(0);
	$sql = "DELETE FROM X_ADMINISTRACION";
	$query->sql = $sql;
	$query->ejecuta_query();

	if(strlen($query->regi['ERROR']))
	{
	    echo $query->regi['ERROR'];
	    die("OCURRIÓ UN ERROR AL INTENTAR BORRAR LOS REGISTROS<br/>\n");
	}

}


?>