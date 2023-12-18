<?php
define("Server_Path", "../../");
include (Server_Path . "herramientas/sql/class/class_sql.php");


$query = new sql();
$query->sql = "select * from ejemplo";
$query->ejecuta_query();
$query->crear_arreglo();
echo $query->sql.'<br>';
print_r ($query->arreglo);
print_r ($query->arreglo_atributos);
?>