<?php

include('../config.php');
define("Verifica_session", false); 		//no verifica la session
define('Usa_Log', false);
$_SESSION['db'] = 'semilla'; 			//base de datos general de la agenda.

include_once (Server_Path . "herramientas/jwt/class/class_jwt.php");
$jwt = new jwt();
$auth = $jwt->decode($_REQUEST['auth']);
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
$query = new sql();
$query->sql = "UPDATE M_REGISTRO_AGENDA SET ESTATUS='AUTH' WHERE ID_M_REGISTRO_AGENDA='". $auth->ID."'";
$query->ejecuta_query();
if($query->Reg_Afect)
{
	echo 'Dispositivo vinculado con exito';
}else
{
	echo 'Error al vincular dispositivo';
}

?>
