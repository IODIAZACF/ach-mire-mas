<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha                 = date("d/m/Y");

// Se determinan los datos de la caja en función del maestro de cajas
$query = new sql();
$query->sql = "SELECT * FROM M_CAJAS WHERE IP='". $_SERVER['REMOTE_ADDR'] ."'";
$query->ejecuta_query();
$query->next_record();
$xid_m_cajas 	 		= $query->Record['ID_M_CAJAS'];
$xnombre_caja			= $query->Record['NOMBRES'];
$tmp_data 				= explode('_', $query->Record['CAMPO1']);

// Si no hay caja asignada a este equipo se toma la caja por defecto (Registro control en M_CAJAS)
if(!$xid_m_cajas){
	$xid_m_cajas='XXXX';
	$query = new sql();
	$query->sql = "SELECT * FROM M_CAJAS WHERE ID_M_CAJAS='". $xid_m_cajas . "'";
	$query->ejecuta_query();
	$query->next_record();
	$xnombre_caja = $query->Record['NOMBRES'];
}

// Se identifican las formas de pago para colocar disponibles en javascript
$query = new sql();
$query->sql = "SELECT ID_M_TIPO_PAGOS,ID_M_BANCOS,NUMERO,CUENTA,CODIGO1,TITULAR,TELEFONOS,ID_M_CUENTAS_BANCARIAS,ID_M_CLIENTES,ID_M_CUENTAS_BANCARIAS2 FROM V_M_TIPO_PAGOS_CAJAS";
$query->ejecuta_query();
$frm_campos = "var frm_campos = new Array();\n";
$frm_cuentas = "var frm_cuentas = new Array();\n";
while($query->next_record())
{
    $tmp ='';
    while (list($c, $v) = each($query->Record))
        {
        if($v=='*' && $c!='ID_M_TIPO_PAGOS')
        {
            if(strlen($tmp)) $tmp .=',';
            $tmp .= "'" .$c . "'";
        }
        $v='';
        }
    $frm_campos .= 'frm_campos[\''.  $query->Record['ID_M_TIPO_PAGOS'] .'\'] = new Array('. $tmp .');' . "\n";
	$frm_cuentas .= 'frm_cuentas[\''.  $query->Record['ID_M_TIPO_PAGOS'] .'\'] = new Array(\''. $query->Record['ID_M_CUENTAS_BANCARIAS2'] .'\');' . "\n";
}

// Se identifica el almacén de venta asignado al equipo en el mestro de equipos
$xID_M_ALMACENES  		= getsession('M_ESTACIONES_ID_M_ALMACENES_VENTAS');

// Si no hay almacén de venta asignado a este equipo se toma el almacén de venta por defecto (Almacén principal en M_ALMACENES)
if(!$xID_M_ALMACENES){
	$xID_M_ALMACENES='0011';
}

// Se determina el nombre del almacén
$query = new sql();
$query->sql = "SELECT * FROM M_ALMACENES WHERE ID_M_ALMACENES='". $xID_M_ALMACENES . "'";
$query->ejecuta_query();
$query->next_record();
$xnombre_almacen = $query->Record['NOMBRES'];

// Se determinan los datos del cliente de control (Contado)
$query = new sql();
$query->sql = "SELECT * FROM M_CLIENTES WHERE ID_M_CLIENTES='XXXX'";
$query->ejecuta_query();
$query->next_record();

$xRAZON 	= $query->Record['RAZON'];
$xCODIGO1 	= $query->Record['CODIGO1'];
$xDIRECCION	= $query->Record['DIRECCION'];
$xTELEFONO1 = $query->Record['TELEFONO1'];
$xCORREO1   = $query->Record['CORREO1'];

$my_ini     = new ini('modulo');

encabezado( $my_ini->seccion('VENTANA','TITULO') );

$onClose = 'Salir();';

$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga(); parent.menu.reset();">' . "\n";

cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora,jquery');

echo <<<EOT

{$modulo->inicio}

<table border="0">
	<tr valign="top">
		<td>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td id="DETALLES">
					</td>
				</tr>
				<tr>
					<td id="EXTRA" class="" style="padding-top: 4px;">
					</td>
				</tr>
			</table>
		</td>
		<td id="PAGOS" style="display:none;">
		</td>
		<td id="IMAGEN" class="grid_cuadro_inactivo" >
			<table border="0" width="100%">
				<tr>
					<td align="center" style="width:310px; height:250px; padding: 5px;">
						<img id="FOTO" name="foto" src="" onerror="sin_imagen(this)" width="290px">
					</td>
				</tr>
				<tr>
					<td align="center" class="grid_cuadro_activo">
						<hr>
						<span id="FOTO_PRECIO" style="font-size: 25px; color: #ff0000"></span>
						<hr>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

{$modulo->fin}

<script type="text/javascript">

var ultimo_item;
var xfecha  			= '{$xfecha}';
var xID_X_M_DOCUMENTOS	= null;
var xID_M_CAJAS 		= '{$xid_m_cajas}'; 
var xID_M_ALMACENES		= '{$xID_M_ALMACENES}';
var xNOMBRE_CAJA		= '{$xnombre_caja}';
var xNOMBRE_ALMACEN		= '{$xnombre_almacen}';

var xID_M_CLIENTES		= 'XXXX';
var xRAZON 	 			= '{$xRAZON}';
var xCODIGO1 			= '{$xCODIGO1}';
var xDIRECCION			= '{$xDIRECCION}';
var xTELEFONO1 			= '{$xTELEFONO1}';
var xCORREO1   			= '{$xCORREO1}';

var xDisponibles		= 0;
var xcredito_anterior 	= 0;

//----variables de formas de pago-----//
{$frm_campos}
{$frm_cuentas}
//------------------------------------//

var xTITULAR                = '';
var xCODIGO1                = '';
var xTELEFONOS              = '';
var xID_M_BANCOS            = '';
var xNUMERO                 = '';
var xCUENTA                 = '';
var xID_M_ANTICIPOS         = '';
var xID_M_CUENTAS_BANCARIAS = '';

var xmonto_factura      = 0;
var xmonto_pagado       = 0;
var xmonto_saldo        = 0;
var tope                = 1;
var xvalor              = 0;

var mask                = new Mask('#,###.##', 'number');

//*************************************//

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 100;
contenedor.alto        = 100;
contenedor.titulo      = ' ';
contenedor.x           = 100;
contenedor.y           = 100;
contenedor.botonCerrar = true;
contenedor.leyenda     = '  ';
contenedor.ayuda       = 1;
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.modalResult = true;
contenedor.onClose     = Cerrar_contenedor;

x_documentos              = new lista('cotizacion/x_documentos');
x_documentos.nombre       = 'x_documentos';
x_documentos.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
x_documentos.usaCookie    = false;
x_documentos.funcion      = t_x_documentos;
x_documentos.padre        = "DETALLES";
x_documentos.asyncload    = true;
x_documentos.buscador     = true;
x_documentos.onSelect     = ver_imagen;
x_documentos.onFocus      = x_documentosFocus;
x_documentos.filtro       = 'ID_X_M_DOCUMENTOS';

m_productos             = new lista("cotizacion/d_i_prod_almacen_facturables")
m_productos.nombre      = "m_productos";
m_productos.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_productos.funcion     = t_m_productos;
m_productos.buscador    = true;
m_productos.x           = 1;
m_productos.y           = 1;
m_productos.filtro      ='ID_M_ALMACENES';
m_productos.xfiltro     = xID_M_ALMACENES;
m_productos.modal       = true;
m_productos.botonCerrar = true;
m_productos.enter       = 2;

m_servicios             = new lista("cotizacion/m_servicios")
m_servicios.nombre      = "m_servicios";
m_servicios.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_servicios.funcion     = t_m_servicios;
m_servicios.buscador    = true;
m_servicios.x           = 1;
m_servicios.y           = 1;
m_servicios.modal       = true;
m_servicios.botonCerrar = true;
m_servicios.enter       = 1;

m_clientes             = new lista("maestros/m_clientes")
m_clientes.nombre      = "m_clientes";
m_clientes.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_clientes.funcion     = t_m_clientes;
m_clientes.buscador    = true;
m_clientes.modal       = true;
m_clientes.botonCerrar = true;
m_clientes.enter       = 1;

f_edicion = new formulario2('cotizacion/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'EXTRA';
f_edicion.tipo         = 'formulario';

f_edicion2              = new formulario2('cotizacion/f_edicion2');
f_edicion2.nombre       = 'f_edicion2';
f_edicion2.funcion      = t_f_edicion2;
f_edicion2.padre        = 'contenedor_cuadro';
f_edicion2.tipo         = 'formulario';
f_edicion2.onExit       = function(){ t_f_edicion2('',_f12);};

f_servicios_xxxx              = new formulario2('cotizacion/f_servicios_xxxx');
f_servicios_xxxx.nombre       = 'f_servicios_xxxx';
f_servicios_xxxx.funcion      = t_f_servicios_xxxx;
f_servicios_xxxx.padre        = 'contenedor_cuadro';
f_servicios_xxxx.tipo         = 'formulario';
f_servicios_xxxx.onExit       = function(){ f_servicios_xxxx('',_f12);};

f_cierre               = new formulario2('cotizacion/f_cierre');
f_cierre.nombre        = 'f_cierre';
f_cierre.funcion       = t_f_cierre;
f_cierre.padre         = 'contenedor_cuadro';
f_cierre.tipo          = 'formulario';

var resp = iniciar();
if(!resp)
{
	Salir();
}
else
{
	inicio();
}

function t_x_documentos(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
			m_productos.mostrar();
			m_productos.setFocus();
		break;

		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_DOCUMENTOS');
			if(!registro){
				x_documentos.setFocus();
				return;
			}
			xtipoprod     = registro[0]['TIPO_PRODUCTO'];
			xproducto     = registro[0]['ID_M_PRODUCTOS'];
			
			if(registro[0]['ID_M_PRODUCTOS']=='XXXX'){
				f_servicios_xxxx.buscar(registro[0]['ID_D_DOCUMENTOS']);
				mostrar_formulario(f_servicios_xxxx);				
			}else{
				f_edicion2.buscar(registro[0]['ID_D_DOCUMENTOS']);
				mostrar_formulario(f_edicion2);				
			}
		break;

		case _supr:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_DOCUMENTOS');
			if(!registro) return;

			if(confirm('{$t_eliminar_registro}'))
			{
				var url   = server_path + "herramientas/utiles/actualizar_registro.php";
				var param = "origen=cotizacion/x_documentos&procedimiento=ELIMINAR_ITEM&ID_D_DOCUMENTOS=" + registro[0]['ID_D_DOCUMENTOS'];
				var x     = enviar(url,param,'POST');
			}
			x_documentos.buscar('*');
		break;

		case _esc:
			cancelaTecla(evt);
			f_edicion.setFocus(); 
		break;

		case _f1:
		case _f2:
			cancelaTecla(evt);
		break;

		case _f3:
			cancelaTecla(evt);
			m_clientes.mostrar();
			m_clientes.setFocus();
		break;

		case _f4:
		case _f5:
			cancelaTecla(evt);
		break;

		case _f6:
			cancelaTecla(evt);
		break;

		case _f7:
			cancelaTecla(evt);
			m_servicios.buscar('*');
			m_servicios.mostrar();
			m_servicios.setFocus();
		break;
		case _f8:
			cancelaTecla(evt);
		break;
		case _f9:
			cancelaTecla(evt);
			var anular = confirm('{$t_documento_anular}');
			if(!anular){ 
				f_edicion.setFocus();
				return;				
			}
			var url = server_path + 'herramientas/utiles/actualizar_registro.php';
			var param =  "origen=cotizacion/x_documentos&procedimiento=ELIMINAR_DETALLE_DOCUMENTO&ID_X_M_DOCUMENTOS=" + xID_X_M_DOCUMENTOS;
			var xml = enviar(url,param,'POST');
			var resp = controlError(xml);
			if(!resp) return;
			
			var url = server_path + 'herramientas/utiles/actualizar_registro.php';
			var param =  "origen=cotizacion/x_documentos&procedimiento=ELIMINAR_DOCUMENTO&ID_X_M_DOCUMENTOS=" + xID_X_M_DOCUMENTOS;
			var xml = enviar(url,param,'POST');
			var resp = controlError(xml);
			if(!resp) return;
			inicio(0);			
		break;
		case _f10:
			cancelaTecla(evt);
			if(x_documentos.rows.length>0){
				Refrescar();
				f_cierre.limpiar();
				f_cierre.buscar(xID_X_M_DOCUMENTOS);
				mostrar_formulario(f_cierre);					
			}else{
				alert('{$t_documento_noregistro}');
				return;
			}
			break;
		case _f11:
		case _f12:
			cancelaTecla(evt);
		break;
	}
}

function t_m_productos(objeto, tecla, xml, e){
	var evt = window.event || e;
	switch (tecla){
		case _insert:
			cancelaTecla(evt);
		break;
		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_PRODUCTOS');
			if(!registro){
				m_productos.setFocus();
				return;
			}
			if(unformat(registro[0]['DISPONIBLES'])<=0){
				alert('No se permite facturar productos sin existencia');
				return;
			}
			
			m_productos.ocultar();
			f_edicion2.limpiar();
			xproducto     	= registro[0]['ID_M_PRODUCTOS'];
			xpresentacion 	= registro[0]['PRESENTACION'];
			xid_m_lineas 	= registro[0]['ID_M_LINEAS'];
			xDisponibles  	= unformat(registro[0]['DISPONIBLES']);
			
			f_edicion2.setValue('ID_M_PRODUCTOS' , xproducto);
			f_edicion2.setValue('ID_M_ALMACENES' , registro[0]['ID_M_ALMACENES']);
			f_edicion2.setValue('DESCRIPCION'    , registro[0]['DESCRIPCION']);
			f_edicion2.setValue('ID_D_PRODUCTOS' , registro[0]['ID_D_PRODUCTOS']);
			f_edicion2.setValue('ID_I_PROD_ALMA' , registro[0]['ID_I_PROD_ALMA']);
			f_edicion2.setValue('IMPUESTO'       , registro[0]['IMPUESTO']);
			f_edicion2.setValue('ID_M_IMPUESTOS' , registro[0]['ID_M_IMPUESTOS']);
			f_edicion2.setValue('NOMBRE_IMPUESTO', registro[0]['NOMBRE_IMPUESTO']);
			f_edicion2.setValue('COSTO'          , registro[0]['COSTO']);
			f_edicion2.setValue('COSTO_PROMEDIO' , registro[0]['COSTO_PROMEDIO']);
			f_edicion2.setValue('TIPO_PRODUCTO'  , registro[0]['TIPO']);
			f_edicion2.setValue('ID_D_I_PROD_ALMA', registro[0]['ID_D_I_PROD_ALMA']);
			f_edicion2.setValue('CLASE_PRODUCTO' , registro[0]['TIPO']);
			f_edicion2.setValue('COMENTARIOS'    , registro[0]['PREPARACION']);
			
			// Evaluar solucion para la oferta Benjamin
			// Debería evaluarse a la luz de las politicas del cliente
			// Hay campos de Oferta Cantidad y Oferta Desde - Hasta
			
			f_edicion2.setValue('PRECIO'    	 , registro[0]['PRECIO1']);
			f_edicion2.setValue('PRESENTACION'   , xpresentacion);
			f_edicion2.setValue('xbusca'         , '-1');
			f_edicion2.setValue('CANTIDAD'       , 1);
			f_edicion2.setValue('ID_X_M_DOCUMENTOS', xID_X_M_DOCUMENTOS);
			f_edicion2.setValue('TIPO'           , 'FAC');
			xtipoprod ='P';
				  
			f_edicion2.activarCampo('CANTIDAD');
			mostrar_formulario(f_edicion2);
		break;

		case _supr:
			cancelaTecla(evt);
		break;		
		case _esc:
			m_productos.ocultar();
			f_edicion.setFocus();
		break;
		case _f1:
		case _f2:
		case _f3:
		case _f4:
		case _f5:
		case _f6:
		case _f7:
		case _f8:
		case _f9:
		case _f10:
		case _f11:
		case _f12:
			cancelaTecla(evt);
		break;
	}
}

function t_m_servicios(objeto, tecla, xml,e){
	var evt = window.event || e;
	switch (tecla){
		case _insert:
			cancelaTecla(evt);
		break;
		
		case _enter:
			cancelaTecla(evt);
			var registro   = valida_xml(xml,'ID_M_SERVICIOS');
			if(!registro){
				m_servicios.setFocus();
				return;
			}
			m_servicios.ocultar();
			xproducto      = registro[0]['ID_M_SERVICIOS'];
			xtipoprod      = 'S';
			if(registro[0]['ID_M_SERVICIOS']=='XXXX'){
				f_servicios_xxxx.limpiar();
				f_servicios_xxxx.setValue('ID_M_PRODUCTOS' , registro[0]['ID_M_SERVICIOS']);
				f_servicios_xxxx.setValue('DESCRIPCION'    , registro[0]['DESCRIPCION']);
				f_servicios_xxxx.setValue('ID_I_PROD_ALMA' , registro[0]['ID_I_PROD_ALMA']);
				f_servicios_xxxx.setValue('IMPUESTO'       , registro[0]['IMPUESTO']);
				f_servicios_xxxx.setValue('ID_M_IMPUESTOS' , registro[0]['ID_M_IMPUESTOS']);
				f_servicios_xxxx.setValue('NOMBRE_IMPUESTO', registro[0]['NOMBRE_IMPUESTO']);
				f_servicios_xxxx.setValue('COSTO'          , registro[0]['COSTO']);
				f_servicios_xxxx.setValue('COSTO_PROMEDIO' , registro[0]['COSTO_PROMEDIO']);
				f_servicios_xxxx.setValue('COMISION'       , registro[0]['MONTO_COMISION1']);
				f_servicios_xxxx.setValue('TIPO_PRODUCTO'  , registro[0]['TIPO']);
				f_servicios_xxxx.setValue('CLASE_PRODUCTO' , registro[0]['TIPO']);
				f_servicios_xxxx.setValue('COMENTARIOS'    , registro[0]['PREPARACION']);
				f_servicios_xxxx.setValue('PRECIO'			, registro[0]['PRECIO1'] );

				f_servicios_xxxx.setValue('xbusca'         , '-1');
				f_servicios_xxxx.setValue('ID_X_M_DOCUMENTOS', xID_X_M_DOCUMENTOS);
				f_servicios_xxxx.setValue('TIPO'             , 'FAC');
				f_servicios_xxxx.setValue('CANTIDAD'         , 1);
				f_servicios_xxxx.setValue('DESCUENTO'        , 0);
				mostrar_formulario(f_servicios_xxxx);		  
			}else{	  
				f_edicion2.limpiar();
				f_edicion2.setValue('ID_M_PRODUCTOS' , registro[0]['ID_M_SERVICIOS']);
				f_edicion2.setValue('DESCRIPCION'    , registro[0]['DESCRIPCION']);
				f_edicion2.setValue('IMPUESTO'       , registro[0]['IMPUESTO']);
				f_edicion2.setValue('ID_M_IMPUESTOS' , registro[0]['ID_M_IMPUESTOS']);
				f_edicion2.setValue('NOMBRE_IMPUESTO', registro[0]['NOMBRE_IMPUESTO']);
				f_edicion2.setValue('COSTO'          , registro[0]['COSTO']);
				f_edicion2.setValue('COSTO_PROMEDIO' , registro[0]['COSTO_PROMEDIO']);
				f_edicion2.setValue('COMISION'       , registro[0]['MONTO_COMISION1']);
				f_edicion2.setValue('TIPO_PRODUCTO'  , registro[0]['TIPO']);
				f_edicion2.setValue('CLASE_PRODUCTO' , registro[0]['TIPO']);
				f_edicion2.setValue('COMENTARIOS'    , registro[0]['PREPARACION']);

				f_edicion2.setValue('xbusca'         , '-1');
				f_edicion2.setValue('ID_X_M_DOCUMENTOS', xID_X_M_DOCUMENTOS);
				f_edicion2.setValue('TIPO'             , 'FAC');
				f_edicion2.setValue('CANTIDAD'         , 1);
				f_edicion2.setValue('DESCUENTO'        , 0);
				f_edicion2.setValue('PRECIO'			, registro[0]['PRECIO1'] );

				mostrar_formulario(f_edicion2);
			}
		break;
		case _supr:
			cancelaTecla(evt);
		break;
		
		case _esc:
			cancelaTecla(evt);
			m_servicios.ocultar();
			f_edicion.setFocus();
		break;
		case _f1:
		case _f2:
		case _f3:
		case _f4:
		case _f5:
		case _f6:
		case _f7:
		case _f8:
		case _f9:
		case _f10:
		case _f11:
		case _f12:
			cancelaTecla(evt);
		break;
	}
}

function t_m_clientes (objeto, tecla, xml,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _enter:
			var registro = valida_xml(xml,'ID_M_CLIENTES');
			if(!registro)return;
			m_clientes.ocultar();
			$('#FOTO').attr('src', server_path + 'imagenes/productos/0.jpg');
			xID_M_CLIENTES 	= registro[0]['ID_M_CLIENTES'];
			xRAZON 			= registro[0]['RAZON'];
			xCODIGO1 		= registro[0]['CODIGO1'];
			xDIRECCION 		= registro[0]['DIRECCION'];
			xTELEFONO1 		= registro[0]['TELEFONO1'];
		    xCORREO1		=  registro[0]['CORREO1'];
			abrirDocumento();
			 
		break;

		case _esc:
			cancelaTecla();
			if(xID_M_CLIENTES==null)
			{
				Salir();
				return;
			}
			m_clientes.ocultar();
			abrirDocumento();
			//x_documentos.setFocus();
		break;
  }
}

function t_f_servicios_xxxx(objeto, tecla, xml, e){
	var evt = window.event || e;
	switch (tecla){
		case _insert:
			cancelaTecla(evt);
		break;
		case _enter:
			cancelaTecla(evt);
		break;
		case _supr:
			cancelaTecla(evt);
		break;		
		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_servicios_xxxx);
			f_edicion.setFocus();
		break;

		case _f1:
		case _f2:
		case _f3:
		case _f4:
		case _f5:
		case _f6:
		case _f7:
		case _f8:
		case _f9:
		case _f10:
		case _f11:
			cancelaTecla(evt);
		break;
		case _f12:
			cancelaTecla(evt);
			var registro = f_servicios_xxxx.submit();
			if(registro){
				var indice = registro[0]['UNICO'];
				Refrescar();
				ocultar_formulario(f_servicios_xxxx);
				x_documentos.localiza('ID_M_PRODUCTOS',xproducto);
				x_documentos.setFocus();
			}
		break;
	}
}

function t_f_edicion(elemento, tecla,e){
	var evt = window.event || e;
	switch (tecla){
		case _enter:
			cancelaTecla(evt);
			
			if(elemento.id=='CODIGO1' && f_edicion.getValue("CODIGO1")=='') {
				f_edicion.setFocus();
				return;
			}
			
			if(elemento.id=='CODIGO1' && f_edicion.getValue("CODIGO1")!=''){
				var xbusca = f_edicion.getValue("CODIGO1");
				
				buscar_producto(xbusca, function(registro){
	
					if(!registro) {
						alert('Producto no encontrado');
						f_edicion.setFocus();
						return;
					}
					
					f_edicion.limpiar();

					for (var campo in registro[0]){
						f_edicion.setValue(campo, registro[0][campo]);
					}

					f_edicion.setValue('NETO'            , registro[0]['NETO1']);
					f_edicion.setValue('PRECIO'          , registro[0]['PRECIO1']);

					f_edicion.setValue('CANTIDAD'         , 1);
					f_edicion.setValue('COSTO'            , registro[0]['COSTO']);
					f_edicion.setValue('COSTO_PROMEDIO'   , registro[0]['COSTO_PROMEDIO']);
					f_edicion.setValue('ID_X_M_DOCUMENTOS', xID_X_M_DOCUMENTOS);
					f_edicion.setValue('TIPO'             , 'FAC');
					f_edicion.setValue('xbusca'           , '-1');
					f_edicion.setValue('CODIGO1'           , xbusca);

					$("#frm_f_edicion #CANTIDAD").focus();
					$("#frm_f_edicion #CANTIDAD").select();
				});
				
				
			}
			
			if(elemento.id=='CANTIDAD' && f_edicion.getValue("DESCRIPCION")!=''){
				Guardar_item(f_edicion, function(){
					f_edicion.limpiar();
					f_edicion.setFocus();
				});
			}
		break;
		
		case _esc:
			cancelaTecla(evt);
			Salir();
		break;
		case 40: // 
			cancelaTecla(evt);
			x_documentos.localiza('ID_D_DOCUMENTOS', ultimo_item);
			x_documentos.setFocus();
		break;
		case 41: // flecha abajo
			cancelaTecla(evt);
			x_documentos.localiza('ID_D_DOCUMENTOS', ultimo_item);
			x_documentos.setFocus();
		break;
		case _insert:
		case _f1:
		case _f2:
		case _f3:
			cancelaTecla(evt);
			t_x_documentos('', _f3,    	x_documentos.elementoXml());
		break;		
		case _f4:
		case _f5:
		case _f6:
		case _f7:
		case _f8:
		break;
		case _f9:
			cancelaTecla(evt);
			t_x_documentos('', _f9,    	x_documentos.elementoXml())		
		break;
		case _f10:
			cancelaTecla(evt);
			t_x_documentos('', _f10);			
		break;
		case _f11:
		break;
		case _f12:
			cancelaTecla(evt);
			Guardar_item(f_edicion);
		break;
	}
}

function t_f_edicion2(objeto, tecla, xml, e){
	var evt = window.event || e;
	switch (tecla){
		case _insert:
			cancelaTecla(evt);
		break;
		case _enter:
			cancelaTecla(evt);
		break;
		case _supr:
			cancelaTecla(evt);
		break;		
		case _esc: 
			cancelaTecla(evt);
			ocultar_formulario(f_edicion2);
			f_edicion.setFocus();
		break;

		case _f1:
		case _f2:
		case _f3:
		case _f4:
		case _f5:
		case _f6:
		case _f7:
		case _f8:
		case _f9:
		case _f10:
		case _f11:
			cancelaTecla(evt);
		break;

		case _f12:
			cancelaTecla(evt);
			var xdescuento = unformat(f_edicion2.getValue('DESCUENTO'));
			var ximpuesto  = unformat(f_edicion2.getValue('IMPUESTO'));
			var xprecio    = unformat(f_edicion2.getValue('PRECIO'));
			var xcantidad  = unformat(f_edicion2.getValue('CANTIDAD'));
			var xcosto     = unformat(f_edicion2.getValue('COSTO_PROMEDIO'));

			var url     = server_path + "herramientas/genera_xml/genera_xml.php";
			var param 	= "tabla=V_D_I_PROD_ALMA_FACTURABLES&campos=DISPONIBLES&busca=ID_D_I_PROD_ALMA&operador==&xbusca="+ f_edicion2.getValue('ID_D_I_PROD_ALMA');
			var x       = enviar(url,param,'POST');
						
			var xreg  	= valida_xml(x,'DISPONIBLES');
			xTIPO_PRODUCTO = f_edicion2.getValue('TIPO_PRODUCTO');
			if(xTIPO_PRODUCTO!='S'){
				xDisponibles  	= unformat(xreg[0]['DISPONIBLES']);
			
				if((xDisponibles<xcantidad) && (xtipoprod =='P')){
					alert('La cantidad ingresada '+ xcantidad +' es mayor a cantidad disponible ' + xDisponibles);
					return false;	
				}
			}	
			if((xdescuento>0) && (xtipoprod =='P')){
				xdescuento        = (Math.abs(xdescuento) / 100) + 1;
				ximpuesto         = (ximpuesto / 100) + 1;
				var xcosto_iva    = xcosto;// * ximpuesto;
				var xprecio_des   = xprecio / xdescuento;
				if(xprecio_des < xcosto_iva){
					alert('Descuento No permitido');
					return;
				}
			}
			var registro = f_edicion2.submit();
			if(registro){
				var indice = registro[0]['UNICO'];
				Refrescar();
				ocultar_formulario(f_edicion2);
				x_documentos.localiza("ID_M_PRODUCTOS",xproducto);
				f_edicion.setFocus();
			}
		 break;
	}
}

function t_f_cierre(objeto, tecla, xml,e){
	var evt = window.event || e;
	switch (tecla){
		case _insert:
			cancelaTecla(evt);
		break;
		case _enter:
			cancelaTecla(evt);
		break;
		case _supr:
			cancelaTecla(evt);
		break;
		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_cierre,x_documentos);
		break;
		case _f1:
		case _f2:
		case _f3:
		case _f4:
		case _f5:
		case _f6:
		case _f7:
		case _f8:
		case _f9:
		case _f10:
		case _f11:
			cancelaTecla(evt);
		break;
		case _f12:
			cancelaTecla(evt);
			var xtipo  = f_cierre.getValue('SRI_TIPO');
			var xcodigo1  = f_cierre.getValue('CODIGO1');
			if(xtipo=='07'){
				//CONSUMIDOR FINAL
				if(xcodigo1!='9999999999999'){
					alert('Ruc invalido para tipo de cliente CONSUMIDOR FINAL');
					f_cierre.setFocus();
					return;
				}
				if(xmonto_factura>=200){
					alert('El monto de la factura ' + xmonto_factura + ' supera los 200$ permitido para este tipo de cliente');
					f_cierre.setFocus();
					return;
				}
			}
			if(xtipo=='04'){
				//RUC
				if(xcodigo1=='9999999999999'){
					alert('Ruc reservado para tipo de liente CONSUMIDOR FINAL');
					f_cierre.setFocus();
					return;				   
				}
				if(xcodigo1.length!=13){
					alert('la RUC debe contener 13 digitos');
					f_cierre.setFocus();
					return;
				}
			}
			if(xtipo=='05'){
				//CI
				if(xcodigo1.length!=10){
					alert('la Cedula debe contener 10 digitos');
					f_cierre.setFocus();
					return;
				}
			}
			var registro = f_cierre.submit();
			if(registro){
				var url    = server_path + 'herramientas/utiles/actualizar_registro.php';
				var params = 'tabla=X_M_DOCUMENTOS&busca=ID_X_M_DOCUMENTOS&xbusca=' + xID_X_M_DOCUMENTOS + '&c_ESTATUS_CSS=C&c_ID_M_CAJAS_CSS='+ xID_M_CAJAS;
				var x      = enviar(url, params, 'POST');
				//var resp = controlError(x);
				//if(!resp) return;

				var url   = server_path + 'herramientas/genera_xml/genera_xml.php';
				var param = "tabla=M_DOCUMENTOS&campos=ID_M_DOCUMENTOS&operador==&busca=ID_X_M_DOCUMENTOS&xbusca="+ xID_X_M_DOCUMENTOS;
				var xml     = enviar(url,param,'POST');
				var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
				if(!registro){
					alert('Error al buscar documento');
					return false;					
				}
				xID_M_DOCUMENTOS = registro[0]['ID_M_DOCUMENTOS'];
				ocultar_formulario(f_cierre);
				//Imprimir();
				inicio(0);
			}
		break;
	}
}


function x_documentosFocus()
{
       mostrarLeyenda(0)
}

function mostrar_formulario(xformulario)
{
	contenedor.setTitle(xformulario.titulo);
	contenedor.setWidth(xformulario.ancho);
	contenedor.setHeight(xformulario.alto);
	centrarObj(contenedor.contenedor);
	contenedor.setLegend(xformulario.leyenda);
	contenedor.mostrar();
	xformulario.mostrar();
	f_activo = xformulario;
	window.setTimeout("f_activo.setFocus()", 1);
}

function ocultar_formulario(xformulario,xobjecto_destino)
{
	contenedor.ocultar();
	xformulario.ocultar();
	if(xobjecto_destino) xobjecto_destino.setFocus();
}

function Cerrar_contenedor()
{
	f_activo.ocultar();
	contenedor.ocultar();
}

function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_edicion2':
			t_f_edicion2('',_f12);
		break;		

		case 'f_servicios_xxxx':
			t_f_servicios_xxxx('',_f12);
		break;		
		
		case 'f_cierre':
			t_f_cierre('',_f12);
		break;

		case 'f_cierre':
			t_f_cierre('',_f12);
		break;
		}
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_edicion2':
			t_f_edicion2('',_esc);
		break;
		
		case 'f_servicios_xxxx':
			t_f_servicios_xxxx('',_esc);
		break;		

		case 'f_cierre':
			t_f_cierre('',_esc);
		break;	
    }
}




function iniciar()
{
	console.clear();
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);
	
	m_productos.inicializa();
	centrarObj(m_productos.contenedor);	

	m_servicios.inicializa(false);
	centrarObj(m_servicios.contenedor);	

	m_clientes.inicializa(true);
	centrarObj(m_clientes.contenedor);
	
	x_documentos.inicializa(false);
	x_documentos.mostrar();

	f_edicion.inicializa(false);
	f_edicion.mostrar();
	f_edicion2.inicializa(false);
	f_servicios_xxxx.inicializa(false);
	f_cierre.inicializa(false);
	
	document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	addEvent(X_DOCUMENTOS_INS,     	"click",   function() { t_x_documentos('', _insert,	x_documentos.elementoXml()) } );
	addEvent(X_DOCUMENTOS_ENTER,	"click",   function() { t_x_documentos('', _enter, 	x_documentos.elementoXml()) } );
	addEvent(X_DOCUMENTOS_F3,      	"click",   function() { t_x_documentos('', _f3,    	x_documentos.elementoXml()) } );
	addEvent(X_DOCUMENTOS_F7,      	"click",   function() { t_x_documentos('', _f7,    	x_documentos.elementoXml()) } );
	addEvent(X_DOCUMENTOS_SUP,    	"click",   function() { t_x_documentos('', _supr,  	x_documentos.elementoXml()) } );
	addEvent(X_DOCUMENTOS_F8,     	"click",   function() { t_x_documentos('', _f8,    	x_documentos.elementoXml()) } );
	addEvent(X_DOCUMENTOS_F9,     	"click",   function() { t_x_documentos('', _f9,    	x_documentos.elementoXml()) } );
	addEvent(X_DOCUMENTOS_F10,     	"click",   function() { t_x_documentos('', _f10,   	x_documentos.elementoXml()) } );
	addEvent(X_DOCUMENTOS_ESC,     	"click",   function() { t_x_documentos('', _esc,   	x_documentos.elementoXml()) } );

	addEvent(m_productos_ENTER, 	"click",   function() { t_m_productos('', _enter, 	m_productos.elementoXml()) } );
	addEvent(m_productos_ESC  , 	"click",   function() { t_m_productos('', _esc,   	m_productos.elementoXml()) } );
	
	addEvent(m_servicios_LEYENDA5, 	"click",   function() { t_m_servicios('', _enter, 	m_servicios.elementoXml()) } );
	addEvent(m_servicios_LEYENDA6, 	"click",   function() { t_m_servicios('', _esc,   	m_servicios.elementoXml()) } );

	addEvent(m_clientes_ENTER, 		"click",   function() { t_m_clientes('', _enter, 	m_clientes.elementoXml()) } );
	addEvent(m_clientes_ESC, 		"click",   function() { t_m_clientes('', _esc,   	m_clientes.elementoXml()) } );
	
	return true;

}

function inicio()
{
	x_documentos.limpiar();
			
	limpiarElementos("ENCABEZADO,PIE");
	
	m_clientes.mostrar();
	setTimeout(function(){ 
		m_clientes.setFocus();
	}, 300);

	
}


function buscar_producto(xbusca, callback){

	if(!xbusca) return;
	
	//BUSCO EL PRODUCTO EN LA LISTA DE INVENTARIO POR CODIGO1
	var url    = server_path + 'herramientas/genera_xml/genera_xml.php';
	var params = 'tabla=V_D_I_PROD_ALMA_FACTURABLES&campos=*&operador==&busca=CODIGO1&xbusca=' + xbusca + '&filtro=ID_M_ALMACENES&xfiltro='+xID_M_ALMACENES;
	var xml    = enviar(url, params, 'POST');
	var registro = valida_xml(xml, 'ID_M_PRODUCTOS');
	
	
	//BUSCO EL PRODUCTO EN LA LISTA DE INVENTARIO POR CODIGO4
	if(!registro){
		var url    = server_path + 'herramientas/genera_xml/genera_xml.php';
		var params = 'tabla=V_D_I_PROD_ALMA_FACTURABLES&campos=*&operador==&busca=CODIGO4&xbusca=' + xbusca + '&filtro=ID_M_ALMACENES&xfiltro='+xID_M_ALMACENES;
		var xml    = enviar(url, params, 'POST');
		var registro = valida_xml(xml,'ID_M_PRODUCTOS');
	}	

	//BUSCO EL PRODUCTO EN LA LISTA DE INVENTARIO POR DESCRIPCION CON LIKE
	if(!registro){
		var url    = server_path + 'herramientas/genera_xml/genera_xml.php';
		var params = 'tabla=V_D_I_PROD_ALMA_FACTURABLES&campos=*&busca=DESCRIPCION&xbusca=' + xbusca + '&filtro=ID_M_ALMACENES&xfiltro='+xID_M_ALMACENES;
		var xml    = enviar(url, params, 'POST');
		var registro = valida_xml(xml,'ID_M_PRODUCTOS');
	}
	
	if(callback) {
		setTimeout( function(){ callback(registro); }, 100 );
	}	
}

function Guardar_item(xformulario){
	if(xformulario.getValue("ID_I_PROD_ALMA")==''){
		return false;
	}
	var registro = xformulario.submit();
	if(registro){
		ultimo_item  			= registro[0]['ID_D_DOCUMENTOS'];
		var indice 				= registro[0]['UNICO'];
		xid_i_prod_alma			= xformulario.getValue("ID_I_PROD_ALMA");
		Refrescar();
		xformulario.limpiar();
		xformulario.setFocus();
		return true;
	}
	return false;
}

function Refrescar(){
	x_documentos.buscar('*');
    var url   	= server_path + "herramientas/genera_xml/genera_xml.php";
    var param 	= 'tabla=X_M_DOCUMENTOS&campos=MONTO_BRUTO,MONTO_DESCUENTO,MONTO_IMPUESTO,NETO&busca=ID_X_M_DOCUMENTOS&xbusca=' +xID_X_M_DOCUMENTOS +'&operador==';
	var xml     = enviar(url,param,'POST');
	var registro = valida_xml(xml);
	actualizaHTML(xml, 'PIE', 'totales');
}

function sin_imagen(img)
{
	img.src = server_path + 'imagenes/productos/0.jpg';
}

function ver_imagen()
{
	
	$("#IMAGEN").show();

	var xml = x_documentos.elementoXml();
	var registro = XML2Array(xml);
	if(!registro[0]['ID_M_PRODUCTOS']) return false;
	
	
	if(!registro[0]) return false;
	var rand_no = Math.ceil(100000000*Math.random())

	var url = server_path + 'imagenes/productos/'+ registro[0]['ID_M_PRODUCTOS'] +'.jpg?rndid='+rand_no ;
	$("#FOTO").attr('src', url);
	$("#FOTO_PRECIO").html( registro[0]['PRECIO'] );
	
}

function abrirDocumento(){
	//Buscar si existe documento para el cliente
	var url   = server_path + 'herramientas/genera_xml/genera_xml.php';
	var param = 'origen=cotizacion/x_documentos&procedimiento=ABIERTAS&ID_M_CLIENTES=' + xID_M_CLIENTES;
	var xml_encabezado   = enviar(url, param, 'POST');

	var registro_encabezado = valida_xml(xml_encabezado, 'ID_X_M_DOCUMENTOS');

	// Si no hay documento abierto se crea
	if(!registro_encabezado) {
		var url = server_path + 'herramientas/utiles/actualizar_registro.php';
		var params  = '';
		params += 'tabla=X_M_DOCUMENTOS';
		params += '&c_TEMP_NSS=0';
		params += '&busca=ID_X_M_DOCUMENTOS';
		params += '&xbusca=-1';
		params += '&c_TIPO_CSS=PRE';
		params += '&c_ID_M_CLIENTES_CSS='+xID_M_CLIENTES;
		params += '&c_NOMBRES_CSS='+ escape(xRAZON);
		params += '&c_CODIGO1_CSS='+ xCODIGO1;
		params += '&c_DIRECCION_CSS='+escape(xDIRECCION);
		params += '&c_TELEFONO_CSS=' + xTELEFONO1;
		params += '&c_ID_M_ALMACENES_CSS='+ xID_M_ALMACENES;
		params += '&c_ID_M_CAJAS_CSS='+ xID_M_CAJAS;
		params += '&c_CORREO_CSS=' + xCORREO1;

		var xml_encabezado = enviar(url, params, 'POST');
		var registro_encabezado = valida_xml(xml_encabezado,'ID_X_M_DOCUMENTOS');
		if(!registro_encabezado) {
			console.error('Error al crear el documento');
		return;	
	}	

	actualizaHTML(xml_encabezado,'ENCABEZADODOC_');
	x_documentos.limpiar();
	} else {
		//Si existe el documento se muestra
		actualizaHTML(xml_encabezado, 'ENCABEZADODOC_');
	}
	
	actualizaTag('ENCABEZADOID_M_CLIENTES',xID_M_CLIENTES);
	actualizaTag('ENCABEZADOCODIGO1',xCODIGO1);
	actualizaTag('ENCABEZADORAZON',xRAZON);
	actualizaTag('ENCABEZADONOMBRE_CAJA',xNOMBRE_CAJA);
	actualizaTag('ENCABEZADONOMBRE_ALMACEN',xNOMBRE_ALMACEN);

	xID_X_M_DOCUMENTOS = registro_encabezado[0]['ID_X_M_DOCUMENTOS'];
	x_documentos.xfiltro = xID_X_M_DOCUMENTOS;
	x_documentos.setFocus();

	Refrescar();

	setTimeout(function(){ 
		f_edicion.setFocus();
	}, 300);
	
}

function Salir(){

	$.confirm({
		title: 'Realmente desea salir?',
		content: 'Presione ENTER si desea salir, ESC si desea regresar..',
		closeIcon: true,
		theme: 'my-theme',
		backgroundDismiss: true,
		useBootstrap: false,
		boxWidth: '30%',
		escapeKey: 'cancelar',
		buttons: {
			aceptar: {
				text: 'Aceptar',
				keys: ['enter'],
				action: function(){
					location.href = server_path + 'main/inicio.php';
				}
			},
			cancelar: {
				text: 'Cancelar',
				action: function(){
					f_edicion.setFocus();
				}
			}	
		}
	});
}


</script>


<style>


</style>

</body>
</html>

EOT;

?>