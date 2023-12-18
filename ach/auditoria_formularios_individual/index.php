<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha    = date("d/m/Y");
$ventana   = getvar('ventana','modulo');
$id        = getvar('id');

$id_m_usuario  = getsession('M_USUARIOS_ID_M_USUARIO');

$my_ini        = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora,jquery,forma_simple');

echo <<<EOT

{$modulo->inicio}
<table width="100%">
	<tr valign="top">
		<td id="GRUPO1" width="60%"></td>
		<td id="GRUPO2" width="40%"></td>
	</tr>
</table>

{$modulo->fin}

<script type="text/javascript">

var fecha         			= '{$xfecha}';

$.getScript( "./index.js?" + makeid(), function( data, textStatus, jqxhr ) { });

</script>

EOT;

?>

</body>
</html>

