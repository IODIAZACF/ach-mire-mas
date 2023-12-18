<?php
//require_once __DIR__ . '/vendor/autoload.php';
// Parse PDF file and build necessary objects.
//use



$parser = new \Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('4M.pdf');

$text = $pdf->getText();
echo $text;

