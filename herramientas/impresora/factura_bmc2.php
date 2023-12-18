<?php
define('Server_Path','../');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$DOCUMENTO="SELECT D.TIPO,COALESCE(D.CAMPO2,D.NOMBRES) NOMBRE_CLIENTE,IIF(D.CAMPO1 IS NULL,D.CODIGO1,D.CAMPO1) CODIGO1_CONFORME,SUBSTRING(D.NOMBRES FROM 1 FOR 40) NOMBRES ,D.ID_M_PACIENTES,D.CODIGO1,D.ID_M_DOC_FINAL, A_DOCUMENTOS.ID_M_PRODUCTOS, M_PRODUCTOS.DESCRIPCION  PROTOCOLO_PRINCIPAL, SUBSTRING(D.CONFORME_DIRECCION FROM 1  FOR 34) AS DIRECCION1, TRIM(SUBSTRING(D.CONFORME_DIRECCION FROM 34 FOR 34)) AS DIRECCION2,     TRIM(SUBSTRING(D.CONFORME_DIRECCION FROM 68 FOR 34)) AS DIRECCION3 
           FROM V_M_DOCUMENTOS_CLINICA D  
           LEFT JOIN A_DOCUMENTOS ON A_DOCUMENTOS.IDX = D.ID_M_DOCUMENTOS AND A_DOCUMENTOS.TABLA = 'M_DOCUMENTOS'  
           LEFT JOIN M_PRODUCTOS ON M_PRODUCTOS.ID_M_PRODUCTOS = A_DOCUMENTOS.ID_M_PRODUCTOS  
           WHERE D.ID_M_DOCUMENTOS='". $_REQUEST['ID_M_DOCUMENTOS'] ."'";

$DETALLE="select * from (
        SELECT 1 CANTIDAD,NOMBRE_TIPO||' '||GRUPO DESCRIPCION, cast(SUM(NETO) as numeric(15,2)) PRECIO,ORDEN,SUM(CAST(CANTIDAD*PRECIO AS NUMERIC(15,2))) as XNETO,0 IMPUESTO  FROM V_RESUMEN_DOCUMENTO ('". $_REQUEST['ID_M_DOCUMENTOS'] ."','SERVICIOS','OTROS SERVICIOS','EQUIPOS','HONORARIOS','PRODUCTOS')
        WHERE TIPO = 'PRT' and neto > 0
        GROUP BY NOMBRE_TIPO,GRUPO,ORDEN,IMPUESTO --,CAMPO_DEBUG
        UNION ALL
        SELECT 1 CANTIDAD,NOMBRE_TIPO||' '||GRUPO DESCRIPCION, cast(SUM(NETO) as numeric(15,2)) PRECIO,SUM(CAST(CANTIDAD*PRECIO AS NUMERIC(15,2))) XNETO,0 IMPUESTO, 21 ORDEN FROM V_RESUMEN_DOCUMENTO ('". $_REQUEST['ID_M_DOCUMENTOS'] ."','SERVICIOS','OTROS SERVICIOS','EQUIPOS','HONORARIOS','PRODUCTOS')
        WHERE TIPO = 'EQU' and neto > 0
        GROUP BY NOMBRE_TIPO,GRUPO,IMPUESTO
        UNION ALL
        SELECT 1 CANTIDAD,NOMBRE_TIPO DESCRIPCION, CAST(SUM(NETO) AS NUMERIC(15,2)) PRECIO,22 ORDEN,SUM(CAST(CANTIDAD*PRECIO AS NUMERIC(15,2))) XNETO,0 IMPUESTO  FROM V_RESUMEN_DOCUMENTO ('". $_REQUEST['ID_M_DOCUMENTOS'] ."','SERVICIOS','OTROS SERVICIOS','EQUIPOS','HONORARIOS','PRODUCTOS')
        WHERE TIPO = 'SER' and neto > 0
        GROUP BY NOMBRE_TIPO,GRUPO,IMPUESTO
        UNION ALL
        SELECT 1 CANTIDAD,DESCRIPCION, CAST(NETO AS NUMERIC(15,2)) PRECIO,25 ORDEN,CAST(CANTIDAD*PRECIO AS NUMERIC(15,2)) XNETO,0 IMPUESTO FROM V_RESUMEN_DOCUMENTO ('". $_REQUEST['ID_M_DOCUMENTOS'] ."','SERVICIOS','OTROS SERVICIOS','EQUIPOS','HONORARIOS','PRODUCTOS')
        WHERE TIPO = 'PRO' and neto > 0
        )
        ORDER BY ORDEN";

$HONORARIOS="SELECT 1 CANTIDAD, TRIM(DESCRIPCION) AS DESCRIPCION, NETO PRECIO,ORDEN,CAST(CANTIDAD*PRECIO AS NUMERIC(15,2)) XNETO,0 IMPUESTO  FROM V_RESUMEN_DOCUMENTO ('". $_REQUEST['ID_M_DOCUMENTOS'] ."','SERVICIOS','OTROS SERVICIOS','EQUIPOS','HONORARIOS','PRODUCTOS')
     WHERE TIPO = 'HON' and neto > 0 ORDER BY ORDEN";



$query = new sql();
$query->sql = $DOCUMENTO;
$query->ejecuta_query();
$query->next_record();

$C[] = 'i01'. substr($query->Record['CODIGO1_CONFORME'], 0,36);
$C[] = 'i02'. substr($query->Record['NOMBRE_CLIENTE'], 0,36);
$C[] = 'i03'. $query->Record['DIRECCION1'];
$C[] = 'i04'. $query->Record['DIRECCION2'];
$C[] = 'i05'. $query->Record['DIRECCION3'];
$C[] = 'i06'. substr($query->Record['CODIGO1'], 0,36);
$C[] = 'i06'. substr($query->Record['NOMBRES'], 0,36);
$C[] = 'i08'. 'REF.#:' . $query->Record['ID_M_DOC_FINAL'];


$query->sql = $DETALLE;
$query->ejecuta_query();
while($query->next_record()){
	$linea ='';
	if($query->Record['IMPUESTO'] > 0){
		$linea = '!';
	}else{
		$linea = ' ';
	}
	$precio 	= floatval($query->Record['PRECIO'])*100;
	$cantidad 	= floatval($query->Record['CANTIDAD'])*1000;
	$linea .= str_pad($precio,15,"0", STR_PAD_LEFT); 
	$linea .= str_pad($cantidad,16,"0", STR_PAD_LEFT); 
	$linea .= substr($query->Record['DESCRIPCION'],0,40);  
	$C[] = $linea;	
	
}

$query->sql = $HONORARIOS;
$query->ejecuta_query();
while($query->next_record()){
	$linea ='';
	if ($query->Record['IMPUESTO']> 0){
		$linea = '!';
	}else{
		$linea = ' ';
	}
	$precio 	= floatval($query->Record['PRECIO'])*100;
	$cantidad 	= floatval($query->Record['CANTIDAD'])*1000;
	
	$linea .= str_pad($precio,15,"0", STR_PAD_LEFT); 
	$linea .= str_pad($cantidad,16,"0", STR_PAD_LEFT); 
	$linea .= substr($query->Record['DESCRIPCION'],0,40);  
	$C[] = $linea;	
}
$C[] = '101';	

echo join("\n", $C);


?>