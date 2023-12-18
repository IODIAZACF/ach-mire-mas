<?php
//ini_set("display_errors", "1");
set_time_limit(0);
$db_server[0]['DB']         = "/opt/lampp/firebird/db/". $_REQUEST['db'] .".gdb";
header('content-type: text/plain; charset=iso8859-1');
header('Cache-Control: no-cache');
header('Pragma: no-cache');

$protocol = "http://";
if (isset($_SERVER['HTTPS'])) $protocol = "https://"; 

$server_path = $protocol.$_SERVER['HTTP_HOST']."/";
$scr = $_SERVER["SCRIPT_NAME"];
$scr = explode("/", $scr);

$server_path .= $scr[1]."/";

$t_ini = time();
include_once (Server_Path . "herramientas/utiles/comun.php");
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . "herramientas/sql/class/class_sql.php");
include_once (Server_Path . "herramientas/numero_letras/numero_letras.php");

header("content_type: application/json; charset=utf8");

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

$ini = $my_ini->archivo_ini;

$obj = array();
$variable =  $_REQUEST; // array();
$variable["SERVERPATH"] = $server_path;

if (isset($_REQUEST["debug"])) echo print_r ($_REQUEST, true);

foreach($_REQUEST as $f => $v){
  $dmy = "/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/";
  preg_match($dmy, $v, $arr);
  if (sizeof($arr)==4){
    $_REQUEST[$f] = $arr[2]."/".$arr[1]."/".$arr[3];
  }
}

if (isset($_REQUEST["debug"])) echo print_r ( $_REQUEST, true );

//========================================================= VARIABLES =========================================

foreach($ini["PARAMETRO"] as $f => $v){
  $variable[$f] = parseVars($v);
}

foreach($ini["VARIABLES"] as $f => $v){
  $variable[$f] = parseVars($v);
}


foreach($_SESSION as $f => $v){
  if (!$variable[$f]) $variable[$f] = parseVars($v);
}

unset( $ini['VARIABLE']['ONOMBRE'] );

$variables = $my_ini->seccion('VARIABLE');

$nquery = 0;

if ( is_array( $variables ) ) {
	unset( $variables['ONOMBRE'] );
	foreach($variables as $f =>  $xsql ){
		preg_match_all('/({.+?})/', $xsql, $arr);      
		for ($i=0;$i<sizeof($arr[0]);$i++) {
			$param=substr($arr[0][$i],1,strlen($arr[0][$i])-2);
			$xsql = str_replace( $arr[0][$i], $_REQUEST[$param], $xsql );
		}
	  
		$xsql  = str_replace("&lt;", '<', $xsql);
		$xsql  = str_replace("&gt;", '>', $xsql);
		$xsql = str_replace('__S24_EOL__', PHP_EOL,  $xsql);
		$query->sql = trim($xsql);
		$query->ejecuta_query();
	
		if($query->next_record()){
			
			$n=0;
			foreach($query->Record as $campo => $cvalor) {
				$origvalor = $cvalor;			
				
				$ndx  = $query->reg_campos[$campo];
				$tipo = $query->arreglo_atributos[$ndx]['TIPO'];
				switch ($tipo){
					case 'C':
					case 'X':
					case 'B':
						break;
					case 'I':
					case 'N':
						if( strlen( $cvalor ) <= 0 ){
							$cvalor = 0;
						}
						if( ($letras) ){
							$cnumero_letras->setNumero($origvalor);
							$variable[$f."_".$campo."_LETRAS"] = $cnumero_letras->letra();
						}
					break;
					case 'D':
					case 'T':
						$cvalor = fechaDMY($cvalor);
					break;
				}
				$variable[$f."_".$campo] = $cvalor;
				$n++;
			}
		}
	}
}

$variable["fecha"] = date("d/m/Y");
$variable["hora"]  = date("H:i:s");

if(!isset( $variable["TITULO"] )){
	$variable["TITULO"] = isset ( $_REQUEST['ITULO'] ) ? $_REQUEST['ITULO'] : '';	
}

$query->sql = "SELECT * FROM V_M_USUARIOS WHERE ID_M_USUARIO='". $_REQUEST['id_m_usuario'] ."'";
$query->ejecuta_query();

if($query->next_record()) {	
	$n=0;
	foreach( $query->Record as $campo => $cvalor){
		$origvalor=$cvalor;
		
		$ndx  = $query->reg_campos[$campo];
		$tipo = $query->arreglo_atributos[$ndx]['TIPO'];
		switch ($tipo){
			case 'C':
			case 'X':
			case 'B':
				break;
			case 'I':
			case 'N':
				if( strlen( $cvalor ) <= 0 ){
					$cvalor = 0;
				}
				if( ($letras) ){
					$cnumero_letras->setNumero($origvalor);
					$variable["M_USUARIOS_" . $campo."_LETRAS"] = $cnumero_letras->letra();
				}
			break;
			case 'D':
			case 'T':
				$cvalor = fechaDMY($cvalor);
			break;
		}
		
		$variable["M_USUARIOS_".$campo] = $cvalor;
		$n++;
	}
}

$obj["variables"] = $variable ;
//file_put_contents (__DIR__ .'/param.txt', print_r ( $_REQUEST, true) );

//========================================================= BLOQUES =========================================

foreach($ini as $seccion => $bloque){
	if (substr($seccion,0,6) !="BLOQUE") continue;
	
	$xsql = trim($bloque['SQL']);
	if(isset($bloque['WHERE'])) $xsql.=' WHERE '. $bloque['WHERE'];
	
	preg_match_all('/({.+?})/', $xsql, $arr);
	
	for ($i=0;$i<sizeof($arr[0]);$i++) {
		$param=substr($arr[0][$i],1,strlen($arr[0][$i])-2);
		$xsql = str_replace($arr[0][$i], $_REQUEST[$param], $xsql);
	}
  
	$condiciones = procesa_condic($seccion);
	
	if ($condiciones){
		$nn=strpos(strtoupper($xsql), 'WHERE');
		if ($nn===false) {
			$xsql.=' WHERE '.$condiciones;
		}
		else{
			$xsql=str_ireplace('where',' where '.$condiciones.' and ',$xsql);
		}
	}
	
	if( isset( $bloque['GROUP'] ) ) {
		if( strlen( $bloque['GROUP'] ) ){
			$xsql.=' GROUP BY '. $bloque['GROUP'];
		}		
	}
	
	if ( isset( $bloque['ORDEN'] ) ) {
		if ( strlen( $bloque['ORDEN'] ) ){
			$xsql.=' ORDER BY '.$bloque['ORDEN'];
		}
	}
  
	$xsql  = str_replace("&lt;", '<', $xsql);
	$xsql  = str_replace("&gt;", '>', $xsql); 

	unset($ini[ $seccion ]);
	$seccion = $bloque['GRUPO'];
	
	$xsql = str_replace('__S24_EOL__', PHP_EOL, $xsql); 
	
	preg_match_all('/({.+?})/', $xsql, $arr);
	
	for ($i=0;$i<sizeof($arr[0]);$i++) {
		$param=substr($arr[0][$i],1,strlen($arr[0][$i])-2);
		$xsql = str_replace($arr[0][$i], $_REQUEST[$param], $xsql);
	}
	
	
	$query->sql = trim($xsql);
	$query->ejecuta_query();
	$rec=0;
	$encabezado='';  
	
	$ini[ $seccion ]["fields"] = array();
	$ini[ $seccion ]["types"] = array();
	$ini[ $seccion ]["sizes"] = array();
	$ini[ $seccion ]["prec"] = array();
	  
	for($k=0;$k<sizeof($query->arreglo_atributos);$k++){
        $ini[ $seccion ]["fields"][$k]=$query->arreglo_atributos[$k]["NOMBRE"];
        $ini[ $seccion ]["types"][$k]=$query->arreglo_atributos[$k]["TIPO"];
        $ini[ $seccion ]["sizes"][$k]=$query->arreglo_atributos[$k]["LONG"];
        $tip = $query->reg_campos_tipos[$k];
        preg_match("/NUMERIC\s*\([0-9]+\s*\,([0-9]+)\s*\)/i",$tip,$arprec);
        if (sizeof($arprec)>1) $ini[ $seccion ]["prec"][$k] = intval($arprec[1]);
        else $ini[ $seccion ]["prec"][$k] = 0;
	}

	$data = array();
	
	while ($query->next_record()){		
        $fila = array();

        foreach($query->Record as $f => $v) {
			$ndx  = $query->reg_campos[$f];
			$tipo = $query->arreglo_atributos[$ndx]['TIPO'];
			switch ($tipo){
				case 'C':
				case 'X':
				case 'B':
					break;
				case 'I':
				case 'N':
					if( strlen( $v ) <= 0 ){
						$v = 0;
					}
				break;
			}					
			$fila[] = $v ;
        }
		$data[] = $fila;
		unset( $fila );
	}
	
	$ini[ $seccion ]["data"] 		= $data;
	$ini[ $seccion ]["sql"] 		= $query->sql;
	$obj['datasets'][ $seccion ] 	= $ini[ $seccion ];
	
}



if (file_exists($origen . ".fr3")) {
  $rep = file_get_contents($origen . ".fr3");

  if (mb_detect_encoding($rep)) $rep = utf8_decode($rep);

  $rep = preg_replace("/\.url\s*\:\=/i", ".tagStr := ", $rep);

  $rep = preg_replace("/DataSetName=\"(\w+)(\s+)\"/", 'DataSetName="$1"', $rep);
  $rep = preg_replace("/(\w+)(\s+)\.\&\#34\;/", "$1.&#34;", $rep);
  $obj["reports"]= array( $origen =>  $rep );
  
  while (preg_match("/ParentReport\=\"(.*)\.fr3\"/i", $rep, $out)) { 
    $nx = dirname($origen) . "/" . preg_replace("/\\\\/", "/", $out[1]);
    
	$fn = __DIR__."/../../$nx.fr3";
	dir($fn);
    if (file_exists($fn)) {
      $rep = file_get_contents($fn);
      if (mb_detect_encoding($rep)) $rep = utf8_decode($rep);
      $obj["templates"][$nx] = $rep;
    }
    else $rep="";
  }
}else{
	echo "No Existe $origen.fr3";
	die();
}


foreach($_REQUEST as $f => $v){
  $ymd = "/([0-9]{4})\-([0-9]{2})\-([0-9]{2})/";  
  preg_match($ymd, $v, $arr);
  if (sizeof($arr)==4){	  
    $obj['variables'][$f] = $arr[3]."/".$arr[2]."/".$arr[1];
  }
}


//die('xxx');
if (isset($_REQUEST["debug"])) echo print_r ($obj, true);
$obj = utf8_converter($obj);
if (isset($_REQUEST["debug"])) echo json_encode($obj, /*JSON_UNESCAPED_UNICODE +*/ JSON_PRETTY_PRINT);
else echo json_encode($obj/*, JSON_UNESCAPED_UNICODE*/);


function procesa_condic($bloque)
{
  global $my_ini;
  $ret='';
  $cond=$my_ini->variable($bloque, 'CONDICIONES');
  if (!$cond) return '';

  $arrcond=explode(',',$cond);

  if (!is_array($arrcond)) return '';
  //print_r ($arrcond);
  

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

function utf8_converter($array) {
    array_walk_recursive($array, function(&$item, $key){
        if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
        }
    }); 
    return $array;
}

function parseVars($in){
  preg_match_all("/\{\s*(\w+)\s*}/", $in, $arr);
  
  $vars = $arr[1];
  
  for($i=0;$i<sizeof($vars);$i++){
      $repl = "";
      if (isset($_REQUEST[$vars[$i]])) {
          $repl = $_REQUEST[$vars[$i]];
      }

      $in = preg_replace("/\{".$vars[$i]."\}/", $repl, $in);
  }

  return $in;
}

function fechaDMY($valor)
{
  if (!$valor) $vsalida='';
  else $vsalida = substr($valor,8,2).'/'.substr($valor,5,2).'/'. substr($valor,0,4).' '.substr($valor,11);
  return $vsalida;
}

?>