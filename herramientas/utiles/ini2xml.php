<?php
if (!defined('INI_CLASS')) include_once(Server_Path . "herramientas/ini/class/class_ini.php");
include_once(Server_Path . "herramientas/utiles/comun.php");
include_once(Server_Path . "herramientas/sql/class/class_sql.php");

$Url_Modulo = '';
$Url_Modulo = isset($_REQUEST['url_modulo']) ? dirname($_REQUEST['url_modulo'],2) : '';
if(getvar('origen')){
	if($Url_Modulo!=''){
		$origen = Server_Path . $Url_Modulo  . '/' .  getvar('origen');
		if (str_contains($Url_Modulo, 'herramientas')) {
			$origen = RUTA_SISTEMA . getvar('origen');
		}
		if (str_contains(getvar('origen'), 'maestros/')) {
			$origen = RUTA_SISTEMA . getvar('origen');
		}
	}else{
		$origen = RUTA_SISTEMA . getvar('origen');
	}
}

$my_ini = new ini();
if($origen)
{
    $my_ini->origen  = $origen;
    $my_ini->cargar_ini();
}else{
    $query = new sql();
    $query->tabla		= getvar('tabla');
    $query->campos		= getvar('campos','*');
    $query->busca		= getvar('busca');
    $query->xbusca		= getvar('xbusca');
    $query->operador	= '=';
    $query->arma_sql();
    $query->ejecuta_query();
    $b=1;
    while($query->next_record())
    {
        $campo = 'CAMPO' . $b++;
        $tmp_ini[$campo]            = $query->Record;
        $tmp_ini[$campo]['CAMPO']   = $query->Record['NOMBRES'];
        //$tmp_ini[$campo]['ROTULO']  = noTag (ucfirst(strtolower($query->Record['ROTULO'])));
        $tmp_ini[$campo]['ROTULO']  = ucfirst(strtolower($query->Record['ROTULO']));

        $x = str_replace(' ','',$query->Record['GRUPO']);
        $aGrupo[$x]['ROTULO'] = ucfirst(strtolower($query->Record['GRUPO']));
		
        if($aGrupo[$x]['LINEA_DESDE']=='') $aGrupo[$x]['LINEA_DESDE'] = 10000000;
        if($aGrupo[$x]['LINEA_HASTA']=='') $aGrupo[$x]['LINEA_HASTA'] = 0;
        $aGrupo[$x]['LINEA_DESDE'] = $query->Record['POSICION'] <= intval($aGrupo[$x]['LINEA_DESDE']) ?  $query->Record['POSICION'] : intval($aGrupo[$x]['LINEA_DESDE']);
        $aGrupo[$x]['LINEA_HASTA'] = $query->Record['POSICION'] >= intval($aGrupo[$x]['LINEA_HASTA']) ?  $query->Record['POSICION'] : intval($aGrupo[$x]['LINEA_HASTA']);;
	}
   	$b=1;
   	reset($aGrupo);
   	while (list($ngrupo, $valor) = each($aGrupo))
   	{
        $grupo = 'GRUPO' . $b++;
        $tmp_ini[$grupo]            = $valor;
   	}

    $my_ini->archivo_ini = $tmp_ini;
    $my_ini->generaXML();
}
header('Content-Type: application/xml');
header('Expires: Fri, 1 Ene 1980 00:00:00 GMT'); //la pagina expira en fecha pasada
header('Last-Modified: ' . gmdate("D, d M Y H:i:s"));// . ' GMT
header('Cache-Control: no-cache, must-revalidate');//
header('Pragma: no-cache');

echo $my_ini->xml;
?>