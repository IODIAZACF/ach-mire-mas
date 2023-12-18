<?php

$url="http://localhost:9997/?".$_SERVER["QUERY_STRING"];

$txt = file_get_contents($url);
$json = json_decode($txt, true);

$title=$_REQUEST["title"];

if ($json["pdf"] && file_exists($json["pdf"])) {
	$uid = uniqid();
	$file = $json["pdf"];
	$out  = "pdf/$uid.pdf";
	if ($title) $out  = "pdf/$title.$uid.pdf";

	//$cmd = "gs -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/printer -sOutputFile='./$out' $file";

	//$res = @shell_exec($cmd);
	if (file_exists("./$out")) {
		unlink($json["pdf"]);
	}
	else rename($file, $out);
    $json["pdf"] = $out;
}

echo json_encode($json);

?>
