<?php

define ("Server_Path","../../");
$origen = $_REQUEST['origen'];
    $origen  = Server_Path . $origen . '.ini';
	echo "<pre>";
	echo $origen ;
	if( mb_detect_encoding( file_get_contents($origen) =='UTF-8') ){
		echo utf8_decode( file_get_contents($origen) );		
	}

?>