<?php

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
$variable["SERVERPATH"] = Server_Path;

foreach($_REQUEST as $f => $v){
  $dmy = "/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/";
  preg_match($dmy, $v, $arr);
  if (sizeof($arr)==4){
    $_REQUEST[$f] = $arr[2]."/".$arr[1]."/".$arr[3];
  }
}

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

unset ($ini["VARIABLE"]['ONOMBRE']);
foreach($ini["VARIABLE"] as $f => $v){
  $sql = $v;
  preg_match_all('/{(.+?)}/', $sql, $arr);
  
  for($i=0;$i<sizeof($arr[1]);$i++){

    $nm = $arr[1][$i];
    $v = isset($_REQUEST[$nm])?$_REQUEST[$nm]:(isset($variable[$nm])?$variable[$nm]:"");

    $sql = preg_replace('/\{'.$nm.'\}/', $v, $sql);
  }
  
  $query->sql = trim($sql);
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
        
        if ($query->arreglo_atributos[$n]['TIPO']=='N' || $query->arreglo_atributos[$n]['TIPO']=='I')
        {
          if ($query->arreglo_atributos[$n]['TIPO']=='N') $cvalor=floatval($cvalor);
          else $cvalor=intval($cvalor);

          if ($letras) $cnumero_letras->setNumero($origvalor);
          $variable[$f."_".$campo."_LETRAS"] = $cnumero_letras;
        }
        $variable[$f."_".$campo] = $cvalor;
        $n++;
      }
  }
}

$variable["fecha"] = date("d/m/Y");
$variable["hora"] = date("H:i:s");

$obj["variables"]=$variable;

//========================================================= BLOQUES =========================================


foreach($ini as $f => $v){
  if (substr($f,0,6)=="BLOQUE") {
      $nm = $f;
      $group=isset($ini[$f]["GROUP"])?$ini[$f]["GROUP"]:"";
      $orden=isset($ini[$f]["ORDEN"])?$ini[$f]["ORDEN"]:"";
      if ($v["GRUPO"]) $nm=trim($v["GRUPO"]);
      if ($v["SQL"]) {
          $cond = array();
          $cond2 = array();
          $sql = $v["SQL"];

          if (isset($v["WHERE"]) && trim($v["WHERE"])) array_push($cond, parseVars($v["WHERE"]));
          if ($v["CONDICIONES"]){
              $c=explode(",", $v["CONDICIONES"]);
              $added=false;

              $cn = "";
              for($i=0;$i<sizeof($c);$i++){
                  if (isset($ini[$c[$i]])) {
                      $el=$ini[$c[$i]];
                      if (isset($el["VARIABLE"]) && isset($el["CONDICION"])) {
                          $var = $el["VARIABLE"];
                          if (isset($_REQUEST[$var]) && $_REQUEST[$var]) {
                              if (count($cond2)) {
                                if ($el["CONECTOR"]) {
                                    $cn=$el["CONECTOR"];
                                }
                                array_push($cond2, $cn);
                              }
                              array_push($cond2, parseVars($el["CONDICION"]));
                          }
                      }
                      unset($ini[$c[$i]]);
                  }
              }

              if (count($cond) && count($cond2)){
                $cond = array_push($cond," and ");
                $cond = array_push($cond, $cond2);
              }
              else if (count($cond2)) {
                $cond = $cond2;
              }
          }
      }

      $cond = implode(" ",$cond);

      if ($cond) {
        if (preg_match("/select\s+(.*)from\s+(\w+)\s+(where)\s+(.*)\s+(order\s+by)\s+(.*)/is", $sql, $matches)) {
          $sql = "SELECT {$matches[1]} FROM {$matches[2]} WHERE {$matches[4]} and {$cond} order by {$matches[6]}";
        }
        else if (preg_match("/select\s+(.*)from\s+(\w+)\s+(order)\s+(.*)/is", $sql, $matches)) {
          $sql = "SELECT {$matches[1]} FROM {$matches[2]} WHERE {$cond} order by {$matches[4]}";
        }
        else if (preg_match("/select\s+(.*)from\s+(\w+)\s+(where)\s+(.*)/is", $sql, $matches)) {
          $sql = "SELECT {$matches[1]} FROM {$matches[2]} WHERE {$matches[4]} and {$cond}";
        }
        else $sql .= " WHERE $cond";
      }
            
      if ($nm !== $f) unset($ini[$f]);

      if ($group) $sql .= " group by $group";      
      if ($orden) $sql .= " order by $orden";

      $sql = preg_replace("/\s+FROM\s+(\w+|\w+\s+\w+|\w+\s+as\s+\w+)\s+and\s+/i",  " FROM $1 WHERE ", $sql);

      $sql = preg_replace("/\&lt\;/i",  "<", $sql);
      $sql = preg_replace("/\&gt\;/i",  ">", $sql);

	  $sql = str_replace('__S24_EOL__', PHP_EOL, $sql);
      $query->sql = parseVars(trim($sql));

      $query->ejecuta_query();

      $ini[$nm]["fields"] = array();
      $ini[$nm]["types"] = array();
      $ini[$nm]["sizes"] = array();
      $ini[$nm]["prec"] = array();
	  
      for($k=0;$k<sizeof($query->arreglo_atributos);$k++){
        $ini[$nm]["fields"][$k]=$query->arreglo_atributos[$k]["NOMBRE"];
        $ini[$nm]["types"][$k]=$query->arreglo_atributos[$k]["TIPO"];
        $ini[$nm]["sizes"][$k]=$query->arreglo_atributos[$k]["LONG"];
        $tip = $query->reg_campos_tipos[$k];
        preg_match("/NUMERIC\s*\([0-9]+\s*\,([0-9]+)\s*\)/i",$tip,$arprec);
        if (sizeof($arprec)>1) $ini[$nm]["prec"][$k] = intval($arprec[1]);
        else $ini[$nm]["prec"][$k] = 0;
      }

      $data=array();

      while ($query->next_record()) {
        $fila = array();
        foreach($query->Record as $f => $v) {
          array_push($fila, $v);
        }
        array_push($data, $fila);
      }
      $ini[$nm]["data"] = $data;
      $ini[$nm]["sql"] = $query->sql;
      $obj['datasets'][$nm] = $ini[$nm];
  }
}

//ob_start("ob_gzhandler");

$obj = utf8_converter($obj);

$uid = uniqid();

$tempfile = __DIR__ . "/".$uid.".json";
file_put_contents($tempfile, json_encode($obj)); // -- guardo el json con el dataset y las variables


$tempfile = "/tmp/".$uid.".json";
file_put_contents($tempfile, json_encode($obj)); // -- guardo el json con el dataset y las variables



$title = $obj["title"]?$obj["title"]:"reporte_".$uid;
header('Content-Type: application/vnd.ms-excel;');
header("Content-type: application/x-msexcel");
header('Content-Disposition: inline; filename="' . $title . '.xlsx"');
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

echo shell_exec("python ".__DIR__."/python/sqltoxlsx.py ".$tempfile);

function utf8_converter($array)
{
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