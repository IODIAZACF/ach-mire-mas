<?php

if(is_uploaded_file($_FILES["archivo"]["tmp_name"]))
{
	$conte = file($_FILES["archivo"]["tmp_name"]);
    $query = new sql();

    for($i=0;$i<sizeof($conte);$i++)
    {
        if(trim($conte[$i])<>'')
        {
        	$reg = explode(',',$conte[$i]);
        	$c = trim($reg[2]);
        	$pro[$c]+=$reg[3];
        }
    }

    while (list($codigo, $cantidad) = each($pro))
    {
        $query->sql ="SELECT ID_D_DOCUMENTOS FROM V_X_DOCUMENTOS_AUDITORIA WHERE CODIGO1 LIKE '%". $codigo ."'%";
        $query->ejecuta_query();
        $query->next_record();
        if($query->Record['ID_D_DOCUMENTOS'])
        {
            $query->sql = "UPDATE X_DOCUMENTOS SET CONDICION1='*',CAMPO1='*',CANTIDAD2=". $cantidad ." WHERE ID_D_DOCUMENTOS='". $query->Record['ID_D_DOCUMENTOS'] ."'";
            $query->ejecuta_query();
        }


	}

    die('Archivo procesado con exito.');
}

//rdebug($_SERVER);

///herramientas/utiles/actualizar_registro.php?onload=procesar_auditoria&ID_M_M_DOCUMENTOS=0011786&rndid=83964008


$id_m_m_documentos = getvar('ID_M_M_DOCUMENTOS');
echo '<form name="upload" enctype="multipart/form-data" method="POST" action="'. $_SERVER['REQUEST_URI'].'">' . "\n";
echo '  <input name="archivo" type="file" size="50">' . "\n";
echo '  <input type="submit" name="Submit" value="Enviar Archivo">' . "\n";
echo '  <input name="ID_X_M_DOCUMENTOS" type="hidden" value="'. getvar('ID_M_M_DOCUMENTOS') .'">' . "\n";
echo '  <input name="onload" type="hidden" value="'. getvar('onload') .'">' . "\n";
echo '</form>' . "\n";
die();

?>