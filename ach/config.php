<?php

if(isset($_REQUEST['debug_php'])){
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL ^ E_DEPRECATED);	
}
define("Sistema", explode('/', $_SERVER['SCRIPT_NAME'])[1]);

$script_name = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : $_SERVER['SCRIPT_NAME'];
define("REAL_SCRIPT_NAME", $script_name); 

$excepciones[]= '/' . Sistema .'/herramientas/password/password.php';
$excepciones[]= '/' . Sistema .'/herramientas/password/seguridad.php';
$excepciones[]= '/' . Sistema .'/herramientas/password/logout.php';
$excepciones[]= '/' . Sistema .'/herramientas/utiles/genera_script.php';
$excepciones[]= '/' . Sistema .'/herramientas/error/error.php';
$excepciones[]= '/' . Sistema .'/herramientas/impresora/impresora.php';
$excepciones[]= '/' . Sistema .'/herramientas/impresora/impresora_new.php';

$excepciones[]= '/' . Sistema .'/movil/modulo_base/index.php';
$excepciones[]= '/' . Sistema .'/herramientas/genera_json/genera_json.php';
$excepciones[]= '/' . Sistema .'/herramientas/genera_json/agi_json.php';
$excepciones[]= '/' . Sistema .'/herramientas/uploader/php/receive.php';

$video_conferencia = true;
$cliente_correo = false;
$chat_omnicanal = false;
$cliente_pbx = false;

if(isset($_SERVER['REDIRECT_URL'])){
	
}else{
	
}

$session_name = explode('/', $_SERVER['SCRIPT_NAME'])[1] . '_session';
if(isset($_REQUEST['debug_php'])) echo "<pre>" . PHP_EOL ;
if(isset($_REQUEST['debug_php'])) echo 'SCRIPT_NAME: ' .$_SERVER['SCRIPT_NAME'] . PHP_EOL;
if(isset($_REQUEST['debug_php'])) echo 'REAL_SCRIPT_NAME: ' . REAL_SCRIPT_NAME. PHP_EOL;
if(isset($_REQUEST['debug_php']) && isset($_SERVER['REDIRECT_URL'])) echo 'REDIRECT_URL: ' . $_SERVER['REDIRECT_URL'] . PHP_EOL;

session_name ( $session_name );
session_start();

if(isset($_REQUEST['debug_php'])) echo 'session_id(): ' . session_id() . PHP_EOL; 
if(isset($_REQUEST['debug_php'])) echo 'session_name(): ' . session_name() . PHP_EOL; 
if(isset($_REQUEST['debug_php'])) print_r($_COOKIE); 
  
if(isset($_REQUEST['debug_php'])) $id_session = session_id();	
if(isset($_REQUEST['debug_php'])) echo $id_session . PHP_EOL;
if(isset($_REQUEST['debug_php'])) print_r ($_SESSION);
if(isset($_REQUEST['debug_php'])) print_r ($_SERVER);
if(isset($_REQUEST['debug_php'])) print_r ($_REQUEST);

$xdb = isset($_SESSION['db']) ? $_SESSION['db'] : 'noDB';
$API_HOME_DIR   = $_SERVER['DOCUMENT_ROOT']. "/". Sistema ."/herramientas/sql/class/txt-db/";
$DB_DIR         = $_SERVER['DOCUMENT_ROOT']. "/txt-databases/";
$DB_LOG_ERROR   = $_SERVER['DOCUMENT_ROOT']. "/db_error/";
$DB_LOG_CONEXION= $_SERVER['DOCUMENT_ROOT']. "/conexion/";
$DB             = "/opt/lampp/firebird/db/". $xdb .".gdb";


@mkdir($DB_DIR       );
@mkdir($DB_DIR       . "log_". Sistema);
@mkdir($DB_LOG_CONEXION);
@mkdir($DB_LOG_CONEXION . "log_". Sistema);
@mkdir($DB_LOG_ERROR );
@mkdir($DB_LOG_ERROR . "log_". Sistema);


$db_server[0]['SERVIDOR']   = "localhost";
$db_server[0]['DB']         = $DB;
$db_server[0]['TIPO']       = "interbase";
$db_server[0]['USUARIO']    = "SYSDBA";
$db_server[0]['PASSWORD']   = "masterkey";

define("Dtipo","interbase");
define("Dcharset","ISO8859_1");

define('RUTA_HERRAMIENTAS', $_SERVER['DOCUMENT_ROOT']);
define('Server_Path', $_SERVER['DOCUMENT_ROOT'] );
define('RUTA_SISTEMA', $_SERVER['DOCUMENT_ROOT'] .  explode('/', str_replace ($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']))[0] .'/');
define('WWW_PATH', '/' . explode('/', str_replace ($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']))[0]);

$JS_SERVER_PATH = $_SERVER['REQUEST_SCHEME'] . '://' .  $_SERVER['SERVER_NAME'] ;
$JS_SERVER_PATH.= ($_SERVER['SERVER_PORT']!='443' && $_SERVER['SERVER_PORT']!='80') ? ':' . $_SERVER['SERVER_PORT'] : '';
$JS_SERVER_PATH.=WWW_PATH .'/';

$Server_Addr	= 'http://' . $_SERVER['SERVER_ADDR'] . WWW_PATH .'/';

define('JS_SERVER_PATH', $JS_SERVER_PATH);
define('Server_Addr', $Server_Addr);

if(isset($_REQUEST['debug_php'])) echo  'RUTA_SISTEMA: ' . RUTA_SISTEMA . PHP_EOL;
if(isset($_REQUEST['debug_php'])) echo  'WWW_PATH: ' . WWW_PATH . PHP_EOL;
if(isset($_REQUEST['debug_php'])) echo  'JS_SERVER_PATH: ' . JS_SERVER_PATH . PHP_EOL;
if(isset($_REQUEST['debug_php'])) echo  'Server_Addr: ' . Server_Addr . PHP_EOL;
if(isset($_REQUEST['debug_php'])) print_r ( $excepciones);

$Usa_Log = 1;

$reporte['FECHA'] 		= date('d/m/Y');
$reporte['HORA'] 		= date('H:i:s a');
$reporte['IP'] 	        = $_SERVER['REMOTE_ADDR'];



define("DB", $xdb); 

?>
