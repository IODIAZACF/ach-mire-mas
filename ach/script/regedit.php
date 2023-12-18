<?php
$x= leer_vars();

for($i=0;$i<sizeof($x);$i++)
{
    if(strlen($query)>0) $query.='&';
    $query.=$x[$i][nombre] .'='.  str_replace('\\\\', '\\', $x[$i][valor]);
}

$url = 'http://'. getvar('ipremoto') .':8888/enviar.php?xid='. md5(uniqid(rand(), true)) . '&'. $query;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $campos);
$result = curl_exec($ch);
curl_close($ch);
echo $result;


die();
?>