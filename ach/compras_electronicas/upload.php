<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

@mkdir(Server_Path . "../tmp",7777);


echo '<pre>Seleccione y Cargue el archivo';

echo '<form name="upload" enctype="multipart/form-data" method="post" action="procesar.php">' . "\n";
echo ' Archivo XML :  <input name="archivo" type="file" size="70">' . "\n";
echo ' Archivo PDF :  <input name="archivo_pdf" type="file" size="70">' . "\n";
echo '<hr>';
echo '<p>' . "\n";
//echo '  <input type="submit" name="enviar" value="Enviar Archivo" onclick="this.form.elements[\'enviar\'].disabled=true;document.getElementById(\'proceso\').style.visibility = \'visible\';submit();">' . "\n";
echo '  <input type="submit" name="enviar" value="Enviar Archivo" onclick="document.getElementById(\'proceso\').style.visibility = \'visible\';submit();">' . "\n";
echo '</p>' . "\n";
echo '  <input name="ID_D_CLIENTES" id="ID_D_CLIENTES" type="hidden" value="'. getvar('ID_D_CLIENTES') .'">'  . "\n";
echo '  <div width="100%" id="proceso" style="margin-top:30px;visibility:hidden" align="center" ><img src="'.Server_Path.'imagenes/utiles/loading.gif " height="32" width="32" /img></br><p>Procesando archivo</p></div>';
echo '</form>' . "\n";

?>

