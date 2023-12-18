#!/usr/bin/php

<?php	

$_REQUEST['debug'] = 0;
defined('Debug') 			or define('Debug',			isset($_REQUEST['debug']) ? $_REQUEST['debug'] : 0 );
defined('Server_Path') 		or define('Server_Path',			'/opt/lampp/htdocs');
defined('path_Utilidades') 	or define('path_Utilidades',		'/opt/lampp/utilidades');
defined('Template_Path') 	or define('Template_Path',  		Server_Path . '/ach/plantillas_correos');
defined('DB') 				or define('DB',	'ach');
defined('Email_Spool') 		or define('Email_Spool',  		'/var/spool/correo/' . DB);

include_once (path_Utilidades 	. '/php/clases/class_sql.php');	
include_once (Server_Path 		. '/herramientas/ini/class/class_ini.php');	
include_once (Server_Path 		. '/ach/email_monitoreo/vendor/autoload.php');

if(!file_exists(Email_Spool)){
	system('mkdir ' . Email_Spool . '/');
	system('chmod -R 0777 ' . Email_Spool . '/');
}

use PHPMailer\PHPMailer\PHPMailer;
use raelgc\view\Template;
if(isset($argv)){
	$ID_D_CORREO = $argv[1];
}else{
	$ID_D_CORREO = $_REQUEST['ID_D_CORREO']; 	
}
 
if(file_exists(Email_Spool . '/' . $ID_D_CORREO . '.eml')){
	system('rm -rf ' . Email_Spool . '/' . $ID_D_CORREO . '.eml');
}
$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/". DB .".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();

$sql = "SELECT * FROM D_CORREO WHERE ID_D_CORREO='". $ID_D_CORREO ."'";
if(Debug>0) echo __LINE__ . ' => ' . $sql ."\n";
$query->sql = $sql;
$query->ejecuta_query();
$query->next_record();
$Record= xFormat($query);
$eRecord=$Record;
if(Debug>1) print_r($Record);

/*
ugi@nohungerforum.org clave: Guz90407
$MAIL_HOST			= $Record['MAIL_HOST'];
$MAIL_PORT			= $Record['MAIL_PORT'];
$MAIL_USER			= $Record['MAIL_USER'];
$MAIL_PASSWORD		= $Record['MAIL_PASSWORD'];
*/

$MAIL_HOST			= 'smtp.office365.com';
$MAIL_PORT			= 587;
$MAIL_USER			= 'ugi@nohungerforum.org';
$MAIL_PASSWORD		= 'Guz90407';

$MAIL_FROM			= $Record['MAIL_FROM'];
$MAIL_CC 			= $Record['MAIL_CC'];

$IDX 				= $Record['IDX'];
$TABLA 				= $Record['TABLA'];

$Plantilla     = $Record['ORIGEN'];
$Tpl_Mail = new Template(Template_Path . '/' . $Plantilla . '/' . $Plantilla . '.html');
if(Debug>0) echo __LINE__ . ' => ' . Template_Path . '/' . $Plantilla . '/' . $Plantilla  . "\n";

foreach($Record as $campo => $valor){
	$Tpl_Mail->__set($campo , $valor);
	$Record['ASUNTO'] = str_replace('{' . $campo. '}', $valor, $Record['ASUNTO']);
}
			
if(file_exists(Template_Path .'/' . $Plantilla . '/'. $Plantilla . '.ini'))		
{
	$my_ini = new ini(Template_Path  .'/' . $Plantilla . '/' . $Plantilla );
	/********************PARSEO DE VARIABLE DE INI**************************/
	foreach($my_ini->seccion('VARIABLE') as $variable => $xsql){
		preg_match_all('/({.+?})/', $xsql, $arr);
		for ($i=0;$i<sizeof($arr[0]);$i++){
			$param=substr($arr[0][$i],1,strlen($arr[0][$i])-2);
			if(isset($_REQUEST[$param]))   $xsql = str_replace($arr[0][$i], $_REQUEST[$param], $xsql);
			if(isset($Record[$param])) $xsql = str_replace($arr[0][$i], $Record[$param], $xsql);
		}
		if(Debug>0) echo __LINE__ . ' => ' . trim($xsql) . "\n";
		$query->sql = trim($xsql);
		$query->ejecuta_query();
		if($query->next_record())
		{						
			$xRecord = xFormat($query);
			if(Debug>1) print_r($xRecord) ;
			if(Debug>1) echo "<hr>";
			foreach($xRecord as $campo => $cvalor)
			{
				$xCampo = $variable.'.'.$campo;
				$Tpl_Mail->__set($xCampo, $cvalor);
				$Record['ASUNTO'] = str_replace('{' . $xCampo. '}', $cvalor, $Record['ASUNTO']);							
			}
		}
	}						
				
	/********************PARSEO DE VARIABLE DE INI**************************/
	$bn=1;
	$ds = $my_ini->seccion('BLOQUE'.$bn);
	
	
	
	
	while (is_array($ds) && (sizeof($ds)>0))
	{					
		$xSQL = trim($ds['SQL']);
		$xSQL .= isset($ds['WHERE'])  ? ' WHERE '. $ds['WHERE'] :  '';
		
		preg_match_all('/({.+?})/', $xSQL, $arr);
		for ($i=0;$i<sizeof($arr[0]);$i++)
		{
			$param=substr($arr[0][$i],1,strlen($arr[0][$i])-2);			
			if(isset($_REQUEST[$param]))   $xSQL = str_replace($arr[0][$i], $_REQUEST[$param], $xSQL);
			if(isset($Record[$param])) $xSQL = str_replace($arr[0][$i], $Record[$param], $xSQL);
			
		}
		
		//----- PROCESO LAS CONDICIONES PARA ESTE BLOQUE... --------
		if(isset($ds['CONDICIONES']))
		{
			$condiciones = procesa_condic($ds['CONDICIONES']);
			if ($condiciones)
			{
				$nn=strpos(strtoupper($xsql), 'WHERE');
				if ($nn===false)
				{
					$xSQL.=' WHERE '.$condiciones;
				}
				else
				{
					$xSQL = str_ireplace('where',' where '.$condiciones.' and ',$xSQL);
				}
			}
			
		}
		$xSQL.= isset($ds['GROUP'])  ? ' GROUP BY '. $ds['GROUP'] : '';
		$xSQL.= isset($ds['ORDEN'])  ? ' order by '. $ds['ORDEN'] : '';
		if(Debug>0) echo __LINE__ . ' => ' . trim($xSQL) . "\n";
		
		
		$query->sql = trim($xSQL);
		$query->ejecuta_query();
		$nReg = 0;

		while ($query->next_record()){
			
			$xRecord = xFormat($query);

			$nReg++;
			if(Debug>1) print_r($Record);
			foreach($xRecord as $campo => $cvalor)
			{
				$xCampo = $campo;
				$Tpl_Mail->__set($xCampo, $cvalor);
			}
			 $Tpl_Mail->block($ds['GRUPO']);
		} 
		if(Debug>1) print_r($Record);
		$bn++;
		$ds = $my_ini->seccion('BLOQUE'.$bn);					
	}			
}
$html_mail = $Tpl_Mail->parse();
/************PARSE DE IMAGENES EN HTML **************************/
preg_match_all('/< *img[^>]*src *= *["\']?([^"\']*)/i', $html_mail, $matches);
foreach($matches[1] as $i => $img){
	if(file_exists($img)){
		$img_conte = base64_encode(file_get_contents($img));	
		$tmp_img = '<img src="data:'. mime_content_type($img) .';base64, '. $img_conte;
		$html_mail = str_replace($matches[0][$i], $tmp_img, $html_mail);					
	}else{
		$img = Template_Path .'/' . $Plantilla . '/' . $img;
		if(file_exists($img)){
			$img_conte = base64_encode(file_get_contents($img));	
			$tmp_img = '<img src="data:'. mime_content_type($img) .';base64, '. $img_conte;
			$html_mail = str_replace($matches[0][$i], $tmp_img, $html_mail);					
		}
	}
	if(Debug>0) echo __LINE__ . ' => ' . $img . "\n";
}
			
$e = array();
/* *********** BUSCO LOS ARCHIVOS ADJUNTO EN D_ARCHIVOS****************/
$query->sql = "SELECT * FROM D_ARCHIVOS WHERE TABLA='D_CORREO' AND IDX='". $ID_D_CORREO ."'";
$query->ejecuta_query();
if(Debug>0) echo __LINE__ . ' => ' . $query->sql . "\n";
while ($query->next_record()){  		
	$e[] = $query->Record['RUTA'];
} 	

$query->sql = "SELECT * FROM D_ARCHIVOS WHERE TABLA='". $TABLA ."' AND IDX='". $IDX ."'";
$query->ejecuta_query();
if(Debug>0) echo __LINE__ . ' => ' . $query->sql . "\n";
while ($query->next_record()){  		
	$e[] = $query->Record['RUTA'];
} 	


if(Debug>1) print_r($e);
if($eRecord['BEFORE_PREPARE']!=''){
	include_once ($eRecord['BEFORE_PREPARE']);
} 	

//**********SE MANDA A PREPARAR EL EMAIL*******************/
$email_result = prepare_mail($Record['DESTINATARIO'], $Record['ASUNTO'], $html_mail, $e);			
if($email_result){
	$nombre_fichero_tmp = tempnam(Email_Spool, "eml");
	file_put_contents($nombre_fichero_tmp, $email_result);
	rename($nombre_fichero_tmp , Email_Spool . '/' . $ID_D_CORREO . '.eml');
	system('chmod 0777 ' . Email_Spool . '/' . $ID_D_CORREO . '.eml');
	$resp['estatus'] ='OK';
	echo json_encode($resp);	
	exit(1);		
}else{
	echo "Error Al Preparan el Archivo...";
	exit(0);	
}

function procesa_condic($condiciones)
{
  global $my_ini;
  $ret='';  
  $arrcond=explode(',',$condiciones);
  
  if (!is_array($arrcond)) return '';
  for($index=0;$index<sizeof($arrcond);$index++)
  {
    $sec_cond=$my_ini->seccion($arrcond[$index]);
    if (is_array($sec_cond) && (sizeof($sec_cond)>0))
    {
      $x_var=$my_ini->variable($arrcond[$index], 'VARIABLE');
      $x_sql=$my_ini->variable($arrcond[$index], 'CONDICION');
      $x_cond=$my_ini->variable($arrcond[$index], 'CONECTOR');

      if (!$x_cond) $x_cond=' AND ';
      else $x_cond=' '.$x_cond.' ';

      if ($_REQUEST[$x_var] || ($_REQUEST[$x_var]!=''))
      {
        preg_match_all('/({.+?})/', $x_sql, $arr);
        for ($i=0;$i<sizeof($arr[0]);$i++)
        {
          $param  = substr($arr[0][$i],1,strlen($arr[0][$i])-2);
          $xvalue = str_replace('|','\',\'',$_REQUEST[$param]);
          $x_sql  = str_replace($arr[0][$i], $xvalue, $x_sql);
        }
        if ($ret!='') $ret.=$last_cond;
        $ret.=$x_sql;
        $last_cond=$x_cond;
      }
    }
  }
  return $ret;
}	


function prepare_mail($para, $asunto, $contenido , $files)
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
	$mail->CharSet = 'UTF-8';
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

	if($MAIL_CC!='' ){
		$cc = explode(',', $MAIL_CC);
		foreach($cc as $dest){
			$mail->addBCC($dest);			
		}
	} 

	//$mail->AddCC('dvaldivieso@digtelek.com', 'Diego Valdivieso');
	//Set the subject line
	$mail->Subject = $asunto;
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents($files[0]), __DIR__);
	$mail->msgHTML($contenido);
	//Replace the plain text body with one created manually
	$mail->AltBody = strip_tags($contenido);
	//Attach an image file

	//print_r($files);
	foreach ($files as $xfile) {
		$mail->addAttachment($xfile);	
	}

	//send the message, check for errors
	if (!$mail->preSend()) {
		return false;
	} else {
		return serialize($mail);
	}	
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
