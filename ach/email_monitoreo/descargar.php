<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_time_limit(0);
define('Server_Path',			dirname(__DIR__ ,1));
define('DB',	basename(Server_Path));
define('Email_Spool',  		'/var/spool/correo/' . DB);

system('chmod -R 0777 /var/spool/');
include_once (Server_Path 		. '/email_monitoreo/vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
$phpMailerObj = new PHPMailer();

$file_mail = Email_Spool . '/'. $_REQUEST['ID_D_CORREO'] .'.eml';
if(!file_exists($file_mail)){
	ob_start();
	include_once (Server_Path 		. '/email_monitoreo/correo_crear.php');	
	ob_end_clean();
}


$rfcMail = file_get_contents($file_mail);
$phpMailerObj = unserialize( $rfcMail );

$mail = new PhpMimeMailParser\Parser();
$mail->setText($phpMailerObj->getSentMIMEMessage());
$resp = $mail->saveAttachments('/tmp/attachments/');
$archivo = '/tmp/attachments/' . $_REQUEST['archivo'];

header("Content-Description: File Transfer"); 
header("Content-Type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=\"". basename($archivo) ."\""); 

readfile($archivo);

?>
