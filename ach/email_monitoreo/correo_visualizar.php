<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1 );
error_reporting(E_ALL);
*/
header('Content-Type: text/html; charset=iso-8859-1');
include('../config.php');

set_time_limit(0);
defined('Debug') 			or define('Debug',			isset($_REQUEST['debug']) ? $_REQUEST['debug'] : 0 );
//define('Server_Path',			dirname(__DIR__ ,1));
define('DB',	basename(Server_Path));
define('Email_Spool',  		'/var/spool/correo/' . DB);
define('path_Utilidades',		'/opt/lampp/utilidades/');


system('chmod -R 0777 /var/spool/');
include_once ( __DIR__  		. '/vendor/autoload.php');
include_once ( path_Utilidades 	. 'php/clases/class_sql.php' );	

use PHPMailer\PHPMailer\PHPMailer;
$phpMailerObj = new PHPMailer();

$file_mail = Email_Spool . '/'. $_REQUEST['ID_D_CORREO'] .'.eml';

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/". DB .".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();

$sql = "SELECT * FROM D_CORREO WHERE ID_D_CORREO='". $_REQUEST['ID_D_CORREO'] ."'";
$query->sql = $sql;
$query->ejecuta_query();
$query->next_record();
//print_r($query->Record);
if($query->Record['ESTATUS']=='BORRADOR'){
	@unlink($file_mail);
}		
if(!file_exists($file_mail)){
	ob_start();
	include_once (Server_Path 		. '/email_monitoreo/correo_crear.php');	
	$correo_crear = ob_get_contents();
	ob_end_clean();
}
if(!file_exists($file_mail)){
	die('No existe el archivo ' . $file_mail );
}

$rfcMail = file_get_contents($file_mail);
$phpMailerObj = unserialize( $rfcMail );

$mail = new PhpMimeMailParser\Parser();
$mail->setText($phpMailerObj->getSentMIMEMessage());

$to = $mail->getHeader('to');             
$addressesTo = $mail->getAddresses('to'); 

$from = $mail->getHeader('from');             
$addressesFrom = $mail->getAddresses('from'); 

$subject = $mail->getHeader('subject');
$message_id = $mail->getHeader('Message-ID');
$text = $mail->getMessageBody('text');
$html = $mail->getMessageBody('html');
$htmlEmbedded = $mail->getMessageBody('htmlEmbedded');

$attachments = $mail->getAttachments();

@system('mkdir /tmp/attachments/');
@system('chmod 0777 /tmp/attachments/'); 

$resp = $mail->saveAttachments('/tmp/attachments/');

echo utf8_decode($htmlEmbedded);

echo "<hr>Archivos Adjuntos.<br>";
foreach ($attachments as $attachment) {    
	$t = filesize('/tmp/attachments/'. $attachment->getFilename());
	echo '<b><a href="./descargar.php?ID_D_CORREO='.  $_REQUEST['ID_D_CORREO'] .'&archivo='. $attachment->getFilename() .'" target="_blank">' . $attachment->getFilename().'</a></b> ' .  round((( $t /1024)/1000) ,2) . ' mb (' . $attachment->getContentType() .")<br>";
}

if(Debug>0) echo "<hr><pre><b>DEBUG</b>\n";
if(Debug>0) echo $correo_crear ."<hr>";
?>
