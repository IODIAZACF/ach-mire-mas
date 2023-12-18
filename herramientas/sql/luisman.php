<?php
define("Server_Path", "../../");
include (Server_Path . "herramientas/sql/class/class_sql.php");

$query = new sql();
rdebug($query);
$query->beginTransaction();
$query->sql = 'INSERT INTO M_CORRELATIVOS ("NOMBRES", "TIPO", "ABREVIATURA", "NUMERO", "ID_EMPRESA") VALUES (\'GGA\', \'GGA\', \'GGA\', 0, \'001\')';
$query->ejecuta_query();
rdebug($query);
$query->sql = 'INSERT INTO M_CORRELATIVOS ("NOMBRES", "TIPO", "ABREVIATURA", "NUMERO", "ID_EMPRESA") VALUES (\'GGB\', \'GGB\', \'GGB\', 0, \'001\')';
$query->ejecuta_query();
$query->commit();
rdebug($query,'s');
?>