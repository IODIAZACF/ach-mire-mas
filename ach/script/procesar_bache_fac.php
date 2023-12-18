<?php
ini_set('max_execution_time',0);
error_reporting(E_ALL);
$bache = $query[0]->Record;
$xid_m_baches = getvar('xbusca');

$xdir  = '../../../download/';
$contenido = file_get_contents($xdir . $bache['NOMBRES']);
$jbache = json_decode($contenido);
//CAMPOS DE LA CABECERA DEL DOCUMENTO....
$cx_m_documentos = explode(',','TEMP,TIPO,ID_M_CLIENTES,NOMBRES,CODIGO1,DIRECCION,TELEFONO,ID_M_VENDEDORES,NOMBRE_VENDEDOR,ID_M_CAJAS,CREDITO');
for($i=0;$i<sizeof($cx_m_documentos);$i++)
{
    $key = $cx_m_documentos[$i];
    $valor_xm[] = dtipo(utf8_decode($jbache->ENCABEZADO->$key),$jbache->ENCABEZADO_ATT->$key);
    $campo_xm[] = $key;
}

$campo_xm[] = 'CONDICION_GENERA_NUM';
$valor_xm[] = "'*'";
$campo_xm[] = 'ID_M_DOC_FINAL';
$valor_xm[] = "'". $jbache->ENCABEZADO->ID_M_DOC_FINAL ."'";


$tcampos  = join(',',$campo_xm);
$tvalores = join(',',$valor_xm);

$q      = new sql();
$q->sql = "INSERT INTO X_M_DOCUMENTOS(". $tcampos .") values (". $tvalores .")";
$q->ejecuta_query();
if($q->Reg_Afect)
{
	$q->sql = "SELECT * FROM X_M_DOCUMENTOS WHERE UNICO ='".  $q->unico  ."'";;
	$q->ejecuta_query();
	$q->next_record();
	$xID_X_M_DOCUMENTOS = $q->Record['ID_X_M_DOCUMENTOS'];
}
//CAMPOS DE LOS DETALLES DEL DOCUMENTO....
$cx_d_documentos = explode(',','ID_M_PRODUCTOS,PRESENTACION,CANTIDAD,PRECIO,DESCUENTO,COMENTARIOS,COSTO,IMPUESTO,ID_X_M_DOCUMENTOS,TIPO,COSTO_PROMEDIO,ID_M_ALMACENES,ID_D_PRODUCTOS,ID_I_PROD_ALMA,ID_D_I_PROD_ALMA,ID_M_IMPUESTOS,TIPO_DESCUENTO,DESCUENTO1,DESCUENTO2,DESCUENTO3,DESCUENTO4');
for($d=0;$d<sizeof($jbache->DETALLE);$d++)
{
    unset($valor_xd);
    unset($campo_xd);

    for($i=0;$i<sizeof($cx_d_documentos);$i++)
    {
        $key = $cx_d_documentos[$i];
	    $valor_xd[] = dtipo(utf8_decode($jbache->DETALLE[$d]->$key),$jbache->DETALLE_ATT->$key);
	    $campo_xd[] = $key;
    }

    $xpos = array_search('ID_X_M_DOCUMENTOS', $campo_xd);
    $valor_xd[$xpos]="'" . $xID_X_M_DOCUMENTOS . "'";

    $tcampos  = join(',',$campo_xd);
	$tvalores = join(',',$valor_xd);

	$q->sql = "INSERT INTO X_DOCUMENTOS(". $tcampos .") values (". $tvalores .")";
    $q->ejecuta_query();
    if(!$q->Reg_Afect)
    {
        rdebug($q,'x');
    }
}
rdebug($jbache->PAGOS,'s');
//CAMPOS DE LOS DETALLES DE PAGOS....
$cx_d_pagos = explode(',','ID_M_CLIENTES, FECHA_PAGO,CREDITOS,ID_M_TIPO_PAGOS, IDX,TABLA, ID_M_CAJAS');
for($d=0;$d<sizeof($jbache->PAGOS);$d++)
{
    unset($valor_xd);
    unset($campo_xd);

    for($i=0;$i<sizeof($cx_d_pagos);$i++)
    {
        $key = $cx_d_pagos[$i];
	    $valor_xd[] = dtipo(utf8_decode($jbache->PAGOS[$d]->$key),$jbache->PAGOS_ATT->$key);
	    $campo_xd[] = $key;
    }

    $xpos = array_search('IDX', $campo_xd);
    $valor_xd[$xpos]="'" . $xID_X_M_DOCUMENTOS . "'";

    $xpos = array_search('TABLA', $campo_xd);
    $valor_xd[$xpos]="'X_M_DOCUMENTOS'";


    $tcampos  = join(',',$campo_xd);
	$tvalores = join(',',$valor_xd);

	$q->sql = "INSERT INTO D_PAGOS(". $tcampos .") values (". $tvalores .")";
    $q->ejecuta_query();
    if(!$q->Reg_Afect)
    {
        rdebug($q,'x');
    }
    rdebug($q,'s');
}
echo "fino";


function dtipo($valor,$parameter_tipo)
{

    switch ($parameter_tipo)
    {
        case 'F':
            if(strlen($valor)){
                    $valor_sql = $valor;
            }
            else
            {
                    $valor_sql = "NULL";
            }
            break;
        case 'R':
            if(strlen($valor)){
                    $valor_sql = "'" . addcslashes($valor, "'") . "'";
            }
            else
            {
                    $valor_sql = "NULL";
            }
            break;
        case 'X':
        case 'B':
        case 'C':
        case 'L':
            if(strlen($valor)){
                    $valor_sql = "'" . addcslashes($valor, "'") . "'";
            }
            else
            {
                    $valor_sql = "NULL";
            }
            break;
        case 'N':   //-- Este es obvio! --
        case 'I':   //-- Este es obvio! --
            if($valor != "") {
                $valor_sql = str_replace(',','', $valor);
            }
            else
            {
                    $valor_sql = 0;
            }
            break;

        case '5':   //-- md5 --
            if($valor != "") {
                $valor_sql = "'". md5(strtoupper($valor)) . "'" ;
            }
            else
            {
                    $valor_sql = "NULL";
            }
            break;

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