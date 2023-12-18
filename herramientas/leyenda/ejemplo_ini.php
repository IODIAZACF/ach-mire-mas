<?php
  define('Server_Path','../../');
  include(Server_Path . 'herramientas/utiles/comun.php');
  encabezado('EJEMPLO LEYENDA INI');
  javascript('utiles');
?>
<body id="leyenda">
<?php
cargando();

define("Server_Path", "");
include (Server_Path ."herramientas/leyenda/class/class_leyenda.php");

$leyenda = new  class_leyenda();
$leyenda->origen = "ejemplo";
$leyenda->estilo = 'leyenda';
$leyenda->armar();
echo $leyenda->contenido_html;
// echo $leyenda->contenido;

?>
<script>
	var x = document.getElementById('LEYENDA1');

</script>

<p>
    <a href="http://validator.w3.org/check?uri=referer"><img
        src="http://www.w3.org/Icons/valid-html401"
        alt="Valid HTML 4.01 Strict" height="31" width="88"></a>
  </p>

</body>
</html>