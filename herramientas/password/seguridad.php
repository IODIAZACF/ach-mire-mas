<?php
define("Verifica_session",false);
include_once(RUTA_HERRAMIENTAS . "/herramientas/utiles/comun.php");
$tabla		= getvar('tabla','V_M_USUARIOS');
$campo		= getvar('campo','*');
$login		= strtoupper(getvar('login'));
$password	= strtoupper((md5(strtoupper(getvar('password')))));
$verifica   = getvar('verifica',0);

if(join('',$_REQUEST))
{
	$db_server[0]['DB']         = "/opt/lampp/firebird/db/". $_REQUEST['db'] .".gdb";
    include_once(RUTA_HERRAMIENTAS . "/herramientas/sql/class/class_sql.php");
	$query = new sql();
	$query->sql = "SELECT ". $campo ." FROM ". $tabla . "  WHERE CLAVE = '". $password ."' AND ESTATUS='ACT'";
    if(strlen($login)) $query->sql .=" AND LOGIN ='". $login ."'";
    $query->ejecuta_query();
	if(isset($_REQUEST['debug_php'])) echo $query->sql;
    if($query->next_record())
    {
        if(strlen($login) && (!$verifica))
        {
	        $comando = "logger ACCESO " . $query->Record['NOMBRES'] . " - " . $query->Record['GRUPO'] . ' - ' . Sistema . ' - ' . $_REQUEST['db'] ;
	        $salida = shell_exec($comando);

            $xsession_id     = session_id();
            $xusuario_id     = $query->Record['ID_M_USUARIO'];
			$xnombre_usuario = $query->Record['NOMBRE_USUARIO'];

			$xgrupo_id       = $query->Record['ID_M_GRUPOS'];
			$xnombre_grupo   = $query->Record['GRUPO'];
 			
			setsession('db', $_REQUEST['db']);
            setsession('ACCESO_REMOTO', 		  getvar('remoto'));
            setsession('M_USUARIOS_LOGIN', $query->Record['LOGIN']);
            setsession('M_USUARIOS_ID_M_USUARIO', $query->Record['ID_M_USUARIO']);
			setsession('M_USUARIOS_ID_M_DEPARTAMENTOS', $query->Record['ID_M_DEPARTAMENTOS']);
	        setsession('M_USUARIOS_NOMBRES',      $query->Record['NOMBRES']);
            setsession("M_USUARIOS_SESSION_ID",   $xsession_id);
            setsession("M_GRUPOS_ID_GRUPOS",      $query->Record['ID_M_GRUPOS']);
	        setsession("M_GRUPOS_GRUPOS",         $query->Record['GRUPO']);
			
			$xIP = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

            $CONDICION_SESION = "NULL";
            $query->sql = "update M_USUARIOS SET CONDICION_SESION=$CONDICION_SESION,IP='". $xIP . "', SESSION_ID='". $xsession_id  ."' WHERE ID_M_USUARIO='". $query->Record['ID_M_USUARIO'] ."'";
	        $query->ejecuta_query();

	        $query->sql = "SELECT * FROM CONFIGURACION";
            $query->ejecuta_query();

            if($query->next_record())
	        {
	            while(list($variable, $valor) = each($query->Record))
	            {
                   setsession('CONFIGURACION_' .$variable, $valor);
	            }
	        }

			@include_once(RUTA_SISTEMA . "/script/session.php");
			if($_REQUEST['script']){
				include_once(RUTA_SISTEMA . 'script/'. $_REQUEST['script'] .'.php');
			}
			$url = '../main/main.php';
        }
	    
		$xml  = '<tabla>' ."\n";
	    $xml .= '  <registro numero="1">' ."\n";
	    $xml .= '     <MENSAJE>OK</MENSAJE>' ."\n";
	    $xml .= '     <URL>'. $url .'</URL>' ."\n";
	    $xml .= '     <ID_M_USUARIO>' . $xusuario_id  . '</ID_M_USUARIO>' ."\n";
	    $xml .= '     <NOMBRE_USUARIO>'  . $xnombre_usuario . '</NOMBRE_USUARIO>' ."\n";
	    $xml .= '     <ID_M_GRUPOS>'  . $xgrupo_id . '</ID_M_GRUPOS>' ."\n";
	    $xml .= '     <NOMBRE_GRUPO>'  . $xnombre_grupo . '</NOMBRE_GRUPO>' ."\n";
	    $xml .= '  </registro>' ."\n";
	    $xml .= '</tabla>' ."\n";
        die($xml);
    }
    else
    {
		session_destroy();
	    $xml  = '<tabla>' ."\n";
	    $xml .= '  <registro numero="1">' ."\n";
	    $xml .= '     <MENSAJE>Acceso denegado login o password invalido</MENSAJE>' ."\n";
	    $xml .= '     <URL></URL>' ."\n";
	    $xml .= '  </registro>' ."\n";
	    $xml .= '</tabla>' ."\n";
        die($xml);
    }
}
		session_destroy();
	    $xml  = '<tabla>' ."\n";
	    $xml .= '  <registro numero="1">' ."\n";
	    $xml .= '     <MENSAJE>Acceso denegado login o password invalido</MENSAJE>' ."\n";
	    $xml .= '     <URL></URL>' ."\n";
	    $xml .= '  </registro>' ."\n";
	    $xml .= '</tabla>' ."\n";
        die($xml);