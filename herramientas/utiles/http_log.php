<?php
$xsistema = explode("/", $_SERVER['SCRIPT_NAME']);
@mkdir('/opt/tmp/http_log/'. $xsistema[1] . '/', 0777,true);
@chmod('/opt/tmp/http_log/'. $xsistema[1], 0777);

$logFile 	= '/opt/tmp/http_log/'. $xsistema[1] . '/http_'. date("Y-m-d")  .'.txt';
$hitCounter = '/opt/tmp/http_log/'. $xsistema[1] . '/counter_'. date("Y-m-d")  .".txt";

$r = array();

// Date & Time
$r['datetime'] = date('Y-m-d H:i:s');

// IP
$r['ip'] = $_SERVER['REMOTE_ADDR'];

// Port
//$r['port'] = $_SERVER["REMOTE_PORT"];

// URI
$r['uri'] = $_SERVER['REQUEST_URI'];

// Browser
$r['agent'] = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "";

// Referer
$r['referer'] = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "";

// Script
//$r['script'] = $_SERVER["SCRIPT_FILENAME"];

// Query (GET data)
$r['query'] = $_SERVER["QUERY_STRING"];

// POST data
$r['post'] = file_get_contents("php://input");

// Method
//$r['method'] = $_SERVER["REQUEST_METHOD"];

// Host
//$r['host'] = $_SERVER["HTTP_HOST"];

// Cookie
//$r['cookie'] = $_SERVER["HTTP_COOKIE"];

// Via
//$r['via'] = $_SERVER["HTTP_VIA"];

// Forwarded for
//$r['forwarded'] = $_SERVER["HTTP_X_FORWARDED_FOR"];


//---------------------------------------------------

// Hit Counter
$hit = file_exists($hitCounter)? file_get_contents($hitCounter) : 0;
$hit++;
file_put_contents($hitCounter, $hit);
$r['hit'] = $hit;

// Debug
if(isset($_GET['logdebug']))
{
	echo "<pre>";
	print_r($r);
	echo "</pre>";
}

// Log Report
$log = "";
$log .= str_pad("Hit", 10) 		. ": " . $r['hit'] . "\n";
$log .= str_pad("Time", 10) 	. ": " . $r['datetime'] . "\n";
$log .= str_pad("IP", 10) 		. ": " . $r['ip'] . "\n";
$log .= str_pad("URI", 10) 		. ": " . $r['uri'] . "\n";
$log .= str_pad("POST", 10) 	. ": " . $r['post'] . "\n";
$log .= str_pad("QUERY", 10) 	. ": " . $r['query'] . "\n";
$log .= str_pad("Agent", 10) 	. ": " . $r['agent'] . "\n";
$log .= str_pad("Referer", 10) 	. ": " . $r['referer'] . "\n";
$log .= str_repeat("-=", 100) ."\n";

// Log File
file_put_contents($logFile, $log, FILE_APPEND);

