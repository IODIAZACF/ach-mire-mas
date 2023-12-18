<?php
$archivo ="../../tmp/". $_REQUEST['ID_M_KOBO_FORMULARIOS'] . ".csv";
header("Pragma: public"); // required
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false); // required for certain browsers
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".basename($archivo)."\";" );
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($archivo));
readfile("$archivo");
exit();

?>