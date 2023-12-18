<?php
global $Verifica_session;
include_once (Server_Path . "herramientas/jwt/class/class_jwt.php");
$jwt = new jwt();
$_HEADER = getallheaders();
if($_HEADER['llave']!=''){
	session_start();
	$Verifica_session = 'NO';
    $_SESSION['db']	= $_HEADER['db'];
	include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

	$query = new sql();
	$query->sql = "SELECT * FROM V_M_USUARIOS WHERE LLAVE= '". $_HEADER['llave'] ."'";
    $query->ejecuta_query();
    if($query->next_record()){
        setsession('M_USUARIOS_ID_M_USUARIO', $query->Record['ID_M_USUARIO']);
        setsession('M_USUARIOS_NOMBRES',      $query->Record['NOMBRES']);
        setsession("M_GRUPOS_ID_M_GRUPOS",    $query->Record['ID_M_GRUPOS']);
        setsession("M_GRUPOS_GRUPOS",         $query->Record['GRUPO']);
        setsession("M_USUARIOS_ID_M_CLIENTES",  $query->Record['ID_M_CLIENTES']);
        setsession("M_USUARIOS_NOMBRE_CLIENTE", $query->Record['NOMBRE_CLIENTE']);
        setsession("M_USUARIOS_HOST",           $query->Record['HOST']);
        setsession("M_USUARIOS_SISTEMA",        $query->Record['SISTEMA']);        
    }else{
        session_unset();
        session_destroy();
        $_SESSION = array();
        session_start();
    }
    $Verifica_session = 'SI';
}	


/*
if($_REQUEST['auth']!='')
{
	session_start();
	define("Verifica_session", false);
	$auth = $jwt->decode($_REQUEST['auth']);
    $auth = $jwt->decode($_REQUEST['auth']);
    $_SESSION['db']						= $auth->tabla->db;
	include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

	$query = new sql();
	$query->sql = "SELECT * FROM V_M_USUARIOS WHERE ID_M_USUARIO= '". $auth->tabla->id_m_usuarios ."' AND ESTATUS='ACT'";
    $query->ejecuta_query();
    if($query->next_record())
    {
        setsession('M_USUARIOS_ID_M_USUARIO', $query->Record['ID_M_USUARIO']);
        setsession('M_USUARIOS_NOMBRES',      $query->Record['NOMBRES']);
        setsession("M_GRUPOS_ID_M_GRUPOS",    $query->Record['ID_M_GRUPOS']);
        setsession("M_GRUPOS_GRUPOS",         $query->Record['GRUPO']);
        setsession("M_USUARIOS_ID_M_CLIENTES",  $query->Record['ID_M_CLIENTES']);
        setsession("M_USUARIOS_NOMBRE_CLIENTE", $query->Record['NOMBRE_CLIENTE']);
        setsession("M_USUARIOS_HOST",           $query->Record['HOST']);
        setsession("M_USUARIOS_SISTEMA",        $query->Record['SISTEMA']);
        setsession("M_USUARIOS_DB",             $query->Record['DB']);
		setsession("M_USUARIOS_CLIENTE_ID",     $auth->tabla->cliente_id);
    }
	define("Verifica_session", true);
}
*/

?>
