<?php
define("Server_Path","../../");
include_once(Server_Path . "herramientas/utiles/comun.php");
include_once(Server_Path . "herramientas/sql/class/class_sql.php");
include_once(Server_Path . "herramientas/ini/class/class_ini.php");

$Usa_log = true;

control_session();
$my_ini = new ini();
$query = new sql();
$query->sql  = "SELECT " . getvar('campos') . " FROM "  . getvar('tabla');

if(getvar('busca')) $query->sql .= " WHERE " . getvar('busca') . " = '"  . getvar('xbusca') . "'";
$query->sql .= " ORDER BY POSICION ";
$query->ejecuta_query();
$b=1;

while($query->next_record())
{
	$TID  =  $query->arreglo_atributos[0]['NOMBRE'];
    $campo = 'CAMPO' . $b++;
    $tmp_ini[$campo]            = $query->Record;
    //$tmp_ini[$campo]['CAMPO']   = $query->Record['CAMPO'] . '_'. $query->Record[$TID];
    $tmp_ini[$campo]['CAMPO']   = $query->Record['TIPO'] . $query->Record[$TID];
    $x = str_replace(' ','',$query->Record['GRUPO']);
    if($x)
    {
        $aGrupo[$x]['ROTULO'] = ucfirst(strtolower($query->Record['GRUPO']));
        if($aGrupo[$x]['LINEA_DESDE']=='') $aGrupo[$x]['LINEA_DESDE'] = 10000000;
        if($aGrupo[$x]['LINEA_HASTA']=='') $aGrupo[$x]['LINEA_HASTA'] = 0;
        $aGrupo[$x]['LINEA_DESDE'] = $query->Record['POSICION'] <= intval($aGrupo[$x]['LINEA_DESDE']) ?  $query->Record['POSICION'] : intval($aGrupo[$x]['LINEA_DESDE']);
        $aGrupo[$x]['LINEA_HASTA'] = $query->Record['POSICION'] >= intval($aGrupo[$x]['LINEA_HASTA']) ?  $query->Record['POSICION'] : intval($aGrupo[$x]['LINEA_HASTA']);;
    }
}
$b=1;
if(is_array($aGrupo))
{
    reset($aGrupo);
    while (list($ngrupo, $valor) = each($aGrupo))
    {
        $grupo = 'GRUPO' . $b++;
        $tmp_ini[$grupo]            = $valor;
    }
}
$my_ini->archivo_ini = $tmp_ini;
$my_ini->generaXML();
echo $my_ini->xml;
?>