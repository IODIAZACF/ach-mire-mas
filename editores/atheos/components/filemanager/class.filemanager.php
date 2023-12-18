<?php

//////////////////////////////////////////////////////////////////////////////80
// FileManager Class
//////////////////////////////////////////////////////////////////////////////80
// Copyright (c) Atheos & Liam Siira (Atheos.io), distributed as-is and without
// warranty under the MIT License. See [root]/docs/LICENSE.md for more.
// This information must remain intact.
//////////////////////////////////////////////////////////////////////////////80
// Authors: Codiad Team, @Fluidbyte, Atheos Team, @hlsiira
//////////////////////////////////////////////////////////////////////////////80

require_once("vendor/differential/diff_match_patch.php");

class Filemanager {

	//////////////////////////////////////////////////////////////////////////80
	// METHODS
	//////////////////////////////////////////////////////////////////////////80

	// -----------------------------||----------------------------- //

	//////////////////////////////////////////////////////////////////////////80
	// Create (Creates a new file or directory)
	//////////////////////////////////////////////////////////////////////////80
	public function create($path, $type) {

		if (file_exists($path)) {
			Common::send("error", i18n("path_exists"));
		}

		// $path = strip_tags($path);
		$path = htmlspecialchars($path);

		if ($type === "folder" && @mkdir($path)) {
			Common::send("success");
		} elseif ($type === "file" && $file = fopen($path, 'w')) {
			$modifyTime = filemtime($path);
			fclose($file);
			Common::send("success", array("modifyTime" => $modifyTime));
		} else {
			$error = error_get_last();
			debug($error);
			Common::send("error", i18n("path_unableCreate"));
		}
	}

	//////////////////////////////////////////////////////////////////////////80
	// Delete (Deletes a file or directory (+contents))
	//////////////////////////////////////////////////////////////////////////80
	public function delete($path) {
		if (!file_exists($path)) {
			Common::send("error", "Invalid path.");
		}

		if (is_dir($path)) {
			$path = preg_replace("/[\/]+/", "/", "$path/");
		}

		Common::rDelete($path);
		Common::send("success");
	}

	//////////////////////////////////////////////////////////////////////////80
	// Duplicate (Creates a duplicate of the object - (cut/copy/paste)
	//////////////////////////////////////////////////////////////////////////80
	public function duplicate($path, $dest) {
		if (!file_exists($path) || !$dest) {
			Common::send("error", "Invalid path.");
		}

		if (file_exists($dest)) {
			Common::send("error", "Duplicate path.");
		}

		function rCopyDirectory($src, $dst) {
			$dir = opendir($src);
			@mkdir($dst);
			while (false !== ($file = readdir($dir))) {
				if (($file !== '.') && ($file !== '..')) {
					if (is_dir($src . '/' . $file)) {
						rCopyDirectory($src . '/' . $file, $dst . '/' . $file);
					} else {
						copy($src . '/' . $file, $dst . '/' . $file);
					}
				}
			}
			closedir($dir);
		}

		if (is_file($path)) {
			copy($path, $dest);
			Common::send("success", i18n("duplicated_file"));
		} else {
			rCopyDirectory($path, $dest);
			Common::send("success", i18n("duplicated_folder"));
		}
	}

	//////////////////////////////////////////////////////////////////////////80
	// Index (Returns list of files and directories)
	//////////////////////////////////////////////////////////////////////////80
	public function index($path) {
		$path = Common::cleanPath($path);

		$relativePath = $path !== "/" ? "$path/" : $path;
		$path = Common::isAbsPath($path) ? $path : WORKSPACE . "/" . $path;

		if (!file_exists($path)) {
			Common::send("error", "Invalid path.");
		}

		if (!is_dir($path) || !($handle = opendir($path))) {
			Common::send("error", "Unreadable path.");
		}

		$index = array();


		while (false !== ($object = readdir($handle))) {
			if ($object === "." || $object === "..") {
				continue;
			}

			if (is_dir($path.'/'.$object)) {
				$type = "folder";
				$size = count(glob($path.'/'.$object.'/*'));
			} else {
				$type = "file";
				$size = @filesize($path.'/'.$object);
			}


			$index[] = array(
				// "path" => strip_tags($relativePath . $object),
				"path" => htmlspecialchars($relativePath . $object),
				"type" => $type,
				"size" => $size
			);
		}

		$folders = array();
		$files = array();
		foreach ($index as $item => $data) {
			if ($data['type'] == 'folder') {

				$repo = file_exists($data['path'] . "/.git");

				$folders[] = array(
					"path" => $data['path'],
					"type" => $data['type'],
					"size" => $data['size'],
					"repo" => $repo
				);
			}
			if ($data['type'] == 'file') {
				$files[] = array(
					"path" => $data['path'],
					"type" => $data['type'],
					"size" => $data['size']
				);
			}
		}

		function sorter($a, $b, $key = 'path') {
			return strnatcasecmp($a[$key], $b[$key]);
			// return strnatcmp($a[$key], $b[$key]);
		}

		usort($folders, "sorter");
		usort($files, "sorter");

		$output = array_merge($folders, $files);

		Common::send("success", array("index" => $output));
	}

	//////////////////////////////////////////////////////////////////////////80
	// Move
	//////////////////////////////////////////////////////////////////////////80
	public function move($path, $dest) {
		if (file_exists($dest)) Common::send("error", "Target already exists.");

		if (rename($path, $dest)) {
			Common::send("success", "Target moved.");
		} else {
			Common::send("error", "Failed to move target.");
		}
	}

	//////////////////////////////////////////////////////////////////////////80
	// Open (Returns the contents of a file)
	//////////////////////////////////////////////////////////////////////////80
	public function open($path) {
		if (!$path || !is_file($path)) {
			Common::send("error", "Invalid path.");
		}

		$output = file_get_contents($path);
		$t = explode('.', $path);
		$ext = array_pop( $t );
		if( $ext == 'ini'){
			$output = procesa_ini ( $output );
		}
			
			if (extension_loaded('mbstring')) {
				if (!mb_check_encoding($output, 'UTF-8')) {
					if (mb_check_encoding($output, 'ISO-8859-1')) {
						$output = utf8_encode($output);
					} else {
						$output = mb_convert_encoding($output, 'UTF-8');
					}
				}
			}
		
		$modifyTime = filemtime($path);
		Common::send("success", array("content" => $output, "modifyTime" => $modifyTime));
	}

	//////////////////////////////////////////////////////////////////////////80
	// Rename
	//////////////////////////////////////////////////////////////////////////80
	public function rename($path, $name) {
		$parent = dirname($path);

		$newPath = $parent . "/" . $name;
		// $newPath = strip_tags($newPath);
		$newPath = htmlspecialchars($newPath);

		if (file_exists($newPath)) {
			Common::send("success", i18n("path_exists"));
		} elseif (rename($path, $newPath)) {
			Common::send("success");
		} else {
			Common::send("success", i18n("path_unableRename"));
		}
	}

	//////////////////////////////////////////////////////////////////////////80
	// Save (Modifies a file name/contents or directory name)
	//////////////////////////////////////////////////////////////////////////80
	public function save($path, $modifyTime, $patch, $content) {
		// Change content
		if (!$content && !$patch) {
			$file = fopen($path, 'w');
			fclose($file);
			Common::send("success", array("modifyTime" => filemtime($path)));
		}

		if ($content === ' ') {
			$content = ''; // Blank out file
		}
		if ($patch && ! $modifyTime) {
			Common::send("error", "ModifyTime");
		}
		if (!is_file($path)) {
			Common::send("error", "Invalid path.");
		}

		$serverModifyTime = filemtime($path);
		$fileContents = file_get_contents($path);

		if ($patch && $serverModifyTime !== (int)$modifyTime) {
			Common::send("warning", "out of sync");
		} elseif (strlen(trim($patch)) === 0 && !$content) {
			// Do nothing if the patch is empty and there is no content
			Common::send("success", array("modifyTime" => $serverModifyTime));
		}
		try {
			$file = fopen($path, 'w');
			
		file_put_contents ('/opt/editor/original.ini', $fileContents );
		file_put_contents ('/opt/editor/nuevo.ini', $content );

			if ($file) {
				if ($patch) {
					$dmp = new diff_match_patch();
					$patch = $dmp->patch_apply($dmp->patch_fromText($patch), $fileContents);
					$content = $patch[0];
					file_put_contents ('/opt/editor/nuevo_patch.ini', $content );
					file_put_contents ('/opt/editor/nuevo_patch_arr.ini', print_r ( $patch, true  ) );
				}

				if (fwrite($file, $content)) {
					// Unless stat cache is cleared the pre-cached modifyTime will be
					// returned instead of new modification time after editing
					// the file.
					clearstatcache();
					Common::send("success", array("modifyTime" => filemtime($path)));
				} else {
					Common::send("error", "Client does not have access.");
				}

				fclose($file);
			} else {
				Common::send("error", "Client does not have access.");
			}
		}catch(Exception $e) {
			Common::send("error", "Client does not have access.");
		}

	}
}

function procesa_ini($oconte){
	$tc= explode("\n", $oconte);
	$atc = array();
	$osec = array();
	$iSQL=false;
	$pSQL=false;
	for($i=0;$i<sizeof($tc);$i++)
	{
		$tc[$i] = trim($tc[$i]);
		$tc[$i] = str_replace("\n", "", $tc[$i]);
		$tc[$i] = preg_replace("[\n|\r|\n\r]", "", $tc[$i]);
		if($tc[$i]=='') continue;
		if(!mb_detect_encoding($tc[$i], 'utf-8', true)){
				$tc[$i] = utf8_encode($tc[$i]);
		}

		if( mb_detect_encoding( $tc[$i] )  =='UTF-8') {
			$tc[$i] = utf8_decode( $tc[$i] );
			//$tc[$i] = $this->noTag($tc[$i]);
		}

		if(substr($tc[$i],0,1)=='['){
			$oname = str_replace(array('[',']'),array('', ''), $tc[$i]);
			$n = str_replace('[','',preg_replace ("/[0-9]/", "", $tc[$i]));
			$n = trim(str_replace(']','', $n));
			switch ($n){
				case 'SQL':
					$iSQL=true;
				break;
				case 'CAMPO':
				case 'COLUMNA':
				case 'GRUPO':
				case 'LEYENDA':
				case 'OPCION':
				case 'PIE':
				case 'ENCABEZADO':
					$iSQL=false;
					$pSQL=false;
					//echo "$n\n";
					if(!isset($idx[$n])){
						$idx[$n] = 0;
					} 
					$idx[$n]++;
					$id = $idx[$n];
					$tc[$i] = '[' . $n . ']';
			}
			$nname = str_replace(array('[',']'),array('', ''), $tc[$i]);
			$osec[$nname] = $oname;
		}else{
			//$tc[$i] = $this->noTag( $tc[$i] );
			$t = explode("=", $tc[$i]);
			switch (trim($t[0])){
				case 'ROTULO':
				case 'TITULO':
				case 'ICONO':
				case 'COMENTARIO':
					$x  = trim($t[1]);
					$x1 = substr($x,0,1);
					$x2 = substr($x,-1,1);
					$c1='"';
					$c2='"';
					if($x1=='"') $c1='';
					if($x2=='"')
					{
						$c2='';
					}
					else
					{
						if($x1=='"')
						{
							$p1 = strpos ($x, '"');
							$p2 = strripos ($x, '"');
							if($p1!=$p2) $x = substr($x, 0, $p2);
						}

					}
				$tc[$i] = trim($t[0]) .  '='. $c1 . $x . $c2 ;
				break;
					default:
						if($iSQL){
							break;
						}
						$n = preg_replace ("/[0-9]/", "", $t[0]);
						if($n=='ALERTA'){
							//$tc[$i] = $this->Tag( $tc[$i]);
						}
				break;

			}			
		}
		$atc[] = $tc[$i];
	}
	//$oconte =  $this->Tag( join("\n", $atc) );
	$oconte =  join("\n", $atc) ;
	$oconte =  str_replace('[', PHP_EOL . "[" , $oconte) ;
	$p =strpos ($oconte, '[');
	
	$oconte= substr($oconte, $p);
	$sql = explode('[SQL]', $oconte);
	if( isset( $sql[1]) ){
		$bsql = str_replace('"', '', $sql[1]);
		$l = explode("\n", $bsql);
		$tsql='';
		
		foreach( $l as $isql ){
			$t = explode('=', $isql);
			if( isset ($t[1] )){
				$p = explode(' ', $t[1] );
				$inst = strtoupper ($p[0]);
				switch(strtoupper ($inst) ){
					case 'INSERT':
					case 'SELECT':
					case 'DELETE':
					case 'UPDATE':
						$inst = '"'. $inst;
						if($tsql !=''){
							$tsql = trim($tsql);
							$tsql .='"' .PHP_EOL;
						} 
						$isql = str_replace ( $p[0], $inst, $isql );
				}				
			}
			if($isql !='' ) $tsql .= $isql .PHP_EOL;
		}
		$tsql = trim($tsql);
		$x2 = substr($tsql,-1,1);
		if($x2 != '"') $tsql.='"';
		$oconte = $sql[0] . PHP_EOL . '[SQL]' .PHP_EOL . $tsql;
		
	}
	
	
	file_put_contents ('/opt/editor/ini_original.ini', $oconte );
	
	return $oconte;
	
}