<?php
/*SCRIP DE CORREO DE ESCALAMIENTO MUNDO MOTRIZ*/
include_once (__DIR__ . '/miTemplate.php');
$Plantilla    = __DIR__ . "/tpl/tpl_mail_escalar_0016.html";
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


$qm->sql = "SELECT * FROM V_M_TICKETS WHERE ID_M_TICKETS ='". $aRecord['IDX'] ."'";
$qm->ejecuta_query();
while($qm->next_record())
{
	$tRecord= xFormat($qm);
	foreach($tRecord as $campo => $valor)
	{
		$Tpl_Mail->SetVar($campo , $valor);		
	}
}

$resp = file_get_contents('http://127.0.0.1/usame/mundomotriz/genera_json_mundomotriz.php?tabla=ORDENESCONSULTA&campos=*&busca=MATRICULA&xbusca=' . $tRecord['CODIGO1']);
$json = json_decode($resp);

foreach($json->tabla->registro[0] as $campo => $valor)
{
	$Tpl_Mail->SetVar($campo , $valor);		
}

//print_r($Record);
$Tpl_Mail->Parse("main", false);
$html_mail = $Tpl_Mail->GetVar("main");
//file_put_contents(__DIR__ .'/aamail.html', $html_mail);

$MAIL_FROM 			= 'MUNDO MOTRIZ';
$MAIL_HOST			= 'smtp.office365.com';
$MAIL_PORT			= 587;
$MAIL_USER			= 'servicioalcliente@mundomotriz.com.ec';
$MAIL_PASSWORD		= 'S0po*.R3kw5)g';
//si no va una copia dejar definida en blanco
$MAIL_CC   			= 'ruben.rivadeneira@usame.ec';

die();

?>