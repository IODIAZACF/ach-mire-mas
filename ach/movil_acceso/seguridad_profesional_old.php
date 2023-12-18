<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers: llave, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

define("Server_Path", "../");
define("Verifica_session",false);
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

if(join('',$_REQUEST))
{
    //echo "paso....";
    include_once(Server_Path . "herramientas/sql/class/class_sql.php");
	$query = new sql();
	$query->sql = "SELECT ". $campo ." FROM ". $tabla . "  WHERE ID_M_USUARIO = 'XXXXUSUA001' AND ESTATUS='ACT'";

	if($_HEADER['llave']!=''){
		$query->sql = "SELECT ". $campo ." FROM ". $tabla . "  WHERE LLAVE = '". $_HEADER['llave']."' AND ESTATUS='ACT'";
		$login = $_HEADER['llave'];
	}	

    $query->ejecuta_query();

    if($query->next_record()){
		
		if(strlen($login) )
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
			
	    	$xREG['tabla']['db']        	= $DB;
            $xREG['tabla']['id_m_usuarios'] = $USERID;
	    	
			$AUTH = $jwt->encode($xREG);

            $CONDICION_SESION = "NULL";
            if($browser['name']=='unrecognized') $CONDICION_SESION = "'*'";

            $query->sql = "update M_USUARIOS SET CONDICION_SESION=$CONDICION_SESION,IP='". $_SERVER['REMOTE_ADDR'] . "', SESSION_ID='". $xsession_id  ."' WHERE ID_M_USUARIO='". $query->Record['ID_M_USUARIO'] ."'";
	        $query->ejecuta_query();
			$_SESSION['llavel'] = $_HEADER['llave'];

			$ID_M_PROFESIONALES  = "";
			$NOMBRE_PROFESIONAL  = "";

			
			$query->sql = "SELECT * FROM M_PROFESIONALES WHERE LOGIN='" . $login . "' AND CLAVE = '".  $password  ."' AND ESTATUS='ACT'";
			$query->ejecuta_query();

			if($query->next_record()){
				$ID_M_PROFESIONALES  = $query->Record['ID_M_PROFESIONALES'];
				$NOMBRE_PROFESIONAL  = $query->Record['NOMBRE_COMPLETO'];
			
				$query->sql = "SELECT * FROM CONFIGURACION";
				$query->ejecuta_query();

				if($query->next_record())
				{
					while(list($variable, $valor) = each($query->Record))
					{
					   setsession('CONFIGURACION_' .$variable, $valor);
					}

					@include_once(Server_Path . "script/session.php");
					$url = Server_Path . 'main/main.php';
					
					$xData['tabla']['registro']['MENSAJE'] 		  = 'OK';
					$xData['tabla']['registro']['URL'] 			  = $url;
					$xData['tabla']['registro']['SISTEMA'] 		  = $SISTEMA;
					$xData['tabla']['registro']['DB'] 			  = $DB;
					$xData['tabla']['registro']['UID'] 			  = $USERID;
					$xData['tabla']['registro']['NOMBRE_USUARIO'] = $NOMBRE_USUARIO;
					$xData['tabla']['registro']['AUTH'] 		  = $AUTH;
					$xData['tabla']['registro']['LLAVE'] 		  = $LLAVE;
					$xData['tabla']['registro']['ID_M_PROFESIONALES']= $ID_M_PROFESIONALES;
					$xData['tabla']['registro']['NOMBRE_PROFESIONAL']= $NOMBRE_PROFESIONAL;

					echo json_encode($xData);
					die();
				} else {
					$xData['tabla']['registro']['MENSAJE'] 		='Acceso denegado login o password invalido';
					$xData['tabla']['registro']['URL'] 			='';
					echo json_encode($xData);
					die();
				}
				

			}
			
        }
	
    }
    else
    {
		$xData['tabla']['registro']['MENSAJE'] 		='Acceso denegado login o password invalido';
		$xData['tabla']['registro']['URL'] 			='';
		echo json_encode($xData);
		die();
    }
}

$xData['tabla']['registro']['MENSAJE'] 		='Acceso denegado login o password invalido';
$xData['tabla']['registro']['URL'] 			='';
echo json_encode($xData);
die();
?>