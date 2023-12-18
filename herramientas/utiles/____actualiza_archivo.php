<?php

$arch=$_REQUEST['nombre'];
$ruta=$_REQUEST['ruta'];
$truta = explode('/',$ruta);

$iruta =  '../..' ;
for($i=0;$i<sizeof($truta);$i++)
{
	$iruta.='/'.$truta[$i];
    @mkdir($iruta, 07777);

}

$fn=basename($arch);
$fp='fotos/'.str_replace($fn,'',$arch);
$fp=str_replace('\\\\','/',$fp);

//@mkdir($fp, 0x777, true);

$fp = '../../' . $ruta . '/';

if (is_uploaded_file($_FILES['imagen']['tmp_name']))
{
  move_uploaded_file($_FILES['imagen']['tmp_name'], $fp.$fn);
  echo 'OK';
}
else
{
  echo 'ERROR';
}
?>