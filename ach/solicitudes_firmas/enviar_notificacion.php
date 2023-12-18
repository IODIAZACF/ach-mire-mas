<?php	

//echo "<pre>";
use PHPMailer\PHPMailer\PHPMailer;
use raelgc\view\Template;


include_once (Server_Path 		. '/email_monitoreo/vendor/autoload.php');
$Plantilla     = __DIR__ .'/plantilla.html';
$Tpl = new Template($Plantilla);


$query->sql ="SELECT * FROM V_M_SOLICITUDES_FIRMANTES WHERE ID_M_SOLICITUDES_FIRMANTES ='". $eRecord['IDX'] ."'";
$query->ejecuta_query();
$query->next_record();
$tRecord= xFormat($query);


$query->sql ="SELECT * FROM V_M_SOLICITUDES WHERE ID_M_SOLICITUDES ='". $tRecord['ID_M_SOLICITUDES'] ."'";
$query->ejecuta_query();
$query->next_record();
$tRecord= xFormat($query);

foreach($tRecord as $campo => $valor){
	$Tpl->__set($campo , $valor);
}


$query->sql ="SELECT * FROM V_M_SOLICITUDES_FIRMANTES WHERE ID_M_SOLICITUDES ='". $tRecord['ID_M_SOLICITUDES'] ."'";
$query->ejecuta_query();
$GRUPO = '';
$oGRUPO = '';

while ($query->next_record())
{  		
	$tRecord = xFormat($query);
	$GRUPO = $xRecord['ORDEN'];
	if($oGRUPO=='') $oGRUPO = $GRUPO;
	if($oGRUPO!=$GRUPO){
		$Tpl->block('GRUPO');
		$oGRUPO = $GRUPO;
	} 
	foreach($tRecord as $campo => $cvalor)
	{
		$xCampo = $campo;
		$Tpl->__set($xCampo, $cvalor);
	}
	if($tRecord['ESTATUS']=='FIRMADO') $Tpl->block('FIRMA');
	$Tpl->block('FIRMANTE');
} 
$Tpl->block('GRUPO');
$html = $Tpl->parse();
@mkdir('/opt/lampp/htdocs/tmp/', 0777);
$nombre_fichero_tmp = tempnam("/opt/lampp/htdocs/tmp/", "rpt");

file_put_contents($nombre_fichero_tmp, $html);
rename($nombre_fichero_tmp , $nombre_fichero_tmp . '.html');			
system('chmod 0777 '. $nombre_fichero_tmp . '.html');
$cmd_pdf = ' /usr/local/bin/wkhtmltopdf';
$cmd_pdf.= ' --disable-smart-shrinking';
$cmd_pdf.= ' http://127.0.0.1/tmp/'. basename($nombre_fichero_tmp) .'.html '. $nombre_fichero_tmp . '.pdf > '. __DIR__ . '/__xlog.txt 2>&1';
$seguir = true;
$execI = 0;
while($seguir){
	$execI++;
	if(!file_exists($nombre_fichero_tmp . '.pdf')){
		sleep(1);
		echo "\n" . $cmd_pdf . "\n";
		file_put_contents(__DIR__ . '/__x.txt', $cmd_pdf);
		$resp = exec($cmd_pdf);		
		file_put_contents(__DIR__ . '/__1x.txt', $resp);
		$seguir = true;
	}else{
		$seguir = false;
	} 
	if( $execI>10 ) $seguir = false;
}
system('chmod -R 0777 '. $nombre_fichero_tmp . '.*');
$e[] = $nombre_fichero_tmp . '.pdf';
sleep(1);
?>
