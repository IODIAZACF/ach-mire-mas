<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha 	= date("d/m/Y");
$ventana 	= getvar('ventana','modulo');
$id 		= getvar('id');
$Server_Path= Server_Path;

$id_m_usuario    = getsession('M_USUARIOS_ID_M_USUARIO');

$my_ini 	= new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora,jquery');

echo <<<EOT

{$modulo->inicio}

<table class="contenido_modulo" border="0" cellspacing="0">
	<tr>
		<td id="GRUPO1">
			<div class="etiqueta" id="contenido" style="padding: 0px; height: 530px;">
				<table border="0" id="panel-instaladores" >
					<tr>
						<td class="rotulo" width="1120px">Descripción</td><td class="rotulo" width="150px">Acción</td>
					</tr>

					<tr>
						<td class="valor">Instala utilidad en equipo local para realizar impresiones y previsualizaciones desde el sistema, Instala aplicación minibrowser</td>
						<td class="valor"><a href='{$Server_Path}main/instaladorO24.exe'  >Instalar</a></td>
					</tr>

					<tr>
						<td class="valor">Instala aplicacion para utilizar Web cam desde el sistema y capturar imagenes</td>
						<td class="valor"><a href='{$Server_Path}main/instaladorvideo.exe'>Instalar</a></td>
					</tr>

					<tr>
						<td class="valor">Instala Parche para deshabilitar el Debug de Internet explorer al presionar la tecla F12</td>
						<td class="valor"><a href='{$Server_Path}main/f12_disable.reg'    >Instalar</a></td>
					</tr>

					<tr>
						<td class="valor">Lee la direccion Mac de la Tarjeta de Red y la envia al Servidor</td>
						<td class="valor"><a href='{$Server_Path}main/red.exe'            >Instalar</a></td>
					</tr>
					<tr>
						<td class="valor">Instala utilidad local para revisar CCTV desde el sistema</td>
						<td class="valor"><a href='{$Server_Path}main/instaladorvideo_cctv.exe'            >Instalar</a></td>
					</tr>
					<tr>
						<td class="valor">Instala utilidad local subir multiples archivos al servidor</td>
						<td class="valor"><a href='../main/upload24.exe'            >Instalar</a></td>
					</tr>
					<tr>
						<td class="valor">Instala utilidad local Scannear archivos al servidor</td>
						<td class="valor"><a href="../main/scanner24.exe">Instalar</a></td>
					</tr>
					<tr>
						<td class="valor" colspan="2" height="360px"></td>
					</tr>

				</table>


				<table border="0" id="panel-query" style="display: none;" >
					<tr>
						<td class="rotulo" width="1265px">Query</td>
					</tr>
					<tr>
						<td class="rotulo" width="1265px" height="475px" valign="top" >
							<textarea id="query" name="query" rows=25 cols=177 wrap="off"></textarea>
							<hr>
							<input type="button" value="Ejecutar"  onclick="ejecutar()">
							<input type="button" value="Limpiar"   onclick="limpiar()">
						</td>
					</tr>
				</table>

				<table border="0" id="panel-resultado" style="display: none;">
					<tr>
						<td class="rotulo" width="1265px">Resultado</td>
					</tr>
					<tr>
						<td id="resultado" class="rotulo" width="1267px" height="475px" style="overflow-y: auto; overflow-x: auto; display: block; margin: auto; padding: 1px;">
						
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>

{$modulo->fin}

<script type="text/javascript">

function ejecutar()
{

	var obj  = document.getElementById('query');
	var obj2 = document.getElementById('mensaje');
	$("#resultado").html("");
	
	var url   = server_path + "instaladores/procesar.php";
    var param = "query=" + obj.value;
    //prompt('',url+'?'+param);
    var html = enviar(url, param, 'POST');
    
	$("#resultado").html(html);
	ver_resultado();
}


function limpiar()
{
	var obj  = document.getElementById('query');
	var obj2 = document.getElementById('mensaje');
    obj.value='';
    obj2.innerHTML = "";

}

function ver_instaladores(){
	$("#panel-instaladores").css("display","block");
	$("#panel-query").css("display","none");
	$("#panel-resultado").css("display","none");
}

function ver_query(){
	$("#panel-instaladores").css("display","none");
	$("#panel-query").css("display","block");
	$("#panel-resultado").css("display","none");
}

function ver_resultado(){
	$("#panel-instaladores").css("display","none");
	$("#panel-query").css("display","none");
	$("#panel-resultado").css("display","block");
}

function Salir()
{
   location.href = server_path + 'main/inicio.php';
}

function iniciar()
{
     addEvent(ESCAPE, 	"click",   	function() { Salir() } )
	 addEvent(F6, 	"click",   		function() { ver_instaladores() } )
	 addEvent(F7, 	"click",   		function() { ver_query() } )
	 addEvent(F8, 	"click",   		function() { ver_resultado() } )
     return true;
}

function inicio()
{
}

var resp = iniciar();

if(!resp)
{
	Salir();
}
else
{
	inicio();
}

</script>

</body>
</html>

EOT;

?>