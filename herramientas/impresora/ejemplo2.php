<?php

define('Server_Path','../../');
include_once (Server_Path . 'herramientas/utiles/comun.php');

encabezado('EJEMPLO DE IMPRESION');

echo '<body onload="ocultaCarga();">' . "\n";
cargando();
javascript('utiles,impresora');


echo <<<EOT

<script type="text/javascript">


var p = new printer();
p.url= server_path + 'herramientas/impresora/impresora.php';
p.origin='reportes/r_marco';
p.setValue('SERVERPATH', server_path);
p.preview();

</script>


</body>


EOT;


?>