<? 
$imagenes =  glob('../../../imagenes/personalizados/' . $_REQUEST['carpeta'] . '/*.*');
for($i=0;$i<sizeof($imagenes);$i++){	
	$info = pathinfo($imagenes[$i]);
	//$tmp['url'] = $imagenes[$i];
	$tmp['url'] = '../../imagenes/personalizados/' . $_REQUEST['carpeta'] . '/' . basename($imagenes[$i]);
	$tmp['caption'] =  $info['filename'];
	$lista[] = $tmp;
}
echo json_encode($lista);
?> 