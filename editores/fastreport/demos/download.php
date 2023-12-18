<?php
$url ='https://dsg2014.fast-report.com:3000/api/demos/';
echo "<pre>";
$reportes = explode(PHP_EOL, download($url));
foreach($reportes as $reporte){	
	$conte = download( $url . urlencode ($reporte) );
	file_put_contents (__DIR__ .'/' . $reporte, $conte);
	echo $reporte .PHP_EOL;
}

function download($url){
	$userAgent = 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31' ;

	$ch = curl_init($url);

	$options = array(
		CURLOPT_CONNECTTIMEOUT => 20 , 
		CURLOPT_USERAGENT => $userAgent,
		CURLOPT_AUTOREFERER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => 0 ,
		CURLOPT_SSL_VERIFYHOST => 0
	);

	curl_setopt_array($ch, $options);
	$kl = curl_exec($ch);
	curl_close($ch);
	return $kl;
	
}

?>