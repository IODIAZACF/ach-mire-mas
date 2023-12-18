<?php

Ini_set('max_execution_time',0);
//validacion para verificar si existe algun bache abierto.....
$xid      = $query[0]->Record['ID_M_ARCHIVOS'];
$xestatus = $query[0]->Record['ESTATUS'];

//rdebug($query[0],'s');
$xsql   = "SELECT * FROM X_M_DOCUMENTOS WHERE CAMPO4='REC_BACHE' AND TIPO='EQU'";
$query[0]->sql = $xsql;
$query[0]->ejecuta_query();
$query[0]->next_record();
//rdebug($query[0],'s');
if($query[0]->Record['ID_X_M_DOCUMENTOS'])
{
    if($query[0]->Record['CAMPO3']==$xid && $xestatus=='PEN')
    {
		$query[0]->sql = "DELETE FROM X_M_DOCUMENTOS WHERE ID_X_M_DOCUMENTOS ='". $query[0]->Record['ID_X_M_DOCUMENTOS'] ."'";
		$query[0]->ejecuta_query();
    }
    else
    {
    	die('existen batch sin procesar: '. $query[0]->Record['CAMPO5']);
    }
}
$xsql   = "SELECT * FROM M_ARCHIVOS WHERE ID_M_ARCHIVOS='" . $xid . "'";
$query[0]->sql = $xsql;
$query[0]->ejecuta_query();
$query[0]->next_record();

$xdir  = '../../../download/';
$bache = $xdir  . $query[0]->Record['NOMBRES'];
$xid_m_doc_final = $query[0]->Record['ID_M_DOC_FINAL'];
$xid_m_archivos  = $query[0]->Record['ID_M_ARCHIVOS'];
$query = new sql(0);



$ufile = md5_file($bache);
$gestor = fopen($bache, "r");
$rr=0;
if($gestor)
{
	$xcampos = trim(fgets($gestor));
    $tipos   = explode(';', fgets($gestor));
    $campos  = explode(';', $xcampos);
    while(!feof($gestor))
    {
        $linea  = trim(fgets($gestor));
        if(strlen($linea))
        {
	        $xquery = preparaDetalle($campos, $tipos, $linea, $xid_m_doc_final, $xid_m_archivos);
            if($xquery['error'])
            {
	            $sql = "DELETE FROM X_M_DOCUMENTOS WHERE CAMPO4='REC_BACHE' AND CAMPO5='". $xid_m_doc_final ."' AND TIPO='EQU'";
	            $query->sql = $sql;
	            $query->ejecuta_query();

	            $sql = "UPDATE M_ARCHIVOS SET CAMPO5 = '". $xquery['msg'] ."' WHERE ID_M_ARCHIVOS='" . $xid . "'";
	            $query->sql = $sql;
	            $query->ejecuta_query();

                die($xquery['msg']);

                break;
            }else
            {
	            $sql = 'INSERT INTO X_DOCUMENTOS (' . $xquery['msg'] . ')';
	            $query->sql = $sql;
	            $query->ejecuta_query();
	            if(strlen($query->regi['ERROR']))
	            {
	                echo $query->regi['ERROR'];
	                die();
	            }
            }
        }
    }
}

/*
$sql = "UPDATE X_M_DOCUMENTOS SET ESTATUS = 'C' WHERE CAMPO4='REC_BACHE' AND COMENTARIOS='". $xquery['idx'] ."' AND TIPO='EQU'";
$query->sql = $sql;
$query->ejecuta_query();
*/

$sql = "UPDATE M_ARCHIVOS SET ESTATUS = 'PRO', COMENTARIOS =COMENTARIOS||',OK' WHERE ID_M_ARCHIVOS='" . $xid . "'";
$query->sql = $sql;
$query->ejecuta_query();

die('OK');
function preparaDetalle($campos, $tipos, $linea, $xid_m_doc_final, $xid_m_archivos)
{
    global $query;

    $linea  = substr($linea, 1, strlen($linea)-2);
    $reg    = explode('";"', $linea);
	$xpos = array_search('ID_M_PRODUCTOS', $campos);
    $xid_m_productos = $reg[$xpos];

    //echo $xid_m_productos . "<br>\n";
    $sql = "SELECT ID_M_ALMACENES,ALMACENES FROM V_I_PROD_ALMA2 WHERE ID_M_PRODUCTOS='". $xid_m_productos. "'";
    $query->sql = $sql;
    $query->ejecuta_query();
    $query->next_record();
	if(!$query->Record[ID_M_ALMACENES])
    {
        $tmp['error'] = 1;
        $tmp['idx']   = $reg[0];
        $tmp['msg']   = 'PRODUCTO ' . $xid_m_productos . ' NO TIENE ALMACEN FACTURABLE.';
        return $tmp;
    }

    if($query->Record[ID_M_ALMACENES]=='0011')
    {
        $tmp['error'] = 1;
        $tmp['idx']   = $reg[0];
        $tmp['msg']   = 'PRODUCTO ' . $xid_m_productos . ' NO PUEDE SER FACTURABLE EN ALMACEN TIENDA.';
        return $tmp;
    }
    $xid_x_m_documentos = creaEquipamiento($query->Record[ID_M_ALMACENES], $query->Record[ALMACENES], $reg[0], $xid_m_doc_final, $xid_m_archivos);

    $sql = "SELECT ID_I_PROD_ALMA FROM I_PROD_ALMA WHERE ID_M_ALMACENES ='0011' AND ID_M_PRODUCTOS='". $xid_m_productos. "'";
    $query->sql = $sql;
    $query->ejecuta_query();
    $query->next_record();

	$xpos       = array_search('ID_I_PROD_ALMA', $campos);
    $reg[$xpos] = $query->Record['ID_I_PROD_ALMA'];

	$xvalores = '';
    $xcampos  = '';
    for($i=1;$i<sizeof($reg);$i++)
    {
        $tipo  = $tipos[$i];
        $valor = $reg[$i];
        switch ($tipo)
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
            case 'X':
            case 'B':
            case 'C':
            case 'L':
            case 'D':
                if(strlen($valor)){
                        $valor_sql = "'" . $valor  . "'";
                }
                else
                {
                        $valor_sql = "''";
                }
                break;
            case 'N':   //-- Este es obvio! --
            case 'I':   //-- Este es obvio! --
                if($valor != "")
                {
                    $valor_sql = $valor;
                }
                else
                {
                        $valor_sql = 0;
                }
                break;
        }
        if(strlen($xvalores))
        {
        	$xvalores .= ',';
            $xcampos  .= ',';
        }
        $xvalores .= $valor_sql;
        $xcampos  .= $campos[$i];
    }

    $tmp['error'] = 0;
    $tmp['idx']   = $reg[0];
    $tmp['msg']   = 'ID_X_M_DOCUMENTOS,'. $xcampos . ') VALUES (\''. $xid_x_m_documentos . '\','. $xvalores;

    return $tmp;
}

function creaEquipamiento($xid_m_almacenes, $almacenes, $idx, $xid_m_doc_final, $xid_m_archivos)
{
    global $query;
    $query->sql = "SELECT * FROM X_M_DOCUMENTOS WHERE ID_M_ALMACENES2='". $xid_m_almacenes ."' AND TIPO ='EQU' AND ESTATUS='A'";
    $query->ejecuta_query();
    if($query->next_record())
    {
    	return $query->Record['ID_X_M_DOCUMENTOS'];
    }else
    {
        $sql ="INSERT INTO X_M_DOCUMENTOS(TIPO,ID_M_ALMACENES,ID_M_ALMACENES2, NOMBRES,CAMPO4,CAMPO5,CAMPO3,REFERENCIA) values ('EQU','0011','". $xid_m_almacenes ."','". $almacenes ."','REC_BACHE','". $xid_m_doc_final ."','". $xid_m_archivos ."', '". $xid_m_doc_final ."')";
        $query->sql = $sql;
        $query->ejecuta_query();

        $sql ="SELECT * FROM X_M_DOCUMENTOS WHERE UNICO ='".  $query->unico  ."'";
        $query->sql = $sql;
        $query->ejecuta_query();
        $query->next_record();
    	return $query->Record['ID_X_M_DOCUMENTOS'];
    }
}
?>