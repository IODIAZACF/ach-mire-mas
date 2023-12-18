<?php
echo "<pre>";
$xpath 		= '/opt/lampp/';
@unlink(__DIR__.'/tmp/err.txt');

$xpath = '/opt/lampp/utilidades';
$xtmp  = '/opt/tmp/';

$db = 'ach';

include_once ($xpath . '/php/clases/class_sql.php');

setlocale(LC_CTYPE, 'en_AU.utf8');

$xXML =  file_get_contents(__DIR__ . '/aDXrFrurvXGFUshfkMYp9w.xml');

$resp = pForm($xXML);
echo "<pre>";
print_r($resp);

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