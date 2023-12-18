<?php
 define('Server_Path','../../');
  include(Server_Path . 'herramientas/utiles/comun.php');
  encabezado('EJEMPLO XML');
 /*
select * from d_p_maestros
inner join p_maestros on (d_p_maestros.idp = p_maestros.id_p_maestros)
where p_maestros.tabla= 'M_CLIENTES' and d_p_maestros.idx = '001-1'

 */

  ?>
<body>
<a href="ejemplo_sql.php">     Generador de XML desde Base de Datos ->EJEMPLO_SQL.PHP     </a> <br>
<a href="ejemplo_arreglo.php"> Generador de XML desde Arreglo       ->EJEMPLO_ARREGLO.PHP </a> <br>
</body>
</html>
