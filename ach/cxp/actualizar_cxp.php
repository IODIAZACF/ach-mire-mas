<?
define("Server_Path","../");
include_once(Server_Path . "herramientas/utiles/comun.php");
include_once(Server_Path . "herramientas/sql/class/class_sql.php");
include_once(Server_Path . "herramientas/ini/class/class_ini.php");

$Usa_log   = true;
$variables = leer_vars2("c_");
$query = new sql();

$query->beginTransaction();
/*
$query->sql = 'INSERT INTO D_PAGOS (
                  IDX,
                  TABLA,
                  DEBITOS,
               	  COMENTARIOS

)
               VALUES (

               	  :IDX,
               	  :TABLA,
                  :DEBITOS,
                  :COMENTARIOS
)';

$query->sql = parsearSql($query->sql, $variables);

$q1 = $query->sql;
$query->ejecuta_query();

$error = '';
$unico = 'OK';
if ($query->erro)
{
  $error = $query->erro_msg;
  $unico = '';
}


if (!$error)
{

  $unico = $query->unico;
  $query->sql = 'select * from d_pagos where unico = \''.$unico.'\'';
  $query->ejecuta_query();
  $query->next_record();
  $idx = $query->Record['ID_D_PAGOS'];
*/

  $query->sql = 'INSERT INTO D_CXCCXP(
              ID_PADRE,
              TIPO,
              ID_M_CONCEPTOS,
              NOMBRE_CONCEPTO,
              DEBITOS,
              IDX,
              TABLA,
              CLASE,
              FECHA_DOCUMENTO,
              DOCUMENTO,CONDICION1,FECHA1)
  VALUES (
              :ID_PADRE,
              :TIPO,
              :ID_M_CONCEPTOS,
              :NOMBRE_CONCEPTO,
              :DEBITOS,
              :IDX,
              :TABLA,
              :CLASE,
              :FECHA_PAGO,
              :DOCUMENTO,
              NULL,:FECHA_PAGO)';

  $query->sql = parsearSql($query->sql, $variables);
  $q2 = $query->sql;
  $query->ejecuta_query();
  if ($query->erro)
  {
    $error = $query->erro_msg;
  }
/*}
*/
$unico = $query->unico; //Eduardo
$query->commit();
//- fin --

  echo '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n\n";
  echo '<tabla>' . "\n";
  echo '<registro>' . "\n";
  echo '<query>'."\n";
  echo '<![CDATA['."\n";
  print_r($query->sql);
  echo ']]>'."\n";
  echo '</query>'."\n";
  echo '<ERROR>' . "\n";
  echo '<![CDATA['.$error.']]>' . "\n";
  echo '</ERROR>' . "\n";
  echo '<QUERY>'. "\n";
  echo '<![CDATA['. $q1 . $q2. ']]>';
  echo '</QUERY>' . "\n";
  echo '<UNICO>'. $unico .'</UNICO>' . "\n";
  echo '<RETORNO></RETORNO>' . "\n";
  echo '</registro>' . "\n";
  echo '</tabla>' . "\n";


function leer_vars2($prefijo="")
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') $arr = $_POST;
    else $arr = $_GET;

    foreach($arr as $key => $value) {
        if(preg_match ("/^" . $prefijo . "\S+$/i", $key))
        {
            $nombre = str_replace($prefijo, '', $key);
            $attrib = substr($nombre,-3);
            $tipo   = substr($attrib,0,1);
            $guardar= substr($attrib,1,1);
            $nombre = substr($nombre,0,sizeof($nombre)-5);
            $items['valor']     = $value;
            $items['tipo']      = $tipo;
            $items['guardar']   = $guardar;
            if($nombre) $variables[$nombre] = $items;
        }
    }

    return $variables;
}

function parsearSql($sql, $variables)
{

  foreach($variables as $campo => $props)
  {
    switch ($props['tipo'])
    {
      case 'D':
        $valor = $props['valor'];
        $valor = "'".substr($valor,3,2).'/'.substr($valor,0,2).'/'. substr($valor,6)."'";
        break;

	  case 'R':
        $valor = $props['valor'];
        if(strlen($valor)) $valor = "'".strtoupper($props['valor'])."'";
        else $valor = "NULL";
        break;

      case 'N':
        $valor = str_replace(',','',$props['valor']);
        break;

      default:
        $valor = "'".strtoupper($props['valor'])."'";
        break;
    }
    $sql = str_replace(':'.$campo, $valor, $sql);
  }
  return $sql;
}
?>