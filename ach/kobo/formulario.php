#!/usr/bin/php

<?php
system('clear');
system("printf '\033[3J'");

//ob_start();
//set_error_handler('error_handler');

//@unlink(__DIR__ . '/__sql.txt');
$xpath = '/opt/lampp/utilidades';
$xtmp  = '/opt/tmp/';
$db = 'ach';

include_once ($xpath . '/php/clases/class_sql.php');

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/" . $db . ".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();

$idForm = $argv[1];

$Url = 'https://kobo.humanitarianresponse.info/api/v2/assets/'. $idForm .'.xml';
$xmlForm = gURL($Url);

$url_data 		='https://kobo.humanitarianresponse.info/api/v2/assets/'. $idForm .'/data.json';	
$url_download 	='https://kobo.humanitarianresponse.info/api/v2/assets/'. $idForm .'.xml';	
file_put_contents(__DIR__ .'/tmp/' . $idForm .'.xml', $xmlForm);

$form = pForm($xmlForm);

$sql = "SELECT * FROM M_FORMULARIOS WHERE UID='". $idForm  ."'";
$query->sql = $sql;
$query->ejecuta_query();
if(!$query->next_record()){
	$ID = $query->Next_ID('M_FORMULARIOS');
	$ID_M_FORMULARIOS = '001'. $ID;			
		
	$sql = 	"INSERT INTO M_FORMULARIOS (";
	$sql.= 	"ID";
	$sql.= 	",ID_M_FORMULARIOS";
	$sql.= 	",UID";
	$sql.= 	",NOMBRES";
	$sql.= 	",COMENTARIOS";
	$sql.= 	",URL_DATA";
	$sql.= 	",URL_CAMPOS";
	$sql.=	",ESTATUS";   
	$sql.=	") VALUES (";
	$sql.=	"'".   $ID ."'";
	$sql.=	",'".  $ID_M_FORMULARIOS ."'";
	$sql.=	",'".  $idForm ."'";	
	$sql.=	",'".  qT($form['nombre']) ."'";
	$sql.=	",'".  ''  ."'";
	$sql.=	",'".  $url_data ."'";
	$sql.=	",'".  $url_download ."'";
	$sql.=	",'". 'ACT' ."'";	
	$sql.=	")";
			
	$query->sql = $sql;
	$query->ejecuta_query();
			
	if($query->erro_msg!=''){
		ob_end_clean();	
		$resp['estatus'] ='ERROR';
		$resp['msg'] = 'error creando el fomulario';
		echo json_encode($resp);
		exit(0);	
	}
}	
else
{
	$ID = $query->Record['ID'];
	$ID_M_FORMULARIOS = $query->Record['ID_M_FORMULARIOS'];	
}


//file_put_contents(__DIR__ . '/__form.txt', print_r($form, true));

$grupos = $form['g'];

//file_put_contents(__DIR__ . '/__grupos.txt', print_r($grupos, true));

foreach($grupos as $id => $grupo){		
	$rotulo 	= $grupo['label'];
	$ref 		= $grupo['ref'];
	//echo  $ref . "   " . $rotulo  . "\n";
	pCampos($grupo['f'], $rotulo);
}


$resp['estatus'] ='OK';
$resp['id'] = $ID_M_FORMULARIOS;
$resp['msg'] = 'Creado con exito';
$resp['nombre'] = utf8_encode(qT($form['nombre']));
echo json_encode($resp);
exit(1);


function pCampos($campos, $grupo ='', $subgrupo=''){
	global $ID_M_FORMULARIOS, $form, $db;
	
	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/" . $db . ".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();

	
	foreach($campos as $id => $campo){
		if($campo['tipo']=='grupo'){
			pCampos($campo['campos']['f'], $grupo, $campo['label']);
			
		}else{
			$rotulo = $campo['label'];
			$ref 		= $campo['ref'];
			
			$nref = array_pop( explode('/', $ref ) );
			
			$opciones 	= isset($campo['opciones']) ? "'" . $campo['opciones'] . "'" : 'NULL';
			$tipo 		= isset($form['tc'][$ref]) ? $form['tc'][$ref] : 'C';
			
			$sql = "SELECT * FROM P_FORMULARIOS WHERE ID_M_FORMULARIOS='". $ID_M_FORMULARIOS  ."' AND REFERENCIA='". $nref ."'";
			$query->sql = $sql;
			$query->ejecuta_query();
			if(!$query->next_record()){				
			
				$sql = 	"INSERT INTO P_FORMULARIOS (";
				$sql.= 	"ID_M_FORMULARIOS";
				$sql.= 	",ROTULO";
				$sql.= 	",REFERENCIA";
				$sql.= 	",GRUPO";
				$sql.= 	",SUBGRUPO";
				$sql.= 	",OPCIONES";
				$sql.=	",ESTATUS";   
				$sql.=	",TIPO";   
				$sql.=	") VALUES (";
				$sql.=	"'".  $ID_M_FORMULARIOS ."'";
				$sql.=	",'".  $rotulo ."'";
				$sql.=	",'".  $nref ."'";
				$sql.=	",'".  $grupo ."'";	
				$sql.=	",'".  $subgrupo ."'";	
				$sql.=	",".  $opciones ."";
				$sql.=	",'". 'ACT' ."'";	
				$sql.=	",'".  $tipo ."'";
				$sql.=	");\n";
				
				$query->sql = $sql;
				$query->ejecuta_query();
				
				if($query->erro_msg!=''){
					ob_end_clean();
					$resp['estatus'] ='ERROR';
					$resp['msg'] = 'error creando campo en el formulario';
					echo json_encode($resp);
					exit(0);	
				}
				
			
				//echo $sql . "\n";
				//file_put_contents(__DIR__ . '/__sql.txt', $sql, FILE_APPEND);			
			}
		}
	}
}


function pForm($xXML){
	$xXML = str_replace('h:', '', $xXML);		
	$xXML = str_replace('á', 'a', $xXML);	
	$xXML = str_replace('é', 'e', $xXML);	
	$xXML = str_replace('í', 'i', $xXML);	
	$xXML = str_replace('ó', 'o', $xXML);	
	$xXML = str_replace('ú', 'u', $xXML);	

	$xXML = str_replace('Á', 'A', $xXML);	
	$xXML = str_replace('É', 'E', $xXML);	
	$xXML = str_replace('Í', 'I', $xXML);	
	$xXML = str_replace('Ó', 'O', $xXML);	
	$xXML = str_replace('Ú', 'U', $xXML);		
	
	$xml = simplexml_load_string($xXML, "SimpleXMLElement", LIBXML_NOCDATA);
	$json = json_encode($xml, JSON_UNESCAPED_UNICODE );
	//file_put_contents(__DIR__ . '/__json.txt', print_r($json, true));
	$doc = json_decode($json,true);
	
	//file_put_contents(__DIR__ . '/__doc.txt', print_r($doc, true));
	//file_put_contents(__DIR__ . '/__xmlgroup.txt', print_r($doc['body']['group'], true));
	
	$form['tc'] = array();
	
	$form['nombre'] = $doc['head']['title'];
	$campos = array();
	
	$gid = 0; 
	
	$form['tc'] = array();
	foreach($doc['head']['model']['bind'] as $id => $gcampo){
		if(isset($gcampo['@attributes']['type'])){
			$id =  $gcampo['@attributes']['nodeset'];
			switch (strtoupper($gcampo['@attributes']['type']))
			{
				case 'INT':
					$form['tc'][$id] = 'N';
					break;
				case 'DATE':
					$form['tc'][$id] = 'F';
					break;
				default:
					$form['tc'][$id] = 'C';
			}
		}
	}	
	
	foreach($doc['body']['group'] as $id => $GRUPO){
		$form['g'][] = pGrupo($GRUPO);		
	}
	
	return $form;
	
}

function error_handler($code, $message, $file, $line) {
    echo " $code, $message, $file, $line";
}

function pGrupo($grupo){	
	$tmp = array();
	$op = array();
	$xtmp = array();
	$campos = array();
	$ref = $grupo['@attributes']['ref'];
	$campos['ref'] = $ref;
	$campos['label'] = qT($grupo['label']);
	$campos['tipo'] = 'grupo';	
	
	//file_put_contents(__DIR__ . '/__' . str_replace('/', '_', $ref) . '.txt', print_r($grupo, true));
	
	unset($xtmp);
	if(isset($grupo['input'])){
		$xGRUPO_CAMPO = $grupo['input'];
		if(!isset($xGRUPO_CAMPO[0])){
			unset($xGRUPO_CAMPO);
			$xGRUPO_CAMPO[] = $grupo['input'];
		}
		foreach($xGRUPO_CAMPO as $i => $campo){
			$xtmp['ref'] = $campo['@attributes']['ref'];
			$xtmp['label'] = utf8_decode($campo['label']);				
			$xtmp['tipo'] = 'input';
			$campos['f'][] = $xtmp;		
		}
	}
	
	unset($xtmp);
	if(isset($grupo['select1'])){
		$xGRUPO_CAMPO = $grupo['select1'];
		if(isset($xGRUPO_CAMPO['item'])){
			unset($xGRUPO_CAMPO);
			$xGRUPO_CAMPO[] = $grupo['select1'];
		}		
		foreach($xGRUPO_CAMPO as $i => $select){
			$xtmp['ref'] = $select['@attributes']['ref'];
			$xtmp['label'] = utf8_decode($select['label']);
			$xtmp['tipo'] = 'select1';
			
			unset($op);
			
			$xITEM = $select['item'];
			if(!isset($xITEM[0])){
				unset($xITEM);
				$xITEM[] = $select['item'];
			}
			
			foreach($xITEM as $item){
				$op[] = $item['value'] . ':' . $item['label'];
			}
			$xtmp['opciones'] = join(',', $op);					
			$campos['f'][] = $xtmp;		
		}
	}
	
	unset($xtmp);
	
	if(isset($grupo['select'])){
		$xGRUPO_CAMPO = $grupo['select'];
		if(isset($xGRUPO_CAMPO['item'])) {
			unset($xGRUPO_CAMPO);
			$xGRUPO_CAMPO[] = $grupo['select'];
		}	
		foreach($xGRUPO_CAMPO as $i => $select){
			$xtmp['ref'] = $select['@attributes']['ref'];
			$xtmp['label'] = utf8_decode($select['label']);
			$xtmp['tipo'] = 'select';
			
			unset($op);
			
			$xITEM = $select['item'];
			if(!isset($xITEM[0])){
				unset($xITEM);
				$xITEM[] = $select['item'];
			}
			
			foreach($xITEM as $item){
				$op[] = $item['value'] . ':' . $item['label'];
			}

			$xtmp['opciones'] = join(',', $op);					
			$campos['f'][] = $xtmp;
		}		
	}
		
	unset($xtmp);
	if(isset($grupo['group'])){
		$xGRUPO_CAMPO = $grupo['group'];
		if(!isset($xGRUPO_CAMPO[0])) {
			unset($xGRUPO_CAMPO);
			$xGRUPO_CAMPO[] = $grupo['group'];
		}	
		foreach($xGRUPO_CAMPO as $i => $grupo){
			$xtmp['ref'] = $grupo['@attributes']['ref'];
			$xtmp['label'] = utf8_decode($grupo['label']);
			$xtmp['tipo'] = 'grupo';
			
			$xtmp['campos'] = pGrupo($grupo);
			
			//print_r($xtmp);
			$campos['f'][] = $xtmp;
		}		
	}


	return $campos;	
}



function gURL($Url){
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => $Url,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => array(
		"Authorization: Token 0a4363a291013a98e3e574a1713f9f9702c1d739"
	  ),
	));

	$response = curl_exec($curl);
	curl_close($curl);
	
	return $response;
	
	
}

function lT($xTime){
	$startTime = $xTime;
	$timeI = strtotime($startTime);
	$LocalDate = date("Y-m-d H:i:s", $timeI);
	return $LocalDate;	
}

function dT($startTime, $endTime){
	$date1 = new DateTime($startTime);
	$date2 = new DateTime($endTime);
	$diff = $date1->diff($date2);

	$duracion = $diff->h < 10 ? '0' . $diff->h : $diff->h;
	$duracion.= $diff->i < 10 ? ':0' . $diff->i : ":".$diff->i;
	$duracion.= $diff->s < 10 ? ':0' . $diff->s : ":".$diff->s;
	$tmp['h'] = $duracion;
	$tmp['o'] = $diff;
	return $tmp;
}


function qT($cadena, $upper = true){
	if($cadena=='') return $cadena;
	if(is_array($cadena) || is_object($cadena)) {
		/*
		print_r($cadena);
		ob_start();
		debug_print_backtrace();
		$trace = ob_get_contents();
		ob_end_clean();				
		file_put_contents(__DIR__ . '/err.txt', $trace);				
		//die();
		*/
	}
    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $cadena = utf8_decode($cadena);
    //$cadena = strtr($cadena,  utf8_decode($originales), $modificadas);
    if($upper) $cadena = strtoupper($cadena);
    return utf8_encode($cadena);
}

?>