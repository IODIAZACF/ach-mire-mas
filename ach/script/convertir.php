<?php
$query = new sql();
$query->sql = "UPDATE X_M_DOCUMENTOS SET DOCUMENTO='". getvar('DOCUMENTO')  ."' WHERE UNICO='". $unico."'";
$query->ejecuta_query();

?>