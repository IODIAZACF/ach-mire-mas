<?php
//header('Content-Type: application/json; charset=utf-8');
//header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Origin:*");

include ("../config.php");

include_once (Server_Path . 'herramientas/ini/class/class_ini.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$origen = 'reportes/f_' . $_REQUEST['origen'];
$ruta_origen = RUTA_SISTEMA . 'reportes/f_' . $_REQUEST['origen'];

$ini = new ini();

$ini->origen = $ruta_origen;
$ini->cargar_ini();

$cont = $ini->generaObj();
//print_r ( $cont);

//$ret.= '"'. $origen . '": {"NOMBRE":"EDSON"}';

$contenido = utf8_string_array_encode($cont);
//print_r($cont);

$retorno = "{";

$retorno .= '"'. $origen . '":' . json_encode( $contenido ) ;

$ret = json_encode( $cont ); 

$retorno .= "}";

echo $retorno;

?>

