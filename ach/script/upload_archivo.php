<?php
$query = new sql();
$query->sql = "SELECT NOMBRES,MONTO,ID_M_USUARIOS,TABLA,NOMBRES FROM M_ARCHIVOS WHERE ID_M_ARCHIVOS='". getvar('xbusca')  ."'";
$query->ejecuta_query();
$query->next_record();

foreach($query->Record as $campo => $valor)
{
    $campos['c_'. $campo] = urlencode($valor);
}
$bache = './../../../baches/'. $query->Record[NOMBRES]  .'.O24';
$campos['NOMBRES'] = '@'.$bache;
$campos['verificacion'] = md5_file($bache);


$request_url = 'http://192.168.9.150/admin/herramientas/upload/procesa_upload.php';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $request_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $campos);
$result = curl_exec($ch);
curl_close($ch);
echo $result;
?>