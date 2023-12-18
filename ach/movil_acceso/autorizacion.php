<?php

include('../config.php');
define("Verifica_session", false); 		//no verifica la session
$_SESSION['db'] = 'semilla'; 			//base de datos general de la semilla.
$UID			= $_REQUEST['uid'];

if($UID=='')
{
    $xREG['tabla']['estatus']   = "ERROR";
    $xREG['tabla']['msg']=utf8_encode('UID no valido');
	echo  json_encode($xREG);
    die();
}

include_once (Server_Path . 'herramientas/utiles/http_log.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
include_once (Server_Path . "herramientas/jwt/class/class_jwt.php");
$jwt = new jwt();

$query = new sql();
$query->sql = "SELECT * FROM V_M_USUARIOS WHERE UID='" . $UID  ."' OR UID2='" . $UID  ."'";
$query->ejecuta_query();

if($query->next_record())
{
    if($query->Record['ESTATUS']!='ACT')
    {
	    $xREG['tabla']['estatus']   = "ERROR";
	    $xREG['tabla']['msg']=utf8_encode('Usuario No Autorizado');
    }
    else
    {
	    foreach($query->Record as $campo => $valor)
	    {
            $tmpReg[$campo]=utf8_encode($valor);
	    }
        //$tmpReg['ID_M_USUARIO']

        $USERID = filter_var($query->Record['CLIENTE_ID'], FILTER_SANITIZE_NUMBER_INT);
        $tmpReg['ID_M_USUARIO'] = $USERID;

	    $xREG['tabla']['estatus']   = "OK";
	    $xREG['tabla']['host']      = $query->Record['HOST'];
	    $xREG['tabla']['db']        = $query->Record['DB'];
        $xREG['tabla']['id_m_usuarios'] = $USERID;

        $tmp_auth = $xREG;
        $xREG['tabla']['auth']      = $jwt->encode($tmp_auth);
	    $xREG['tabla']['registro']  = $tmpReg;
	    $xREG['tabla']['nombre_colegio'] = $query->Record['NOMBRE_CLIENTE'];

	    $ximg  = Server_Path .'imagenes/m_clientes/'. $query->Record['ID_M_CLIENTES'] .'.*';
		$xfile = glob($ximg);
		$img_logo = $xfile[0];
	    //$img_logo = Server_Path .'imagenes/m_clientes/'. $query->Record['ID_M_CLIENTES'] .'.jpg';
	    $imgData = base64_encode(file_get_contents($img_logo));
	    $src = 'data: '.mime_content_type($img_logo).';base64,'.$imgData;
	    $xREG['tabla']['logo_colegio'] = $src;

    	//rdebug($xREG);
    }

}
else
{
    $xREG['tabla']['estatus']   = "ERROR";
    $xREG['tabla']['msg']=utf8_encode('UID no registrado');
}
echo  json_encode($xREG);
?>
