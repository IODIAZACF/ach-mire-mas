<?php
include_once (Server_Path . "herramientas/genera_xml/class/class_genera_xml.php");
include_once (Server_Path . "herramientas/numero_letras/numero_letras.php");
$query = new sql();
$xml = new class_genera_xml();

$sql  = "SELECT * FROM S_ANALIZA_PRODUCTO(";
$sql .= getvar('DIAS');
$sql .= ",". getvar('DIAS2');
$sql .= getvar('FACTOR') == '' ? ",0.0" : ",". getvar('FACTOR');
$sql .= getvar('ID_M_PRODUCTOS') == '' ? ",NULL" : ",'". getvar('ID_M_PRODUCTOS') . "'";
$sql .= ')' ;

$query->sql = $sql;
$query->ejecuta_query();
$query->next_record();

die('OK');



?>