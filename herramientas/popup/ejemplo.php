<?php

define('Server_Path','../../');
include_once (Server_Path . 'herramientas/utiles/comun.php');

encabezado('EJEMPLO DE POPUP');

echo '<body onload="ocultaCarga();">' . "\n";
cargando();
javascript('popup,utiles');

echo <<<EOT

<!--button style="top:500;left:400;position:absolute;" name="b" id="b" onclick="p.show(b, 'grupo1');">Elemento de Prueba</button-->
<button style="top:500;left:400;position:absolute;" name="b" id="b" onclick="pmenu.show(b, 'admin');" onkeypress="dale()">Elemento de Prueba</button>

<script type="text/javascript">

var pmenu=new popup('pmenu');
pmenu.position=_popupUp;
pmenu.addItem('admin', 'Lista de Posibles Egresos','f1');
pmenu.addItem('admin', 'Ver trayectoria del paciente','f1');
pmenu.addItem('admin', 'Reporte de camas disponibles','f1');
pmenu.init();

function dale()
{
  pmenu.show(b, 'admin');
}


/*
var p=new popup('p');
p.position=_popupDown;
p.addItem('grupo1','prueba1','location.href=\'http://www.google.com\';');
p.addItem('grupo1','prueba 2','location.href=\'http://www.yahoo.com\';');
p.addItem('grupo1','prueba3','alert(\'HOLAAAAAA\');');
p.addItem('grupo1','llamado de funcion en el script','func(\'este es un saludo\')');
p.init();
*/
function func(s)
{
  alert(s);
}
</script>

</body>


EOT;


?>