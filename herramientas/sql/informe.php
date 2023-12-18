<?php
define("Server_Path", "../../");
include (Server_Path . "herramientas/sql/class/class_sql.php");

$query = new sql();
$query2 = new sql();
$query->sql = "select * from M_INFORMES_MEDICOS";
$query->ejecuta_query();
while($query->next_record())
{
	$informe = $query->Record['CONTENIDO'];
	//echo $informe ."\n\n\n";
	$informe = str_replace('\\\\', '', $informe);
	$informe = str_replace('\"', '"', $informe);
	$informe = str_replace('"""', '"', $informe);
	$informe = str_replace('&quot;', '', $informe);
	$informe = str_replace('\\\'', '', $informe);
	//echo $informe ."\n\n\n";

	$valor_sql = "'" . addcslashes($informe, "'") . "'";

	$query2->sql = "UPDATE M_INFORMES_MEDICOS SET CONTENIDO =" . $valor_sql . " WHERE ID_M_INFORMES_MEDICOS='". $query->Record['ID_M_INFORMES_MEDICOS'] ."'";
	$query2->ejecuta_query();
}



?>