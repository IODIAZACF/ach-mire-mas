<?php

function buscar($tabla,$campo,$busca,$xbusca, $operador= '=')
{
    $resp = '';
    $query = new sql();
    $query->sql ="SELECT " . $campo ." FROM ". $tabla ." WHERE ". $busca . $operador . $xbusca ;
    $query->ejecuta_query();
    if($query->next_record()) $resp = $query->Record[$campo];
    return $resp;
}

function control_session()
{
    $permitido = false;
/*
	$permitido = true;
    return;
*/
	if(Verifica_session)
	{
	    for($i=0;$i<sizeof($excepciones);$i++){
	        if($_SERVER['SCRIPT_NAME'] == $excepciones[$i])
	        {
	            $permitido = true;
	            break;
	        }
	    }
	    if($debug_sql) $permitido = true;
	    if(!$permitido){
	        $query = new sql();
	        $query->sql ="SELECT SESSION_ID,IP FROM M_USUARIOS WHERE ID_M_USUARIO ='" . getsession('M_USUARIOS_ID_M_USUARIO') . "'";
	        $query->ejecuta_query();
	        $query->next_record();
	        if(strtoupper(session_id()) != $query->Record['SESSION_ID'])
	        {
	            echo '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n\n";
	            echo '<tabla>' . "\n";
	            echo '<registro>' . "\n";
	            echo '<javascript>' . "\n";
	            echo '<![CDATA[ ' . "\n";
	            echo ' cerrar_session(\''.  $query->Record['IP'] .'\'); ' . "\n";
	            echo ' ]]>' . "\n";
	            echo '</javascript>' . "\n";
	            echo '</registro>' . "\n";
	            echo '</tabla>' . "\n";
	            session_unset();
	            session_destroy();
	            die();
	        }
	    }
	}
}


function encabezado($titulo)
{
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'. "\n";
        echo '<html>'. "\n";
        echo '<head>'. "\n";
        echo '  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">'. "\n";
        echo '<link rel="stylesheet" type="text/css"  href="' . Server_Path .'estilo/' . Estilo . '/estilo.css" >'. "\n";
        echo '  <title>'.$titulo.'</title>'. "\n";
        echo '</head>'. "\n";

}

function cargando($ocultaCarga=true)
{
echo <<<EOT

    <script type="text/javascript">

    function ocultaCarga()
    {
      document.getElementById("loading").style.display="none";
    }
EOT;

  if ($ocultaCarga)
  {
    echo '
      if (document.addEventListener) document.addEventListener("DOMContentLoaded", ocultaCarga, null);
      else window.onload = ocultaCarga;
    ';
  }
echo <<<EOT
    </script>
    <table id="loading" class="carga_tabla1"><tr><td align="center"><table class="carga_tabla2"><tr><td></td></tr></table></td></tr></table>
EOT;
}

function rdebug($a,$b='')
{
        echo "<pre>";
        print_r($a);
        if(strlen($b)) die('final del Script por Debug');
}

function texto2js($texto){
    $tmp = explode("\n",$texto);
    $texto = '';
    for($i=0;$i<sizeof($tmp);$i++){
        if($texto) $texto .= " +\n";
        $texto .= "'" . str_replace("'", "\'", $tmp[$i]) . "'";
    }
        return $texto;
}

function html2ajax(&$objeto){
    header('content-type: text/html'."\n\n");
    header ("Expires: Fri, 1 Ene 1980 00:00:00 GMT"); //la pagina expira en fecha pasada
    header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
    header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
    header ("Pragma: no-cache");

    $objeto->contenido_html = texto2js($objeto->contenido_html);

   /*
    $tmp = explode("\n",$objeto->contenido_js);
    $objeto->contenido_js = '';
    for($i=0;$i<sizeof($tmp);$i++){
        if($objeto->contenido_js) $objeto->contenido_js .= " +\n";

        $prefijo = substr($tmp[$i],0,7);
        if (($prefijo!='<script') && ($prefijo!='</scrip'))  $objeto->contenido_js .= "'" . str_replace("'", "\'", $tmp[$i]) . "'";
    }
   */
    $objeto->contenido_js =  str_replace('<script type="text/javascript">','', $objeto->contenido_js);
    $objeto->contenido_js =  str_replace('</script>','', $objeto->contenido_js);
    $objeto->contenido_js =  texto2js($objeto->contenido_js);

    echo 'var contenido_ajax = {Html : '. $objeto->contenido_html .', Js  : ' . $objeto->contenido_js . '};';
}


function path($root=_root)
{
    $aUri = array();
    if (!empty($_SERVER['REQUEST_URI'])) $aUri = parse_url($_SERVER['REQUEST_URI']);
    if (empty($aUri['scheme']))
    {
            if (!empty($_SERVER['HTTP_SCHEME']))
        {
            $aUri['scheme'] = $_SERVER['HTTP_SCHEME'];
        }
        else
            {
            $aUri['scheme'] = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off') ? 'https' : 'http';
        }

        if (!empty($_SERVER['HTTP_HOST']))
            {
            if (strpos($_SERVER['HTTP_HOST'], ':') > 0)
            {
               list($aUri['host'], $aUri['port']) = explode(':', $_SERVER['HTTP_HOST']);
            }
            else
                {
                   $aUri['host'] = $_SERVER['HTTP_HOST'];
            }
        }
            else if (!empty($_SERVER['SERVER_NAME']))
            {
            $aUri['host'] = $_SERVER['SERVER_NAME'];
        }
            else
            {
                print "Error";
            exit();
        }

        if (empty($aUri['port']) && !empty($_SERVER['SERVER_PORT']))
        {
            $aUri['port'] = $_SERVER['SERVER_PORT'];
        }

        if (empty($aUri['path']))
        {
            if (!empty($_SERVER['PATH_INFO']))
            {
                $path = parse_url($_SERVER['PATH_INFO']);
            }
                else
                                    {
                $path = parse_url($_SERVER['PHP_SELF']);
            }
            $aUri['path'] = $path['path'];
            unset($path);
        }
    }

    $sUri = $aUri['scheme'] . '://';
    if (!empty($aUri['user']))
                    {
        $sUri .= $aUri['user'];
        if (!empty($aUri['pass']))
                            {
            $sUri .= ':' . $aUri['pass'];
        }
        $sUri .= '@';
    }

    $sUri .= $aUri['host'];


    if (!empty($aUri['port']) && (($aUri['scheme'] == 'http' && $aUri['port'] != 80) || ($aUri['scheme'] == 'https' && $aUri['port'] != 443)))
                    {
        $sUri .= ":" .$aUri['port'];
        //$sUri .= $aUri['port'];
        //die('aqui');
    }

    if ($root == _root)
    {
            return $sUri .'/';
        exit;
    }

    if ($root == _folder)
    {
        $sUri .= substr($aUri['path'], 0, strpos($aUri['path'], '/', strpos($aUri['path'], '/')+1) + 1);
            return $sUri;
        exit;
    }

    // And finally path, without script name
    $sUri .= substr($aUri['path'], 0, strrpos($aUri['path'], '/') + 1);

    //$sUri .= substr($aUri['path'], 0, strpos($aUri['path'], '/')+1);

    unset($aUri);

    return $sUri;
}



function rgb($color, $indice)
 {
         $color = str_replace('#','',$color);
         $color =  explode("\n", chunk_split ($color,2,"\n"));
         $tmp[R] = hexdec($c[0]);
    $tmp[G] = hexdec($c[1]);
    $tmp[B] = hexdec($c[2]);
    return $tmp[$indice];
}

function leer_vars($prefijo="")
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        foreach($_POST as $key => $value) {
            if(preg_match ("/^" . $prefijo . "\S+$/i", $key))
            {
                $nombre = str_replace($prefijo, '', $key);
                $items['nombre']    = $nombre;
                $items['valor']     = $value;
                if($nombre) $variables[] = $items;
            }
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == 'GET'){
        foreach($_GET as $key => $value) {
            if(preg_match ("/^" . $prefijo . "\S+$/i", $key))
            {
                $nombre = str_replace($prefijo, '', $key);
                $items['nombre']    = $nombre;
                $items['valor']     = $value;
                if($nombre) $variables[] = $items;
            }
        }
    }
    return $variables;
}

function parse_texto($texto,$variables='')
{
        if(is_array($variables))
    {
            for($i=0;$i<sizeof($variables);$i++){
                $texto = str_replace('{' . $variables[$i]['nombre'] . '}',$variables[$i]['valor'], $texto);
            }
    }else
    {
            $texto = variable($texto);
    }
        return $texto;
}

function parse_variable($cadena, $variable, $valor='')
{
        $texto = str_replace('{' . $variable . '}',$valor, $cadena);
        return $texto;
}

function variable($cadena)
{
    return preg_replace_callback('/\{[\w->]*\}/is', reemplazo ,  $cadena);
}
function reemplazo($xvariable)
{
        global $arrData;
    $variable = $xvariable[0];
    $variable = str_replace('{','', $variable);
    $variable = str_replace('}','', $variable);
    if(strstr($variable,'->')){
            $xvariable = split('->',$variable);
        $variale = $xvariable[1];
        switch ($xvariable[0]){
                case  'SESSION':
                    return strtoupper(getsession($variale));
                break;
            case  'GET':
            case  'POST':
                    return strtoupper(getvar($variale));
                    break;
            default :
                    return $arrData[$xvariable[1]][$variable];
            break;
        }
    }
    else
    {
            return strtoupper(getvar($variable));
    }
}



function getvar($variable, $vdefault="", $fdefault=false)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        if(isset($_POST[$variable]))
        {
            if($fdefault) return !strlen($_POST[$variable]) ? $vdefault : $_POST[$variable];
            return $_POST[$variable];
        }
        else
        {

            return strlen($vdefault) ? $vdefault : false;
        }

    }
    if ($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET[$variable]))
        {
            if($fdefault) return !strlen($_GET[$variable]) ? $vdefault : $_GET[$variable];
            return $_GET[$variable];

        }
        else
        {
            return strlen($vdefault) ? $vdefault : false;
        }
    }
}

function texto2html($texto){
        return htmlentities($texto, ENT_QUOTES);
}

function getsession($variable)
{
    return isset($_SESSION[$variable]) ? $_SESSION[$variable] : "";
}

function setsession($variable, $valor)
{
    $_SESSION[$variable] = $valor;
}


function CCGetQueryString($CollectionName, $RemoveParameters)
{
    $querystring = "";
    $postdata = "";
    if($CollectionName == "Form")
        $querystring = CCCollectionToString($_POST, $RemoveParameters);
    else if($CollectionName == "QueryString")
        $querystring = CCCollectionToString($_GET, $RemoveParameters);
    else if($CollectionName == "All")
    {
        $querystring = CCCollectionToString($_GET, $RemoveParameters);
        $postdata = CCCollectionToString($_POST, $RemoveParameters);
        if(strlen($postdata) > 0 && strlen($querystring) > 0)
            $querystring .= "&" . $postdata;
        else
            $querystring .= $postdata;
    }
    else
        die("1050: Common Functions. CCGetQueryString Function. " .
            "The CollectionName contains an illegal value.");
    return $querystring;
}

function CCCollectionToString($ParametersCollection, $RemoveParameters)
{
  $Result = "";
  if(is_array($ParametersCollection))
  {
    reset($ParametersCollection);
    foreach($ParametersCollection as $ItemName => $ItemValues)
    {
      $Remove = false;
      if(is_array($RemoveParameters))
      {
        foreach($RemoveParameters as $key => $val)
        {
          if($val == $ItemName)
          {
            $Remove = true;
            break;
          }
        }
      }
      if(!$Remove)
      {
        if(is_array($ItemValues))
          for($J = 0; $J < sizeof($ItemValues); $J++)
            $Result .= "&" . $ItemName . "[]=" . urlencode(CCStrip($ItemValues[$J]));
        else
           $Result .= "&" . $ItemName . "=" . urlencode(CCStrip($ItemValues));
      }
    }
  }

  if(strlen($Result) > 0)
    $Result = substr($Result, 1);
  return $Result;
}

function CCMergeQueryStrings($LeftQueryString, $RightQueryString = "")
{
  $QueryString = $LeftQueryString;
  if($QueryString === "")
    $QueryString = $RightQueryString;
  else if($RightQueryString !== "")
    $QueryString .= "&" . $RightQueryString;

  return $QueryString;
}

function CCAddParam($querystring, $ParameterName, $ParameterValue)
{
    $querystring = $querystring ? "&" . $querystring : "";
    $querystring = preg_replace ("/&".$ParameterName."(\[\])?=[^&]*/", "", $querystring);
    if(is_array($ParameterValue)) {
        foreach($ParameterValue as $key => $val) {
            $querystring .= "&" . $ParameterName . "[]=" . urlencode($val);
        }
    } else {
    $querystring .= "&" . $ParameterName . "=" . urlencode($ParameterValue);
    }
    $querystring = substr($querystring, 1);
    return $querystring;
}

function Querystring2Array($cadena){
        //echo $cadena;
        $variables = explode('&', $cadena);
    $respuesta = array();
    for($i=0;$i<sizeof($variables);$i++){
      $resp = explode('=',$variables[$i]);
          $clave = $resp[0];
      $valor = urldecode($resp[1]);
      //echo urldecode($resp[1]) . "<br>";
      $respuesta[$clave] = $valor;
    }
    return $respuesta;

}


function CCRemoveParam($querystring, $ParameterName)
{
    $querystring = "&" . $querystring;
    $Result = preg_replace ("/&".$ParameterName."(\[\])?=[^&]*/", "", $querystring);
    if (substr($Result, 0, 1) == "&")
        $Result = substr($Result, 1);
    return $Result;
}

function CCStrip($value)
{
  if(get_magic_quotes_gpc() != 0)
  {
    if(is_array($value))
      foreach($value as $key=>$val)
        $value[$key] = stripslashes($val);
    else
      $value = stripslashes($value);
  }
  return $value;
}

function ordena_arreglo($arreglo, $columna_string)
{
        for ($i=0;$i<sizeof($arreglo);$i++) $columna[] = $arreglo[$i][$columna_string];
    if(is_array($columna)) array_multisort($columna, SORT_REGULAR ,$arreglo);
        return $arreglo;
}


function javascript($modulo)
{

$modulo = explode(',',$modulo);
$Estilo = Estilo;
$Path         = path(1);


$fp = fopen(Server_Path . 'jsversion.txt', "a+");
if(filesize(Server_Path . 'jsversion.txt'))
{
	$JSVERSION = fread($fp, filesize(Server_Path . 'jsversion.txt'));
}
fclose($fp);

echo <<<EOT

<script type="text/javascript">
var server_path           = '{$Path}';
var estilo_actual         = '{$Estilo}';
var t_msg_error         = new Array();
var t_msg_unico         = new Array();
var aventanas                 = new Array();
var abmaestros                = new Array();
</script>


EOT;

//function buscar($tabla,$campo,$busca,$xbusca, $operador= '=')
$contador = -1;
while ($modulo [++$contador])
{
    //echo path(_folder);
    $ruta = path(_folder)."herramientas/".$modulo[$contador]."/javascript/".$modulo[$contador].".js?$JSVERSION";
    echo '<script type="text/javascript" src="' . $ruta . '"></script>' . "\n";

}


}

/*
function javascript($modulo)
{

$modulo = explode(',',$modulo);
$Estilo = Estilo;
$Path         = path(1);

echo <<<EOT

<script type="text/javascript">
var server_path           = '{$Path}';
var estilo_actual         = '{$Estilo}';
var t_msg_error         = new Array();
var t_msg_unico         = new Array();
var aventanas                 = new Array();
var abmaestros                = new Array();
</script>


EOT;


$contador = -1;
while ($modulo [++$contador])
{
    //echo path(_folder);
    $ruta = path(_folder)."herramientas/".$modulo[$contador]."/javascript/".$modulo[$contador].".js";
    echo '<script type="text/javascript" src="' . $ruta . '"></script>' . "\n";

}


}*/


function format($valor, $tipo)
{
  if(!strlen($valor)) $valor = 0;
  switch ($tipo)
  {
    case 'N':
            return number_format($valor, 2, '.', ',');
        break;
    case 'I':
            return number_format($valor, 0, '.', ',');
        break;
    case 'D':
        return str_replace('-', '/', $valor);
        break;
    case 'T':
        return str_replace('-', '/', $valor);
        break;
    default:
        return $valor;
  }
}

 function numerotexto ($numero) {
    // Primero tomamos el numero y le quitamos los caracteres especiales y extras
    // Dejando solamente el punto "." que separa los decimales
    // Si encuentra mas de un punto, devuelve error.
    // NOTA: Para los paises en que el punto y la coma se usan de forma
    // inversa, solo hay que cambiar la coma por punto en el array de "extras"
    // y el punto por coma en el explode de $partes



    $extras= array("/[\$]/","/ /","/,/","/-/");
    $limpio=preg_replace($extras,"",$numero);
    $partes=explode(".",$limpio);
    if (count($partes)>2) {
        return "Error, el n&uacute;mero no es correcto";
        exit();
    }
    // Ahora explotamos la parte del numero en elementos de un array que
    // llamaremos $digitos, y contamos los grupos de tres digitos
    // resultantes

    $digitos_piezas=chunk_split ($partes[0],1,"#");
    $digitos_piezas=substr($digitos_piezas,0,strlen($digitos_piezas)-1);
    $digitos=explode("#",$digitos_piezas);
    $todos=count($digitos);
    $grupos=ceil (count($digitos)/3);

    // comenzamos a dar formato a cada grupo

    $unidad = array    ('un','dos','tres','cuatro','cinco','seis','siete','ocho','nueve');
    $decenas = array ('diez','once','doce', 'trece','catorce','quince');
    $decena = array    ('dieci','veinti','treinta','cuarenta','cincuenta','sesenta','setenta','ochenta','noventa');
    $centena = array    ('ciento','doscientos','trescientos','cuatrocientos','quinientos','seiscientos','setecientos','ochocientos','novecientos');
    $resto=$todos;

    for ($i=1; $i<=$grupos; $i++) {

        // Hacemos el grupo
        if ($resto>=3) {
            $corte=3; } else {
            $corte=$resto;
        }
            $offset=(($i*3)-3)+$corte;
            $offset=$offset*(-1);

        // la siguiente seccion es una adaptacion de la contribucion de cofyman y JavierB

        $num=implode("",array_slice ($digitos,$offset,$corte));
        $resultado[$i] = "";
        $cen = (int) ($num / 100);              //Cifra de las centenas
        $doble = $num - ($cen*100);             //Cifras de las decenas y unidades
        $dec = (int)($num / 10) - ($cen*10);    //Cifra de las decenas
        $uni = $num - ($dec*10) - ($cen*100);   //Cifra de las unidades
        if ($cen > 0) {
           if ($num == 100) $resultado[$i] = "cien";
           else $resultado[$i] = $centena[$cen-1].' ';
        }//end if
        if ($doble>0) {
           if ($doble == 20) {
              $resultado[$i] .= " veinte";
           }elseif (($doble < 16) and ($doble>9)) {
              $resultado[$i] .= $decenas[$doble-10];
           }else {
              $resultado[$i] .=' '. $decena[$dec-1];
           }//end if
           if ($dec>2 and $uni<>0) $resultado[$i] .=' y ';
           if (($uni>0) and ($doble>15) or ($dec==0)) {
              if ($i==1 && $uni == 1) $resultado[$i].="uno";
              elseif ($i==2 && $num == 1) $resultado[$i].="";
              else $resultado[$i].=$unidad[$uni-1];
           }
        }

        // Le agregamos la terminacion del grupo
        switch ($i) {
            case 2:
            $resultado[$i].= ($resultado[$i]=="") ? "" : " mil ";
            break;
            case 3:
            $resultado[$i].= ($num==1) ? " mill&oacute;n " : " millones ";
            break;
        }
        $resto-=$corte;
    }

    // Sacamos el resultado (primero invertimos el array)
    $resultado_inv= array_reverse($resultado, TRUE);
    $final="";
    foreach ($resultado_inv as $parte){
         $final.=$parte[0];
    }
	$temp = "";
	$digitos_piezas= chunk_split ($partes[1],1,"#");
	$digitos_piezas=substr($digitos_piezas,0,strlen($digitos_piezas)-1);
	$digitos=explode("#",$digitos_piezas);
	if($partes[1]<=9) $temp = $unidad[intval($partes[1])-1];
	if($partes[1]>=10 && $partes[1]<=15){	//15
		$temp = $decenas[$digitos[1]];
	}
	if($partes[1]>=16 && $partes[1]<=19){	//18
		$temp = $decena[0] . " y ";
		$temp .= $unidad[$digitos[1]-1];
	}
	if($partes[1]==20) $temp = " veinte";
	if($partes[1]>=21){	//18
		$temp = $decena[$digitos[0]-1] . " y ";;
		$temp .= $unidad[$digitos[1]-1];
	}
	if($temp != "") $temp = " con " . $temp;
	$suf = $partes[1]>0 && $partes[1]<=9 ? " centimo" : "";
	$suf = $partes[1]>10 ? " centimo" : $suf;
	$temp .= $suf . " exacto.";
	$final = str_replace('  ',' ',$final);
    return ucfirst  ($final . " Bolivares " . $temp );
}


?>