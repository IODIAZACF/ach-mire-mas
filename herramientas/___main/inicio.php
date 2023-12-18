<?php


include ("../config.php");

include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');

encabezado('Inicio');

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";

cargando();

javascript('jquery,utiles');


?>
<script type="text/javascript">



</script>