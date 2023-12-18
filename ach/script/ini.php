<?php

class ini2array {
	/*****************************
	Transforme le fichier ini en tableau.
	$file	= Fichier ini a parser
	$buff= Si a true, alors la classe parse le contenu de la variabel $file, cela permet de passer en paremtre un contenu de fichier ini.
	Format du tableau :
	[Section1][clef1]=valeur1
	[Section1][clef2]=valeur2
	[Section2][clef1]=valeur1 ....
	*****************************/
	function ini2array($file,$buff=false) {
		$this->file		= $file;
		$this->buff		= $buff;
		$this->IniArray = array();

		if ($buff==false)
			$fline 		= file($this->file);
		else
			$fline 		= explode("\n",$this->file);

		$section		= '';

		//crer le tableau et efface les ligne vide
		for ($j=0;$j!=count($fline);$j++) {
			$fline[$j]=rtrim($fline[$j]);
			if ($fline[$j] == '') continue;
			if ($section != '') {
				if ($fline[$j]{0} != '[') {
					$tmp 						= explode('=',$fline[$j],2);
					$this->IniArray[$section][$tmp[0]]	= $tmp[1];
				} else
					$section					= '';
			}
			if ($fline[$j]{0} == '[') //Section
				$section = str_replace(array('[',']'), '', $fline[$j]);
		}
	}
	/*****************************
	Renvoi le tableau
	Format du tableau :
	[Section1][clef1]=valeur1
	[Section1][clef2]=valeur2
	[Section2][clef1]=valeur1 ....
	*****************************/
	function getArray() {
		return $this->IniArray;
	}
	/*****************************
	Transforme le tabelau en fichier ini.
	$file	= Fichier ini a creer, si non fournit, va ecrire dans le fichier fourni a ini2array
	Format du tableau :
	[Section1][clef1]=valeur1
	[Section1][clef2]=valeur2
	[Section2][clef1]=valeur1 ....
	*****************************/
	function array2ini($tableau,$file='')
    {
		if ($file=='') $file = $this->file;
		$ini='';
		foreach ($tableau as $skey => $svalue) {
			$ini.='['.$skey."]\n";
			foreach ($svalue as $key => $value)
            {
				$ini.=$key.'='.$value."\n";
            }
            $ini.= "\n";
		}
		if ($this->buff==false) {
            @chmod($file, 0777);
            $handle = fopen($file, 'w');
			fwrite($handle, $ini);
			fclose($handle);
		} else {
			return $ini;
		}
	}
}

$query = new sql();
$sql ="SELECT * FROM M_AYUDAS WHERE ID_M_AYUDAS='". getvar('xbusca') ."'";
$query->sql = $sql;
$query->ejecuta_query();
$query->next_record();

//rdebug($query,'s');

$xini = Server_Path . strtolower($query->Record['ORIGEN']) . '.ini';
$xini2 = Server_Path . strtolower($query->Record['ORIGEN']) . '_new.ini';
$ini = new ini2array($xini);

$ayuda  = $query->Record['CODIGO1'];
//$enter  = $query->Record['ENTER'];
$titulo = $query->Record['TITULO'];

$ini->IniArray[VENTANA]['AYUDA'] = $ayuda;
//$ini->IniArray[VENTANA]['ENTER'] = $enter;
$ini->IniArray[VENTANA]['TITULO'] = $titulo;

$ini->array2ini($ini->IniArray);


?>