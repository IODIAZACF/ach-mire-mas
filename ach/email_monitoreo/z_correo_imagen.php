<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once './vendor/autoload.php';

$xpath_mail = '/opt/lampp/Maildir';
$mail = new PhpMimeMailParser\Parser();

$email = $_REQUEST['ID_D_CORREO'];
$archivo = $_REQUEST['ARCHIVO'];

$xemail   = $xpath_mail . '/pro/' . $email;

$mail->setText(file_get_contents($xemail));
$include_inline = true;
$attachments = $mail->getAttachments($include_inline);

if (count($attachments) > 0) 
{
	foreach ($attachments as $attachment) 
	{
		if($attachment->getFilename() == $archivo)
		{
				header("Content-Type: ". $attachment->getContentType());
				echo $attachment->getContent();
				die();
		}
	}
}




?>

