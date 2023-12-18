<?php
$xDOCUMENTO =  getvar('xbusca');
$xDOCUMENTO =  getvar('xbusca');

$query = new sql(0);

$query->sql = "SELECT * FROM X_M_DOCUMENTOS WHERE ID_X_M_DOCUMENTOS ='". $xDOCUMENTO ."'";
$query->ejecuta_query();
$query->next_record();

$xID_M_CLIENTES = $query->Record['ID_M_CLIENTES'];
$XNETO          = $query->Record['NETO'];
$query->sql = "SELECT * FROM M_TIPO_PAGOS WHERE CONDICION2='*'";
$query->ejecuta_query();
$query->next_record();
$xid_pago = $query->Record['ID_M_TIPO_PAGOS'];
$xforma   = $query->Record['NOMBRES'];


$query->sql = "INSERT INTO D_PAGOS (ID_M_CLIENTES, FECHA_PAGO,CREDITOS,ID_M_TIPO_PAGOS, IDX,TABLA, ID_M_CAJAS) VALUES ('". $xID_M_CLIENTES ."', '". date('Y-m-d') ."',". $XNETO .", '". $xid_pago ."','". $xDOCUMENTO ."','X_M_DOCUMENTOS','". getvar('c_ID_M_CAJAS_CSS') ."')";
$query->ejecuta_query();

?>