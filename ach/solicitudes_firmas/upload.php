<?php
include('../config.php');
$Server_Path ='../';
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
javascript('utiles');


$ID_M_SOLICITUDES =$_REQUEST['ID_M_SOLICITUDES'];

@mkdir(Server_Path . "../tmp",7777);

echo <<<EOT

Seleccione el Archivo
<hr>
<form name="upload" enctype="multipart/form-data" method="post" action="procesar.php">
<table>
	<tr>
		<td>Archivo</b>:</td>
		<td><input name="ARCHIVO" type="file" size="40"></td>
		<td><input name="ID_M_SOLICITUDES" type="hidden" value="{$ID_M_SOLICITUDES}"></td>
	</tr>
</table>
<hr>
<input type="submit" name="enviar" id="enviar" value="Enviar Archivo">
<div width="100%" id="proceso" align="center" ><img src="{$Server_Path}imagenes/utiles/loading.gif " height="32" width="32" /img></br><p>Procesando archivo</p></div>
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