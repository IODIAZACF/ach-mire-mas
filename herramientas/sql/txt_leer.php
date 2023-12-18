<?
system('clear');
system("printf '\033[3J'");


$DIR_LOG = __DIR__ . '/sql_log/';
$xpath = '/opt/lampp/utilidades';
$xtmp  = '/opt/tmp/';
$db = 'comercialapolo';
@mkdir($DIR_LOG, true);

include_once ($xpath . '/php/clases/class_sql.php');

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/" . $db . ".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();

$logs = glob('/opt/lampp/htdocs/txt-databases/log_comercialapolo_recuperar/*');
//print_r($logs);
foreach($logs as $log){
	$reg =  file($log);
	$sql_log = str_replace( '.txt', '.sql', basename($log));
	$cc= 0;
	file_put_contents($DIR_LOG . $sql_log, '');
	$usql = '';
	for($i=1; $i<sizeof($reg);$i++){
		$registro = $reg[$i];
		$registro = str_replace("\'", "'", $registro);
		$col = explode('#', $registro ) ;		
		print_r($col);
		if(str_contains( $col[7] , 'actualizar_registro') ){
			$str = substr(trim(strtoupper($col[8])),0, 11);
			//si es un  insert..........
			if( $str == 'INSERT INTO'){
				$p = $i;
				$p++;
				$tcol = explode('#', $reg[$p] ) ;
				if($tcol[6]=='***ACTUALIZA_REGISTRO***'){
					
					$str_sql = explode('actualizar_registro.php#', $registro)[1];
					
					$xsqlu = explode('unico = ', trim($tcol[8]) );
					$xsql = explode(') values (', trim($str_sql) );
					
					$bsql  = explode(' ',trim($tcol[8]));
					
					$sql = 'SELECT ' . $bsql[3] . ' FROM ' . $bsql[1] . ' WHERE ' . $bsql[3] .' ' . $bsql[4] ."@|@";
					$sql.= $xsql[0].',UNICO, '. $bsql[3] .') values (';
					$sql.= substr($xsql[1], 0, -1) .',';
					/*
					if($bsql[4]=="='001543'"){
						echo "\n\n";
						echo $xsql[1];
						echo "\n\n";
						echo $registro;
						echo "\n\n";
						die();
						
					}
					*/
					
					$sql.= $xsqlu[1] .','. str_replace('=', '', $bsql[4]) .")\n";					
					file_put_contents($DIR_LOG . $sql_log, str_replace('\\', '', $sql), FILE_APPEND);
					
					$sql = 'SELECT ' . $bsql[3] . ' FROM ' . $bsql[1] . ' WHERE ' . $bsql[3] .' ' . $bsql[4];
					$query->sql = $sql;
					$query->ejecuta_query();
					if($query->erro_msg!=''){
						echo "\nError ejecutando\n";
						echo $query->sql;
						print_r($query);
						echo "\n\n";
						die();
					}
					if(!$query->next_record()){
						$sql = $xsql[0].',UNICO, '. $bsql[3] .') values (';
						$sql.= substr($xsql[1], 0, -1) .',';
						$sql.= $xsqlu[1] .','. str_replace('=', '', $bsql[4]) .")";
						
						$query->sql = $sql;
						$query->ejecuta_query();
						
						if($query->erro_msg!=''){
							echo "\nError ejecutando\n";
							echo $query->sql;
							print_r($query);
							echo "\n\n $sql_log \n";
							die();
						}						
					}
				}				
			}			
			else{
				$str_sql = explode('actualizar_registro.php#', $registro)[1];
				$sql = trim($str_sql);
				if($usql!=$sql){
					$usql=$sql;
					$sql.="@|@\n";
					file_put_contents($DIR_LOG . $sql_log, str_replace('\\', '', $sql), FILE_APPEND);
					
					$query->sql = $usql;
					$query->ejecuta_query();
					
				}
			}
		}
	}	
}

    function str_contains (string $haystack, string $needle)
    {
        return empty($needle) || strpos($haystack, $needle) !== false;
    }

?>