#!/usr/bin/php

<?php
include __DIR__ . '/config.php';


$curl = curl_init();

//echo base64_encode('admin:rune7780') . "\n";
curl_setopt_array($curl, array(
  CURLOPT_URL => "http://127.0.0.1/". WWW_PATH ."/index.php/restapi/login",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_VERBOSE => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => array('"username' => 'admin','password' => 'rune7780'),
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic " . base64_encode(WWW_USER . ':' . WWW_KEY),
    "Cookie: PHPSESSID=kbluj0cpqap5m2jbbrsbos0g50"
  ),
));

$response = curl_exec($curl);
curl_close($curl);

echo $response;
//$result = json_decode(($response));

?> 