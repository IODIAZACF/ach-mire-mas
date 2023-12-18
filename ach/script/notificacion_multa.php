<?php

/*SCRIP DE CORREO DE ESCALAMIENTO MUNDO MOTRIZ*/
include_once (__DIR__ . '/miTemplate.php');
$Plantilla    = __DIR__ . "/tpl/tpl_mail_notificacion_multa.html";
$BlockToParse  = "main";
$Tpl_Mail = new clsmiTemplate();
$Tpl_Mail->templates_path = '';
$Tpl_Mail->LoadTemplate($Plantilla, "main");

$qm = new sql();
$qm->DBHost     = "127.0.0.1";
$qm->DBDatabase = "/opt/lampp/firebird/db/usame.gdb";
$qm->DBUser     = "SYSDBA";
$qm->DBPassword = "masterkey";
$qm->Initialize();


$qm->sql = "SELECT * FROM V_M_CONSULTA_NOTIFICACION WHERE ID_M_CONSULTA_NOTIFICACION ='". $aRecord['IDX'] ."'";
$qm->ejecuta_query();
while($qm->next_record())
{
	$tRecord= xFormat($qm);
	foreach($tRecord as $campo => $valor)
	{
		$Tpl_Mail->SetVar($campo , ucfirst($valor));		
	}
	$aRecord['DESTINATARIO'] = $tRecord['CORREO1'];
}


$aRecord['ASUNTO'] = 'Notificacion #'. $tRecord['ID'];

/*
$resp = file_get_contents('http://127.0.0.1/usame/mundomotriz/genera_json_mundomotriz.php?tabla=ORDENESCONSULTA&campos=*&busca=MATRICULA&xbusca=' . $tRecord['CODIGO1']);
$json = json_decode($resp);

foreach($json->tabla->registro[0] as $campo => $valor)
{
	$Tpl_Mail->SetVar($campo , $valor);		
}
*/

//print_r($Record);
$Tpl_Mail->Parse("main", false);
$html_mail = $Tpl_Mail->GetVar("main");
//file_put_contents(__DIR__ .'/aamail.html', $html_mail);

/*
$MAIL_FROM 			= 'Sistema de Notificacion';
$MAIL_HOST			= 'smtp.office365.com';
$MAIL_PORT			= 587;
$MAIL_USER			= 'usrusame5@segurosequinoccial.com';
$MAIL_PASSWORD		= 'Seguros.123';
//si no va una copia dejar definida en blanco
$MAIL_CC   			= 'ruben.rivadeneira@usame.ec';
*/



?>