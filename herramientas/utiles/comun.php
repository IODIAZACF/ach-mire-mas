<?php
if(isset($_REQUEST['debug_php'])) echo "------se incluyo comun.php ------ " . PHP_EOL;

$jsver = RUTA_HERRAMIENTAS . 'herramientas/jsversion.txt';

$JSVERSION = '';

if(filesize($jsver))
{
	$JSVERSION = file_get_contents($jsver);
}

Verifica_Session();

define("_root", 0);
define("_folder", 1);
define("_path", 2);


if(file_exists (RUTA_SISTEMA . "/estilo/estilo.php") ){
	include(RUTA_SISTEMA . "/estilo/estilo.php");
} else{
	include(RUTA_HERRAMIENTAS . "/herramientas/estilo/estilo.php");
}
include(RUTA_SISTEMA . "/idiomas/es.php");
$s_script = explode('/', $_SERVER['SCRIPT_NAME']);
$s_path = $_SERVER['DOCUMENT_ROOT'] . '/'. $s_script[1];
$t_path = $s_path. "/idiomas/";

if ($gestor = opendir($t_path))
{
    while (false !== ($archivo = readdir($gestor)))
    {
        if ($archivo != "." && $archivo != "..")
        {
            $ext = substr($archivo,-3);
            if($ext=='php') include(RUTA_SISTEMA . "/idiomas/$archivo");
        }
    }
    closedir($gestor);
}

function buscar($tabla,$campo,$busca,$xbusca, $operador= '=')
{
    $resp = '';
    $query = new sql();
    $query->sql ="SELECT " . $campo ." FROM ". $tabla ." WHERE ". $busca . $operador . $xbusca ;
    $query->ejecuta_query();
    if($query->next_record()) $resp = $query->Record[$campo];
    return $resp;
}



function encabezado($titulo)
{
	global $JSVERSION;
	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'. "\n";
	echo '<html>'. "\n";
	echo '<head>'. "\n";
	
	if(is_dir(WWW_PATH .'/estilo')){

		echo '	<link rel="stylesheet" type="text/css"  href="' . WWW_PATH .'/estilo/' . Estilo . '/estilo.css?' . $JSVERSION .  '">'. "\n";		
		echo '	<link rel="stylesheet" type="text/css"  href="' . WWW_PATH .'/estilo/bulma/bulma.min.css?' . $JSVERSION . '">'. "\n";
		echo '	<link rel="stylesheet" type="text/css"  href="' . WWW_PATH .'/estilo/fontawesome/css/all.min.css">'. "\n";		
		echo '	<link rel="icon" type="image/x-icon" href="' . WWW_PATH .'/favicon.png">'. "\n";
		echo '	<script src="/herramientas/main/javascript/winbox.bundle.min.js"></script>'. "\n";
		
	}
	else{
		echo '	<link rel="stylesheet" type="text/css"  href="' . '/herramientas' .'/estilo/' . Estilo . '/estilo.css?' . $JSVERSION .  '">'. "\n";		
		echo '	<link rel="stylesheet" type="text/css"  href="' . WWW_PATH .'/variables.css?' . $JSVERSION . '">'. "\n";
		echo '	<link rel="stylesheet" type="text/css"  href="' . '/herramientas' .'/estilo/bulma/bulma.min.css?' . $JSVERSION . '">'. "\n";
		echo '	<link rel="stylesheet" type="text/css"  href="' . '/herramientas' .'/estilo/fontawesome/css/all.min.css">'. "\n";		
		echo '	<link rel="icon" type="image/x-icon" href="' . WWW_PATH .'/favicon.png">'. "\n";
		echo '	<script src="/herramientas/main/javascript/winbox.bundle.min.js"></script>'. "\n";
		
	}
	

	echo '	<title>' . $titulo . '</title>'. "\n";
	echo '	<!------ [' . $_SERVER["REQUEST_URI"] . '] ------>' . "\n";

	echo '</head>'. "\n";
}

function cargando($ocultaCarga=true){
	
echo <<<EOT

<script type="text/javascript">

function ocultaCarga(){
	setTimeout(function(){
		 $("#LOADING").hide();
	},100 );
}

document.onclick=function() { if (parent.menu) parent.menu.reset(); }


</script>

<div id="LOADING" class="loading"></div>
	
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
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        foreach($_POST as $key => $value) {
            if(preg_match ("/^" . $prefijo . "\S+$/i", $key))
            {
                $nombre = str_replace($prefijo, '', $key);
                $items['nombre']    = $nombre;
                $items['valor']     = $value ;
                if($nombre) $variables[] = $items;
            }
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        foreach($_GET as $key => $value) {
            if(preg_match ("/^" . $prefijo . "\S+$/i", $key))
            {
                $nombre = str_replace($prefijo, '', $key);
                $items['nombre']    = $nombre;
				$items['valor']     = $value ;
                if($nombre) $variables[] = $items;
            }
        }
    }
    return $variables;
}

function Mayuscula( $s ){
	$s = utf8_encode( $s );

	$search  = array('à', 'è', 'ì', 'ò', 'ù', 'á', 'é', 'í', 'ó', 'ú', 'À', 'È', 'Ì', 'Ò', 'Ù','ñ');
	$replace = array('Á', 'É', 'Í', 'Ó', 'Ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Á', 'É', 'Í', 'Ó', 'Ú','Ñ');
	$s =  str_replace($search, $replace, $s);

	$s = utf8_decode( $s );
	$s = strtoupper ( $s );

	return $s;
}

function parse_texto($texto,$variables='')
{
    if(is_array($variables))
    {
      for($i=0;$i<sizeof($variables);$i++)
      {
        $texto = str_replace('{' . $variables[$i]['nombre'] . '}',$variables[$i]['valor'], $texto);
      }
    }
    else
    {
      $texto = variable($texto);
    }
    $texto=str_replace('|',"'",$texto);
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

function variable2($cadena)
{
    return preg_replace_callback('/\{[\w->]*\}/is', reemplazo2 ,  $cadena);
}


function getvar($variable, $vdefault="", $fdefault=false)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        if(isset($_POST[$variable]))
        {
            if($fdefault) return !strlen($_POST[$variable]) ? $vdefault : urldecode($_POST[$variable]);
            return  urldecode($_POST[$variable]);
        }
        else
        {

            return strlen($vdefault) ? $vdefault : false;
        }

    }
    if ($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET[$variable]))
        {
            if($fdefault) return !strlen($_GET[$variable]) ? $vdefault : urldecode($_GET[$variable]);
            return urldecode($_GET[$variable]);

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





function javascript($modulo)
{
	global $JSVERSION;
	
	$modulos = array_flip(explode(',' , $modulo)) ;
	unset($modulos['jquery']);
	$modulos = array_flip($modulos) ;
	array_unshift($modulos, 'jquery');
	
	
    $Estilo = Estilo;
    $Path         = JS_SERVER_PATH;
	$Server_Addr  = Server_Addr;
	$fecha_actual = date('d/m/Y');
	$Sistema 	  = Sistema;	
	$DB 	  = defined('DB') ? DB : Sistema;

    $s_script = explode('/', $_SERVER['SCRIPT_NAME']);
    $s_path   = $_SERVER['DOCUMENT_ROOT'] . $s_script[1];

    //rdebug($_SERVER,'s');
	$id_m_usuario   	= getsession('M_USUARIOS_ID_M_USUARIO');
	$nombre_usuario 	= getsession('M_USUARIOS_NOMBRES');
	$id_m_grupo_usuario	= getsession('M_GRUPOS_ID_GRUPOS');
	
    echo "

<script type=\"text/javascript\">
var db 					  = '$DB';
var sistema 			  = '$Sistema';
var server_path           = '$Path';
var server_addr           = '$Server_Addr';
var server_date           = '$fecha_actual';
var estilo_actual         = '$Estilo';
var t_msg_error           = new Array();
var t_msg_unico           = new Array();
var aventanas             = new Array();
var abmaestros            = new Array();

var id_m_usuario 	  	  = '$id_m_usuario';
var nombre_usuario 	      = '$nombre_usuario';
var id_m_grupo_usuario 	  = '$id_m_grupo_usuario';
</script>
";

	foreach($modulos as $script){
        $ruta   = "/herramientas/" . $script ."/javascript/" . $script . ".js?$JSVERSION";
        echo '<script type="text/javascript" src="' . $ruta . '"></script>' . "\n";
        $plugins  = glob(RUTA_HERRAMIENTAS . "/herramientas/".$script ."/javascript/plugin/*");
		foreach($plugins as $plugin){
            $ruta   = "/herramientas/" . $script . "/javascript/plugin/" . basename($plugin) . "?$JSVERSION";
			echo '<script type="text/javascript" src="' . $ruta . '"></script>' . "\n";
        }
	
	}
	return generateRandomString();
}


function format($valor, $tipo)
{
  switch ($tipo)
  {
    case 'N':
            return number_format($valor, 6, '.', ',');
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

function utf8_string_array_encode(&$array){
    $func = function(&$value,&$key){
        if(is_string($value)){
            $value = utf8_encode($value);
        }
        if(is_string($key)){
            $key = utf8_encode($key);
        }
        if(is_array($value)){
            utf8_string_array_encode($value);
        }
    };
    array_walk($array,$func);
    return $array;
}


function reemplazo($xvariable)
{
        global $arrData;
    $variable = $xvariable[0];
    $variable = str_replace('{','', $variable);
    $variable = str_replace('}','', $variable);
    if(strstr($variable,'->')){
            $xvariable = explode('->',$variable);
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

function reemplazo2($xvariable)
{
    global $arrData;
    $variable = $xvariable[0];
    $variable = str_replace('{','', $variable);
    $variable = str_replace('}','', $variable);
    return '';
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

function noTag($val)
{
    $s= htmlspecialchars($val,ENT_QUOTES);
    return $s;

    $s = str_replace("<", "&lt;", str_replace(">", "&gt;", $val) );
	$s = str_replace("�", "&#209;", $s);
	$s = str_replace("�", "&#208;", $s);
	$s = str_replace("�", "&#193;", $s);
	$s = str_replace("�", "&#201;", $s);
	$s = str_replace("�", "&#205;", $s);
	$s = str_replace("�", "&#211;", $s);
	$s = str_replace("�", "&#218;", $s);
	$s = str_replace("�", "&#225;", $s);
	$s = str_replace("�", "&#233;", $s);
	$s = str_replace("�", "&#237;", $s);
	$s = str_replace("�", "&#243;", $s);
	$s = str_replace("�", "&#250;", $s);
	return $s;
}


function xnum($valor)
{
    return preg_replace("/[^0-9]/","", $valor);
}

if (!function_exists('str_contains')) {
    function str_contains (string $haystack, string $needle)
    {
        return empty($needle) || strpos($haystack, $needle) !== false;
    }
}


function Verifica_Session(){
    global $Verifica_session, $excepciones, $debug_sql;
	//print_r($excepciones);
    if($Verifica_session!='SI')
    {
        if(getsession("M_USUARIOS_ID_M_USUARIO")=='')
        {
            $permitido = false;
            for($i=0;$i<sizeof($excepciones);$i++)
            {
                if( REAL_SCRIPT_NAME == $excepciones[$i])
                {
                    $permitido = true;
                    break;
                }
            }
            if($debug_sql) $permitido = true;
            if(!$permitido)
            {
                $url = "<script language=\"javascript\">\n";
                $url.= "        window.parent.location.replace('/" . Sistema . "/herramientas/password/password.php?db=". Sistema . "');\n";
                $url.= "</script>\n";					
                die($url);
            }
        }
    }

}

function control_session() {
    global $Verifica_session;
	
    $permitido = false;
	//if($Verifica_session) 
	//{
	    for($i=0;$i<sizeof($excepciones);$i++){
	        if(REAL_SCRIPT_NAME == $excepciones[$i])
	        {
	            $permitido = true;
	            break;
	        }
	    }
	    if($debug_sql) $permitido = true;
		if($_SESSION['llave']) $permitido = true;
	    if(!$permitido){
	        $query = new sql();
			$xsession_id  = strtoupper(session_id());
	        $query->sql ="SELECT SESSION_ID,IP FROM M_USUARIOS WHERE ID_M_USUARIO ='" . getsession('M_USUARIOS_ID_M_USUARIO') . "'";
	        $query->ejecuta_query();
	        $query->next_record();
	        if(session_id() != $query->Record['SESSION_ID']) {

				header("Content-type: text/xml");
				echo '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n\n";
				echo '<error>' . "\n";
				echo '	<ERROR>' . "\n";
				echo '		<![CDATA[session '. $query->Record['IP'] .' ]]>' . "\n";
				echo '	</ERROR>' . "\n";
				echo '	<query>'. "\n";
				echo '		<![CDATA['. $xsql .']]>';
				echo '	</query>' . "\n";
				echo '</error>' . "\n";
	            session_unset();
	            session_destroy();
	            die();
	        }
	    }
	//}
}

function generateRandomString($length = 16) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>
