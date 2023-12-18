<?php
$query = new sql();
$query->sql ="SELECT * FROM X_M_DOCUMENTOS WHERE ID_M_USUARIOS='".getsession('M_USUARIOS_ID_M_USUARIO')."' AND ESTATUS='A'";
$query->ejecuta_query();
if($query->next_record())
{
	$xID_X_M_DOCUMENTOS = $query->Record['ID_X_M_DOCUMENTOS'];
}else
{
	$sql = "INSERT INTO X_M_DOCUMENTOS(";
    $sql.= "TIPO,ID_M_CLIENTES,ESTATUS,CONDICION_PAGO) VALUES(";+
    $sql.= "'FAC',";
    $sql.= "'001198',";
    $sql.= "'A',";
    $sql.= "'CON')";
    $query->sql = $sql;
    $query->ejecuta_query();
    $tunico = $query->unico;
	if(!$query->Reg_Afect)
	{
	    $xx['tabla']['estatus']="ERROR";
	    $xx['tabla']['msg']=utf8_encode($query[0]->erro_msg);
	    echo  json_encode($xx);
	    die('');
	}
    $query->sql ="SELECT * FROM X_M_DOCUMENTOS WHERE UNICO='".$tunico."'";
	$query->ejecuta_query();
	$query->next_record();
	$xID_X_M_DOCUMENTOS = $query->Record['ID_X_M_DOCUMENTOS'];
}
$_REQUEST['c_ID_X_M_DOCUMENTOS_CSS']=$xID_X_M_DOCUMENTOS;
$_GET['c_ID_X_M_DOCUMENTOS_CSS']=$xID_X_M_DOCUMENTOS;
$_POST['c_ID_X_M_DOCUMENTOS_CSS']=$xID_X_M_DOCUMENTOS;
//rdebug($_REQUEST,'s');
?>