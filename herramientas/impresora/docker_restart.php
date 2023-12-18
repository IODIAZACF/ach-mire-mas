<?php

$salida = shell_exec('sudo docker stop wine-server');
echo "<pre>$salida</pre>";

sleep(2);

$salida = shell_exec('sudo docker start wine-server');
echo "<pre>$salida</pre>";


?>

