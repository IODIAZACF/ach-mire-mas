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
p.url= server_path + 'herramientas/reporte_xml/reporte_xml.php';
p.origin='reportes/r_recipe';
p.setParam('ID_M_RECIPES', '00115');
p.print();

</script>


</body>


EOT;


?>

