<?php
system('clear');
system("printf '\033[3J'");

//cd /root
//cp /etc/init.d/firebird ./

$dbs = glob('/opt/lampp/firebird/db/*.gdb');

//$dbs[] = '/opt/lampp/firebird/db/protecseguros.gdb';
$dbR =array();

foreach($dbs as $db){

	$t['db_pro'] 	= $db;
	$t['db_gbk'] 	= '/opt/tmp/backup/fb4_' . str_replace('.gdb', '.gbk', basename( $db ) );
	$t['db_res'] 	= '/opt/tmp/backup/fb4_'. basename( $db ) ;
	$dbR[] = $t;
	
	info('Respaldando...', $t['db_pro']);
	
	if(!file_exists ( $t['db_gbk'] ) ){
		$cmd = '/opt/firebird/bin/gbak -b -t -v  -user SYSDBA -password masterkey '. $t['db_pro'] .' ' . $t['db_gbk'];
		exec($cmd,  $resp, $estatus);
		if($estatus=='0'){
			info('Restaurando...', $t['db_gbk']);
			$cmd = '/opt/firebird/bin/gbak -c -r -v -user SYSDBA -password masterkey '. $t['db_gbk'] .' '. $t['db_res'] .' -FIX_FSS_M ISO8859_1';
			exec($cmd,  $resp, $estatus);
			if($estatus=='0'){
				$cmd = 'rm -f '. $t['db_res'];
				exec($cmd,  $resp, $estatus);
			}else{
				$cmd = 'rm -f '. $t['db_res'];
				exec($cmd,  $resp, $estatus);
				
				$cmd = 'rm -f '. $t['db_gbk'];
				exec($cmd,  $resp, $estatus);

				info('Ocurrio un Error restaurando', $t['db_gbk']);
				die();			
			}
		}else{
			info('Ocurrio un Error respaldando', $t['db_pro']);
			echo print_r ($resp, true);
			die();
		}		
	}
	
}

$cmd = '#!/bin/bash'. PHP_EOL;
$cmd.= '' . PHP_EOL;
$cmd.= 'cd /root/' . PHP_EOL;
$cmd.= 'cp /etc/init.d/firebird ./' . PHP_EOL;
$cmd.= 'sudo /opt/firebird/bin/FirebirdUninstall.sh' . PHP_EOL;
$cmd.= 'wget https://github.com/FirebirdSQL/firebird/releases/download/v4.0.1/Firebird-4.0.1.2692-0.amd64.tar.gz' . PHP_EOL;
$cmd.= 'tar zxvf Firebird-4.0.1.2692-0.amd64.tar.gz' . PHP_EOL;
$cmd.= 'cd Firebird-4.0.1.2692-0.amd64/' . PHP_EOL;
$cmd.= 'sudo ./install.sh';

file_put_contents(__DIR__ .'/fbinstall.sh', $cmd);
chmod(__DIR__ .'/fbinstall.sh', 0777);


$cmd = __DIR__ .'/fbinstall.sh';
passthru($cmd);

file_put_contents('/root/.bashrc', PHP_EOL . 'export PATH=/opt/firebird/bin:$PATH' .PHP_EOL, FILE_APPEND);

$cmd = 'sudo chown -R firebird:firebird /opt/lampp/firebird/db/';
exec($cmd,  $resp, $estatus);

$cmd = 'cp ./firebird /etc/init.d/';
exec($cmd,  $resp, $estatus);

chmod('/etc/init.d/firebird', 0755);

$cmd = 'update-rc.d firebird defaults';
exec($cmd,  $resp, $estatus);

$cmd = '#!/bin/bash'. PHP_EOL;
$cmd.= '' . PHP_EOL;

file_put_contents('/root/restoredb.sh', $cmd);

foreach($dbR as $id => $t){

	$mv = '/opt/lampp/firebird/db/__'. basename( $t['db_pro']) ;
	
	$cmd = 'mv '. $t['db_pro'] .' '. $mv;
	exec($cmd,  $resp, $estatus);
	
	$cmd = '/opt/firebird/bin/gbak -c -r -v -user SYSDBA -password masterkey '. $t['db_gbk'] .' '. $t['db_pro'] .' -FIX_FSS_M ISO8859_1' . PHP_EOL;
	file_put_contents('/root/restoredb.sh', $cmd , FILE_APPEND);
	
	$cmd = 'chmod 0777 '. $t['db_pro'] . PHP_EOL;
	file_put_contents('/root/restoredb.sh', $cmd , FILE_APPEND);
	
}

chmod('/root/restoredb.sh', 0777);

echo "Fin de la Instalacion....reinicie el servidor y ejecute al iniciar el siguiente script para actualizar las base de datos a las version 4 de Firebird" .PHP_EOL;
echo "/root/restoredb.sh" .PHP_EOL .PHP_EOL;

function info($t, $m){
	echo str_pad($t, 40, ".") .'->' . $m . PHP_EOL;
}


?>