<?php
  define('Server_Path','../../');
  include(Server_Path . 'herramientas/utiles/comun.php');
  encabezado('EJEMPLO LEYENDA');
?>
<body id="leyenda">

<?php

cargando();

 define("Server_Path", "");
include (Server_Path ."herramientas/leyenda/class/class_leyenda.php");
$data['LEYENDA1']['TECLA'] 	= "F1";
$data['LEYENDA1']['ROTULO']	= "AYUDA";

$data['LEYENDA2']['TECLA'] 	= "F2";
$data['LEYENDA2']['ROTULO']	= "OTRA COSA";

$leyenda = new  class_leyenda();
$leyenda->origen = $data;
$leyenda->estilo = 'leyenda';
$leyenda->armar();

echo $leyenda->contenido_html;
?>

<p>
    <a href="http://validator.w3.org/check?uri=referer"><img
        src="http://www.w3.org/Icons/valid-html401"
        alt="Valid HTML 4.01 Strict" height="31" width="88"></a>
  </p>
</body>
</html>