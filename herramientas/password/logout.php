<?php
include_once(Server_Path . "herramientas/utiles/comun.php");
include_once(Server_Path . "herramientas/sql/class/class_sql.php");
$xdb = getsession('db');

if($xdb){
	$query = new sql();
	$query->sql = "update D_SESIONES SET FECHA_SAL='". date("m-d-Y H:i:s") . "' WHERE  SESSION_ID='NULL'";
	$query->ejecuta_query();	
}
$redirect = $_REQUEST['redirect'];

if(isset($_COOKIE[session_name()])) { 
    setcookie(session_name(), '', time() - 42000, '/'); 
}

unset($_SESSION); 
session_unset();
session_destroy();

$url = "<script language=\"javascript\">\n";
//$url.= "        window.parent.location.replace('/" . Sistema . "/herramientas/password/password.php?db=". $xdb . "');\n";
$url.= "        window.parent.location.replace('" . $redirect  . "');\n";
$url.= "</script>\n";					
die($url);

?>