<?php
define('debug', false);
define('limite', 50);
define("Server_Path", "");
include_once("../../config.php");


$id = 0; $i = 0;
			$stmt = $conexion->PrepareSP( "update ejemplo set val=:i where id_ejemplo=:id");
			$conexion->Parameter($stmt,$id,'id');
            $conexion->Parameter($stmt,$i, 'i');
            	for ($cnt=0; $cnt < 1000; $cnt++)
                {
                	$id = $cnt; 	$i = $cnt * $cnt; # works with oci8!
			$conexion->Execute($stmt);
            }


?>