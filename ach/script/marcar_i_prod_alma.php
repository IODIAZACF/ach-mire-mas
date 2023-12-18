<?php
$query = new sql();
$descripcion = getvar('descripcion');
if($descripcion =='*') $descripcion =',';
$descripcion = str_replace(',','%', $descripcion) . "%";
$marca = getvar('marca','');
$query->sql = "UPDATE I_PROD_ALMA SET CONDICION3='". $marca ."' WHERE ID_M_ALMACENES ='". getvar('almacen')  ."' AND ID_M_PRODUCTOS IN (SELECT ID_M_PRODUCTOS FROM M_PRODUCTOS WHERE DESCRIPCION LIKE '". $descripcion . "')";
$query->ejecuta_query();
//rdebug($query,'s');
echo "OK";
die();
//rdebug($query,'s');

?>