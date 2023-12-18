<?php
define('Server_Path','../../');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$msg = stripslashes (getvar("msg"));
$url = getvar("url");
$linea = getvar("linea");
$id_usuario = getsession('M_USUARIOS_ID_M_USUARIO');

function reemplazar($texto)
{
	$texto = str_replace("'", "", $texto);
    $texto = str_replace("\\", "", $texto);
    return $texto;
}

$error = "Error en la linea : " . getvar("linea") . ", ". reemplazar(getvar("msg"));

$query = new sql();
$query->sql = "INSERT INTO D_ERRORES (IP, URL,ERRORES,ESTATUS,ID_M_USUARIOS2) VALUES ('" . $_SERVER['REMOTE_ADDR'] . "','". $url ."', '". $error ."', 'PEN', '". $id_usuario ."')";
$query->ejecuta_query();
echo '<body>' . "\n";
cargando();

//javascript('auto_tabla,utiles,formulario2,forma,submodal,impresora');
echo <<<EOT

<title>Dialogo de Error</title>
<script>
function DaFoco() {
	setTimeout("self.focus()",100);
}

</script>
<style>
body {background: white; color: black; border: 10 solid navy; font-family: tahoma, arial, helvitica; font-size: 12px; margin: 0;}
p {font-family: tahoma, arial, helvitica; font-size: 12px; margin-left: 10px; margin-right: 10px;}
h1        {font-family: arial black; font-style: italic; margin-bottom: -15; margin-left: 10; color:red}
button {margin: 0; border: 1 solid #dddddd; background: #eeeeee; color: black; font-family: tahoma, arial; width: 100}
a {color: navy;}
a:hover {color: blue;}
</style>
<body scroll="no" onBlur="DaFoco();";>
<h1>ALERTA</h1>
<p>A ocurrido un error en este modulo<br><strong id="url">{$url}</strong><br>el cual pudiera impedir que trabaje usted correctamente.</p>
<p style="margin-bottom: 5;">Se ha notificado automaticamente al Departamento de sistemas</p>
<table style="width: 100%;" cellspacing=0 cellpadding=10><tr><td>
</td><td align="RIGHT"><button onclick="window.close()" onmouseover="this.style.borderColor='black'" onmouseout="this.style.borderColor='#dddddd'">Ok</button>
</td></tr></table>
<div id="infoArea" style="display: block;">
<div id="info" style="background: #eeeeee; margin: 10; margin-bottom: 0; border: 1 solid #dddddd;">
<table>
<tr><td><p>Mensaje:</p></td><td><p id="msg">{$msg}</p></td></tr>
<tr><td><p>Linea:</p></td><td><p id="ln">{$linea}</p></td></tr>
</table>
</div>
</div>
</body>


EOT;

?>