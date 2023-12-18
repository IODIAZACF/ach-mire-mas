#!/usr/bin/php

<?php
system('clear');
system("printf '\033[3J'");

include __DIR__ . '/config.php';

include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');

include_once (UTIL_PATH . 'utilidades/php/clases/class_ini.php');
include_once (UTIL_PATH . 'utilidades/php/clases/comun.php'); 


while(1){	
	
	$equery = new sql();
	$equery->DBHost     = "127.0.0.1";
	$equery->DBDatabase = "/opt/lampp/firebird/db/" . DB . ".gdb";
	$equery->DBUser     = "SYSDBA";
	$equery->DBPassword = "masterkey";
	$equery->Initialize();
	
	echo "esperando evento....\n";
	$equery->wait('EDUARDO');	
	
	$equery->close();
	unset($equery);
}
	
?>