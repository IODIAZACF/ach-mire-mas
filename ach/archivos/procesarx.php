<?php

//print_r($_REQUEST);
include('../config.php');
$_SESSION['db'] = $_REQUEST['db'];
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
$xfolde = explode('/',$_REQUEST['carpeta']);

for($i=0;$i<sizeof($xfolde);$i++)
{
   if(strlen($tmpf)) $tmpf.='/';
   $tmpf.= $xfolde[$i];
   mkdir(Server_Path . $tmpf);
}
$cant = sizeof($_FILES);

$query = new sql(0);
$query->sql = "UPDATE D_ARCHIVOS SET FECHA_DIGITAL='" . date("Y-m-d") . "', CAMPO1='". $_REQUEST['ID_M_USUARIOS'] ."' WHERE ID_D_ARCHIVOS='". $_REQUEST['ID_D_ARCHIVOS'] ."'";
$query->ejecuta_query();

if($query->Error)
{
    //echo "Error al actualizar el archivo...\n";
    //echo $query->regi['ERROR'];
    echo "ERROR [" . $query->regi['ERROR'] . "]";
    die();
}
$nfile = 0;
$archivo  = glob(Server_Path . $tmpf . "/". $_REQUEST['nombre_archivo'] .'*');
if(is_array($archivo))
{
	$nfile = sizeof($archivo);
}


foreach($_FILES as $nombre => $archivo)
{
    $nfile=date("YmdHis");
    if($cant==1)
	{
        //$nfile++;
        $recibido                = $archivo["tmp_name"];
	    $archivo_destino         = Server_Path . $tmpf . "/". $_REQUEST['nombre_archivo'] . "_$nfile.jpg";
	    move_uploaded_file($recibido, $archivo_destino);
	}else
	{
        $sec = str_replace('img_','', $nombre);
        //$nfile++;
        $recibido           = $archivo["tmp_name"];
        $archivo_destino    = Server_Path . $tmpf . "/". $_REQUEST['nombre_archivo'] . "_$nfile.jpg";
        move_uploaded_file($recibido, $archivo_destino);
	}
}
echo "OK";

?>
