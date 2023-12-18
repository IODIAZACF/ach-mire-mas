<?php
  header("content-type: text/plain\n\n");
  header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past


//$aspell_prog	= '"C:\Program Files\Aspell\bin\aspell.exe"';	// windows
  $aspell_prog	= 'aspell';                                   // Linux
  $lang			= 'es';
  $aspell_opts	= "-a --lang=$lang --encoding=iso-8859-1 --extra-dicts=es_o24.rws --rem-sgml-check=alt --personal=o24 --use-other-dicts";
  $aspell_dir   = "/usr/lib/aspell";

  $textinput=$_REQUEST['palabra'];
  $optadd=$_REQUEST['agregar'];

  $cmd = "echo $textinput | $aspell_prog $aspell_opts";



  srand((double)microtime()*1000000);
  $randval  = mt_rand (1,9999999999999);
  $unico    = strtoupper(md5($randval));

  $arr=Array();
  if(!file_exists("$aspell_dir/es_o24.rws"))
  {
    @mkdir("../diccionario");
    $fp = fopen("../diccionario/es_o24.txt","a+");
    fwrite($fp, $textinput."\n");
    @chmod($aspell_dir,0777);
    fclose($fp);
    $cmd = "$aspell_prog create master --lang=es --encoding=iso-8859-1 $aspell_dir/es_o24.rws < ../diccionario/es_o24.txt";
    @chmod("$aspell_dir/es_o24.rws",0777);
  }
  if ($textinput && $optadd)
  {
    @mkdir("../diccionario");
    $fp = fopen("../diccionario/es_o24.txt","a+");
    fwrite($fp, $textinput."\n");
    @chmod($aspell_dir,0777);
    fclose($fp);
    $cmd = "$aspell_prog create master --lang=es --encoding=iso-8859-1 $aspell_dir/es_o24.rws < ../diccionario/es_o24.txt";
  }

//  echo $cmd.'<br>';

  if(($textinput) && ($aspellret = shell_exec( $cmd )))
  {
    $lineas=explode("\n",$aspellret);
    foreach ($lineas as $clave=>$valor)
    {
//      echo '<br>'.$clave.'='.$valor;
      $res=substr($valor,0,1);

      switch($res)
      {
        case '&':
        case '#': $line = explode( " ", $valor, 5 );
             if( isset( $line[4] ))
             {
               $suggs = explode( ", ", $line[4] );
             }
             else
             {
               $suggs = array();
             }
             echo $res;
             foreach ($suggs as $i=>$sugg)
             {
               echo '|'.$sugg;
             }
             break;
      }

    }

  }
  else echo '?';
?>