<?php
include("CNumeroaLetra.php");
$numalet= new CNumeroaletra;
$numalet->setNumero(14414024);
echo $numalet->letra() . "<br>";
?>
<br>
<?
$numalet->setMayusculas(0);
$numalet->setGenero(0);
$numalet->setMoneda("Bolivares");
$numalet->setPrefijo("");
$numalet->setSufijo("");
echo $numalet->letra() . "<br>";

?>