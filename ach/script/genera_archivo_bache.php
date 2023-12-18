<?php
error_reporting(E_ALL);
$bache = $query[0]->Record;
$xid_m_baches = getvar('xbusca');
$q = new sql();
//BUSCO LA CABEZERA DEL DOCUMENTO
$q->sql = "SELECT * FROM M_DOCUMENTOS WHERE ID_M_DOCUMENTOS='" . $bache['ID_M_DOCUMENTOS'] . "'";
$q->ejecuta_query();
$q->next_record();

$t= explode(" ", $q->Record['FECHA_REGISTRO']);
$f = explode("-", $t[0]);
$tmp = $f[2]. '/' . $f[1]. '/'. $f[0]. '  ';
$f = explode(":", $t[1]);
if($f[0]>12) $f[0] = $f[0] - 12;
$tmp .= $f[0]. ':' . $f[1]. ':'. $f[2];

$q->Record['FECHA_REGISTRO'] = $tmp;
while (list($key, $val) = each($q->Record))
{
	$q->Record[$key] = utf8_encode($val);
    $xid = $q->reg_campos[$key];
    $att[$key] = $q->arreglo_atributos[$xid]['TIPO'];
}
$tk['ENCABEZADO']=$q->Record;
$tk['ENCABEZADO_ATT']=$att;

//BUSCO LA DETALLES DEL DOCUMENTO
$q->sql = "SELECT * FROM D_DOCUMENTOS WHERE ID_M_DOCUMENTOS='" . $bache['ID_M_DOCUMENTOS'] . "'";
$q->ejecuta_query();
unset($att);
while($q->next_record())
{
	$t= explode(" ", $q->Record['FECHA_REGISTRO']);
	$f = explode("-", $t[0]);
	$tmp = $f[2]. '/' . $f[1]. '/'. $f[0]. '  ';
	$f = explode(":", $t[1]);
	if($f[0]>12) $f[0] = $f[0] - 12;
	$tmp .= $f[0]. ':' . $f[1]. ':'. $f[2];

	$q->Record['FECHA_REGISTRO'] = $tmp;
	while (list($key, $val) = each($q->Record))
	{
	    $q->Record[$key] = utf8_encode($val);
        $xid = $q->reg_campos[$key];
    	$att[$key] = $q->arreglo_atributos[$xid]['TIPO'];
	}
	$tk['DETALLE'][]=$q->Record;
    $tk['DETALLE_ATT']=$att;
}

//BUSCO LA DETALLES DE PAGOS DEL DOCUMENTO
$q->sql = "SELECT * FROM D_PAGOS WHERE IDX='". $bache['ID_M_DOCUMENTOS'] ."' AND TABLA='M_DOCUMENTOS'";
$q->ejecuta_query();
unset($att);
while($q->next_record())
{
	$t= explode(" ", $q->Record['FECHA_REGISTRO']);
	$f = explode("-", $t[0]);
	$tmp = $f[2]. '/' . $f[1]. '/'. $f[0]. '  ';
	$f = explode(":", $t[1]);
	if($f[0]>12) $f[0] = $f[0] - 12;
	$tmp .= $f[0]. ':' . $f[1]. ':'. $f[2];

	$q->Record['FECHA_REGISTRO'] = $tmp;
	while (list($key, $val) = each($q->Record))
	{
	    $q->Record[$key] = utf8_encode($val);
        $xid = $q->reg_campos[$key];
    	$att[$key] = $q->arreglo_atributos[$xid]['TIPO'];
	}
	$tk['PAGOS'][]=$q->Record;
    $tk['PAGOS_ATT']=$att;
}

$contenido = json_encode($tk);
$path = '../../../baches';
mkdir($path,0777);
$fp   = fopen($path . '/' . $xid_m_baches .'.O24', "w+");
fwrite($fp,$contenido);
fclose($fp);
$xsql   = "UPDATE M_BACHES SET NOMBRES ='". $xid_m_baches .".O24', ESTATUS='PEN' WHERE ID_M_BACHES='" . $xid_m_baches . "'";
$query[0]->sql = $xsql;
$query[0]->ejecuta_query();

function dtipo($valor,$parameter_tipo)
{
    switch ($parameter_tipo)
    {
        case 'F':
        case 'R':
        case 'X':
        case 'B':
        case 'C':
        case 'L':
        case 'N':   //-- Este es obvio! --
        case 'I':   //-- Este es obvio! --
        case '5':   //-- md5 --
        	break;
        case 'T':   //-- time --
            if(strlen($val))
            {
	            $t= explode(" ", $valor);
	            $f = explode("-", $t[0]);
	            $valor = $f[2]. '/' . $f[1]. '/'. $f[0];
            }
        case 'D':
            if($valor != "")
            {
                $fecha = explode('/',$valor);
                $valor_sql = "'". $fecha[2] .'-'. $fecha[1] . '-'. $fecha[0] . "'";
            }
            else{
                    $valor_sql = "NULL";
            }
            break;
    }
    return $valor_sql;
}


?>