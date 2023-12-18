<?php
define("Server_Path", "../../");
include (Server_Path . "herramientas/sql/class/class_sql.php");

//$nombre = $_GET['nombre'];
$tabla_comparar = 'D_DOCUMENTOS';
//$tabla='X_DOCUMENTOS';

$query = new sql();
//$query->conexion->MetaTables();

print_r( '------------  Arreglos de LasTablas---------');
print_r($query->conexion->MetaTables());

print_r( '------------  Arreglos de Los campos---------<br>');
echo "<pre>";
foreach ($query->conexion->MetaTables() as $tabla)
{
 print_r('La tabla es:'.$tabla);
 echo '<br>';
 print_r($query->MetaColumns($tabla));
}


/*$campos_comparar =-*/ // print_r($query->MetaColumns($tabla));
//$campos_tabla   =$query->conexion->MetaColumns($tabla);


//rint_r('xxx'.$campos_comparar);



?>