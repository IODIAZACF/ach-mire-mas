<?php

define('Server_Path','../../');
include_once (Server_Path . 'herramientas/utiles/comun.php');

encabezado('EJEMPLO DE CLAVE');

echo '<body onload="ocultaCarga();">' . "\n";
cargando();
javascript('clave,utiles');

echo <<<EOT

<script type="text/javascript">

xclave = new clave('xclave');

function borrar(confirmado)
{
  if (!confirmado)
  {
    xclave.hide();
  }
  else
  {
    alert('registro borrado');
    xclave.hide();
  }

}


</script>
<button onclick="pideClave('borrar')">Borrar registro</button>

</body>


EOT;


?>