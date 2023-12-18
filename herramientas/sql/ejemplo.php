<?php
session_start();
$_SESSION['db'] =$_REQUEST['db'];
/*include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
*/
define("Server_Path", "../../");
include (Server_Path . "herramientas/sql/class/class_sql.php");
echo "<pre>";
echo date("h:i:s") . "\n";
$query = new sql();
$x = $query->listar_tablas();
print_r($x);
echo date("h:i:s") . "\n";

$query->sql = "SELECT * FROM D_PROTOCOLOS ROWS 1 to 10";
$query->ejecuta_query();
$query->next_record();



?>