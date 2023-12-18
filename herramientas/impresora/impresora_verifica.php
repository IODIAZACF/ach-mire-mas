<?php

$xml  = simplexml_load_file ($origen . '.fr3');
$txtReport = print_r($xml, true);
$json = json_decode(json_encode($xml), true);

/*

echo "<pre>";
print_r ( $json );
die();

*/

if(isset($json['TfrxReportPage']['@attributes'])){
	//tiene una sola pagina.......
	
	//print_r ( $json['TfrxReportPage']);	
	procesarCom( $json['TfrxReportPage'] );
}else{
	foreach($json['TfrxReportPage'] as $id => $page){
		procesarCom( $page );
	}	
}

function procesarCom($obj){		
	foreach($obj as $el => $com){
		if($el=='@attributes'){
			$PageName = $com['Name'];
			continue;
		}
		if(isset($com['@attributes'])){
			procesarMemo ( $com['TfrxMemoView'] );
			if( isset ( $com['TfrxSysMemoView'] ) ){
				procesarMemo ( $com['TfrxSysMemoView'] );				
			}
			
		}else{
			if($el!='TfrxMemoView'){
				if(isset ( $com[0] ) ) {
					foreach ( $com as $id => $tobj ){
						if(isset($tobj['@attributes'])){
							procesarMemo ( $tobj['TfrxMemoView'] );
							procesarMemo ( $tobj['TfrxSysMemoView'] );
						}					
					}
				}else{
					if(isset($tobj['@attributes'])){
						procesarMemo ( $tobj['TfrxMemoView'] );
						procesarMemo ( $tobj['TfrxSysMemoView'] );
					}					
				}
				continue;
			} 
			procesarMemo ( $com['TfrxMemoView'] );
			procesarMemo ( $com['TfrxSysMemoView'] );
		}
	}	
}

function procesaObj($v){
	global $verifiTag;	
	
	$n = $v['@attributes']['Name'];
	$rpE[$n] = $v['@attributes'];
	$tag = $v['@attributes']['Text'];
	
	preg_match_all("/\[[^\]]*\]/", $tag, $matches);
	$aU = array_unique($matches[0]);
	//echo $n .PHP_EOL;
	if( isset($aU[0]) ){	
		foreach($aU as $tag){
			$tag = str_replace(array('[',']'), array('',''),$tag);
			if($v['@attributes']['Name']=='SysMemo1'){
			}	
			preg_match_all('/<(.*?)>/', $tag, $xTag);
			if(isset($xTag[1][0])){
				//print_r ($xTag );
				foreach($xTag[1] as $n => $id){
					if( trim($id) != ''){
						$id = str_replace('"', '', $id) ;
						$verifiTag[ $id ]['ITEMS'][] = sTag ( $id, $v['@attributes'] );								
					}
				}
			}else{
				$id = $tag;
				$id = str_replace('"', '', $id) ;
				$verifiTag[ $id ]['ITEMS'][] = sTag ( $id, $v['@attributes'] );
			}						
		}
	}else{

			if($v['@attributes']['Name']=='SysMemo1'){
				echo "aquiiii";
				
			}	
		$id = strlen ( trim ( $v['@attributes']['Text'] ) ) ? $v['@attributes']['Text'] : $v['@attributes']['Name'] ;
		$id = str_replace('"', '', $id) ;
		$verifiTag[ $id ]['ITEMS'][] = sTag ( $id, $v['@attributes'] );
	}

}
function procesarMemo($com){
	global $verifiTag;
	if( isset ( $com['@attributes'] ) ){
		procesaObj($com);
		
	}else{
		foreach($com as $e=> $v){
			procesaObj($v);
		}
	}
	
}

$LogInfo = array();

verifica_variable ('fecha', 'SISTEMA');
verifica_variable ('hora', 'SISTEMA');

foreach($_REQUEST as $nom => $valor){  
  verifica_variable ($nom, 'REQUEST');
  $LogInfo[$nom] = $valor;
}

function sTag($id, $tag){

	$tmp['Name'] = $tag['Name'];
	$tmp['Text'] = utf8_decode( $tag['Text'] );
    $tmp['DataField'] 	= isset($tag['DataField'])  ? $tag['DataField'] : '';
	$tmp['DataSetName'] = isset($tag['DataSetName'])  ? $tag['DataSetName'] : '';
	$tmp['DisplayFormat.FormatStr']		= isset($tag['DisplayFormat.FormatStr'])  ? $tag['DisplayFormat.FormatStr'] : '';
	$tmp['DisplayFormat.Kind']		= isset($tag['DisplayFormat.Kind'])  ? $tag['DisplayFormat.Kind'] : '';
	
	//$tmp['Existe'] 		= 'NO VINCULADO';
	$tmp['Seccion'] 	= '';
	$tmp['Origen'] = $id;
	if($tmp['DataSetName']!='' && $tmp['DataField']){
		$tmp['Tipo'] = 'BLOQUE';
	}else{
		if(substr($tag['Text'],0,1)=='['){
			$tmp['Tipo'] = 'VARIABLE';			
		}else{
			$tmp['Tipo'] = 'ETIQUETA';	
		}
		$tmp['Origen'] = $id;
	} 

	return $tmp;
}	

	

function verifica_variable($variable, $seccion){
	global $verifiTag;

	if ( isset( $verifiTag[$variable] ) ) {		
		$verifiTag[$variable]['Existe'] ='VINCULADO';
		$verifiTag[$variable]['Seccion'] = $verifiTag[$variable]['Seccion'] != '' ? $verifiTag[$variable]['Seccion'] .','. $seccion : $seccion ;			
	}	
}





function resultado(){
	global $verifiTag, $LogInfo;
	

/*
	echo "<pre>";
	print_r ( $verifiTag );
	die();
*/
	
echo <<<EOT

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Impresora Debug</title>
	<link rel="stylesheet" type="text/css"  href="/herramientas/estilo/bulma/bulma.min.css?d2cf0c6a8b5f6d18a47a478870f4bcac">
	<link rel="stylesheet" type="text/css"  href="/herramientas/estilo/jquery.json-viewer.css?d2cf0c6a8b5f6d18a47a478870f4bcac">
	<script type="text/javascript" src="/herramientas/jquery/javascript/jquery.js?d2cf0c6a8b5f6d18a47a478870f4bcac"></script>
	<script type="text/javascript" src="/herramientas/impresora/javascript/impresora.js?d2cf0c6a8b5f6d18a47a478870f4bcac"></script>	
    <script type="text/javascript" src="/herramientas/jsonviewer/javascript/jquery.json-viewer.js"></script>
</head>



<style>


.copiar{
	cursor: pointer;
	
}

.copiado
{
  color: #D70000;
	
}


</style>
<body>
<div class="m-6  is-fluid">

<section class="hero is-link">
  <div class="hero-body">
    <div class="subtitle">
      Impresora Debug
    </div>
  </div>
</section>

<table class="table is-bordered is-hoverable is-size-7">
  <thead>
    <tr>
      <th>Name</th>
	  <th>Tipo</th>
      <th>Texto</th>
	  <th>Origen</th>
	  <th>Seccion</th>
	  <th>Tipo</th>
	  <th>Formato</th>
	  <th>Existe</th>
    </tr>
  </thead>
  <tbody>
  

EOT;

foreach($verifiTag as $id => $tags){
	
	if( isset($tags['Existe']) ){
		$class ='has-text-success';
		$xESTATUS = 'VINCULADO';
	}else{
		$class = $tags['Tipo']=='ETIQUETA' ? 'has-text-info' : 'has-text-danger';		
		$xESTATUS = 'NO VINCULADO';
	}
	$Seccion = $tags['Seccion'];
	
	foreach($tags['ITEMS'] as $tag  ){
		echo '<tr class="'. $class  .'">' . PHP_EOL;
		echo '  <td>'. $tag['Name']		.'</td>' . PHP_EOL;
		echo '  <td>'. $tag['Tipo']  	.'</td>' . PHP_EOL;
		echo '  <td>'. htmlentities($tag['Text'])  	.'</td>' . PHP_EOL;
		echo '  <td>'. utf8_decode ( $tag['Origen'] ) 	.'</td>' . PHP_EOL;
		echo '  <td>'. $Seccion  .'</td>' . PHP_EOL;
		echo '  <td>'. $tag['DisplayFormat.Kind']		.'</td>' . PHP_EOL;
		echo '  <td>'. $tag['DisplayFormat.FormatStr']		.'</td>' . PHP_EOL;
		echo '  <td class="'. $class  .'">'. $xESTATUS 	.'</td>' . PHP_EOL;
		echo '</tr>' . PHP_EOL;	
	}
	
}
	
echo <<<EOT
	
  </tbody>
</table>
<table class="table is-bordered is-hoverable is-size-7">
  <thead>
    <tr>
      <th>Variable</th>
	  <th>Valor (Haz click sobre el valor y se copia al portapapeles)</th>
      <th>Comentario</th>
    </tr>
  </thead>
  <tbody>

EOT;
  
  
foreach($LogInfo as $variable => $valor){	
	echo '<tr>' . PHP_EOL;
    echo '  <td class="has-text-weight-bold">'. $variable		.'</td>' . PHP_EOL;
	echo '  <td class="copiar">'.  $valor		.'</td>' . PHP_EOL;
	echo '  <td class="">'.  $valor		.'</td>' . PHP_EOL;
    echo '</tr>' . PHP_EOL;	
}
	

$Query_Url = JS_SERVER_PATH . 'herramientas/impresora/impresora_new.php?' . str_replace('impresora_debug=1&', '', parse_url ( $_SERVER['REQUEST_URI'] )['query']);
$xLogInfo = '<a href="' . $Query_Url . '" target="_blank">'. $Query_Url .'</a>';

//echo $Query_Url;

//die( 'xxxxxxxxxxxxxxxx' );

$Query_Url = Server_Addr . 'herramientas/impresora/impresora_new.php?' . str_replace('impresora_debug=1&', '', parse_url ( $_SERVER['REQUEST_URI'] )['query']);

$ch=curl_init();
$timeout=10;

curl_setopt($ch, CURLOPT_URL, $Query_Url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
	echo "error· " . $error_msg ;
	$data_json = 'var data = {};';
}else{
	$data_json = 'var data = ' . $result;
	
}
curl_close($ch);

//echo $result;

	
echo <<<EOT
	
  </tbody>
</table>

<table class="table is-bordered is-hoverable is-size-7">
  <thead>
    <tr>
      <th>{$xLogInfo}</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
		   <p class="options">
			  Options:
			  <label title="Generate node as collapsed">
				<input type="checkbox" id="collapsed" checked>Collapse nodes
			  </label>
			  <label title="Allow root element to be collasped">
				<input type="checkbox" id="root-collapsable" checked>Root collapsable
			  </label>
			  <label title="Surround keys with quotes">
				<input type="checkbox" id="with-quotes">Keys with quotes
			  </label>
			  <label title="Generate anchor tags for URL values">
				<input type="checkbox" id="with-links" checked>
				With Links
			  </label>
			</p>	  
	  </td>
    </tr>
    <tr>
      <td><pre id="json-renderer"></pre></td>
    </tr>
  </tbody>
 </table>

 
</div>

<script>
function iniciar(){
	console.log('ejecuto', Url_Path);
}

function copy(text){
	var temp = $("<div>");
	$("body").append(temp);
	temp.attr("contenteditable", true)
	   .html( text ).select()
	   .on("focus", function() { document.execCommand('selectAll',false,null); })
	   .focus();
	document.execCommand("copy");
	temp.remove();
}

$(".copiar").on( "click", function() {
	$(".copiar").removeClass( "copiado" );
	$(this).addClass( "copiado" );
	copy( $(this).text() )
});


$('p.options input[type=checkbox]').click(renderJson);

function renderJson(){
	{$data_json}

    var options = {
      collapsed: $('#collapsed').is(':checked'),
      rootCollapsable: $('#root-collapsable').is(':checked'),
      withQuotes: $('#with-quotes').is(':checked'),
      withLinks: $('#with-links').is(':checked')
    };
	
	$('#json-renderer').jsonViewer(data, options);
	
}

renderJson();
</script>

EOT;
	
}

?>

