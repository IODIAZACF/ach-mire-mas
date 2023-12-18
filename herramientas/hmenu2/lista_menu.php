<?php
//---------------------Genrera XML a partir de una base de datos
define("Server_Path", "../../");
include_once (Server_Path . "herramientas/utiles/comun.php"); 
include_once (Server_Path. "herramientas/sql/class/class_sql.php");
include_once (Server_Path. "herramientas/ini/class/class_ini.php");
include_once (Server_Path. "herramientas/genera_xml/class/class_genera_xml.php");

$id_usuarios = getvar('usuario'); //-- OJO: por sesión
$header = getvar('header');
//$grupo  = getsession('M_GRUPOS_ID_GRUPOS'); //-- variable de session de M_GRUPOS->ID_GRUPOS cargado por password.php
$grupo = getvar('ID_M_GRUPOS');
/*
if (($grupo == '0011') || (!$grupo)) $sql = "select * from m_menu order by posicion";
else $sql = "select * from v_I_MENU_GRUP where id_m_grupos = '$grupo' order by posicion"; */
$sql = "select * from V_I_MENU_GRUP where id_m_grupos = '$grupo' order by posicion";

//$sql = "select * from v_d_menu where id_m_grupos = '$grupo' order by posicion";
$query = new sql();
$query->sql = $sql;

$query->ejecuta_query();
//$datos = $query->crear_arreglo();
$datos=null;

while ($query->next_record()) $datos[]=$query->Record;

class menu
{
  var $id     	= '';
  var $rotulo 	= '';
  var $url    	= '';
  var $params 	= '';
  var $padres  	= '';
  var $posicion = '';
  var $target 	= '';
  var $icono  	= '';
  var $interse  = '';
}

function armaMenu($arMenu, $id, $tag, $sp, $nivel=0)
{
  $res = '';

  for ($i=0;$i<sizeof($arMenu[$id]);$i++)
  {
    $elem = $arMenu[$id][$i];

    $rot=$elem->rotulo;
    if ($nivel > 0)
    {
      $rot = ucfirst(strtolower($rot));
    }
    $res                    .= $sp . '<'.$tag.'>'."\n";
    $res                    .= $sp . '  <ID_M_MENU>' . $elem->id .'</ID_M_MENU>'."\n";
    $res                    .= $sp . '  <ROTULO><![CDATA[' . str_repeat('. . . . ', $nivel). $rot .']]></ROTULO>'."\n";
    if ($elem->url)    $res .= $sp . '  <URL><![CDATA['. $elem->url . '?' . $elem->params . ']]></URL>'."\n";
    $res                    .= $sp . '  <ID_PADRE><![CDATA['.$elem->padres.']]></ID_PADRE>'."\n";
    $res                    .= $sp . '  <POSICION><![CDATA['.$elem->posicion.']]></POSICION>'."\n";
    $res                    .= $sp . '  <ID_I_MENU_GRUP><![CDATA['.$elem->interse.']]></ID_I_MENU_GRUP>'."\n";
    if ($elem->target) $res .= $sp . '  <TARGET><![CDATA['.$elem->target.']]></TARGET>'."\n";
    if ($elem->icono)  $res .= $sp . '  <ICONO><![CDATA['.$elem->icono.']]></ICONO>'."\n";
    $res.= $sp . '</'.$tag.'>'."\n";

    if (sizeof($arMenu[$elem->id])>0)
    {
      $res .= armaMenu($arMenu, $elem->id, 'registro', $sp . '  ', $nivel+1);
    }
  }
  return $res;
}

$hayError = !is_array($datos);

if ($hayError)
{
  $msg = $datos;
  $msg = explode('|',$msg);
  unset($datos);
  $datos[0]['MENSAJE'] = $msg[0];
  $datos[0]['QUERY']   = $msg[1];
}
else
{
  $arMenu  = Array();

  for ($i=0;$i<sizeof($datos);$i++)
  {
    $m = new menu;
    $m->id        = $datos[$i]['ID_M_MENU'];
    $m->rotulo    = $datos[$i]['ROTULO'];
    $m->url       = $datos[$i]['URL'];
    $m->params    = $datos[$i]['PARAMS'];
    $m->padres    = $datos[$i]['ID_PADRE'];
    $m->posicion  = $datos[$i]['POSICION'];
    $m->interse   = $datos[$i]['ID_I_MENU_GRUP'];
    $m->target    = $datos[$i]['TARGET'];
    $m->icono     = $datos[$i]['ICONO'];



    $padre = $datos[$i]['ID_PADRE'];

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
  }

  $xml = '<?xml version="1.0"  encoding="iso-8859-1"?>';
  $xml .= "\n\n<tabla>\n  " . armaMenu($arMenu, 0, 'registro', $sp) . "</tabla>\n";
  print_r($xml);
}

?>