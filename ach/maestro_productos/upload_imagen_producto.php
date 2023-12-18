<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$ID_M_PRODUCTOS     = $_REQUEST['ID_M_PRODUCTOS'];
$xruta     			= Server_Path . 'imagenes/productos';

encabezado($_SESSION['CONFIGURACION_NOMBRES']);

if(is_uploaded_file($_FILES['foto']['tmp_name']))
{
    //rdebug($_FILES,'s');
    @mkdir($xruta, 0777, true);    
    $recibido = $_FILES["foto"]["tmp_name"];
    $nombre   = $_FILES["foto"]["name"];
    move_uploaded_file( $recibido , $xruta  . '/' . $ID_M_PRODUCTOS . ".jpg" );
    procesar_img($xruta  . '/' . $ID_M_PRODUCTOS . ".jpg");
	
    $query = new sql();
    $query->sql = "UPDATE M_PRODUCTOS SET FOTO='*' WHERE ID_M_PRODUCTOS='". $ID_M_PRODUCTOS ."'";
    $query->ejecuta_query();
    //rdebug($query,'s');

}
if($_REQUEST['eliminar']!=''){
	unlink($xruta  . '/' . $ID_M_PRODUCTOS . ".jpg");	
    $query = new sql();
    $query->sql = "UPDATE M_PRODUCTOS SET FOTO=NULL WHERE ID_M_PRODUCTOS='". $ID_M_PRODUCTOS ."'";
    $query->ejecuta_query();
}


if(file_exists($xruta  . '/' . $ID_M_PRODUCTOS . ".jpg"))
{
    srand((double)microtime()*1000000);
    $randval = rand();
    $img_productos = '<img src="'. $xruta  . '/' . $ID_M_PRODUCTOS . ".jpg" . '?rnd='. $randval  .'" border="0" width="250px">' . "\n";
}
else
{
    srand((double)microtime()*1000000);
    $randval = rand();
    $img_productos = '<img src="'. $xruta . '/0.jpg?rnd='. $randval  .'" border="1" width="250px">' . "\n";
}


function procesar_img($archivo_original, $ancho = 800, $alto=800, $tn = true)
{
    $xdir           = dirname($archivo_original);
    $img_origen     = imagecreatefromjpeg($archivo_original);
    $imginfo        = getimagesize($archivo_original);

    $alto_imagen  = $imginfo[1];
    $ancho_imagen = $imginfo[0];

    if($alto_imagen <= $alto && $ancho_imagen<=$ancho)
    {
        $alto  = $imginfo[1];
        $ancho = $imginfo[0];
    }
    else
    {
        $rel_ancho = ($ancho_imagen / $ancho);
        $rel_alto  = ($alto_imagen / $alto);


        if ($rel_ancho > $rel_alto)
        {
          $alto  = intval($alto_imagen * $ancho / $ancho_imagen);
        }
        else
        {
          $ancho = intval($ancho_imagen * $alto / $alto_imagen);
        }
    }
    $ppp = 92;

    $im2 = imagecreatetruecolor($ancho,$alto);
    imagecopyresampled ($im2,   $img_origen, 0, 0, 0, 0, $ancho,   $alto,   $imginfo[0], $imginfo[1]);

    imagejpeg($im2, $archivo_original ,$ppp);
	@chmod ($archivo_original, 0777);
    imagedestroy($im2);

    return true;
}


echo <<<EOT

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Upload File</title>
</head>

EOT;

include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
javascript('jquery');

echo <<<EOT

<script>

function preview(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#ipreview').attr('src', e.target.result);
	  $('#ipreview').show();
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}

function EliminarFoto(){
	var resp = confirm('Desea Eliminar la foto de este producto?');
	if(!resp) return;
	location.href = '?rnd={$randval}&ID_M_PRODUCTOS={$ID_M_PRODUCTOS}&eliminar=si';    
}

</script>
<body>

<form name="imagen_producto" id="imagen_producto" action="?ID_M_PRODUCTOS={$ID_M_PRODUCTOS}" method="post" enctype="multipart/form-data">

<table class="" border="1" width="100%">
	<tr>
		<td class="grid_cuadro etiqueta rotulo" width="50%" align="center">
			<b>Foto actual</b>
		</td>
		<td class="grid_cuadro etiqueta rotulo" width="50%" align="center">
			<b>Foto nueva </b>
		</td>
	</tr>
	<tr>
		<td align="center" height="280px">
			{$img_productos}
		</td>
		<td align="center">
			<img style="display:none;" id="ipreview" border="0" src="" border="0" width="250px" /><br />
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="bottom">
			<input type="hidden" value="{$ID_M_PRODUCTOS}" name="ID_M_PRODUCTOS" />
			<input class="rotulo" id="foto" type="file" name="foto" onchange="preview(this)" onfocus="preview(this)" size="30"  />
		</td>
	</tr>
	<tr>
		<td align="center">
				<button type="button" class="grid_boton_activo" onclick="javascript:EliminarFoto();">Eliminar</button>
		</td>
		<td align="center">
			<input class="grid_boton_activo" type="submit" name="Submit" value="Enviar" />
		</td>
	</tr>
</table>
</form>

</body>
</html>


EOT;

?>