<?php

include('../config.php');
define("Verifica_session", false); 		//no verifica la session
$_SESSION['db'] = 'semilla'; 			//base de datos general de la semilla.
$UID			= $_REQUEST['uid'];
$CORREO			= $_REQUEST['correo'];
$TOKEN			= $_REQUEST['token_notificacion'];

include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
include_once (Server_Path . 'herramientas/jwt/jwt.php');
$query = new sql();


$query->sql = "SELECT * FROM V_M_USUARIOS WHERE UPPER(CORREO)='" . strtoupper($CORREO) ."'";
$query->ejecuta_query();

if($query->next_record())
{
    $xcampo = $query->Record['UID'] == '' ? 'UID': 'UID2';
	if($xcampo == 'UID2'){
	   $xcampo = $query->Record['UID2'] == '' ? 'UID2': '';
	}
	
	if($xcampo!='')
    {
        $MENSAJE = '';
        unset($xREG);
        $query->sql ="INSERT INTO M_REGISTRO_AGENDA (ESTATUS," . $xcampo. ",CORREO, ID_M_CLIENTES,MENSAJE,TOKEN) VALUES ('PEN','". $UID ."', '". $CORREO ."', '". $query->Record['ID_M_CLIENTES'] ."', '". $MENSAJE ."', '". $TOKEN ."')";
        $query->ejecuta_query();

        if($query->erro!='')
        {
            rdebug($query);
            $xREG['tabla']['estatus']="ERROR_SQL";
	        $xREG['tabla']['msg']=utf8_encode("A ocurrido un error inesperado. Contacte al departamento de soporte al 6003391");

        }else{

			$query->sql ="SELECT * FROM M_REGISTRO_AGENDA WHERE UNICO='". $query->unico ."'";
            $query->ejecuta_query();
            $query->next_record();

            $xREG['tabla']['estatus']="OK";
	        $xREG['tabla']['msg']=utf8_encode("En breve recibira  un email en ". $CORREO .", para la confirmacion de activacion de la APP. \nO si prefiere Contacte al departamento de soporte al 6003391 y suministre este id ". $query->Record['ID_M_REGISTRO_AGENDA']);

            //para autorizacion apple
            if(strtoupper($CORREO)=='RODRIGONAVARRO23@GMAIL.COM')
            {
				$query->sql ="UPDATE M_REGISTRO_AGENDA SET ESTATUS='AUTH' WHERE ID_M_REGISTRO_AGENDA='". $query->Record['ID_M_REGISTRO_AGENDA']."'";
            	$query->ejecuta_query();
            }
        }
    }
    else
    {
    	if($query->Record['UID']==$UID || $query->Record['UID2']==$UID)
        {
            $xREG['tabla']['estatus']="OK";
	        $xREG['tabla']['msg']=utf8_encode('UID ya registrado');
        }
        else
        {
	        $xREG['tabla']['estatus']="ERROR_OPERACION_INVALIDA";
	        $xREG['tabla']['msg']=utf8_encode('Ya existe una app vinculada a su cuenta. Contacte al departamento de soporte al 6003391');
        }
    }
}
else
{
    $query->sql ="INSERT INTO M_REGISTRO_AGENDA (ESTATUS,UID,CORREO, MENSAJE) VALUES ('ERR','". $UID ."', '". $CORREO ."','". $MENSAJE ."')";
    $query->ejecuta_query();

    $xREG['tabla']['estatus']="ERROR_CORREO_NOREGISTRADO";
    $xREG['tabla']['msg']=utf8_encode('CORREO electronico no registrado. Contacte al departamento de soporte al 6003391');
}
echo  json_encode($xREG);
?>
