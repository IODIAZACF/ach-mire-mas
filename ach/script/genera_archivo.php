<?php
$xusuario_id =  $query[0]->Record['ID_M_USUARIO'];
$xtabla = $query[0]->Record['TABLA'];
$xid    = $query[0]->Record["ID_M_ARCHIVOS"];

$xsql   = "SELECT * FROM $xtabla WHERE ID_M_ARCHIVOS='" . $xid . "'";
$query[0]->sql = $xsql;
$query[0]->ejecuta_query();
$fp = fopen("../../../baches/$xid.O24", "w+");

$registro = '';
foreach($query[0]->reg_campos as $campo => $valor)
{
    if(strlen($registro)) $registro.=';';
    $registro .= $campo ;
}
$registro .= "\n";
fwrite($fp, $registro);

while($query[0]->next_record())
{
	$registro = '';
	foreach($query[0]->Record as $campo => $valor)
    {
        if(strlen($registro)) $registro.=';';
        $registro .= '"'. $valor .'"' ;
    }
    $registro .= "\n";
    fwrite($fp, $registro);
}
fclose($fp);
$xsql   = "UPDATE M_ARCHIVOS SET NOMBRES = '$xid' WHERE ID_M_ARCHIVOS='" . $xid . "'";
$query[0]->sql = $xsql;
$query[0]->ejecuta_query();
/*    $request_url = 'http://localhost/recibe.php';
    $post_params['name'] = urlencode('Test User');
    $post_params['file'] = '@./conversacion.txt';
    $post_params['submit'] = urlencode('submit');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $request_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
    $result = curl_exec($ch);
    curl_close($ch);
    echo $result;
*/
?>