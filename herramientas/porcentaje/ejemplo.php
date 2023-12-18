<?php

define('Server_Path','../../');
include_once (Server_Path . 'herramientas/utiles/comun.php');


encabezado('EJEMPLO DE PORCENTAJE');

echo '<body onload="ocultaCarga();">' . "\n";
cargando();
javascript('utiles,porcentaje');

echo <<<EOT

<div id="CONTENIDO">
</div>

<script type="text/javascript">

var porc=new porcentaje();
porc.ancho=100;
porc.alto=20;
porc.padre='CONTENIDO';
porc.inicializa();
porc.setValue(0);

</script>

<button onclick="porc.setValue(75);">Prueba</button>

</body>


EOT;


?>
