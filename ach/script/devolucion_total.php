<?php
$comentarios= getvar('COMENTARIOS');

if($comentarios=='null'){
$comentarios='';
}

$query = new sql();
$query->sql = "UPDATE X_DOCUMENTOS SET CANTIDAD=DEVUELTOS,COMENTARIOS='" .strtoupper($comentarios) ."' WHERE ID_X_M_DOCUMENTOS = '" . getvar('ID_X_M_DOCUMENTOS')."' AND DEVUELTOS > 0 ORDER BY ID ";
$query->ejecuta_query();
die('ok');
?>