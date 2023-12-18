<?php
ini_set('max_execution_time',0);
$xid    = $query[0]->Record['ID_M_ARCHIVOS'];
$xsql   = "SELECT * FROM M_ARCHIVOS WHERE ID_M_ARCHIVOS='" . $xid . "'";

$query[0]->sql = $xsql;
$query[0]->ejecuta_query();
$query[0]->next_record();

$xdir  = '../../../download/';
$bache = $xdir  . $query[0]->Record['NOMBRES'];
$query = new sql(0);

$ufile = md5_file($bache);
$gestor = fopen($bache, "r");

fgets($gestor);
fgets($gestor);

$linea = fgets($gestor);
$tmp   = explode(';', $linea);
$i = sizeof($tmp);
//echo $linea;
$xreferencia = substr($tmp[$i-1],1,-2);

$query->sql = "SELECT * FROM M_DOCUMENTOS WHERE REFERENCIA='". $xreferencia  ."' AND TIPO IN('COM','COX')";
$query->ejecuta_query();
$query->next_record();

if($query->Record['ID_M_DOCUMENTOS']=='')
{

    $xsql   = "UPDATE M_ARCHIVOS SET ESTATUS = 'PEN' WHERE ID_M_ARCHIVOS='" . $xid . "'";
	$query->sql = $xsql;
	$query->ejecuta_query();

    die('No se puede proceesar esta bache porque no se ha procesado el documento de compra');
}
$xid_x_m_documentos =  creaDevolucion($query->Record['ID_M_DOCUMENTOS'],$xid);




rewind($gestor);


$rr=0;
if($gestor)
{
	$xcampos = trim(fgets($gestor));
    $tipos   = explode(';', fgets($gestor));
    $campos  = explode(';', $xcampos);
    //rdebug($campos);

    while(!feof($gestor))
    {
        $linea  = trim(fgets($gestor));
        if(strlen($linea))
        {
	        $sql = preparaDetalle($campos, $tipos, $linea);
	        //$sql = 'INSERT INTO X_DOCUMENTOS (ID_X_M_DOCUMENTOS,' . str_replace(';',',', $xcampos) . ') VALUES (\''. $xid_x_m_documentos . '\','. $xvalores . ')';
	        $query->sql = $sql;
	        $query->ejecuta_query();
	        echo 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
	        if(strlen($query->regi['ERROR']))
	        {
	            echo $query->regi['ERROR'];
	            die();
	        }
        }
    }
}

$sql = "UPDATE X_M_DOCUMENTOS SET ESTATUS = 'C' WHERE ID_X_M_DOCUMENTOS='" . $xid_x_m_documentos . "'";
$query->sql = $sql;
$query->ejecuta_query();

if(strlen($query->regi['ERROR']))
{
    echo $query->regi['ERROR'];

	$sql = "UPDATE M_ARCHIVOS SET ESTATUS = 'PEN' WHERE ID_M_ARCHIVOS='" . $xid . "'";
	$query->sql = $sql;
	$query->ejecuta_query();

	$query->sql = "DELETE FROM X_M_DOCUMENTOS WHERE ID_X_M_DOCUMENTOS='" . $xid_x_m_documentos . "'";
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
}


die('OK');
function preparaDetalle($campos, $tipos, $linea)
{
    global $query;
    global $a_doc;
    global $xid, $id_bache;
    global $xid_x_m_documentos;

    $linea  = substr($linea, 1,-2);
    $reg    = explode('";"', $linea);

    $xpos            = array_search('ID_M_PRODUCTOS', $campos);
    $xid_m_productos = $reg[$xpos];

    $sql = "SELECT COSTO_PROMEDIO FROM M_PRODUCTOS WHERE ID_M_PRODUCTOS='". $xid_m_productos. "'";
    $query->sql = $sql;
    $query->ejecuta_query();
    $query->next_record();


	$xpos_costo      = array_search('COSTO_PROMEDIO',  $campos);
    $xpos_precio     = array_search('PRECIO', $campos);

    $xpos_iva        = array_search('IMPUESTO',  $campos);

    $reg[$xpos_precio] = $query->Record['COSTO_PROMEDIO'];
    $reg[$xpos_iva] = 0;

    $sql = "SELECT ID_I_PROD_ALMA FROM I_PROD_ALMA WHERE ID_M_PRODUCTOS='". $xid_m_productos. "' AND ID_M_ALMACENES='0011'";
    $query->sql = $sql;
    $query->ejecuta_query();
    $query->next_record();


    $sql ="INSERT INTO X_DOCUMENTOS (TEMP,TIPO,ID_X_M_DOCUMENTOS,ID_I_PROD_ALMA,IMPUESTO,COSTO_PROMEDIO,COSTO,PRECIO,DEVUELTOS)  values ('B','DEX','". $xid_x_m_documentos ."','". $query->Record['ID_I_PROD_ALMA'] ."',". $reg[8] .",". $reg[11] .",". $reg[11] .",". $reg[4] .",". $reg[3] .")";
    return $sql;


}

function creaDevolucion($factura, $id_bache)
{
    global $query;
    $xid_m_proveedores = '001681';
    $query->sql     = "SELECT * FROM X_M_DOCUMENTOS WHERE ID_M_PROVEEDORES ='". $xid_m_proveedores ."' AND TIPO ='DEX' AND ESTATUS='A'";
    $query->ejecuta_query();
    if($query->next_record())
    {
       return $query->Record['ID_X_M_DOCUMENTOS'];
    }else
    {
		$sql ="SELECT * FROM M_PROVEEDORES WHERE ID_M_PROVEEDORES ='". $xid_m_proveedores ."'";
        $query->sql = $sql;
        $query->ejecuta_query();
        $query->next_record();

	    $documento_nombre    = substr($query->Record['RAZON'],0,200);
	    $documento_codigo1   = substr($query->Record['CODIGO1'],0,20);
	    $documento_codigo2   = substr($query->Record['CODIGO2'],0,20);
	    $documento_direccion = substr($query->Record['DIRECCION'],0,300);
	    $documento_telefono  = substr($query->Record['TELEFONO1'],0,30);

        $sql ="INSERT INTO X_M_DOCUMENTOS(TIPO,ID_M_PROVEEDORES,CONDICION_PAGO, CAMPO4,CAMPO5,REFERENCIA, DOCUMENTO,NOMBRES,CODIGO1,CODIGO2,DIRECCION, TELEFONO) values ('DEX','". $xid_m_proveedores ."','CRE','REC_BACHE','". $id_bache ."','". $id_bache ."','". $factura ."','". $documento_nombre ."','". $documento_codigo1 ."','". $documento_codigo2 ."','". $documento_direccion ."','". $documento_telefono ."')";
        $query->sql = $sql;
        $query->ejecuta_query();

        $sql ="SELECT * FROM X_M_DOCUMENTOS WHERE UNICO ='".  $query->unico  ."'";
        $query->sql = $sql;
        $query->ejecuta_query();
        $query->next_record();
    	return $query->Record['ID_X_M_DOCUMENTOS'];

    }
}