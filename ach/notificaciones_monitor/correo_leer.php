#!/usr/bin/php

<?php
date_default_timezone_set("America/Guayaquil");
include dirname(__FILE__) . '/config.php';

define('MAIL_BUZON', MAIL_DIR .'usame/'); 
define('MAIL_NEW', MAIL_BUZON .'new/');
define('MAIL_PRO', MAIL_BUZON .'pro/');

@mkdir(MAIL_BUZON,0777,true);
@mkdir(MAIL_NEW,0777,true);
@mkdir(MAIL_PRO,0777,true);

system('chmod -R 0777 '. MAIL_DIR);

include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');
//include_once (UTIL_PATH . 'utilidades/php/clases/class.phpmailer.php');
require_once (UTIL_PATH . 'utilidades/php/vendor/autoload.php');

$mail = new PhpMimeMailParser\Parser();

	//system('clear');	
$cmd = 'fetchmail -f /root/.monitoreo_usame -a && chmod -R 0777 '. MAIL_DIR;	
system($cmd);	

$mail_list  = @scandir(MAIL_NEW);

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/". DB .".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();

for($i=0;$i<sizeof($mail_list);$i++)
{
	if($mail_list[$i]=='.' || $mail_list[$i]=='..') continue;
	
	echo "Procesando.: " . $i . " de " . sizeof($mail_list) . "  --> ". $mail_list[$i];	
	$file_mail    = MAIL_NEW . $mail_list[$i];
	$mail->setText(file_get_contents($file_mail));
	
	$to 				= $mail->getHeader('to');             
	$addressesTo 		= $mail->getAddresses('to'); 
	$from 				= getEmail($mail->getHeader('from'));             
	$addressesFrom 		= $mail->getAddresses('from'); 
	$subject 			= $mail->getHeader('subject');
	$message_id 		= $mail->getHeader('Message-ID');
	$text 				= $mail->getMessageBody('text');
	$html 				= $mail->getMessageBody('html');	
	$date_mail 			= $mail->getHeader('Date');
	
	echo "\n" . $from . '  ' . $date_mail . ' ' . date("H:i:s d-m-Y", strtotime ($date_mail)) . "\n";	
	$htmlEmbedded = $mail->getMessageBody('htmlEmbedded'); 
	
	$query->sql ="SELECT * FROM D_CORREO WHERE CORREO_ID='". md5($message_id) ."'";
	$query->ejecuta_query();
	if(!$query->next_record()){
		$next_id = '001'. $query->Next_ID('D_CORREO');
		
		$query->sql ="INSERT INTO D_CORREO (ID_D_CORREO,DESTINATARIO,REMITENTE,ASUNTO,TIPO,ESTATUS,FECHA_ENVIO,ARCHIVO,CORREO_ID,EVENTO) VALUES (";
		$query->sql.="'". $next_id ."',";
		$query->sql.="'". addcslashes(utf8_decode($to), "'")  ."',";
		$query->sql.="'". addcslashes(utf8_decode($from), "'")  ."',";
		$query->sql.="'". addcslashes(utf8_decode($subject), "'") ."', ";
		$query->sql.="'". 'IN' ."', ";
		$query->sql.="'". 'LEI' ."', ";
		$query->sql.="'".  date("m-d-Y H:i:s", strtotime ($date_mail)) ."', ";
		$query->sql.="'".  $next_id ."', ";			
		$query->sql.="'". md5($message_id) ."',";
		$query->sql.="'". 'WHATSAPP' ."'";
		$query->sql.=")";	
		
		$query->ejecuta_query();
		if($query->Reg_Afect)
		{	
			$file_pro    = MAIL_PRO . $next_id;
			echo " .....OK\n";		
			$comando = 'mv ' . $file_mail . ' ' . $file_pro ;
			system($comando);		 
			echo "\n-->" .$comando . "<--\n";
			$comando = 'chmod 0777 ' . $file_pro ;			
			system($comando);		 
		}
		else
		{
			echo "ERROR\n";
			echo "---------------------------------------------------\n";
			$fp = fopen('./x.sql', "w+");
			fwrite($fp, $query->sql);
			fclose($fp);
			echo $query->sql . "\n";
			echo $query->erro_msg . "\n";
			echo "---------------------------------------------------\n";		
		}
		
	}
	else{
		$file_pro    = MAIL_PRO . $query->Record['ID_D_CORREO'];
		echo " .....ya procesado OK\n";		
		$comando = 'mv ' . $file_mail . ' ' . $file_pro ;			
		system($comando);		 
		echo "\n-->" .$comando . "<--\n";
		$comando = 'chmod 0777 ' . $file_pro ;			
		system($comando);		 
		
	}
}

$query->close();
unset($query);	

function getEmail($email){
	preg_match_all("([A-Za-z0-9_.-]+@[A-Za-z0-9_.-]+.[A-Za-z0-9_-]+)", $email, $resultado);
	return trim($resultado[0][0]);
}

?>