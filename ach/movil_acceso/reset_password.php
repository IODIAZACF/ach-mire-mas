<?php

include('../config.php');
define("Verifica_session", false); 		//no verifica la session
$_SESSION['db'] = 'semilla'; 			//base de datos general de la semilla.
$xemail			= strtolower($_REQUEST['email']);

if($xemail=='')
{
    $xREG['tabla']['estatus']   = "ERROR";
    $xREG['tabla']['msg']=utf8_encode('Correo no valido');
	echo  json_encode($xREG);
    die();
}

include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
include_once (Server_Path . 'herramientas/jwt/jwt.php');

$query = new sql();
$query->sql = "SELECT * FROM V_M_USUARIOS WHERE CORREO='" . $xemail  ."'";
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

		$tmp_password = substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz", 6)), 0, 6);
		$XCLAVE 		= strtoupper(md5(strtoupper($tmp_password)));
    	$XCAMPO1 		= strtoupper($tmp_password);
        $XCLIENTE_ID 	= $query->Record['CLIENTE_ID'];

      	$query->sql ="UPDATE M_USUARIOS SET CLAVE='". $XCLAVE ."', CAMPO1='". $XCAMPO1 ."' WHERE ID_M_USUARIO ='". $query->Record['ID_M_USUARIO'] . "'";
        $query->ejecuta_query();

      	$query->sql ="INSERT INTO D_MAIL (ASUNTO,DESTINATARIO,MENSAJE,REMITENTE,PLANTILLA,SCRIPT,ESTATUS) VALUES ('Reset clave de Mi Agenda Escolar', '". $xemail. "', '". $XCLIENTE_ID . "' , 'registroapp@misemillaescolar.com','misemilla_web_reset','misemilla_web','PEN')";
        $query->ejecuta_query();

	    $xREG['tabla']['estatus']   = "OK";
    	$xREG['tabla']['msg']=utf8_encode('En breve recibira un email con los datos de acceso');
    }

}
else
{
    $xREG['tabla']['estatus']   = "ERROR";
    $xREG['tabla']['msg']=utf8_encode('CORREO no registrado');
}
echo  json_encode($xREG);
?>
