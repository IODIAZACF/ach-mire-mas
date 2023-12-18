<?php
//---------------------Genrera XML a partir de una base de datos
define("Server_Path", "../../");
include_once (Server_Path. "herramientas/sql/class/class_sql.php");
include_once (Server_Path. "herramientas/ini/class/class_ini.php");
include_once (Server_Path. "herramientas/genera_xml/class/class_genera_xml.php");

$id_usuarios = getvar('usuario'); //-- OJO: por sesión
$header = getvar('header');
$sql = "select * from m_menu order by posicion";
$query = new sql();
$query->sql = $sql;

$query->ejecuta_query();
$datos=null;

while ($query->next_record()) $datos[]=$query->Record;
class menu
{
  var $id     = '';
  var $rotulo = '';
  var $url    = '';
  var $params = '';
  var $target = '';
  var $icono  = '';
  var $posicion='';
  var $padre   ='';
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
   /*
    $res                    .= $sp . '<'.$tag.'>'."\n";
    $res                    .= $sp . '  <ID_M_MENU>' . $elem->id .'</ID_M_MENU>'."\n";
    $res                    .= $sp . '  <ROTULO><![CDATA[' . str_repeat('. . . . ', $nivel). $rot .']]></ROTULO>'."\n";
    if ($elem->url)    $res .= $sp . '  <URL>'. $elem->url . '?' . $elem->params . '</URL>'."\n";
    if ($elem->target) $res .= $sp . '  <TARGET>'.$elem->target.'</TARGET>'."\n";
    if ($elem->icono)  $res .= $sp . '  <ICONO>'.$elem->icono.'</ICONO>'."\n";
    $res.= $sp . '</'.$tag.'>'."\n";   */


    $res                    .= $sp . '<'.$tag.'>'."\n";
    $res                    .= $sp . '  <ID_M_MENU>' . $elem->id .'</ID_M_MENU>'."\n";
    $res                    .= $sp . '  <ROTULO><![CDATA[' . str_repeat('. . . . ', $nivel). $rot .']]></ROTULO>'."\n";
    if ($elem->url)    $res .= $sp . '  <URL><![CDATA['. $elem->url . '?' . $elem->params . ']]></URL>'."\n";
    if ($elem->target) $res .= $sp . '  <TARGET><![CDATA['.$elem->target.']]></TARGET>'."\n";
    if ($elem->icono)  $res .= $sp . '  <ICONO><![CDATA['.$elem->icono.']]></ICONO>'."\n";
    $res                    .= $sp . '  <POSICION>' . $elem->posicion .'</POSICION>'."\n";
    $res                    .= $sp . '  <PADRE>'    . $elem->padre    .'</PADRE>'."\n";
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
    $m->id     = $datos[$i]['ID_M_MENU'];
    $m->rotulo = $datos[$i]['ROTULO'];
    $m->url    = $datos[$i]['URL'];
    $m->params = $datos[$i]['PARAMS'];
    $m->target = $datos[$i]['TARGET'];
    $m->icono  = $datos[$i]['ICONO'];
    $m->posicion  = $datos[$i]['POSICION'];
    $m->padre     = $datos[$i]['ID_PADRE'];

    $padre = $datos[$i]['ID_PADRE'];

    if (!$padre) $padre = 0;

    $arMenu[$padre][] = $m;
  }


  $sp = '';

/*  if ($header)
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
*/

//print_r($arMenu);

	$xml .= "<tabla>\n  " . armaMenu($arMenu, 0, 'registro', $sp) . "</tabla>\n";
  	print_r($xml);
}

?>