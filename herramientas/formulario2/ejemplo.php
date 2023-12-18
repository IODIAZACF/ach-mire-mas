<?php

define('Server_Path','../../');
include_once (Server_Path . 'herramientas/utiles/comun.php');

echo '<body onload="ocultaCarga();">' . "\n";

$origen = getvar("origen",'formularios/f_usuarios');

cargando();

encabezado('EJEMPLO DE FORMULARIO DESDE INI Y BASE DE DATOS');

javascript('auto_tabla,formulario2,utiles,forma');

echo <<<EOT
<div id="flotante" style="border:solid 3px #4FE21D;position: absolute; top:100px; left:100px; width:300px; height:400px"></div>

<script type="text/javascript">

oFormulario = new formulario2('{$origen}');
oFormulario.nombre      = 'oFormulario';
//oFormulario.debug       = true;
oFormulario.funcion     = teclaForm;
oFormulario.padre       = 'flotante';
oFormulario.inicializa();
oFormulario.mostrar();

function teclaForm(obj, tecla)
{
  switch (tecla)
  {
    case 13:
      break;

    case 27:
      break;

    case 123: // -- F12 = guardar
      break;
  }

}

</script>

</body>


EOT;


?>