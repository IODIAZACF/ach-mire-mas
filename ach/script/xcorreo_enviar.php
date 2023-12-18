#!/usr/bin/php

<?php	
	set_time_limit(0);

	system('clear');
	system("printf '\033[3J'");
	
	define('Server_Path',			'/opt/lampp/htdocs/');
	define('path_Utilidades',		'/opt/lampp/utilidades/');
	include_once (path_Utilidades 	. 'php/clases/class_sql.php');
	use PHPMailer\PHPMailer\PHPMailer;
	require __dir__ .  '/vendor/autoload.php';

	define('nombre_db', 'usame');



	$debug = 0;
	$continua=1;
	while($continua)
	{
	    echo "Iniciando Lectura ...\n";
		$query = new sql();
		$query->DBHost     = "127.0.0.1";
		$query->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
		$query->DBUser     = "SYSDBA";
		$query->DBPassword = "masterkey";
		$query->Initialize();

		$sql = "SELECT * FROM D_CORREO WHERE TIPO='OUT' AND ESTATUS='PEN'";
		$sql = "SELECT * FROM D_CORREO WHERE ID_D_CORREO='0017374'";
		$query->sql = $sql;
		$query->ejecuta_query();
		while($query->next_record())
		{
			$Record[]= xFormat($query);
	    }
		$query->close();
	    if(isset($Record))
	    {
		    for($m=0;$m<sizeof($Record);$m++)
			{
				
				$MAIL_HOST			= 'smtp.1and1.com';
				$MAIL_PORT			= 587;
				$MAIL_USER			= 'usame@notificaciones24.com';
				$MAIL_PASSWORD		= '424639mail';
				$MAIL_FROM			= 'USAME-INNOVATECH';
				$MAIL_CC 			= '';

				$aRecord = $Record[$m];
				
				$e = explode(' ', $Record[$m]['COMANDO']);
				//print_r($e);
				if(explode(':',$e[0])[0]=='SCRIPT'){
					if(!file_exists(__DIR__ . '/' . trim(explode(':',$e[0])[1]) . '.php')){
						continue;
					}else{
						include(__DIR__ . '/' . trim(explode(':',$e[0])[1]) . '.php');
						//$e[0] = $html_mail;
						unset($e);
						$e = array();
						//print_r($aRecord);
						$result = send_mail($aRecord['DESTINATARIO'], $aRecord['ASUNTO'], $html_mail, $e);
					}		
					//$result = send_mail('luisman01@gmail.com', $aRecord['ASUNTO'], $html_mail, $e);
					//die();
				}else{
					//$result = send_mail($Record[$m]['DESTINATARIO'], $Record[$m]['ASUNTO'], $html_mail, $e);
					$html_mail = file_get_contents($e[0]);
					unset($e[0]);
					$result = send_mail($aRecord['DESTINATARIO'], $aRecord['ASUNTO'], $html_mail, $e);
					//$result = send_mail('luisman01@gmail.com', $aRecord['ASUNTO'], $html_mail, $e);
					//die();
				}
				if($result)
				{
					$sql ="UPDATE D_CORREO SET ESTATUS = 'ENV' WHERE ID_D_CORREO= '" .  $Record[$m]['ID_D_CORREO'] . "'";
					$query->sql = $sql;
					$ejecutar=1;
					$i=0;				
					while($ejecutar)
					{
						$i++;
						$ejecutar=0;
						$query->ejecuta_query();
						//echo "ejecutando...";
						if(strlen($query->erro_msg)>0)
						{
							$ejecutar=1;
							echo "\nERROR:\n";
							echo $query->sql . "\n";
							echo $query->erro_msg . "\n";
							echo "\nEjecutando $i de 5 :\n";
							$query->erro_msg = '';
							sleep(1);
						}
						if($i>=5) $ejecutar=0;
					}
				}
		        
		    }
	    }
	    unset($query);
	    unset($Record);	
		echo "Esperando 5 seg.";
		sleep(5);
		die();	
	}

	function send_mail($para, $asunto, $contenido , $files)
	{
		global $MAIL_FROM;
		global $MAIL_CC;
		global $MAIL_HOST;
		global $MAIL_PORT;
		global $MAIL_USER;
		global $MAIL_PASSWORD;

		//echo "z<zzzzzzzzzzzzzzzz";
		//Create a new PHPMailer instance
		$mail = new PHPMailer;
		//Tell PHPMailer to use SMTP
		$mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 2;
		//Set the hostname of the mail server
		$mail->Host = $MAIL_HOST;
		// use
		// $mail->Host = gethostbyname('smtp.gmail.com');
		// if your network does not support SMTP over IPv6
		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$mail->Port = $MAIL_PORT;
		//Set the encryption system to use - ssl (deprecated) or tls
		$mail->SMTPSecure = 'tls';
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		//Username to use for SMTP authentication - use full email address for gmail
		$mail->Username = $MAIL_USER;
		//Password to use for SMTP authentication
		$mail->Password = $MAIL_PASSWORD;
		//Set who the message is to be sent from
		$mail->setFrom($MAIL_USER, $MAIL_FROM);
		//Set an alternative reply-to address
		$mail->addReplyTo($MAIL_USER, $MAIL_FROM);
		//Set who the message is to be sent to
		$mail->addAddress($para);

		if($MAIL_CC!='' ) $mail->addBCC($MAIL_CC, $MAIL_FROM);
			

		//$mail->AddCC('dvaldivieso@digtelek.com', 'Diego Valdivieso');
		//Set the subject line
		$mail->Subject = $asunto;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		//$mail->msgHTML(file_get_contents($files[0]), __DIR__);
		$mail->msgHTML($contenido);
		//Replace the plain text body with one created manually
		$mail->AltBody = 'This is a plain-text message body';
		//Attach an image file

		foreach ($files as &$xfile) {
			$mail->addAttachment($xfile);	
		}

		//send the message, check for errors
		if (!$mail->send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
			return false;
		} else {
			echo "Message sent!";
			return true;
			//Section 2: IMAP
			//Uncomment these to save your message in the 'Sent Mail' folder.
			/*
			if (save_mail($mail)) {
			    echo "Message saved!";
			}
			*/
		}
	}



	function xlog($info)
	{
		echo date("Y-m-d H:i:s") .' - '. $info . "\n";
	}

	function flog($info)
	{
		$lin = date("Y-m-d H:i:s") .' - '. $info . "\n";
	    $fp = fopen("/opt/tmp/email.log", "a+");
	    fwrite($fp, $lin);
	    fclose($fp);
	}

	function logger($texto)
	{
	    $comando = "logger " . $texto;
	    $salida = shell_exec($comando);
	}

	//Section 2: IMAP
	//IMAP commands requires the PHP IMAP Extension, found at: https://php.net/manual/en/imap.setup.php
	//Function to call which uses the PHP imap_*() functions to save messages: https://php.net/manual/en/book.imap.php
	//You can use imap_getmailboxes($imapStream, '/imap/ssl') to get a list of available folders or labels, this can
	//be useful if you are trying to get this working on a non-Gmail IMAP server.
	function save_mail($mail)
	{
	    //You can change 'Sent Mail' to any other folder or tag
	    $path = "{imap.1and1.com:993/imap/ssl}/}Sent Items";

	    //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
	    $imapStream = imap_open($path, $mail->Username, $mail->Password);

		$boxes = imap_list($imapStream, '{imap.1and1.com:993/imap/ssl}Sent Items', '*');

	    print_r($boxes);

	    $result = imap_append($imapStream, ' {imap.1and1.com:993/imap/ssl}}Sent Items', $mail->getSentMIMEMessage());
	    echo imap_last_error();
	    imap_close($imapStream);
	    return $result;
	}

	function xFormat($xQuery){
		
		for($i=0;$i<sizeof($xQuery->arreglo_atributos);$i++)
		{
			$campo    	= $xQuery->arreglo_atributos[$i]["NOMBRE"];
			$valor  	= $xQuery->Record[$campo];
			
			switch ($xQuery->arreglo_atributos[$i]["TIPO"])
			{
				case 'C':
					$valor = utf8_encode($valor);
					 break;
				case 'N':
					 $valor = number_format($valor, 2, '.', '');
					 break;
				case 'I':
					 $valor = number_format($valor, 0, '.', '');
					 break;
				case 'D':
					$valor = date("d/m/Y", strtotime($valor));
					break;
				case 'T':
					//$valor = substr($valor,8,2).'/'.substr($valor,5,2).'/'. substr($valor,0,4) . substr($valor,10);
					$valor = date("H:i:s", strtotime($valor));
					 break;
			}
			$xQuery->Record[$campo] = $valor;
		}
		return  $xQuery->Record;
	}
?>
