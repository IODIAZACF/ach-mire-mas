#!/usr/bin/php

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

include dirname(__FILE__) . '/config.php';
include_once (UTIL_PATH . 'utilidades/php/clases/class_sql.php');
system("clear && printf '\033[3J'");

/*
SEGUIDORES NUEVOS PAR AUTO-SEGUIR
$cmd = 'twurl twurl /1.1/followers/ids.json';
$resp = exec($cmd);
$seguidores = json_decode($resp);
$aseguidores = $seguidores->ids;

file_put_contents(dirname(__FILE__) . '/log/twitter_followers.txt', print_r($seguidores, true));

$cmd = 'twurl twurl /1.1/friends/ids.json';
$resp = exec($cmd);
$amigos = json_decode($resp);
$aamigos = $amigos->ids;
file_put_contents(dirname(__FILE__) . '/log/twitter_friends.txt', print_r($amigos, true));

$nuevos = array_diff($aseguidores, $aamigos);


*/


	$user_id = $argv[1];
	$cmd = 'twurl -X POST /1.1/friendships/create.json?user_id='. $user_id .'&follow=true';
	//'https://api.twitter.com/1.1/friendships/create.json?user_id=USER_ID_TO_FOLLOW&follow=true' 
	$resp = exec($cmd);
	$user_info = json_decode($resp);
	print_r($user_info);
	file_put_contents(dirname(__FILE__) . '/log/'.  $user_id .'.txt', print_r($user_info, true));

die();	
?>