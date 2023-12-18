<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://10.3.150.4:8088/manager?action=login&username=admin&secret=las24horas');
curl_setopt($ch, CURLOPT_GET, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
echo $data ."<br>";
$session  = explode('Set-Cookie:',$data);
curl_setopt($ch, CURLOPT_COOKIE, $session[1]);
curl_setopt($ch, CURLOPT_URL, 'http://10.3.150.4:8088/rawman?action=originate&channel=SIP/3620&context=record_vmenu&exten=1&priority=1&Variable=var1%3dpari_test.gsm&_=');
$data = curl_exec($ch);
echo $data ."<br>";

?>