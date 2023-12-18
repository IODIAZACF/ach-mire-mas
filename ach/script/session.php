<?php
    //$xusuario_id =  $query->Record['ID_M_USUARIO'];

    $query->sql  = "SELECT M_CLIENTES.ID_M_CLIENTES AS ID_M_CLIENTES ,M_CLIENTES.RAZON AS RAZON FROM M_USUARIOS INNER JOIN M_CLIENTES ON M_CLIENTES.ID_M_CLIENTES =  M_USUARIOS.ID_M_CLIENTES WHERE M_USUARIOS.ID_M_USUARIO='". $xusuario_id ."'";
    $query->ejecuta_query();
    if($query->next_record())
    {
        while(list($variable, $valor) = each($query->Record))
        {
           setsession('M_CLIENTES_' .$variable, $valor);
        }
    }

    $query->sql = "SELECT ID_M_ESTACIONES,NOMBRES,IP,ID_M_AREA,ID_M_ALMACENES_VENTAS,ID_M_ALMACENES_COMPRAS,ID_M_USUARIO FROM M_ESTACIONES WHERE  IP='". $_SERVER['REMOTE_ADDR'] . "'";
    $query->ejecuta_query();
    if($query->next_record())
    {
        while(list($variable, $valor) = each($query->Record))
        {
           setsession('M_ESTACIONES_' .$variable, $valor);
        }
    }

    $query->sql = "SELECT ID_M_VENDEDORES,NOMBRES FROM M_VENDEDORES WHERE  ID_M_USUARIO='". $xusuario_id . "' and ESTATUS='ACT'";
    $query->ejecuta_query();

    if($query->next_record())
    {
        while(list($variable, $valor) = each($query->Record))
        {
           setsession('M_VENDEDORES_' .$variable, $valor);
        }
    }
    $query->sql = "SELECT * FROM CONFIGURACION";
    $query->ejecuta_query();
    if($query->next_record())
    {
        while(list($variable, $valor) = each($query->Record))
        {
           setsession('CONFIGURACION_' .$variable, $valor);
        }
    }

?>