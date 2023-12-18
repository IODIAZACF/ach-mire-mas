<?php
$sistema = dirname($_SERVER['SCRIPT_NAME']);
$fake_script = $_SERVER['REDIRECT_URL'] ."\n";
$real_script = trim(str_replace($sistema,'', $fake_script));

include('./config.php');
include(RUTA_HERRAMIENTAS .$real_script);
?>