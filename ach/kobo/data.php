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

$query2 = new sql();
$query2->DBHost     = "127.0.0.1";
$query2->DBDatabase = "/opt/lampp/firebird/db/" . $db . ".gdb";
$query2->DBUser     = "SYSDBA";
$query2->DBPassword = "masterkey";
$query2->Initialize();


//$query->sql = "SELECT * FROM M_FORMULARIOS WHERE ID_M_FORMULARIOS = '0013401'";
$query->sql = "SELECT * FROM M_FORMULARIOS WHERE TIPO ='SURVEY'";
$query->ejecuta_query();
while($query->next_record()){
	$resp = json_decode(gURL($query->Record['URL_DATA']));	
	$ID_M_FORMULARIOS = $query->Record['ID_M_FORMULARIOS'];
	foreach($resp->results as $i => $rs){
		echo  $i . "   ";
		foreach($rs as $pregunta => $valor){
			if(is_array($valor) || is_object($valor)) continue;
			//echo $pregunta . " => " . utf8_decode(qT($valor, false)) ."\n";
			
			$sql = 	"INSERT INTO TMP_RESPUESTAS (";
			$sql.= 	"ID_M_FORMULARIOS";
			$sql.= 	",REFERENCIA";
			$sql.= 	",UID";
			$sql.= 	",VALOR_C";
			$sql.=	",ESTATUS";   
			$sql.=	") VALUES (";
			$sql.=	"'".  $ID_M_FORMULARIOS ."'";
			$sql.=	",'/data/".  $pregunta ."'";	
			$sql.=	",'".  $rs->_id ."'";
			$sql.=	",'".  utf8_decode(qT($valor, false)) ."'";	
			$sql.=	",'". 'ACT' ."'";	
			$sql.=	")";

			$query2->sql = $sql;
			$query2->ejecuta_query();
			
			if($query2->erro_msg!=''){ 
				print_r($query2);
				die();
			}		
		}
		echo "  OK \n";	
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
    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $cadena = utf8_decode($cadena);
    $cadena = strtr($cadena,  utf8_decode($originales), $modificadas);
    if($upper) $cadena = strtoupper($cadena);
    return utf8_encode($cadena);
}


function pForm($xXML){
	//$xXML =  file_get_contents(__DIR__ . '/ajdFvL43XCnQD3zon4X3fx.xml');
	$xXML = str_replace('h:', '', $xXML);
	$doc =  json_decode(json_encode(simplexml_load_string($xXML)), true);

	$campos = array();
	//print_r($doc['body']);
	foreach($doc['body'] as $tipo => $campo){
		if($tipo=='group'){		
			foreach($campo as $gid => $grupo){
				//echo "inicio grupo " . qT($grupo['label'], false)  ." <hr>\n";
				$nGrupo = qT($grupo['label'], false);
				if($nGrupo  =='ll. Sintomatologia'){				
					//print_r(($grupo['select1']));
					//die();
				}
				foreach($grupo as $ic => $gcampos){
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
							$tmp['label'] = utf8_decode(qT($gcampo['label'], false));
						}
						$campos[] = $tmp;
						unset($tmp);
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
				$tmp['ref'] = $xcampo['@attributes']['ref'];
				$tmp['label'] = utf8_decode(qT($xcampo['label'], false));
				$campos[] = $tmp;
				unset($tmp);
			}
		}
			
	}
	
	return $campos;	
}

?>