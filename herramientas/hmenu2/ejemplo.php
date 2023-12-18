<?php

define('Server_Path','../../');
include_once (Server_Path . 'herramientas/utiles/comun.php');

echo '<body onload="ocultaCarga();">' . "\n";

cargando();

encabezado('EJEMPLO DE MENU');

javascript('utiles,submodal,hmenu2');

echo <<<EOT

<style type="text/css">

.menu_fondo
{
  border: 1px solid #000000;
  background-color: #EEEEEE;
  padding: 2px 2px 2px 2px;
}

.menu_superior
{
  background-color: #EEEEEE;
  padding: 2px 6px 2px 6px;
}

.menu_inactivo
{
  background-color: #EEEEEE;
  color: #000000;
  padding: 2px 2px 2px 2px;
  border: 2px;
}

.menu_activo
{
  background-color: #D0D0D0;
  color: #0000DD;
  padding: 2px 2px 2px 2px;
  border: 1px solid #000000;
}

</style>

<script type="text/javascript">

var m = new hmenu();
m.name = 'm';
m.left = 100;
m.top = 100;
m.url = server_path + 'herramientas/hmenu2/genera_menu.php';

m.init();
m.show();

</script>
</body>


EOT;


?>