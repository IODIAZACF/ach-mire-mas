<?php
error_reporting(E_ALL);

include('../config.php');
$excepciones[]= '/clarbusiness/archivos/procesar_upload.php';
$_SESSION['db'] = $_REQUEST['DB'];
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$ID_M_ALUMNOS	 	= $_REQUEST['IDX'];
$NOMBRES	 		= $_REQUEST['NOMBRES'];
$CARPETA	 		= $_REQUEST['CARPETA'];


@mkdir(Server_Path . $CARPETA ,0777,true);
@chmod(Server_Path . $CARPETA ,0777);

$recibido = $_FILES["archivo"]["tmp_name"];
$nombre   = $_FILES["archivo"]["name"];
$tam   	  = ceil($_FILES["archivo"]["size"]/1024);

echo "<pre>";
print_r($_FILES);
$ext	  = strtoupper(substr($_FILES["archivo"]["name"], -3));

function mkPDF($comprime, $destino, $force=false)
{
	$image_list = join(' ' , $comprime);
	$test_file = str_replace(' ','\ ',$destino);	
	$xtest_file = str_replace(basename($destino), 'tmp_'.  basename($destino), $destino);
	
	$comando ='convert '. $image_list  .' -compress jpeg -quality 60 ' . $test_file;
	$resp = system($comando);
	$tsize = ceil(filesize($xtest_file)/1024);
	if($tsize>1024)
	{
		unlink($xtest_file);
		$result = array_pop($comprime);
		$image_list = join(' ' , $comprime);
		$comando ='convert '. $image_list  .' -compress jpeg -quality 60 ' . $test_file;
		$resp = system($comando);
		return $result;
	}
	else{
		if($force){
			rename($xtest_file, $destino);
			return "";
		}else
		{
			unlink($xtest_file);
			return $comprime;			
		}
	}
}
if($ext=='PDF')
{
	$nombre_archivo = $NOMBRES;
    $xruta = '/opt/lampp/htdocs/clarbusiness/'. $CARPETA ;
	
	$pdf_file = $xruta . '/original_'. $nombre_archivo . '.pdf';
	$jpg_file = $xruta . '/'. $nombre_archivo ;

	move_uploaded_file( $recibido , $pdf_file);	
    
	$comando = 'convert -density 150 -antialias "'. $pdf_file .'" -resize 1024x -quality 100 -background white -alpha remove "'. $jpg_file .'-%03d.jpg"';
	$resp = system($comando);
	if($tam>1024)
	{
		$archivo  = glob($jpg_file . '*.jpg');
		$seguir = true;
		$im = 0;
		print_r($archivo);
		$sec_file = 1;
		
		while($seguir)
		{
			$gsize = 0;
			for($i=$im;$i<sizeof($archivo);$i++)
			{
				$xsize = ceil(filesize($archivo[$i])/1024);
				
				if($gsize>1024) 
				{
					$image_list = join(' ' , $comprime);
					$test_file = $xruta . '/tmp_'. str_replace(' ','\ ',$nombre_archivo) . '.pdf';
					$xtest_file = $xruta . '/tmp_'. $nombre_archivo . '.pdf';
					$comando ='convert '. $image_list  .' -compress jpeg -quality 60 ' . $test_file;
					$resp = system($comando);
					$tsize = ceil(filesize($xtest_file)/1024);
					unlink($xtest_file);
					if($tsize>1024)
					{
						echo "Part $sec_file <br>";
						echo "$tsize --- Pos: $im<br>";
						//print_r($comprime);
						array_pop($comprime);
						$im--;	
						echo "Pos: $im<br>";
						print_r($comprime);
						
						$image_list = join(' ' , $comprime);
						$test_file = $xruta . '/tmp_'. str_replace(' ','\ ',$nombre_archivo) . '_'. $sec_file. '.pdf';
						$comando ='convert '. $image_list  .' -compress jpeg -quality 60 ' . $test_file;
						$resp = system($comando);
						$sec_file++;
						unset($comprime);
						echo "<hr>";
						$gsize = 0;
						break;						
						
					}else{
						$gsize+=$xsize;
						$comprime[]= str_replace(' ','\ ',$archivo[$i]);
						$im++;						
					}
				}
				else{
					$gsize+=$xsize;
					$comprime[]= str_replace(' ','\ ',$archivo[$i]);
					$im++;				
				}
			}
			if(is_array($comprime))
			{
				echo "Resto $sec_file <br>";
				print_r($comprime);
				$image_list = join(' ' , $comprime);
				$test_file = $xruta . '/tmp_'. str_replace(' ','\ ',$nombre_archivo) . '_'. $sec_file. '.pdf';
				$comando ='convert '. $image_list  .' -compress jpeg -quality 60 ' . $test_file;
				$resp = system($comando);
				$sec_file++;
				$seguir=false;
			}
			else{
				
			}
		}
		
		die($jpg_file);
		
	}
	else
	{	//si tiene menos de 1024 lo dejo como esta no separo...
		 copy($pdf_file, $xruta . '/original_'. $nombre_archivo . '.pdf');
	}
	$resp = system($comando);

	$imagenes=@glob($jpg_file .'*.jpg');
	if(is_array($imagenes))
	{
		$cantidad = sizeof($imagenes);
	}else
	{
		$cantidad = 0;
	}
	$cantidad++;

	
	$query = new sql(0);
	$query->sql = "UPDATE D_ARCHIVOS SET CANTIDAD=" . $cantidad . " , FECHA_DIGITAL='" . date("Y-m-d") . "' WHERE ID_D_ARCHIVOS='". $_REQUEST['ID_D_ARCHIVOS'] ."'";
	$query->ejecuta_query();

	if($query->Error)
	{
		echo "ERROR [" . $query->regi['ERROR'] . "]";
		die();
	}
	
}
else
{
	$imagenes=@glob(Server_Path . $CARPETA  .'/'. $NOMBRES .'*.jpg');
	if(is_array($imagenes))
	{
		$cantidad = sizeof($imagenes);
	}else
	{
		$cantidad = 0;
	}
	$cantidad++;

	$query = new sql(0);
	$query->sql = "UPDATE D_ARCHIVOS SET CANTIDAD=" . $cantidad . " , FECHA_DIGITAL='" . date("Y-m-d") . "' WHERE ID_D_ARCHIVOS='". $_REQUEST['ID_D_ARCHIVOS'] ."'";
	$query->ejecuta_query();

	if($query->Error)
	{
		echo "ERROR [" . $query->regi['ERROR'] . "]";
		die();
	}

	$xdate = time();
	$secuencia = md5($xdate . $cantidad);
	$nombre_archivo = $NOMBRES  . '_'. $secuencia;

	$im = new imagick($recibido);
	$im->setImageFormat('jpeg');
	$im->writeImage(Server_Path . $CARPETA . '/'. $nombre_archivo . ".jpg");
	
}

echo "OK";

?>
