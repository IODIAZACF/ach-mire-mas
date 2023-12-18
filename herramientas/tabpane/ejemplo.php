<?php
   define('Server_Path','../../');
  include(Server_Path . 'herramientas/utiles/comun.php');
  encabezado('EJEMPLO TABPANEL');
?>

<body>
<?php
 cargando();
?>
<script type="text/javascript">
	document.write("<"+"script type='text/javascript' src='../utiles/genera_script.php?modulo=tabpane,jgrid'><\/script\>");
</script>

<?php
define("Server_Path", "");
include (Server_Path ."herramientas/tabpane/class/class_tabpane.php");


$data['PANEL1']['TITULO']    = "PANEL 1";
$data['PANEL1']['CONTENIDO'] = "CONTENIDO 1";

$data['PANEL2']['TITULO']    = "TITULO PANEL 2";
$data['PANEL2']['CONTENIDO'] = "CONTENIDO PANEL 2";

$data['PANEL3']['TITULO']    = "PANEL 3";
$data['PANEL3']['CONTENIDO'] = "CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>CONTENIDO PANEL 3<br>";

$data['PANEL4']['TITULO']    = "PANEL 4";
$data['PANEL4']['CONTENIDO'] = "CONTENIDO PANEL 4";


$acordion = new class_tabpane('PANEL1',400, 400);
$acordion->data = $data;

$acordion->armar();

echo $acordion->contenido_html;


echo '<script type="text/javascript">' .$acordion->contenido_js . '</script>';


?>
<p>
    <a href="http://validator.w3.org/check?uri=referer"><img
        src="http://www.w3.org/Icons/valid-html401"
        alt="Valid HTML 4.01 Transitional" height="31" width="88" border="no"></a>
  </p>


</body>
</html>