#!/usr/bin/php

<?php	
set_time_limit(0);

system('clear');
system("printf '\033[3J'");

defined('Server_Path') 		or define('Server_Path',			'/opt/lampp/htdocs');
define('path_Utilidades',		'/opt/lampp/utilidades');
define('Template_Path',  		Server_Path . '/ach/correo_plantillas');
define('DB',	'ach');

define('Email_Spool',  		'/var/spool/correo/' . DB);

include_once (path_Utilidades 	. '/php/clases/class_sql.php');	
include_once (Server_Path 		. '/ach/email_monitoreo/vendor/autoload.php');

@mkdir(Email_Spool . '/', 0777, true);
@chmod(Email_Spool . '/', 0777);


use PHPMailer\PHPMailer\PHPMailer;
use raelgc\view\Template;

$debug = 0;
$continua=1;
while($continua)
{
	echo "Iniciando Lectura ...\n";
	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/". DB .".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();

	$sql = "SELECT * FROM D_CORREO WHERE ESTATUS='PENDIENTE'";
	if($debug==1) $sql = "SELECT * FROM D_CORREO WHERE ID_D_CORREO='X00190'";
	$query->sql = $sql;
	$query->ejecuta_query();
	while($query->next_record())
	{
		$Record[]= $query->Record;
	}

	$query->close();
	if(isset($Record))
	{
		for($m=0;$m<sizeof($Record);$m++)
		{
			echo $Record[$m]['DESTINATARIO'] . ' --> Enviando.... ';
			
			$file_mail = Email_Spool . '/' . $Record[$m]['ID_D_CORREO'] .'.eml';
			
			$rfcMail = file_get_contents($file_mail);
			$phpMailerObj = new PHPMailer();
			$phpMailerObj = unserialize( $rfcMail );
			if ( $phpMailerObj->postSend() ) {
				echo ' OK ';
				$sql ="UPDATE D_CORREO SET ESTATUS = 'ENVIADO' , COMENTARIOS=NULL WHERE ID_D_CORREO= '" .  $Record[$m]['ID_D_CORREO'] . "'";
			}else
			{
				echo ' ERROR';
				$sql ="UPDATE D_CORREO SET ESTATUS = 'ERROR', COMENTARIOS='ERROR AL ENVIAR EL CORREO ' WHERE ID_D_CORREO= '" .  $Record[$m]['ID_D_CORREO'] . "'";
			}
			eSQL($sql);
			echo "\n";
		}
	}
	unset($query);
	unset($Record);	
	
	echo "Esperando....\n";
	/*
	$equery = new sql();
	$equery->DBHost     = "127.0.0.1";
	$equery->DBDatabase = "/opt/lampp/firebird/db/" . DB . ".gdb";
	$equery->DBUser     = "SYSDBA";
	$equery->DBPassword = "masterkey";
	$equery->Initialize();
	
	$equery->wait('EMAIL_PENDIENTE');	
	
	$equery->close();
	unset($equery);
	*/
	$continua = 0;
	sleep(2);
	
}
function eSQL($sql_query)
{	
	$query2 = new sql();
	$query2->DBHost     = "127.0.0.1";
	$query2->DBDatabase = "/opt/lampp/firebird/db/". DB .".gdb";
	$query2->DBUser     = "SYSDBA";
	$query2->DBPassword = "masterkey";
	$query2->Initialize();
	
	$ejecutar=1;
	$i=0;
	$query2->sql = $sql_query . ' /* '. date('Y-m-d H:i:s') .' */' ;	
	
	echo "\n\n". $query2->sql . "\n\n";	
	while($ejecutar)
	{
		$i++;
		$ejecutar=0;
		$resp ='';
		$query2->beginTransaction();
		$query2->ejecuta_query();
		echo "ejecutando...";
		if(strlen($query2->erro_msg)>0)
		{
			$ejecutar=1;
			echo "\nERROR:\n";
			echo $query2->sql . "\n";
			echo $query2->erro_msg . "\n";
			echo "\nEjecutando $i de 5 :\n";
			$query2->erro_msg = '';
			$resp = $query2->erro_msg;
			sleep(3);
		}
		if($i>=5) $ejecutar=0;
	}
	echo "Listo\n";	
	$query2->commit();
	$query2->close();
    unset($query2);
	return $resp;
}
?>
