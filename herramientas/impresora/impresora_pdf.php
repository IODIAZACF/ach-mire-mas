<?php
//session_start();
//define('Server_Path','../../');
//$_SESSION['db'] = $_REQUEST['db'];

$db_server[0]['DB']         = "/opt/lampp/firebird/db/". $_REQUEST['db'] .".gdb";

include_once (Server_Path . "herramientas/utiles/comun.php");
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . "herramientas/sql/class/class_sql.php");
include_once (Server_Path . "herramientas/numero_letras/numero_letras.php");
//include_once (Server_Path . "herramientas/impresora/MPDF57/mpdf.php");

require __DIR__ . '/vendor/autoload.php';
use raelgc\view\Template;

$origen  = getvar('origen');
$header  = getvar('header');
$letras  = getvar('letras');

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


$my_ini = new ini($origen);
$query  = new sql();

$cnumero_letras = new numero_letras();
$cnumero_letras->setGenero(1);
$cnumero_letras->setMoneda("CON");
$cnumero_letras->setPrefijo("***");
$cnumero_letras->setSufijo("***");


$Plantilla     = RUTA_SISTEMA . "/reportes_html/guia.html";

$Tpl = new Template($Plantilla);

$variables = $my_ini->seccion('VARIABLE');
$nquery=0;

if (is_array($variables))
{

	while (list($variable, $xsql) = each($variables))
	{
		preg_match_all('/({.+?})/', $xsql, $arr);
		for ($i=0;$i<sizeof($arr[0]);$i++)
		{
			$param=substr($arr[0][$i],1,strlen($arr[0][$i])-2);
			$xsql = str_replace($arr[0][$i], $_REQUEST[$param], $xsql);
		}
		$query->sql = trim($xsql);
		$query->ejecuta_query();
		while($query->next_record())
		{
			$n=0;
			foreach($query->Record as $campo => $cvalor)
			{
				$origvalor=$cvalor;
				if (($query->arreglo_atributos[$n]['TIPO']=='D')||($query->arreglo_atributos[$n]['TIPO']=='T'))
				{
					$cvalor=fechaDMY($cvalor);
				}
				$Tpl->__set($variable . '_'. $campo    ,  $cvalor);
				$n++;
			}
		}
	}
}

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

	$arrcond=split(',',$cond);

	if (!is_array($arrcond)) return '';

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
				}
				if ($ret!='') $ret.=$last_cond;
				$ret.=$x_sql;
				$last_cond=$x_cond;
			}
		}
	}
	return $ret;
}

$n=1;
$bloques = $my_ini->secciones('BLOQUE');

//rdebug($bloque,'s');
for ($i=0;$i<sizeof($bloques);$i++)
{
	$bloque = $bloques[$i];
	$xsql = trim($bloque['SQL']);
	$GRUPO = trim($bloque['GRUPO']);

	if($bloque['WHERE']) $xsql.=' WHERE '. $bloque['WHERE'];
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
	
	if($bloque['GROUP']) $xsql.=' GROUP BY '. $bloque['GROUP'];
	if ($bloque['ORDEN']) $xsql.=' order by '.$bloque['ORDEN'];

	$query->sql = trim($xsql);
	$query->ejecuta_query();
	$rec=0;
	$encabezado='';
	$LINEA = 0;
	
	while ($query->next_record())
	{
		//rdebug($query,'s');
		$LINEA++;
		foreach($query->Record as $campo => $cvalor)
		{
			$sep  ='';
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
					break;
			}
			$Tpl->__set($GRUPO . '_'. $campo ,  $cvalor);
		}
		$Tpl->__set('LINEA',  $LINEA);
		$Tpl->block('DETALLES');
	}
	$Tpl->__set('LINEAS',  $LINEA);
}



$html = $Tpl->Parse();

//echo $html;
//die();


if($_REQUEST['salida']=='pdf')
{
	$id_m_guias = $_REQUEST['ID_M_GUIAS'];
	$nurl = 'http://127.0.0.1' . str_replace('&salida=pdf', '', $_SERVER['REQUEST_URI']);
	$reporte =  file_get_contents($nurl);
	file_put_contents("/opt/lampp/htdocs/tmp/". $id_m_guias  .".html", $reporte);
	$cmd_pdf = 'wkhtmltopdf http://127.0.0.1/tmp/'. $id_m_guias  .'.html /opt/lampp/htdocs/tmp/'. $id_m_guias .'.pdf';
	system($cmd_pdf);
	if($_REQUEST['visualizar']=='1'){
		header('Content-type: application/pdf');   
		header('Content-Disposition: inline; filename="' . $id_m_guias  .'.pdf' . '"');   
		header('Content-Transfer-Encoding: binary');   
		header('Accept-Ranges: bytes'); 
		
	}else{
		header("Pragma: public"); // required
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); // required for certain browsers
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"". $id_m_guias  .'.pdf' ."\";" );
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize('/opt/lampp/htdocs/tmp/'. $id_m_guias .'.pdf'));		
	}
	readfile('/opt/lampp/htdocs/tmp/'. $id_m_guias .'.pdf');	
	exit;
	
}
else
{
	
	echo $html;
	echo '<script>';
	echo 'window.print();';
	echo '</script>';
}

?>