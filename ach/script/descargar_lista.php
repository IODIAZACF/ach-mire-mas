<?php


$xdir = '../../../download/';
@mkdir($xdir,0777);

$url     = 'http://replica2.sistemas24.net/lista.php?CLASE='. $_REQUEST['CLASE'];

$ch      =  curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$content = curl_exec($ch);
curl_close($ch);
die('DEBUG EDSON ->'. );
$resp    = xml2array($content);
$t       = sizeof($resp['tabla']['registro'][0]);


//rdebug(xml2array,'s');

$query = new sql(0);

if($t)
{
    $reg = $resp['tabla']['registro'];
	for($i=0;$i<sizeof($reg);$i++)
	{
    	echo 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
        $resp = marcarRecibido($reg[$i]);
	}
}
else
{
    $reg = $resp['tabla']['registro'];
    if(is_array($reg))
    {
        echo 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
        $resp = marcarRecibido($reg);
    }
}
die('OK');
function marcarRecibido($xreg)
{
    global $query;
    if(is_array($xreg['id_m_doc_final']))
    {
        $xreg['id_m_doc_final'] = substr($xreg['nombres'],0, -4);
    }
    if(is_array($xreg['total_costo_promedio']))
    {
        $xreg['total_costo_promedio'] = 0;
    }
    if($xreg['total_costo_promedio']==0) $xreg['total_costo_promedio'] = $xreg['monto'];


    $query->sql = "SELECT * FROM M_ARCHIVOS WHERE ID_M_DOC_FINAL='". $xreg['id_m_doc_final'] ."' AND CLASE ='".  $_REQUEST['CLASE'] ."'";
    $query->ejecuta_query();
    if(!$query->next_record())
    {
        $query->beginTransaction();
	    $xsql        = "INSERT INTO M_ARCHIVOS (CAMPO3,CAMPO2,CAMPO4, ESTATUS,CAMPO1,CLASE,ID_M_DOC_FINAL,MONTO,TOTAL_COSTO_PROMEDIO) VALUES ('". $xreg['id_m_archivos'] ."','". $xreg['campo2'] ."','". $xreg['nombres'] ."', 'PEN','REC','". $_REQUEST['CLASE'] ."','". $xreg['id_m_doc_final'] ."', " . $xreg['monto'] .", " . $xreg['total_costo_promedio'] .")";
	    $query->sql = $xsql;
	    $query->ejecuta_query();
	    $query->commit();
    }
    return true;
}
?>