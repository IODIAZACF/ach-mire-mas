<?php

include('../config.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
$imagen = '../' .$_GET['imagen'];
$bname=substr($imagen,0, -37);
unlink($imagen);

$imagenes=@glob($bname .'*.jpg');
if(is_array($imagenes))
{
    $cantidad = sizeof($imagenes);
	$XFECHA = "'" . date("Y-m-d") ."'";
}else
{
    $cantidad = 0;
	$XFECHA ="NULL";
}

$query = new sql(0);
$query->sql = "UPDATE D_ARCHIVOS SET CANTIDAD=" . $cantidad . " , FECHA_DIGITAL=" . $XFECHA . " WHERE ID_D_ARCHIVOS='". $_REQUEST['ID_D_ARCHIVOS'] ."'";
$query->ejecuta_query();

if($query->Error)
{
    echo "ERROR [" . $query->regi['ERROR'] . "]";
    die();
}

echo "OK";
?>