<?php
define('Server_Path','../../');
include(Server_Path . 'herramientas/utiles/comun.php');
include (Server_Path ."herramientas/tabpane/class/class_tabpane.php");
include (Server_Path ."herramientas/formulario/class/class_formulario.php");


$nombre = getvar('nombre');



header('content-type: text/xml
Expires: Fri, 1 Ene 1980 00:00:00 GMT"); //la pagina expira en fecha pasada
Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT
Cache-Control: no-cache, must-revalidate
Pragma: no-cache
');

          echo '<?xml version="1.0"  encoding="iso-8859-1"?>' . "\n\n";
    echo '<tabla>' . "\n";

    echo '<html>' . "\n";
        echo '<![CDATA[ HTML = EL NOMBRE ES :' . $nombre . ']]>';
    echo '</html>' . "\n";

    echo '<js>' . "\n";
        echo '<![CDATA[<script type="text/javascript">';
         echo 'alert("JAVASCRIPT EL NOMBRE ES='. $nombre. '")';
    echo  '</script>]]>';
    echo '</js>' . "\n";
    echo '</tabla>' . "\n";

?>