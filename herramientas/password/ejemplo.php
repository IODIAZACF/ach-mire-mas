<?php

define('Server_Path','../../');
include_once (Server_Path . 'herramientas/utiles/comun.php');


encabezado('EJEMPLO DE LOGIN');

echo '<body onload="ocultaCarga();">' . "\n";
cargando();
javascript('utiles,password');


echo <<<EOT

<script type="text/javascript">

/**** Construccion del login ****/

pwd = new password('pwd');

pwd.top = 100;
pwd.left = 100;
pwd.width = 210;
pwd.height = 100;
pwd.url = server_path + 'herramientas/genera_xml/genera_xml.php';
pwd.login = true;
pwd.caption = 'Login,Contraseña';
pwd.title = 'Ingreso de Usuario';

pwd.init();
pwd.show();
pwd.setFocus();

</script>

<p>

<button>Prueba</button>

</body>


EOT;


?>