<?php
define("Server_Path", "../../");
include_once(Server_Path . "herramientas/utiles/comun.php");
include_once(Server_Path . "herramientas/sql/class/class_sql.php");

$query = new sql();
$generador                = getvar('generador','');
$llave              = getvar('llave','');
$frase              = strtoupper(getvar('frase',''));
$nivel                  = getvar('nivel',0);
//if($nivel>0) $xnivel = ' and NIVEL <=' . $nivel;

$query->sql = "SELECT * FROM V_CLAVES_ALEATORIAS(". $generador .") WHERE FRASE='". $frase ."' AND CLAVE='". $llave."'";
$query->ejecuta_query();
$query->next_record();
//rdebug($query,'s');

$resultado  = strlen($query->Record['CLAVE']);

@mkdir($DB_DIR, 0777);
@mkdir($DB_DIR . 'log_clave' , 0777);
$fp=fopen($DB_DIR . 'log_clave/log.txt',"a+");

$valor      = getvar('clave');
$ip         = $_SERVER['REMOTE_ADDR'];
$usuario    = getsession('M_USUARIOS_NOMBRES');
$frase      = strtoupper(getvar('login')) ;
$fecha      = date("d-m-Y");
$autorizado = $query->Record['NOMBRE_USUARIO'];
$xmodulo    = $_REQUEST['url_modulo'];
if($xmodulo=='') $xmodulo = $_SERVER['SCRIPT_NAME'];
$modulo     = $xmodulo;
$script     = $_SERVER['SCRIPT_NAME'];
$msg_error = 'Clave Invalida';
if($nivel>0)
{

    if($query->Record['NIVEL']>$nivel)
    {
		$msg_error = 'Nivel no autorizado';
        $resultado = false;
    }
}

if ($resultado)
{
    fwrite($fp,  "'OK','$ip', '$fecha', '$generador', '$valor','$frase', '$usuario', '$autorizado', '$modulo', '$script'\n");
    $xml  = '<tabla>' ."\n";
    $xml .= '  <registro numero="1">' ."\n";
    $xml .= '     <MENSAJE>OK</MENSAJE>' ."\n";
    $xml .= '     <ID_M_USUARIOS>'. $query->Record['ID_M_USUARIOS'] .'</ID_M_USUARIOS>' ."\n";
    $xml .= '     <NOMBRE_USUARIO>'. $query->Record['NOMBRE_USUARIO'] .'</NOMBRE_USUARIO>' ."\n";
    $xml .= '     <URL>'. $url .'</URL>' ."\n";
    $xml .= '  </registro>' ."\n";
    $xml .= '</tabla>' ."\n";
    die($xml);
}
else
{
    fwrite($fp,  "'ERROR','$ip', '$fecha', '$generador', '$valor','$frase', '$usuario', '$autorizado', '$modulo', '$script'\n");
    $xml  = '<tabla>' ."\n";
    $xml .= '  <registro numero="1">' ."\n";
    $xml .= '     <MENSAJE>'. $msg_error .'</MENSAJE>' ."\n";
    $xml .= '     <URL></URL>' ."\n";
    $xml .= '  </registro>' ."\n";
    $xml .= '</tabla>' ."\n";
    die($xml);
}