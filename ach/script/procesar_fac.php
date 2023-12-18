<?php
Ini_set('max_execution_time',0);
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
$xid_x_m_documentos =  creaFactura();
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
	        $xvalores = preparaDetalle($campos, $tipos, $linea);
	        $sql = 'INSERT INTO X_DOCUMENTOS (ID_X_M_DOCUMENTOS,' . str_replace(';',',', $xcampos) . ') VALUES (\''. $xid_x_m_documentos . '\','. $xvalores . ')';
	        $query->sql = $sql;
	        $query->ejecuta_query();
	        echo '000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
	        if(strlen($query->regi['ERROR']))
	        {
	            echo $query->regi['ERROR'];
	            die();
	        }
        }
    }
}
$sql = "UPDATE M_ARCHIVOS SET ESTATUS = 'PRO' WHERE ID_M_ARCHIVOS='" . $xid . "'";
$query->sql = $sql;
$query->ejecuta_query();

die('OK');
function preparaDetalle($campos, $tipos, $linea)
{
    global $query;

    $linea  = substr($linea, 1, strlen($linea)-2);
    $reg    = explode('";"', $linea);
	$xpos = array_search('ID_M_PRODUCTOS', $campos);
    $xid_m_productos = $reg[$xpos];

    $sql = "SELECT ID_I_PROD_ALMA FROM I_PROD_ALMA WHERE ID_M_PRODUCTOS='". $xid_m_productos. "' AND ID_M_ALMACENES='00119'";
    $query->sql = $sql;
    $query->ejecuta_query();
    $query->next_record();

	$xpos       = array_search('ID_I_PROD_ALMA', $campos);
    $reg[$xpos] = $query->Record['ID_I_PROD_ALMA'];

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

function creaFactura()
{
    global $query;
    $query->sql = "SELECT * FROM X_M_DOCUMENTOS WHERE ID_M_CLIENTES ='00110354' AND TIPO ='FAC' AND ESTATUS='A'";
    $query->ejecuta_query();
    if($query->next_record())
    {
    	return $query->Record['ID_X_M_DOCUMENTOS'];
    }else
    {
        $sql ="INSERT INTO X_M_DOCUMENTOS(TEMP,TIPO,ID_M_CLIENTES,CONDICION_PAGO,CAMPO4) values (0,'FAC','00110354','CRE','BACHE')";
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


