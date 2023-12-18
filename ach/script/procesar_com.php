<?php
Ini_set('max_execution_time',0);
$xid      = $query[0]->Record['ID_M_ARCHIVOS'];
$xestatus = $query[0]->Record['ESTATUS'];
$xid_m_proveedores = '001681';

$xsql   = "SELECT * FROM X_M_DOCUMENTOS WHERE CAMPO4='REC_BACHE' AND TIPO='COX'";
$query[0]->sql = $xsql;
$query[0]->ejecuta_query();
$query[0]->next_record();
if($query[0]->Record['ID_X_M_DOCUMENTOS'])
{
    if($query[0]->Record['CAMPO3']==$xid && $xestatus=='PEN')
    {
		$query[0]->sql = "DELETE FROM X_M_DOCUMENTOS WHERE ID_X_M_DOCUMENTOS ='". $query[0]->Record['ID_X_M_DOCUMENTOS'] ."'";
		$query[0]->ejecuta_query();
    }
    else
    {
    	die('existen batch sin procesar: '. $query[0]->Record['REFERENCIA']);
    }
}



$xsql   = "SELECT * FROM M_ARCHIVOS WHERE ID_M_ARCHIVOS='" . $xid . "'";
$query[0]->sql = $xsql;
$query[0]->ejecuta_query();
$query[0]->next_record();

$id_bache =   $query[0]->Record['ID_M_DOC_FINAL'];
$xdir  = '../../../download/';
$bache = $xdir  . $query[0]->Record['NOMBRES'];
$query = new sql(0);

$ufile = md5_file($bache);
$gestor = fopen($bache, "r");

fgets($gestor);
fgets($gestor);
$xtmp   = explode(';', fgets($gestor));
$x = sizeof($xtmp);
$doc_fiscal = str_replace('"', '', $xtmp[$x-1]);
$doc_fiscal = trim($doc_fiscal);
//$doc_fiscal = str_pad($doc_fiscal, 8, "0", STR_PAD_LEFT);

$xid_x_m_documentos =  creaFactura($xid, $id_bache, $doc_fiscal);

rewind($gestor);

$rr=0;
if($gestor)
{
	$xcampos = trim(fgets($gestor));
    $tipos   = explode(';', fgets($gestor));
    $campos  = explode(';', $xcampos);

    array_pop($campos);
    array_pop($campos);
    $xcampos = join(';', $campos);

    while(!feof($gestor))
    {
        $linea  = trim(fgets($gestor));
        if(strlen($linea))
        {
	        $xvalores = preparaDetalle($campos, $tipos, $linea);
	        $sql = 'INSERT INTO X_DOCUMENTOS (ID_X_M_DOCUMENTOS,' . str_replace(';',',', $xcampos) . ') VALUES (\''. $xid_x_m_documentos . '\','. $xvalores . ')';
	        $query->sql = $sql;
	        $query->ejecuta_query();
	        echo 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
	        if(strlen($query->regi['ERROR']))
	        {
	            echo $query->regi['ERROR'];
	            die();
	        }
        }
    }
}


$query->sql = "UPDATE X_M_DOCUMENTOS SET ESTATUS ='C' WHERE ID_M_PROVEEDORES ='$xid_m_proveedores' AND TIPO ='COX' AND ESTATUS='A' AND CAMPO4='REC_BACHE'";
$query->ejecuta_query();
if(strlen($query->regi['ERROR']))
{
    echo $query->regi['ERROR'];

	$sql = "UPDATE M_ARCHIVOS SET ESTATUS = 'PEN' WHERE ID_M_ARCHIVOS='" . $xid . "'";
	$query->sql = $sql;
	$query->ejecuta_query();

	$query->sql = "DELETE FROM X_M_DOCUMENTOS WHERE ID_M_PROVEEDORES ='$xid_m_proveedores' AND TIPO ='COX' AND ESTATUS='A' AND CAMPO4='REC_BACHE'";
	$query->ejecuta_query();


    die();
}
else
{
    $query->sql = "SELECT * FROM M_DOCUMENTOS WHERE ID_X_M_DOCUMENTOS ='". $xid_x_m_documentos  ."'";
    $query->ejecuta_query();
    $query->next_record();
    //rdebug($query);

    $xid_m_documentos = $query->Record['ID_M_DOCUMENTOS'];
    $sql = "UPDATE M_DOCUMENTOS SET ID_M_ARCHIVOS ='". $xid ."' WHERE ID_M_DOCUMENTOS ='". $xid_m_documentos. "'";
	$query->sql = $sql;
	$query->ejecuta_query();


	$sql = "UPDATE M_ARCHIVOS SET ESTATUS = 'PRO', ID_M_DOCUMENTOS ='". $xid_m_documentos."' WHERE ID_M_ARCHIVOS='" . $xid . "'";
	$query->sql = $sql;
	$query->ejecuta_query();
    //rdebug($query);
}

die('OK');
function preparaDetalle($campos, $tipos, $linea)
{
    global $query;

    $linea  = substr($linea, 1, strlen($linea)-2);
    $reg    = explode('";"', $linea);
    array_pop($reg);
    array_pop($reg);

	$xpos = array_search('ID_M_PRODUCTOS', $campos);
    $xid_m_productos = $reg[$xpos];

    $sql = "SELECT ID_I_PROD_ALMA FROM I_PROD_ALMA WHERE ID_M_PRODUCTOS='". $xid_m_productos. "' AND ID_M_ALMACENES='0011'";
    $query->sql = $sql;
    $query->ejecuta_query();
    $query->next_record();

	$xpos       = array_search('ID_I_PROD_ALMA', $campos);
    $reg[$xpos] = $query->Record['ID_I_PROD_ALMA'];

    //busco el campo costo para sustituir con su valor el precio
	$xpos_costo      = array_search('COSTO_PROMEDIO',  $campos);
    $xpos_precio     = array_search('PRECIO', $campos);

    $xpos_iva        = array_search('IMPUESTO',  $campos);

    $reg[$xpos_precio] = $reg[$xpos_costo];
    $reg[$xpos_iva] = 0;


	$xpos       = array_search('TIPO', $campos);
    $reg[$xpos] = 'COX';

	$xvalores = '';
    for($i=0;$i<sizeof($reg);$i++)
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
        if(strlen($xvalores)) $xvalores .= ',';
        $xvalores .= $valor_sql;
    }
    return $xvalores;
}

function creaFactura($xid_m_archivos, $id_bache,$doc_fiscal)
{
    global $query;
    global $xid_m_proveedores;

    $query->sql = "SELECT * FROM X_M_DOCUMENTOS WHERE ID_M_PROVEEDORES ='$xid_m_proveedores' AND TIPO ='COX' AND ESTATUS='A' AND CAMPO4='REC_BACHE'";
    $query->ejecuta_query();
    if($query->next_record())
    {
    	return $query->Record['ID_X_M_DOCUMENTOS'];
    }else
    {
        $sql ="SELECT * FROM M_PROVEEDORES WHERE ID_M_PROVEEDORES ='$xid_m_proveedores'";
        $query->sql = $sql;
        $query->ejecuta_query();
        $query->next_record();

	    $documento_nombre    = substr($query->Record['RAZON'],0,200);
	    $documento_codigo1   = substr($query->Record['NACIONALIDAD'] . '-' . $query->Record['CODIGO1'],0,20);
	    $documento_codigo2   = substr($query->Record['CODIGO2'],0,20);
	    $documento_direccion = substr($query->Record['DIRECCION'],0,300);
	    $documento_telefono  = substr($query->Record['TELEFONO1'],0,30);

        $sql ="INSERT INTO X_M_DOCUMENTOS(TIPO,ID_M_PROVEEDORES,CONDICION_PAGO, CAMPO4,REFERENCIA, ID_M_DOC_FINAL, NOMBRES,CODIGO1,CODIGO2,DIRECCION, TELEFONO,CAMPO5) values ('COX','". $xid_m_proveedores ."','CRE','REC_BACHE','". $id_bache ."', '". $doc_fiscal ."','". $documento_nombre ."','". $documento_codigo1 ."','". $documento_codigo2 ."','". $documento_direccion ."','". $documento_telefono ."','". $id_bache ."')";
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