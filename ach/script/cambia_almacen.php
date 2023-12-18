<?php
include_once (Server_Path . "herramientas/genera_xml/class/class_genera_xml.php");
include_once (Server_Path . "herramientas/numero_letras/numero_letras.php");
$query = new sql();
$xml = new class_genera_xml();

$sql  = "EXECUTE PROCEDURE S_CAMBIAR_ALMACEN(";
$sql .= getvar('almacen_origen') == '' ? "NULL" : "'". getvar('almacen_origen') . "'";
$sql .= getvar('almacen_destino') == '' ? ",NULL" : ",'". getvar('almacen_destino') . "'";
$sql .= getvar('comanda') == '' ? ",NULL" : ",'". getvar('comanda')."'";
$sql .= ')';

$query->sql = $sql;

$query->ejecuta_query();
//rdebug($query,'s');
die();



?>