<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include('../config.php');

include_once ('../consulta_claro_estatus/config.php');

date_default_timezone_set("America/Guayaquil");
//require_once './vendor/autoload.php';
require_once $xpath .'utilidades/php/vendor/autoload.php';

$xpath_mail = '/opt/lampp/Maildir';
$mail = new PhpMimeMailParser\Parser();

$email = $_REQUEST['ID_D_CORREO'];
//$xemail   = $xpath_mail . '/pro/' . $email;
$xemail    = $mail_dir_pro . $email;

$mail->setText(file_get_contents($xemail));

$to = $mail->getHeader('to');             // "test" <test@example.com>, "test2" <test2@example.com>
$addressesTo = $mail->getAddresses('to'); //Return an array : [[test, test@example.com, false],[test2, test2@example.com, false]]

$from = $mail->getHeader('from');             // "test" <test@example.com>
$addressesFrom = $mail->getAddresses('from'); //Return an array : test, test@example.com, false

$subject = $mail->getHeader('subject');
$message_id = $mail->getHeader('Message-ID');
$text = $mail->getMessageBody('text');
$html = $mail->getMessageBody('html');

$date_mail = $mail->getHeader('Date');
echo utf8_decode($html .' ' . $text);


$attach_dir = '/opt/tmp/correos/';
@mkdir($attach_dir, 0777,true);
$include_inline = true;
//$mail->saveAttachments($attach_dir ,$include_inline);

$attachments = $mail->getAttachments($include_inline);
echo "<pre>";
//print_r($attachments);

if (count($attachments) > 0) 
{
	foreach ($attachments as $attachment) 
	{
		if(substr($attachment->getContentType(),0,5)=='image')
		{
			echo '<img width="400" src="./correo_imagen.php?ID_D_CORREO='. $email .'&ARCHIVO='. $attachment->getFilename() .'">';
		}
	}
}




?>

