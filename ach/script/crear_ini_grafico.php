<?php
$ID_M_GRAFICOS =  $_REQUEST['xbusca'];

$fp = fopen(Server_Path. "modulo_graficos/formularios/f_" . $ID_M_GRAFICOS . ".ini", "w+");
fwrite($fp, $_REQUEST['c_FORMULARIO_LSS']);
fclose($fp);

?>