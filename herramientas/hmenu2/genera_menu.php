<?php
//---------------------Genrera XML a partir de una base de datos
include_once (RUTA_HERRAMIENTAS. "/herramientas/utiles/comun.php");
include_once (RUTA_HERRAMIENTAS. "/herramientas/sql/class/class_sql.php");
include_once (RUTA_HERRAMIENTAS. "/herramientas/ini/class/class_ini.php");
include_once (RUTA_HERRAMIENTAS. "/herramientas/genera_xml/class/class_genera_xml.php");

$id_usuarios = getvar('usuario'); //-- OJO: por sesi�n
$header = getvar('header');
$grupo  = getsession('M_GRUPOS_ID_GRUPOS'); //-- variable de session de M_GRUPOS->ID_GRUPOS cargado por password.php

if ($grupo == '0011' || $grupo == 'XXXXGRUP0011' ) $sql = "SELECT M_MENU.ID_M_MENU,M_MENU.ROTULO,M_MENU.POSICION,IIF(M_MENU.URL IS NOT NULL,'/'||LOWER((SELECT SISTEMA FROM CONFIGURACION ))||'/'||M_MENU.URL,NULL) URL,M_MENU.PARAMS,M_MENU.TARGET,M_MENU.ICONO,M_MENU.ID_PADRE FROM M_MENU ORDER BY POSICION";
else $sql = "select * from v_i_menu_grup where id_m_grupos = '$grupo' order by posicion";

//$sql = "select * from v_d_menu where id_m_grupos = '$grupo' order by posicion";
$query = new sql();
$query->sql = $sql;

$query->ejecuta_query();
//$datos = $query->crear_arreglo();

class menu
{
  var $id     = '';
  var $rotulo = '';
  var $url    = '';
  var $params = '';
  var $target = '';
  var $icono  = '';
}

function armaMenu($arMenu, $id, $tag, $sp)
{
  $res = '';

  for ($i=0;$i<sizeof($arMenu[$id]);$i++)
  {
    $elem = $arMenu[$id][$i];

    $res                    .= $sp . '<'.$tag.'>'."\n";
    $res                    .= $sp . '  <ROTULO><![CDATA[' . $elem->rotulo .']]></ROTULO>'."\n";
    if ($elem->url)    $res .= $sp . '  <URL><![CDATA['. $elem->url . '?' . $elem->params . ']]></URL>'."\n";
    if ($elem->target) $res .= $sp . '  <TARGET><![CDATA['.$elem->target.']]></TARGET>'."\n";
    if ($elem->icono)  $res .= $sp . '  <ICONO><![CDATA['.$elem->icono.']]></ICONO>'."\n";
    if (@count($arMenu[$elem->id])>0)
    {
      $res .= armaMenu($arMenu, $elem->id, 'ITEM', $sp . '  ');
    }
    $res .= $sp . '</'.$tag.'>'."\n";
  }
  return $res;
}

  $arMenu  = Array();

  while($query->next_record())
  {
    $m = new menu;
    $m->id     = $query->Record['ID_M_MENU'];
    $m->rotulo = $query->Record['ROTULO'];
    $m->url    = $query->Record['URL'];
    $m->params = $query->Record['PARAMS'];
    $m->target = $query->Record['TARGET'];
    $m->icono  = $query->Record['ICONO'];

    $padre = $query->Record['ID_PADRE'];

    if (!$padre) $padre = 0;

    $arMenu[$padre][] = $m;
  }


  $sp = '';

  if ($header)
  {
    header('content-type: text/xml
		Expires: Fri, 1 Ene 1980 00:00:00 GMT"); //la pagina expira en fecha pasada
		Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT
		Cache-Control: no-cache, must-revalidate
		Pragma: no-cache
	');
  }else{
	 header('Content-Type: application/xml');
	 echo '<?xml version="1.0" encoding="ISO-8859-1"?>' . "\n";	  
  }

  echo "<tabla>\n";
  echo armaMenu($arMenu, 0, 'registro', $sp);
  echo "</tabla>";

?>