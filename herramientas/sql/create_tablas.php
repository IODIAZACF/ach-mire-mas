<?php
define("Server_Path", "../../");
include (Server_Path . "herramientas/sql/class/class_sql.php");

$nombre = $_GET['nombre'];

$query = new sql();
$query->MetaTables();


foreach ($query->conexion->MetaTables() as $tabla)
{
 $pr='create table '.$tabla.'(';

   if  (Dtipo=='firebird')
   rdebug($query->MetaColumns($tabla));


}

?>