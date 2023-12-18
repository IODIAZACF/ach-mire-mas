<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers: llave, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

include_once('../config.php');
$excepciones[]= '/' . Sistema .'/movil_acceso/seguridad.php';

$tdb = $_REQUEST['db'];

define('isJson', true);

$_HEADER = getallheaders();
file_put_contents(__DIR__ .'/header.txt',  print_r(getallheaders(), true));
file_put_contents(__DIR__ .'/header.txt',  print_r($aHeader, true),FILE_APPEND);
file_put_contents(__DIR__ .'/header.txt',  print_r($_REQUEST, true),FILE_APPEND);
file_put_contents(__DIR__ .'/header.txt',  print_r($_SERVER, true),FILE_APPEND);


if(strlen($tdb))
{
	$_SESSION['db'] = $tdb;
}
include_once(Server_Path . "herramientas/utiles/comun.php");
include_once(Server_Path . "herramientas/jwt/jwt.php");

$tabla		= getvar('tabla','V_M_USUARIOS');
$campo		= getvar('campo','*');
$login		= strtoupper(getvar('login'));
$password	= strtoupper((md5(strtoupper(getvar('password')))));
$verifica   = getvar('verifica',0);


if(join('',$_REQUEST))
{
    //echo "paso....";
    include_once(Server_Path . "herramientas/sql/class/class_sql.php");
	$query = new sql();
	$query->sql = "SELECT ". $campo ." FROM ". $tabla . "  WHERE CLAVE = '". $password ."' AND ESTATUS='ACT'";
    if(strlen($login)) $query->sql .=" AND LOGIN ='". $login ."'";
	if($_HEADER['llave']!=''){
		$query->sql = "SELECT ". $campo ." FROM ". $tabla . "  WHERE LLAVE = '". $_HEADER['llave']."' AND ESTATUS='ACT'";
		$login = $_HEADER['llave'];
	}	
    $query->ejecuta_query();
    

    if($query->next_record())
    {
		if(strlen($login) && (!$verifica))
        {
	        $comando = "logger ACCESO " . $query->Record['NOMBRES'] . " - " . $query->Record['GRUPO'];
	        $salida = shell_exec($comando);

            $xsession_id  = strtoupper(session_id());
            $xusuario_id  = $query->Record['ID_M_USUARIO'];

            setsession('ACCESO_REMOTO', 		  getvar('remoto'));
            setsession('M_USUARIOS_ID_M_USUARIO', $query->Record['ID_M_USUARIO']);
	        setsession('M_USUARIOS_NOMBRES',      $query->Record['NOMBRES']);
            setsession("M_USUARIOS_SESSION_ID",   $xsession_id);
            setsession("M_GRUPOS_ID_GRUPOS",      $query->Record['ID_M_GRUPOS']);
	        setsession("M_GRUPOS_GRUPOS",         $query->Record['GRUPO']);

            $NOMBRE_USUARIO	= utf8_encode($query->Record['NOMBRES']);
            $SISTEMA= $query->Record['SISTEMA'];
			$LLAVE= $query->Record['LLAVE'];
            $DB		= $tdb;
            $USERID = filter_var($query->Record['ID_M_USUARIO'], FILTER_SANITIZE_NUMBER_INT);
			$CLIENTE_ID  = $query->Record['CLIENTE_ID'];
			$ID_M_VENDEDORES = $query->Record['ID_M_VENDEDORES'];
			
	    	$xREG['tabla']['db']        	= $DB;
            $xREG['tabla']['id_m_usuarios'] = $USERID;
			$xREG['tabla']['cliente_id']    = $CLIENTE_ID;
	    	$AUTH = $jwt->encode($xREG);

            //$browser = Browser::detect();
            $CONDICION_SESION = "NULL";
            //print_r($browser);
            if($browser['name']=='unrecognized') $CONDICION_SESION = "'*'";
            //$query->sql = "update M_USUARIOS SET CONDICION_SESION=$CONDICION_SESION,IP='". $_SERVER['REMOTE_ADDR'] . "', SESSION_ID='". $xsession_id  ."' WHERE ID_M_USUARIO='". $query->Record['ID_M_USUARIO'] ."'";
	        //$query->ejecuta_query();
			$_SESSION['llavel'] = $_HEADER['llave'];

	        $query->sql = "SELECT * FROM CONFIGURACION";
            $query->ejecuta_query();

            if($query->next_record())
	        {
	            while(list($variable, $valor) = each($query->Record))
	            {
                   setsession('CONFIGURACION_' .$variable, $valor);
	            }
	        }

			@include_once(Server_Path . "script/session.php");
			$url = Server_Path . 'main/main.php';
			
			
			//echo $DB;
        }
		if(isJson){
			$xData['tabla']['registro']['MENSAJE'] 		  = 'OK';
			$xData['tabla']['registro']['URL'] 			  = $url;
			$xData['tabla']['registro']['SISTEMA'] 		  = $SISTEMA;
			$xData['tabla']['registro']['DB'] 			  = $DB;
			$xData['tabla']['registro']['UID'] 			  = $USERID;
			$xData['tabla']['registro']['NOMBRE_USUARIO'] = $NOMBRE_USUARIO;
			$xData['tabla']['registro']['AUTH'] 		  = $AUTH;
			$xData['tabla']['registro']['LLAVE'] 		  = $LLAVE;
			$xData['tabla']['registro']['ID_M_VENDEDORES']= $ID_M_VENDEDORES;
			
			echo json_encode($xData);
			die();
			
		}else{
			$xml  = '<tabla>' ."\n";
			$xml .= '  <registro numero="1">' ."\n";
			$xml .= '     <MENSAJE>OK</MENSAJE>' ."\n";
			$xml .= '     <URL>'. $url .'</URL>' ."\n";

			$xml .= '     <HOST>'. $HOST .'</HOST>' ."\n";
			$xml .= '     <SISTEMA>'. $SISTEMA .'</SISTEMA>' ."\n";
			$xml .= '     <DB>'. $DB .'</DB>' ."\n";
			$xml .= '     <UID>'. $USERID  .'</UID>' ."\n";
			$xml .= '     <VID>'. $VENDEDOR  .'</VID>' ."\n";
			$xml .= '     <LLAVE>'. $LLAVE  .'</LLAVE>' ."\n";
			$xml .= '     <AUTH>'. $AUTH .'</AUTH>' ."\n";

			$xml .= '  </registro>' ."\n";
			$xml .= '</tabla>' ."\n";
			die($xml);
			
		}
    }
    else
    {
		if(isJson){
			$xData['tabla']['registro']['MENSAJE'] 		='Acceso denegado login o password invalido';
			$xData['tabla']['registro']['URL'] 			='';
			echo json_encode($xData);
			die();
		}else{
			$xml  = '<tabla>' ."\n";
			$xml .= '  <registro numero="1">' ."\n";
			$xml .= '     <MENSAJE>Acceso denegado login o password invalido</MENSAJE>' ."\n";
			$xml .= '     <URL></URL>' ."\n";
			$xml .= '  </registro>' ."\n";
			$xml .= '</tabla>' ."\n";
			die($xml);			
		}
    }
}
		if(isJson){
			$xData['tabla']['registro']['MENSAJE'] 		='Acceso denegado login o password invalido';
			$xData['tabla']['registro']['URL'] 			='';
			echo json_encode($xData);
			die();
		}else{
			$xml  = '<tabla>' ."\n";
			$xml .= '  <registro numero="2">' ."\n";
			$xml .= '     <MENSAJE>Acceso denegado login o password invalido</MENSAJE>' ."\n";
			$xml .= '     <URL></URL>' ."\n";
			$xml .= '  </registro>' ."\n";
			$xml .= '</tabla>' ."\n";
			die($xml);
		}
?>