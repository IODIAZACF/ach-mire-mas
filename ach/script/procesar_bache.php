<?php
echo "<pre>";
$query[0]->sql = "SELECT * FROM M_ARCHIVOS WHERE ID_M_ARCHIVOS ='". getvar('xbusca') . "'";
$query[0]->ejecuta_query();
$query[0]->next_record();
$xid = $query[0]->Record['NOMBRES'];
$gestor  = fopen(Server_Path . "../recepciones/$xid", "rb");
$campos = explode(';', fgets ($gestor));
$tipo   = explode(';', fgets ($gestor));
while (!feof($gestor))
{
	$linea  = trim(substr(fgets ($gestor),1));
	$linea  = substr($linea,0, strlen($linea)-1);
	$reg    = explode('";"', $linea);
}
?>