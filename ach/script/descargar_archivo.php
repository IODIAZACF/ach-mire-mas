<?php
Ini_set('max_execution_time',0);
$xid    = $query[0]->Record['ID_M_ARCHIVOS'];
$xdir = '../../../download/';
@mkdir($xdir,0777);

$archivo = 'http://replica2.sistemas24.net/recepciones/' . $query[0]->Record['CAMPO4'];
$destino = $xdir . $query[0]->Record['CAMPO4'];
$resp = download($archivo, $destino, $query[0]->Record['CAMPO2']);
if($resp)
{
    $resp = marcarRecibido($query[0]->Record);
}
die('OK');

//function marcarRecibido($xid, $nombre,$monto=0)
function marcarRecibido($xreg)
{
	global $query;
    $url   = 'http://replica2.sistemas24.net/actualizar.php?ID_M_ARCHIVOS=' . $xreg['CAMPO3'];
	$ch    =  curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$content = curl_exec($ch);
	curl_close($ch);
    $resp = xml2array($content);
    echo 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
	if($resp['tabla']['registro']['MENSAJE']=='OK')
    {
	    $query[0]->sql = "UPDATE M_ARCHIVOS SET NOMBRES = '". $xreg['CAMPO4'] ."' WHERE ID_M_ARCHIVOS='" . $xreg['ID_M_ARCHIVOS'] . "'";
	    $query[0]->ejecuta_query();
    	return true;
    }
}

function download($archivo, $destino,$checksum,$debug=false)
{
    $gestor = fopen("$archivo", "rb");
    $fp     = fopen("$destino", "w+");
    $contenido = '';
    while (!feof($gestor))
    {
      fwrite($fp,  fread($gestor, 1024*10000));
      echo 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
    }
    fclose($gestor);
    fclose($fp);
    $verificacion = md5_file("$destino");
    if($verificacion==$checksum) return true;
    return false;
}
?>