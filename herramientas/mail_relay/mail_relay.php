<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

$param = file_get_contents("php://input");
file_put_contents ( __DIR__ . '/recibe_data.txt', $param  );

$param = json_decode($param, true);
file_put_contents ( __DIR__ . '/recibe.txt', print_r( $param , true ) );

use PHPMailer\PHPMailer\PHPMailer;
require __DIR__ . '/vendor/autoload.php';

$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 0;
//$mail->Host = $param['mail_host'];
$mail->Host = '192.168.1.240';
$mail->Port = $param['mail_port'];
$mail->SMTPAuth = true;
//$mail->SMTPSecure = false;
$mail->SMTPAutoTLS = true;
$mail->SMTPOptions = array(
	'ssl' => array(
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => true
	)
);

$mail->Username = $param['mail_user'];
$mail->Password = $param['mail_password'];

$mail->setFrom( $param['mail_user'], $param['mail_user'] );

if( isset ( $param['mail_repli_mail'] ) ){
	
	$mail_repli_name = isset ( $param['mail_repli_name'] ) ? $param['mail_repli_name'] : $param['mail_repli_mail'];
	
	$mail->AddReplyTo( $param['mail_repli_mail'] , $mail_repli_name);	
}

if( isset ( $param['mail_cc'] )){	
	$mail->addBCC( $param['mail_cc'] , $param['mail_cc'] );
}

$mail->addAddress( $param['mail_to'] );

$mail->Subject = $param['mail_subject'];
$mail->msgHTML( $param['mail_content'] );	
$mail->AltBody = $param['mail_content'];


if( isset ( $param['mail_adjunto'] ) ) {
	
	foreach( $param['mail_adjunto'] as $f ) {
		$name = $f['name'] . '.' . $f['tipo'];
		$mail->addStringAttachment( base64_decode( $f['base64'] ), $name );
	}
	
}


if (!$mail->send()) {
	echo "Mailer Error: " . $mail->ErrorInfo;
	return false;
} else {
	echo "Message sent!";
	/*if (save_mail($mail)) {
		
	}
	*/
	
	return true;
	//Section 2: IMAP
	//Uncomment these to save your message in the 'Sent Mail' folder.
	//if (save_mail($mail)) {
	//    echo "Message saved!";
	//}
}

function save_mail($mail)
{
    //You can change 'Sent Mail' to any other folder or tag
    /*$path = "{". $mail->Host  .":993/imap/ssl}[1and1]/Sent Mail";
    //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
    $imapStream = imap_open($path, $mail->Username, $mail->Password);
    $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
    imap_close($imapStream);
    return $result;
	*/
}


?>
