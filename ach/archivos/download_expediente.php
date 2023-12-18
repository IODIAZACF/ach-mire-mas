<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$CARPETA	= $_REQUEST['CARPETA'];
$xruta 		=  '/opt/lampp/htdocs/ach/'. $CARPETA ;

$archivo_original = '/opt/tmp/'. basename($CARPETA) .'.tar.gz';
$resp = exec('rm -rf '. $archivo_original);
$comando = 'tar -czvf /opt/tmp/'. basename($CARPETA) .'.tar.gz -C '. $xruta .'/ .';
$resp = exec($comando);
$resp = exec('chmod 0777 '. $archivo_original);

$mime =  mime_content_type($archivo_original);
$size = filesize($archivo_original);
$fp   = fopen($archivo_original, "rb");
if (!($mime && $size && $fp)) {
  // Error.
  return;
}

header("Content-type: " . $mime);
header("Content-Length: " . $size);
// NOTE: Possible header injection via $basename
header("Content-Disposition: attachment; filename=" . basename($archivo_original));
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
fpassthru($fp);


?>