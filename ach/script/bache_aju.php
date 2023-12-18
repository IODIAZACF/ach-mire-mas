<?php
$xusuario_id = $query[0]->Record['ID_M_USUARIO'];
$xtabla      = $query[0]->Record['TABLA'];
$xid         = $query[0]->Record['ID_M_ARCHIVOS'];
$xsql        = "SELECT ID_M_PRODUCTOS,CANTIDAD,ID_M_TIPO_AJUSTES,ID_I_PROD_ALMA,TIPO,IMPUESTO,NOMBRE_IMPUESTO,COSTO,PRECIO,COSTO_PROMEDIO,COMENTARIOS FROM D_DOCUMENTOS WHERE ID_M_ARCHIVOS='" . $xid . "'";



$query[0]->sql = $xsql;
$query[0]->ejecuta_query();

$path = '../../../baches';
@mkdir($path);
$fp   = fopen($path . '/' . $xid .'.O24', "w+");

$campos = '';
$tipos  = '';
for($i=0; $i<sizeof($query[0]->arreglo_atributos);$i++)
{
    if(strlen($campos))
    {
    	$campos.=';';
        $tipos.=';';
    }
    $campos .= $query[0]->arreglo_atributos[$i]['NOMBRE'];
    $tipos  .= $query[0]->arreglo_atributos[$i]['TIPO'];
}
$campos .= "\n";
$tipos .= "\n";
fwrite($fp, $campos);
fwrite($fp, $tipos);

while($query[0]->next_record())
{
	$registro = '';
	foreach($query[0]->Record as $campo => $valor)
    {
        if(strlen($registro)) $registro.=';';
        $registro .= '"'. $valor .'"' ;
    }
    $registro .= "\n";
    fwrite($fp, $registro);
}
fclose($fp);

$xsql   = "UPDATE M_ARCHIVOS SET NOMBRES ='$xid.O24', ESTATUS='PEN', ID_M_DOC_FINAL ='$xid' WHERE ID_M_ARCHIVOS='" . $xid . "'";
$query[0]->sql = $xsql;
$query[0]->ejecuta_query();

?>