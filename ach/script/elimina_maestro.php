<?php
$query = new sql();
$query->sql = "DELETE FROM ".  getvar('tabla') ." WHERE ". getvar('busca') ."='". getvar('xbusca')  ."'";
$query->ejecuta_query();
die('ok');
?>