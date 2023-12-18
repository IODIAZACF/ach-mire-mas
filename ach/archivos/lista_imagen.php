<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//header('Content-Type: application/json');
$archivo  = glob('../'.$_GET['archivo'] .'*');
if(is_array($archivo))
{
    $sec=0;
    for($i=0;$i<sizeof($archivo);$i++)
    {
       $nombre=basename($archivo[$i]);
       $tmp['nombre']= $nombre;
       $tmp['id']= $sec;
       $tmp['rutarchivo'] = $archivo[$i];
	   $archivos['archivos'][]=$tmp;
	   unset($tmp);
       $sec++;
	}
    $archivos['total']=$sec;
}
else
{
    die();
    unset($tmp);
}
/*
echo "<pre>";
print_r($archivos);

*/
echo  json_encode($archivos);

?>