<?php
include('../config.php');
$Server_Path ='../';
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
javascript('utiles');

$IDX 		= $_REQUEST['IDX'];
$NOMBRES	= $_REQUEST['NOMBRES'];
$CARPETA	= $_REQUEST['CARPETA'];
$DB			= $_REQUEST['DB'];


@mkdir(Server_Path . "../tmp",7777);

echo <<<EOT

Seleccione los Archivos
<hr>
<form name="upload" enctype="multipart/form-data" method="post" action="procesar_upload.php">
<table>
	<tr>
		<td>Archivo<b></b>:</td>
		<td><input name="archivo" type="file" size="40"></td>
	</tr>
</table>
<hr>
<input type="submit" name="enviar" id="enviar" value="Enviar Archivo">
<div width="100%" id="proceso" align="center" ><img src="{$Server_Path}imagenes/utiles/loading.gif " height="32" width="32" /img></br><p>Procesando archivo</p></div>
<input name="IDX" type="hidden" value="{$IDX}"><br>
<input name="NOMBRES" type="hidden" value="{$NOMBRES}"><br>
<input name="CARPETA" type="hidden" value="{$CARPETA}"><br>
<input name="DB" type="hidden" value="{$DB}"><br>

</form>

<script>

$(document).ready(function(){
    $("#proceso").hide();
});

$('form').submit(function(){
    $('#enviar').prop('disabled', true);
	$("#proceso").show();
	
});

</script>

EOT;

?>