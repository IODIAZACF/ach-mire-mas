<?php
$fp = fopen(Server_Path . '/herramientas/jsversion.txt', "w+");
$JSVERSION = md5(date("Y-m-d h:i:s") .  microtime());
fwrite($fp, $JSVERSION );
fclose($fp);
@chmod(Server_Path . '/herramientas/jsversion.txt', 0777);
echo $JSVERSION . "\n";

?>