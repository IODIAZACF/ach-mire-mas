<?php
$archivo_original = '../' .$_GET['imagen'];

$mime =  getimagesize($archivo_original);
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