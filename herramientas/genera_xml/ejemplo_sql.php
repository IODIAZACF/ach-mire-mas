<?php
 define('Server_Path','../../');
  include(Server_Path . 'herramientas/utiles/comun.php');
  encabezado('EJEMPLO GENERA XML');
?>

<body>
<script type='text/javascript' src='./javascript/genera_xml.js'></script>
<table border="1">
<tr>
<td id="contenedor">PRUEBA</td>
</tr>
<tr>
<td><input id="buscar" type="text"   value="DAZA"></td>
<td><input id="boton"  type="button" value="BUSCAR" onclick="mostrar('contenedor','EJEMPLO','NOMBRES,APELLIDOS','NOMBRES,APELLIDOS',buscar.value)"></td>
</tr>
<tr>
<td>Este es un ejemplo de Busqueda de <br> Manera DINAMICA con AJAX sin Recargar la Pagina..!</td>
</tr>
</table>

<p>
    <a href="http://validator.w3.org/check?uri=referer"><img
        src="http://www.w3.org/Icons/valid-html401"
        alt="Valid HTML 4.01 Transitional" height="31" width="88"></a>
</p>

</body>

</html>