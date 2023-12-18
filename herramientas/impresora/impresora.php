<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
set_time_limit(0);
$t_ini = time();
include_once (Server_Path . "herramientas/utiles/comun.php");
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . "herramientas/sql/class/class_sql.php");
include_once (Server_Path . "herramientas/numero_letras/numero_letras.php");

$db_server[0]['DB']         = "/opt/lampp/firebird/db/". $_REQUEST['db'] .".gdb";
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

if (isset($_REQUEST['file']))
{  
  $reporte = file_get_contents ($origen . '.fr3');   
  $reporte = preg_replace("/DataSetName=\"(\w+)(\s+)\"/", 'DataSetName="$1"', $reporte);
  $reporte = preg_replace("/(\w+)(\s+)\.\&\#34\;/", "$1.&#34;", $reporte);
  file_put_contents ($origen .'.fr3', $reporte );

  echo $reporte;
  die();
}

define('IMPRESO_DEBUG', isset($_REQUEST['impresora_debug']) ? true : false );

if(IMPRESO_DEBUG) include (Server_Path . "herramientas/impresora/impresora_verifica.php");
$header  = getvar('header');
$letras  = getvar('letras');

$my_ini = new ini($origen);
$query  = new sql();

$cnumero_letras = new numero_letras();
$cnumero_letras->setGenero(1);
$cnumero_letras->setMoneda("CON");
$cnumero_letras->setPrefijo("***");
$cnumero_letras->setSufijo("***");


if (strlen($my_ini->error)){
	echo $my_ini->error;
	die();
}


$header =true;
if(!IMPRESO_DEBUG ){
	header('Content-Type: application/xml');
}

if ($header)
{
  if((isset($_REQUEST['download']) && $_REQUEST['download']!='1' ) && !IMPRESO_DEBUG)
  {
	  header('Expires: Fri, 1 Ene 1980 00:00:00 GMT'); //la pagina expira en fecha pasada
	  header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
	  header('Cache-Control: no-cache, must-revalidate');
	  header('Pragma: no-cache');
	  Escribe('<?xml version="1.0"  encoding="iso-8859-1"?>');
  }
}

Escribe('<?xml version="1.0"  encoding="iso-8859-1"?>'."\n");
Escribe('<reporte>'."\n");

//------- seccion de exportar -----/

$export=$my_ini->seccion('EXPORTAR');
if ($export)
{
  Escribe('<exportacion>'."\n");
  while (list($variable, $valor) = each($export))
  {
    Escribe('<'.$variable.'>'.$valor.'</'.$variable.'>'."\n");
  }
  Escribe('</exportacion>'."\n");
}


//--------------------------------------- SECCION DE VARIABLES -----------------

Escribe('<variables>'."\n");

$variables = $my_ini->seccion('VARIABLE');
$nquery=0;

if (is_array($variables))
{
  unset($variables['ONOMBRE']);
  while (list($variable, $xsql) = each($variables))
  {
      preg_match_all('/({.+?})/', $xsql, $arr);
      for ($i=0;$i<sizeof($arr[0]);$i++)
      {
        $param=substr($arr[0][$i],1,strlen($arr[0][$i])-2);
        $xsql = str_replace($arr[0][$i], $_REQUEST[$param], $xsql);
      }
	  
	  $xsql  = str_replace("&lt;", '<', $xsql);
	  $xsql  = str_replace("&gt;", '>', $xsql);
	  $xsql = str_replace('__S24_EOL__', PHP_EOL,  $xsql);
      $query->sql = trim($xsql);
      $query->ejecuta_query();
	
	 If(IMPRESO_DEBUG){
		 $LogInfo['<b>SQL Variable: </b>' . $variable ] = $query->sql;
		foreach($query->arreglo_atributos as $id => $attr){
			verifica_variable ($variable .'_' . $attr['NOMBRE'], $variable); 
			if ((($attr['TIPO']=='N') || ($attr['TIPO']=='I')) && ($letras)){
				verifica_variable ($variable.'_'.$attr['NOMBRE'] .'_LETRAS', $variable);
			}			
		}
	 } 

		
      if($query->next_record()){
        $n=0;
        foreach($query->Record as $campo => $cvalor)
        {
          $origvalor=$cvalor;
          if (($query->arreglo_atributos[$n]['TIPO']=='D')||($query->arreglo_atributos[$n]['TIPO']=='T'))
          {
            $cvalor=fechaDMY($cvalor);
          }
          $cvalor='<![CDATA['.$cvalor.']]>';
          Escribe('<'.$variable.'_'.$campo.' TIPO="'.$query->arreglo_atributos[$n]['TIPO'].'">'.$cvalor.'</'.$variable.'_'.$campo.'>'."\n");
          if ((($query->arreglo_atributos[$n]['TIPO']=='N') || ($query->arreglo_atributos[$n]['TIPO']=='I')) && ($letras))
          {
            $cnumero_letras->setNumero($origvalor);
            Escribe('<'.$variable.'_'.$campo.'_LETRAS TIPO="C"><![CDATA['.$cnumero_letras->letra().']]></'.$variable.'_'.$campo.'_LETRAS>'."\n");
          }
          $n++;

        }
      }
      Escribe('<QUERY'.(++$nquery).'><![CDATA['.$xsql.']]></QUERY'.($nquery).'>'."\n");

  }
}

//--- agregado por MARCO 27/03/2008 para variables en la seccion "VARIABLES" del ini del reporte "r_xxxxx.ini"
// Ej:
// [VARIABLES]
// XXX_NOMBRE=PEDRO PEREZ
//
// luego aparecer� en el editor del reporte como [XXX_NOMBRE]

$variables = $my_ini->seccion('VARIABLES');
if ($variables) while (list($variable, $valor) = each($variables))
{
  Escribe('<'.$variable.' TIPO="">');
  Escribe('<![CDATA[');
  print_r($valor);
  Escribe(']]></'.$variable.'>');
}
//-- fin

  Escribe('</variables>'."\n");

//--------------------------------------- SECCION DE SESION --------------------

Escribe('<sesion>'."\n");

//print_r ($_SESSION);
foreach($_SESSION as $nom => $valor)
{  
  Escribe('  <'.$nom.'><![CDATA['.$valor.']]></'.$nom.'>'."\n");
  if(IMPRESO_DEBUG) verifica_variable ($nom, 'SESSION');
}
Escribe('  <ip><![CDATA['. $_SERVER['REMOTE_ADDR'] .']]></ip>'."\n");
Escribe('  <fecha><![CDATA['. date("d/m/Y") .']]></fecha>'."\n");
Escribe('  <hora><![CDATA['. date("H:i:s") .']]></hora>'."\n");
//Escribe('  <tiempo><![CDATA['. $t .']]></tiempo>'."\n");
Escribe('</sesion>'."\n");

//--------------------------------------- SECCION DE DATOS ---------------------

function fechaDMY($valor)
{
  if (!$valor) $vsalida='';
  else $vsalida = substr($valor,8,2).'/'.substr($valor,5,2).'/'. substr($valor,0,4).' '.substr($valor,11);
  return $vsalida;
}

function procesa_condic($bloque)
{
  global $my_ini;
  $ret='';
  $cond=$my_ini->variable($bloque, 'CONDICIONES');
  if (!$cond) return '';

  $arrcond=explode(',',$cond);

  if (!is_array($arrcond)) return '';
  //rdebug($arrcond,'1');

  for($index=0;$index<sizeof($arrcond);$index++)
  {
    $sec_cond=$my_ini->seccion($arrcond[$index]);
    if (is_array($sec_cond) && (sizeof($sec_cond)>0))
    {
      $x_var=$my_ini->variable($arrcond[$index], 'VARIABLE');
      $x_sql=$my_ini->variable($arrcond[$index], 'CONDICION');
      $x_cond=$my_ini->variable($arrcond[$index], 'CONECTOR');

      if (!$x_cond) $x_cond=' AND ';
      else $x_cond=' '.$x_cond.' ';

      if ($_REQUEST[$x_var] || ($_REQUEST[$x_var]!=''))
      {
        preg_match_all('/({.+?})/', $x_sql, $arr);
        for ($i=0;$i<sizeof($arr[0]);$i++)
        {
          $param  = substr($arr[0][$i],1,strlen($arr[0][$i])-2);
          $xvalue = str_replace('|','\',\'',$_REQUEST[$param]);
          $x_sql  = str_replace($arr[0][$i], $xvalue, $x_sql);


          //Escribe($arr[0][$i] . " --> " . $_REQUEST[$param]  . " --> " . $xvalue . "<br>";
        }
        if ($ret!='') $ret.=$last_cond;
        $ret.=$x_sql;
        $last_cond=$x_cond;
      }
    }
  }
  //die($ret);
  return $ret;
}

$n=1;
$bloque = $my_ini->seccion('BLOQUE'.$n);

if(isset($_REQUEST['download']) && $_REQUEST['download']=='1')
{
	$fichero = '/opt/tmp/' . session_id() . '.csv';
	$fp = fopen("$fichero","w+");
}

while (is_array($bloque) && (sizeof($bloque)>0))
{
  Escribe('<BLOQUE'.$n.' GRUPO="'. $bloque['GRUPO'].'">'."\n");

  $xsql = trim($bloque['SQL']);
  if(isset($bloque['WHERE'])) $xsql.=' WHERE '. $bloque['WHERE'];
  preg_match_all('/({.+?})/', $xsql, $arr);
  for ($i=0;$i<sizeof($arr[0]);$i++)
  {
    $param=substr($arr[0][$i],1,strlen($arr[0][$i])-2);
    $xsql = str_replace($arr[0][$i], $_REQUEST[$param], $xsql);
  }
  

  //----- PROCESO LAS CONDICIONES PARA ESTE BLOQUE... --------
  $condiciones = procesa_condic('BLOQUE'.$n);
  if ($condiciones)
  {
    $nn=strpos(strtoupper($xsql), 'WHERE');
    if ($nn===false)
    {
      $xsql.=' WHERE '.$condiciones;
    }
    else
    {
      $xsql=str_ireplace('where',' where '.$condiciones.' and ',$xsql);
    }
  }
  
	if( isset( $bloque['GROUP'] ) ){
		if( strlen( $bloque['GROUP'] ) ){
			$xsql.=' GROUP BY '. $bloque['GROUP'];
		}		
	}

	if ( isset( $bloque['ORDEN'] ) ){
		if ( strlen( $bloque['ORDEN'] ) ){
			$xsql.=' ORDER BY '.$bloque['ORDEN'];
		}
	}
  //Escribe($xsql;
  
  $xsql  = str_replace("&lt;", '<', $xsql);
  $xsql  = str_replace("&gt;", '>', $xsql);
  
  

  Escribe('<SQL>'."\n");
  Escribe('<![CDATA['.$xsql.']]>'."\n");
  Escribe('</SQL>'."\n");

  $xsql = str_replace('__S24_EOL__', PHP_EOL, $xsql); 
  
  preg_match_all('/({.+?})/', $xsql, $arr);
  for ($i=0;$i<sizeof($arr[0]);$i++)
  {
    $param=substr($arr[0][$i],1,strlen($arr[0][$i])-2);
    $xsql = str_replace($arr[0][$i], $_REQUEST[$param], $xsql);
  }
  
  $query->sql = trim($xsql);
  $query->ejecuta_query();
  $rec=0;
  $encabezado='';
  
  If(IMPRESO_DEBUG){
		$LogInfo['<b>SQL_Grupo : </b>'. $bloque['GRUPO'] ] = $query->sql;
		foreach($query->arreglo_atributos as $id => $attr){
			verifica_variable ($bloque['GRUPO'] . '.' .$attr['NOMBRE'],  'BLOQUE'.$n);
			if ((($attr['TIPO']=='N') || ($attr['TIPO']=='I')) && ($letras)){
				verifica_variable ($bloque['GRUPO'] . '.' .$attr['NOMBRE'] . '_LETRAS', 'BLOQUE'.$n);
			}			
		}
  } 
  
  while ($query->next_record())
  {
    Escribe('<registro numero="'.(++$rec).'">'."\n");
    $linea='';

    if($rec==1)
    {
        $aRecord=$query->Record;
	    foreach($aRecord as $campo => $cvalor)
	    {
        	if(strlen($encabezado)) $encabezado.=";";
        	$encabezado.=$campo;
        }
        if(isset($_REQUEST['download']) && $_REQUEST['download']=='1') fwrite($fp, $encabezado ."\n");
    }

	foreach($query->Record as $campo => $cvalor)
	{
        $sep  ='';
        if(substr($campo,0,3)=='ID_') $sep="'";
        $ndx=$query->reg_campos[$campo];
        $tipo=$query->arreglo_atributos[$ndx]['TIPO'];
	    if (($tipo=='D') || ($tipo=='T'))
	    {
	      $cvalor=fechaDMY($cvalor);
	    }
        else
        {
         	//$cvalor=format($cvalor, $tipo);
        }

        switch ($tipo)
        {
            case 'C':
            case 'X':
            case 'B':
                $cvalor = stripslashes ($cvalor);
                break;
            case 'I':
            case 'N':
			$xvalor=$cvalor;
            	if(isset($_REQUEST['download']) && $_REQUEST['download']=='1') $cvalor = str_replace('.',',',$cvalor);
            	break;
        }
        if(strlen($linea)) $linea.=";";
        $linea.=$sep.$cvalor;
	    $cvalor='<![CDATA['.$cvalor.']]>';
	    Escribe('<'.$campo.'>'.$cvalor.'</'.$campo.'>'."\n");
		if ((($tipo=='N') || ($tipo=='I')) && ($letras))
		{
			$cnumero_letras->setNumero($xvalor);
			Escribe('<'.$campo.'_LETRAS TIPO="C"><![CDATA['.$cnumero_letras->letra().']]></'.$campo.'_LETRAS>'."\n");
		}
	
	}
    Escribe('</registro>'."\n");
    if(isset($_REQUEST['download']) && $_REQUEST['download']=='1') fwrite($fp, $linea ."\n");
  }
  //rdebug($query,'s');
  if(isset($_REQUEST['download']) && $_REQUEST['download']=='1') fclose($fp);
  if($query->erro)
    {
	    Escribe('<ERROR>' . "\n");
	    Escribe('<![CDATA['. $query->erro_msg .']]>' . "\n");
	    Escribe('</ERROR>' . "\n");
	    Escribe('<![CDATA['.  $query->sql .']]>' . "\n");
	}

  Escribe('<atributos cantidad="'.sizeof($query->arreglo_atributos).'">'."\n");

  for ($i=0;$i<sizeof($query->arreglo_atributos);$i++)
  {
    if ($query->arreglo_atributos[$i]['TIPO']=='X') $long=20000;
    else $long=$query->arreglo_atributos[$i]['LONG'];

    Escribe('<'.$query->arreglo_atributos[$i]['NOMBRE'].' TIPO="'.$query->arreglo_atributos[$i]['TIPO'].'" LONGITUD="'.$long.'"/>'."\n");
		if ((($query->arreglo_atributos[$i]['TIPO']=='N') || ($query->arreglo_atributos[$i]['TIPO']=='I')) && ($letras))
		{
			Escribe('<'.$query->arreglo_atributos[$i]['NOMBRE'].'_LETRAS TIPO="C" LONGITUD="2000"/>'."\n");
		}
	
  }
  Escribe('</atributos>'."\n");


  Escribe('</BLOQUE'.$n.'>'."\n");

  $bloque = $my_ini->seccion('BLOQUE'.(++$n));
}
$t_dos = time();
$t = $t_dos-$t_ini;
Escribe('</reporte>');

if(IMPRESO_DEBUG) resultado();

if(isset($_REQUEST['download']) && $_REQUEST['download']=='1')
{
	header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); // required for certain browsers
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"".basename(Server_Path . $_REQUEST['origen']).".csv\";" );
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize("$fichero"));
	readfile("$fichero");
	exit();
}

function Escribe($texto)
{
	if((isset($_REQUEST['download'])  && $_REQUEST['download']=='1' ) || IMPRESO_DEBUG ) return;
	echo $texto;
}

function displayString($arrayText)
{
	$xx = '';
	while (list($key, $val) = each($arrayText)) {
	      $xx .= "$key => $val\n";
	}
	return $xx;
}
?>