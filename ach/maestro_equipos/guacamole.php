<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');


$query = new sql();

$sql  = "SELECT * FROM M_ESTACIONES WHERE ID_M_TIPO_ESTACIONES = '0011' AND ESTATUS='ACT' ";
$query->sql = $sql;
$query->ejecuta_query();

$xml = '';

$xml .= '<user-mapping>' . "\n";
$xml .= '	<authorize username="admin" password="admin">' . "\n";





while ( $query->next_record() ){
	$xml .= '		<connection name="' . $query->Record['NOMBRES'] . ' - '  . $query->Record['IP'] . '">' . "\n";
	$xml .= '			<protocol>vnc</protocol>' . "\n";
	$xml .= '			<param name="hostname">' . $query->Record['IP'] . '</param>' . "\n";
	$xml .= '			<param name="port">5900</param>' . "\n";
	$xml .= '			<param name="password">las36horas</param>' . "\n";
	$xml .= '        </connection>' . "\n";	

}

$xml .= '    </authorize>' . "\n";


$xml .= '</user-mapping>' . "\n";


echo $xml;

file_put_contents('/etc/guacamole/user-mapping.xml', $xml );

die();


function mayusculas($texto){

	$texto = str_replace('á', 'a', $texto);
	$texto = str_replace('é', 'e', $texto);
	$texto = str_replace('í', 'i', $texto);
	$texto = str_replace('ó', 'o', $texto);
	$texto = str_replace('ú', 'u', $texto);
	$texto = strtoupper($texto);
	$texto = trim($texto);
	return $texto;
}




?>
