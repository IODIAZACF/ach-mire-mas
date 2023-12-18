#!/usr/bin/php

<?php
function generateAutoLoginLink($params){

     $dataRequest = array();
     $dataRequestAppend = array();

     // Destination ID
     if (isset($params['r'])){
         $dataRequest['r'] = $params['r'];
         $dataRequestAppend[] = '/(r)/'.rawurlencode(base64_encode($params['r']));
     }

     // User ID
     if (isset($params['u']) && is_numeric($params['u'])){
         $dataRequest['u'] = $params['u'];
         $dataRequestAppend[] = '/(u)/'.rawurlencode($params['u']);
     }

     // Username
     if (isset($params['l'])){
         $dataRequest['l'] = $params['l'];
         $dataRequestAppend[] = '/(l)/'.rawurlencode($params['l']);
     }

     if (!isset($params['l']) && !isset($params['u'])) {
         throw new Exception('Username or User ID has to be provided');
     }

     // Expire time for link
     if (isset($params['t'])){
         $dataRequest['t'] = $params['t'];
         $dataRequestAppend[] = '/(t)/'.rawurlencode($params['t']);
     }

     $hashValidation = sha1($params['secret_hash'].sha1($params['secret_hash'].implode(',', $dataRequest)));

     return "index.php/user/autologin/{$hashValidation}".implode('', $dataRequestAppend);
}


	$Url = 'https://vps24.dyndns.info/'. basename(dirname(__DIR__ ,1)) . '/' . basename(__DIR__) .'/';
	$Url.= generateAutoLoginLink(array('r' => 'chat/chattabs', 'u' => 1,/* 'l' => 'admin', *//* 't' => time() + 50000 */ 'secret_hash' => 'las36horas'));
	
	
	$resp = file_get_contents($Url);
	
	file_put_contents(__DIR__ . '/x.html', $resp);	
	
	echo $Url . "<br>";

?>

<a target="_blank" href="https://vps24.dyndns.info/<?php echo basename(dirname(__DIR__ ,1)) . '/' . basename(__DIR__) .'/' . generateAutoLoginLink(array('r' => 'chat/chattabs', 'u' => 1,/* 'l' => 'admin', *//* 't' => time() + 50000 */ 'secret_hash' => 'las36horas'))?>">Login me</a>

