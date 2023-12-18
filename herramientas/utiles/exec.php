<?php
session_start();
if(!defined(Server_Path))
{
	define("Server_Path","../../");
}

if($_SESSION["M_USUARIOS_ID_M_USUARIO"] == "")
{
	die('ACCESO DENEGADO REALICE LOGIN');
}
if($_SESSION["db"] == "")
{
	die('ACCESO DENEGADO REALICE LOGIN');
}
$xconfig = "config.php";
if(strlen($_REQUEST['xconfig'])) $xconfig = $_REQUEST['xconfig'] . ".php";
include_once(Server_Path . $xconfig);



if (is_uploaded_file($_FILES["archivo"]["tmp_name"]))
{
	$ip = str_replace('.','_',$_SERVER['REMOTE_ADDR']);
	$ext = substr($_FILES["archivo"]["name"],-3);
    @mkdir("/opt/tmp/run", 0777);
    echo "<pre>";
   	switch($ext)
   	{
   		case 'sql';
            $xfile = '/opt/tmp/run/run_'. $ip .'.sql';
            $comando = '/opt/tmp/run/run_'. $ip .'.sh';

            $fsql = fopen($xfile, "w+");
            fwrite($fsql, "CONNECT '". $db_server[0]['SERVIDOR'] .":". $db_server[0]['DB'] ."' USER '". $db_server[0]['USUARIO'] ."' PASSWORD '". $db_server[0]['PASSWORD'] ."';\n");
            $contenido_sql = file_get_contents($_FILES["archivo"]["tmp_name"]);
            fwrite($fsql, $contenido_sql);
            fclose($fsql);

            $fp = fopen($comando, "w+");
	        fwrite($fp , 'rm -rf /opt/tmp/run/run_'. $ip .'.txt;'."\n");
	        fwrite($fp , '/opt/firebird/bin/isql -i ' .  $xfile .' -m -o /opt/tmp/run/run_'. $ip .'.txt' . "\n");
	        fwrite($fp , 'if [ $? -eq 1 ];'."\n");
	        fwrite($fp , 'then'."\n");
	        fwrite($fp , '  echo "**** ERROR EN SCRIPT ****"'."\n");
	        fwrite($fp , '  cat /opt/tmp/run/run_'. $ip .'.txt'."\n");
	        fwrite($fp , '  exit 1;'."\n");
	        fwrite($fp , 'fi'."\n");
	        fclose($fp);

	        chmod($comando, 0777);
	        $ulinea = exec($comando,$salida,$estatus);
	        echo join("\n", $salida);
        break;
        default:
            $tmp_upload = $_FILES["archivo"]["tmp_name"];
            $comando = '/opt/tmp/run/run_'. $ip . '.' . str_replace('.','',$ext);
            move_uploaded_file($tmp_upload, $comando);
            chmod($comando, 0777);

            $tcomando = '/opt/tmp/run/trun_'. $ip .'.sh';
            $fp = fopen($tcomando, "w+");
            fwrite($fp , '#!/bin/bash'."\n");
            fwrite($fp , "\n");
	        fwrite($fp , 'fromdos '.$comando ."\n");
	        fclose($fp);
			chmod($tcomando, 0777);
            $ulinea = exec($tcomando,$salida,$estatus);

            $ulinea = exec($comando,$salida,$estatus);
	        echo join("\n", $salida);
        break;

   	}
}
echo '<form name="upload" enctype="multipart/form-data" method="post" action="exec.php">' . "\n";
echo '  <input name="archivo" type="file" size="50">' . "\n";
echo '  <input type="submit" name="Submit" value="Enviar Archivo">' . "\n";
echo '</form>' . "\n";




?>