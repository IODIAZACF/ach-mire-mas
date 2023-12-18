#!/usr/bin/php

<?php
system('clear');
system("printf '\033[3J'");
$xpath 		= '/opt/lampp/';
@unlink(__DIR__.'/tmp/err.txt');

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


//error handler function
function customError($errno, $errstr) {
  echo "<b>Error:</b> [$errno] $errstr";
  
  die();
}

//set error handler
//set_error_handler("customError");


//https://kobo.humanitarianresponse.info/api/v2/assets/ajdFvL43XCnQD3zon4X3fx/data.json"
//echo $response;
//"https://kobo.humanitarianresponse.info/api/v2/assets.json"
$seguir = true;
$Url = 'https://kobo.humanitarianresponse.info/api/v2/assets.json';
$resp = json_decode(gURL($Url));
while($seguir){
	foreach($resp->results as $id => $frm){
		if($frm->asset_type!='survey') continue;
		echo $id  . "  ";
		echo "    " . $frm->name  . "    ";
		//echo "    " . $frm->uid . "\n";
		//echo "    " . $frm->settings->description . "\n";
		//echo "    " . $frm->downloads[1]->url  . "\n";

		$sql = "SELECT * FROM M_FORMULARIOS WHERE UID='". $frm->uid  ."'";
		$query->sql = $sql;
		$query->ejecuta_query();
		if($query->next_record()){
			echo "  YA EXISTE \n";
			continue;
		}	
		
		$ID = $query->Next_ID('M_FORMULARIOS');
		$ID_M_FORMULARIOS = '001'. $ID;			
			
		$sql = 	"INSERT INTO M_FORMULARIOS (";
		$sql.= 	"ID";
		$sql.= 	",ID_M_FORMULARIOS";
		$sql.= 	",UID";
		$sql.= 	",TIPO";
		$sql.= 	",NOMBRES";
		$sql.= 	",COMENTARIOS";
		$sql.= 	",URL_DATA";
		$sql.= 	",URL_CAMPOS";
		$sql.= 	",CAMPO5";
		$sql.=	",ESTATUS";   
		$sql.=	") VALUES (";
		$sql.=	"'".   $ID ."'";
		$sql.=	",'".  $ID_M_FORMULARIOS ."'";
		$sql.=	",'".  $frm->uid ."'";	
		$sql.=	",'".  qT($frm->asset_type) ."'";	
		$sql.=	",'".  qT($frm->name) ."'";
		//$sql.=	",'".  qT($frm->settings->description) ."'";
		$sql.=	",'".  ''  ."'";
		$sql.=	",'".  $frm->data ."'";
		$sql.=	",'".  $frm->downloads[1]->url ."'";
		$sql.=	",'".  $id ."'";
		$sql.=	",'". 'ACT' ."'";	
		$sql.=	")";
		
		$query->sql = $sql;
		$query->ejecuta_query();
		
		if($query->erro_msg!=''){ 
			print_r($query);
			die();
		}		
		echo "  OK \n";	
		
		if($frm->asset_type=='survey'){	
			$xmlForm = gURL($frm->downloads[1]->url);
			//file_put_contents(__DIR__ . '/xml_'. $id  .'.txt', $xmlForm);			
			$campos = pForm($xmlForm);		
			//file_put_contents(__DIR__ . '/data'. $id  .'.txt', print_r($campos, true));			
			foreach($campos as $campo){
				
				$rotulo = $campo['label'];
				$ref = isset($campo['ref']) ? $campo['ref'] : '' ;
				$grupo = isset($campo['grupo']) ? $campo['grupo'] : '' ;
				
				$sql = 	"INSERT INTO P_FORMULARIOS (";
				$sql.= 	"ID_M_FORMULARIOS";
				$sql.= 	",ROTULO";
				$sql.= 	",REFERENCIA";
				$sql.= 	",GRUPO";
				$sql.=	",ESTATUS";   
				$sql.=	") VALUES (";
				$sql.=	"'".  $ID_M_FORMULARIOS ."'";
				$sql.=	",'".  $rotulo ."'";	
				$sql.=	",'".  $ref ."'";	
				$sql.=	",'".  $grupo ."'";	
				$sql.=	",'". 'ACT' ."'";	
				$sql.=	")";
				
				$query->sql = $sql;
				$query->ejecuta_query();
				
				if($query->erro_msg!=''){
					print_r($query);
					die();
				}		
			}	
		}

	}
	echo "Nueva Consulta.....\n";
	echo $resp->next . "\n";
	if($resp->next==''){
		$seguir = false;
	}else{
		$Url = $resp->next;
		$resp = json_decode(gURL($Url));		
	}
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
    $cadena = strtr($cadena,  utf8_decode($originales), $modificadas);
    if($upper) $cadena = strtoupper($cadena);
    return utf8_encode($cadena);
}


function pForm($xXML){
	$xXML = str_replace('h:', '', $xXML);
	$doc =  json_decode(json_encode(simplexml_load_string($xXML)), true);

	$campos = array();
	
	foreach($doc['body'] as $tipo => $campo){
		if($tipo=='group'){		
			foreach($campo as $gid => $grupo){
				//echo "inicio grupo " . qT($grupo['label'], false)  ." <hr>\n";
				$xlabel = !is_array($grupo['label'])  ?  $grupo['label'] : '';
				$nGrupo = utf8_decode(qT($xlabel, false));
				
				if($nGrupo  =='ll. Sintomatologia'){				
					//print_r(($grupo['select1']));
					//die();
				}
				
				//print_r($grupo);
				foreach($grupo as $ic => $gcampos){
					unset($tmp);
					unset($xcampos);
					if($ic=='@attributes' || $ic=='label') continue;
					//echo $ic . "\n";				
					if(isset($gcampos['@attributes'])){					
						$xcampos[] = $gcampos;
					}else{
						$xcampos = $gcampos;
					}
					
					foreach($xcampos as $gcampo){
						if(isset($gcampo['@attributes'])) {
							$tmp['ref'] = $gcampo['@attributes']['ref'];
							$tmp['grupo'] = $nGrupo;							
							$xlabel = !is_array($gcampo['label'])  ?  $gcampo['label'] : '';							
							$tmp['label'] = utf8_decode(qT($xlabel, false));
						}
						$campos[] = $tmp;
					}
				}
				//die('----xxxxxxxxxxxxxxxxxxxxx');
			} 
		}else{
			unset($xcampos);
			if(isset($campo['@attributes'])){					
				$xcampos[] = $campo;
			}else{
				$xcampos = $campo;
			}
			foreach($xcampos as $xcampo){
				//print_r($xcampo);
				unset($tmp);
				$tmp['ref'] = $xcampo['@attributes']['ref'];
				$xlabel = !is_array($xcampo['label'])  ?  $xcampo['label'] : '';
				$tmp['label'] = utf8_decode(qT($xlabel, false));
				$campos[] = $tmp;
			}
		}
			
	}
	
	return $campos;	
}
?>