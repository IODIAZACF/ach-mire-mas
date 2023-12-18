<?php
/**
 * OEOG Ejemplo de uso de Class para convertir numeros en palabras
 * Probado en/Tested on PHP 5-Apache2-XP
 *
 *
 * @version   $Id: CNumeroaLetra_ejemplo.php,v 1.0.0 2004-10-29 13:20 ortizom Exp $
 * @author    Omar Eduardo Ortiz Garza <ortizom@siicsa.com>
 * @copyright (c) 2004-2005 Omar Eduardo Ortiz Garza
 * @since     Friday, October 29, 2004
 **/

include("CNumeroaLetra.php");
$numalet= new CNumeroaletra;
$numalet->setNumero(987654321098.76);
//imprime numero con los valore por defecto
echo $numalet->letra();
?>
<br>
<?
//cambia a minusculas
$numalet->setMayusculas(0);
//cambia a femenino
$numalet->setGenero(0);
//cambia moneda
$numalet->setMoneda("Pesetas");
//cambia prefijo
$numalet->setPrefijo("--");
//cambia sufijo
$numalet->setSufijo("++");
//imprime numero con los cambios
echo $numalet->letra();

?>