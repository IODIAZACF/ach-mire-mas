<?php
set_time_limit(0);
define("Server_Path", "../../");
include (Server_Path . "herramientas/sql/class/class_sql.php");

$fp = fopen("c:\v_d_documentos.txt","w+");
$fp2 = fopen("c:\v_d_documentos_up.txt","w+");

$query1 = new sql();
$query = new sql();
$query1->sql = "SELECT * FROM V_I_PROD_ALMA WHERE ID_M_PRODUCTOS ='" . getvar('ID_M_PRODUCTOS'). "' AND  ID_M_ALMACENES='0011'";
$query1->ejecuta_query();
$query1->crear_arreglo();
echo "<pre>";
for($j=0;$j<sizeof($query1->arreglo);$j++)
{

    $xprod      =  $query1->arreglo[$j];
    $xsaldo     =  $xprod['INVENTARIO_INICIAL'];
    //$query->sql = "select * from v_d_documentos where id_m_productos='". $xprod['ID_M_PRODUCTOS'] ."' order by CAST(FECHA_DOCUMENTO AS DATE),cast(id_d_documentos as integer)";
    $query->sql = "select * from v_d_documentos where id_m_productos='". $xprod['ID_M_PRODUCTOS'] ."' ORDER BY ID";
    $query->ejecuta_query();
    $query->crear_arreglo();
    $sql = '';

    $xxc = $xprod['ID_M_PRODUCTOS'] . "  saldo inicial: " . $xsaldo . " - ".  $reg['DESCRIPCION'] ."\n";
    echo $xxc . "<br>";
    for($i=0;$i<sizeof($query->arreglo);$i++)
    {
        $reg = $query->arreglo[$i];
        $xcant = $reg['CANTIDAD'];
        $xtipo = $reg['TIPO'];
        switch($xtipo)
        {
        	case 'PRE':
            	$xcant = 0;
            	break;
        	case 'DEV':
            	break;
        	case 'FAC':
                $xcant = $xcant * -1;
                break;
        	case 'COM':
                break;
        	case 'AJU':
                $xcant = $xcant * $reg['SIGNO_AJUSTE'];
                break;
        }

    	$xxc  = str_pad($xcontenido[0], 6);
        $xxc .= str_pad($reg['ID'], 10,' ',STR_PAD_LEFT) . '   ';
        $xxc .= str_pad($reg['ID_M_DOC_FINAL'], 10,' ',STR_PAD_LEFT) . '   ';
        $xxc .= str_pad($reg['FECHA_DOCUMENTO'], 20);
        $xxc .= str_pad($xcant, 8);
        $xxc .= str_pad($xtipo, 8);
        $xxc .= str_pad($xsaldo, 8);
        $xsaldo += $xcant;
        $xxc .= str_pad($xsaldo, 8);

        echo $xxc ."\n" ;

    }
}

?>