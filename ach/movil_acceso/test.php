<?php
include('../config.php');

    $img_logo = Server_Path .'imagenes/m_colegios/agenda.jpg';
	$imgData = base64_encode(file_get_contents($img_logo));
	$src = 'data: '.mime_content_type($img_logo).';base64,'.$imgData;
    echo '<img src="'.$src.'">';
?>
