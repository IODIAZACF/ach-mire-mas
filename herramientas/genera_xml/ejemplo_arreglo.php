<?php
  define('Server_Path','../../');
//  include(Server_Path . 'herramientas/utiles/comun.php');
//  encabezado('EJEMPLO LEYENDA INI');
//define("Server_Path", "");
include_once (Server_Path ."herramientas/genera_xml/class/class_genera_xml.php");
//include (Server_Path . "herramientas/ini/class/class_ini.php");

$data[0][NOMBRES] 	= "JUAN ARGENIS";
$data[0][APELLIDOS] = "MONSERRAT ADAM";
$data[1][NOMBRES] 	= "JUAN FFFF";
$data[1][APELLIDOS] = "MOSERRAT ADAM";
$data[2][NOMBRES] 	= "CLEIDYS";
$data[2][APELLIDOS] = "DE CARREÑO";
$data[3][NOMBRES] 	= "MARIA";
$data[3][APELLIDOS] = "DE CARREÑO";
$data[4][NOMBRES] 	= "EDSON";
$data[4][APELLIDOS] = "DAZA";


$xml = new  class_genera_xml();
$xml->data = $data;
//$xml->lee_condiciones('ejemplo.ini');

//print_r($xml->condicion);
$xml->condicion['APELLIDOS'][]='NOMBRES,color="#FF224F",=,DAZA';

$xml->imprime_xml();
echo $xml->contenido;

?>
