<?php
$url ='https://declaraciones.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/ConsolidadoContribuyente/obtenerPorNumerosRuc?&ruc=' . $_REQUEST['RUC'];
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_REFERER, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); 
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$result = curl_exec($ch);
curl_close($ch);
if($result!=''){
	if(is_html($result)){
		echo "ERROR";
	}else
	{
	  echo utf8_decode($result);
	}	
}
function is_html($string)
{
  return preg_match("/<[^<]+>/",$string,$m) != 0;
}
?>