#!/usr/bin/php

<?php
ob_start();
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
file_put_contents(__DIR__ .'/___' . $idForm .'.xml', $xmlForm);
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

		
$campos = $form['f'];
foreach($campos as $campo){	
	
	$rotulo = $campo['label'];
	$ref 		= isset($campo['ref']) ? $campo['ref'] : '' ;
	$grupo 		= isset($campo['grupo']) ? $campo['grupo'] : '' ;
	$opciones 	= isset($campo['opciones']) ? "'" . $campo['opciones'] . "'" : 'NULL';
	$tipo 		= isset($form['tc'][$ref]) ? $form['tc'][$ref] : 'C';	
	
	$sql = "SELECT * FROM P_FORMULARIOS WHERE ID_M_FORMULARIOS='". $ID_M_FORMULARIOS  ."' AND REFERENCIA='". $ref ."'";
	$query->sql = $sql;
	$query->ejecuta_query();
	if(!$query->next_record()){	
		$sql = 	"INSERT INTO P_FORMULARIOS (";
		$sql.= 	"ID_M_FORMULARIOS";
		$sql.= 	",ROTULO";
		$sql.= 	",REFERENCIA";
		$sql.= 	",GRUPO";
		$sql.= 	",OPCIONES";
		$sql.=	",ESTATUS";   
		$sql.=	",TIPO";   
		$sql.=	") VALUES (";
		$sql.=	"'".  $ID_M_FORMULARIOS ."'";
		$sql.=	",'".  $rotulo ."'";
		$sql.=	",'".  $ref ."'";
		$sql.=	",'".  $grupo ."'";	
		$sql.=	",".  $opciones ."";
		$sql.=	",'". 'ACT' ."'";	
		$sql.=	",'".  $tipo ."'";
		$sql.=	")";
		
		$query->sql = $sql;
		$query->ejecuta_query();
		
		if($query->erro_msg!=''){
			ob_end_clean();
			$resp['estatus'] ='ERROR';
			$resp['msg'] = 'error creando campo en el formulario';
			echo json_encode($resp);
			exit(0);	
		}
	}
	
	if(isset($campo['hijo'])){
		foreach($campo['hijo'] as $campo){	
			$rotulo = $campo['label'];
			$ref = isset($campo['ref']) ? $campo['ref'] : '' ;
			$grupo = isset($campo['grupo']) ? $campo['grupo'] : '' ;			
			$opciones 	= isset($campo['opciones']) ? "'" . $campo['opciones'] . "'" : 'NULL' ;
			$tipo 		= isset($form['tc'][$ref]) ? $form['tc'][$ref] : 'C';
			
			$sql = "SELECT * FROM P_FORMULARIOS WHERE ID_M_FORMULARIOS='". $ID_M_FORMULARIOS  ."' AND REFERENCIA='". $ref ."'";
			$query->sql = $sql;
			$query->ejecuta_query();
			if(!$query->next_record()){						
				$sql = 	"INSERT INTO P_FORMULARIOS (";
				$sql.= 	"ID_M_FORMULARIOS";
				$sql.= 	",ROTULO";
				$sql.= 	",REFERENCIA";
				$sql.= 	",GRUPO";
				$sql.= 	",OPCIONES";
				$sql.=	",ESTATUS"; 
				$sql.=	",TIPO";   				
				$sql.=	") VALUES (";
				$sql.=	"'".  $ID_M_FORMULARIOS ."'";
				$sql.=	",'".  $rotulo ."'";	
				$sql.=	",'".  $ref ."'";	
				$sql.=	",'".  $grupo ."'";	
				$sql.=	",".  $opciones ."";
				$sql.=	",'". 'ACT' ."'";	
				$sql.=	",'".  $tipo ."'";	
				$sql.=	")";
				
				$query->sql = $sql;
				$query->ejecuta_query();
				
				if($query->erro_msg!=''){
					ob_end_clean();
					$resp['estatus'] ='ERROR';
					$resp['msg'] = 'error creando campo en el formulario';
					echo json_encode($resp);
					exit(0);
				}
			}
		}
		
	}
}
ob_end_clean();
$resp['estatus'] ='OK';
$resp['id'] = $ID_M_FORMULARIOS;
$resp['msg'] = 'Creado con exito';
$resp['nombre'] = utf8_encode(qT($form['nombre']));
echo json_encode($resp);
exit(1);

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


function pForm($xXML){
	$xXML = str_replace('h:', '', $xXML);
	$doc =  json_decode(json_encode(simplexml_load_string($xXML)), true);

	file_put_contents(__DIR__ . '/doc.txt', print_r($doc['head']['model']['bind'], true));
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
	
	//print_r($form['tc']);	
	//die();

	$form['nombre'] = $doc['head']['title'];
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
							unset($gcampo['label']);
							unset($gcampo['@attributes']);
							unset($gcampo['hint']);
							if(isset($gcampo['item'])){
								unset($op);
								foreach($gcampo['item'] as $item){
									$op[] = $item['value'] . ':' . str_replace(':', '=', utf8_decode(qT($item['label'],false)));
								}
								$tmp['opciones'] = join(',xx', $op);
								unset($op);
								unset($gcampo['item']);
							}
							
							//--------------------------------------------------------
							if(count($gcampo)>0){
								foreach($gcampo as $htipo => $hcampo){
									foreach($hcampo as $hxcampo){
										if(isset($hxcampo['@attributes'])) {
											$xtmp['ref'] = $hxcampo['@attributes']['ref'];
											$xtmp['grupo'] = $nGrupo;
											$xlabel = !is_array($hxcampo['label'])  ?  $hxcampo['label'] : '';							
											$xtmp['label'] = utf8_decode(qT($xlabel, false));										
											if(isset($hxcampo['item'])){
												unset($op);
												if(!isset($hxcampo['item'][0])){
													$op[] = $hxcampo['item']['value'] . ':' . str_replace(':', '=', utf8_decode(qT($hxcampo['item']['label'], false)));
												}else{
													foreach($hxcampo['item'] as $item){
														$op[] = $item['value'] . ':' . str_replace(':', '=', utf8_decode(qT($item['label'], false)));
													}													
												}
												$xtmp['opciones'] = join(',***', $op);
											}
											$tmp['hijo'][] = $xtmp;
										}
									}
								}
							}							
							//--------------------------------------------------------
							$campos[] = $tmp;
							unset($tmp);
						}
					}
				}
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
				unset($xcampo['label']);
				unset($xcampo['@attributes']);
				unset($xcampo['hint']);
				
				if(isset($xcampo['item'])){
					unset($op);
					foreach($xcampo['item'] as $item){
						$op[] = $item['value'] . ':' . $item['label'];
					}
					$tmp['opciones'] = join(',???', $op);
					unset($xcampo['item']);
				}
				if(count($xcampo)>0){
					foreach($xcampo as $htipo => $hcampo){
						foreach($hcampo as $hxcampo){
							if(isset($hxcampo['@attributes'])) {
								$xtmp['ref'] = $hxcampo['@attributes']['ref'];
								$xtmp['grupo'] = $nGrupo;
								$xlabel = !is_array($hxcampo['label'])  ?  $hxcampo['label'] : '';							
								$xtmp['label'] = utf8_decode(qT($xlabel, false));										
								if(isset($hxcampo['item'])){
									unset($op);
									if(!isset($hxcampo['item'][0])){
										$op[] = $hxcampo['item']['value'] . ':' . str_replace(':', '=', utf8_decode(qT($hxcampo['item']['label'], false)));
									}else{
										foreach($hxcampo['item'] as $item){
											$op[] = $item['value'] . ':' . str_replace(':', '=', utf8_decode(qT($item['label'], false)));
										}													
									}
									$xtmp['opciones'] = join(',####', $op);
								}
								$tmp['hijo'][] = $xtmp;
							}
						}
					}
				}				
				$campos[] = $tmp;
				unset($tmp);
			}
		}
			
	}
	$form['f'] = $campos;		
	return $form;
}
?>