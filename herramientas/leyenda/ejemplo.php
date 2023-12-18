<?php
 define('Server_Path','../../');
  include(Server_Path . 'herramientas/utiles/comun.php');
  encabezado('EJEMPLO LEYENDA');
?>
<body id="leyenda">
<?php
 cargando();
?>
<a href="ejemplo_ini.php">     Generador de Leyenda desde archivo INI ->EJEMPLO_INI.PHP     </a> <br>
<a href="ejemplo_arreglo.php"> Generador de Leyenda desde Arreglo     ->EJEMPLO_ARREGLO.PHP </a> <br>

<p>
    <a href="http://validator.w3.org/check?uri=referer"><img
        src="http://www.w3.org/Icons/valid-html401"
        alt="Valid HTML 4.01 Strict" height="31" width="88" border="no"></a>
  </p>

</body>
</html>