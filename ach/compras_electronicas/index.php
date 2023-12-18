<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
$ventana         = getvar('ventana','modulo');
$id              = getvar('id');
$xcondicion_pago = strtoupper(getvar('condicion_pago','CRE'));
$db              = getsession('db');

$query = new sql();
$query->sql = "SELECT TIPO1 FROM CONFIGURACION ";
$query->ejecuta_query();
$query->next_record();
$xtipo_contri = $query->Record['TIPO1'];


$xID_M_ALMACENES = getsession('M_ESTACIONES_ID_M_ALMACENES_COMPRAS');
$xestatus   	 = 'C';
$xFecha_Documento= date("d/m/Y");
$my_ini          = new ini('modulo');

$xtitulo = $xcondicion_pago == 'CRE'? $my_ini->seccion('VENTANA','TITULO') : $my_ini->seccion('VENTANA','TITULO2');

encabezado($titulo);

$onClose = 'Salir();';
$modulo  = new class_modulo('modulo',$onClose);
echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora,jquery,wait');

echo <<<EOT
{$modulo->inicio}
<table class="contenido_modulo" border="0">
	<tr>
		<td id="GRUPO1"></td>
	</tr>
	<tr>
		<td id="GRUPO2"></td>
	</tr>
</table>
{$modulo->fin}
<script type="text/javascript">

var mask               = new Mask('#,###.##', 'number');
var td					  = null ;
var xDias                 = 0;   
var xtipo                 ='COM';
var xID_M_DOCUMENTOS      = 0;
var xID_M_ALMACENES       = '0011'; 
var xID_M_ALMACEN_ESTACION= '0011'; 
var xtipo_contri          = '{$xtipo_contri}';
var f_activo              = null;
var xproducto             = null;
var t1                    = null;
var xrango                = 0.5;//PREGUNTAR !!!1
var impresora          	  = new printer();
var xID_M_PROVEEDORES_PRODUCTO 	= null;
var xDESCRIPCION				= null;
var xID_D_DOCUMENTOS			= null;

var xSW							= null;

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 720;
contenedor.alto        = 580;
contenedor.titulo      = ' ';
contenedor.x           = 100;
contenedor.y           = 100;
contenedor.titulo      = 'XXXXXX';
contenedor.botonCerrar = true;
contenedor.leyenda     = '  ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.ayuda       = 100;
contenedor.onClose     = CerrarContenedor;

x_m_documentos             = new lista('compras_electronicas/x_m_documentos');
x_m_documentos.url         = server_path + 'herramientas/genera_xml/genera_xml.php';
x_m_documentos.nombre      = 'x_m_documentos';
x_m_documentos.funcion     = t_x_m_documentos;
x_m_documentos.padre       = "GRUPO1";
x_m_documentos.buscador    = true;
x_m_documentos.onSelect    = actualizaDetalles;
x_m_documentos.onFocus     = x_m_documentos_Focus;
x_m_documentos.noOcultarCombos=true;

x_documentos             = new lista('compras_electronicas/x_documento');
x_documentos.url         = server_path + 'herramientas/genera_xml/genera_xml.php';
x_documentos.nombre      = 'x_documentos';
x_documentos.funcion     = t_x_documentos;
x_documentos.padre       = "GRUPO2";
x_documentos.buscador    = true;
x_documentos.filtro      = 'ID_X_M_DOCUMENTOS';
x_documentos.onFocus     = x_documentos_Focus;
x_documentos.noOcultarCombos=true;

m_productos           = new lista("compras_electronicas/m_productos")
m_productos.nombre    = "m_productos";
m_productos.url       = server_path + "herramientas/genera_xml/genera_xml.php";
m_productos.funcion   = t_m_productos;
m_productos.usaCookie = false;
m_productos.buscador  = true;
m_productos.enter 	  = 1;
m_productos.x           = 400;
m_productos.y           = 50;

m_servicios           = new lista("compras_electronicas/m_servicios")
m_servicios.nombre    = "m_servicios";
m_servicios.url       = server_path + "herramientas/genera_xml/genera_xml.php";
m_servicios.funcion   = t_m_servicios;
m_servicios.usaCookie = false;
m_servicios.buscador  = true;
m_servicios.enter 	  = 1;
m_servicios.x           = 400;
m_servicios.y           = 50;
m_servicios.filtro      = 'ESTATUS';
m_servicios.xfiltro     = 'ACT';


f_comprobante 			  = new formulario2('compras_electronicas/f_comprobante');
f_comprobante.nombre       = 'f_comprobante';
f_comprobante.funcion      = t_f_comprobante;
f_comprobante.padre        = 'contenedor_cuadro';
f_comprobante.tipo         = 'formulario';
f_comprobante.enviarTodo   = true;

f_concepto 			  = new formulario2('compras_electronicas/f_concepto');
f_concepto.nombre       = 'f_concepto';
f_concepto.funcion      = t_f_concepto;
f_concepto.padre        = 'contenedor_cuadro';
f_concepto.tipo         = 'formulario';
f_concepto.enviarTodo   = true;

f_cierre 			  = new formulario2('compras_electronicas/f_cierre');
f_cierre.nombre       = 'f_cierre';
f_cierre.funcion      = t_f_cierre;
f_cierre.padre        = 'contenedor_cuadro';
f_cierre.tipo         = 'formulario';
f_cierre.enviarTodo   = true;

f_proveedores 			  = new formulario2('compras_electronicas/f_proveedores');
f_proveedores.nombre       = 'f_proveedores';
f_proveedores.funcion      = t_f_proveedores;
f_proveedores.padre        = 'contenedor_cuadro';
f_proveedores.tipo         = 'formulario';
f_proveedores.enviarTodo   = true;

m_retenciones             = new lista("compras_electronicas/m_retenciones")
m_retenciones.nombre      = "m_retenciones";
m_retenciones.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_retenciones.funcion     = t_m_retenciones;
m_retenciones.buscador    = true;
m_retenciones.x           = 1;
m_retenciones.y           = 1;
m_retenciones.modal       = true;
m_retenciones.botonCerrar = true;
m_retenciones.enter       = 0;
m_retenciones.filtro	  = 'ID_X_M_DOCUMENTOS';

f_retenciones          = new formulario2('compras_electronicas/f_retenciones');
f_retenciones.nombre   = 'f_retenciones';
f_retenciones.funcion  = t_f_retenciones;
f_retenciones.padre    = 'contenedor_cuadro';

function t_m_retenciones(objeto, tecla, xml){
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
			var xml = x_m_documentos.elementoXml();
			var registro = valida_xml(xml, 'ID_X_M_DOCUMENTOS');
			m_retenciones.ocultar();
			f_retenciones.limpiar();

			f_retenciones.setValue("NOMBRES",registro[0]['NOMBRES']);
			f_retenciones.setValue("CODIGO1",registro[0]['CODIGO1']);
			f_retenciones.setValue("CODIGO2",registro[0]['CODIGO2']);
			f_retenciones.setValue("DIRECCION",registro[0]['DIRECCION']);
			f_retenciones.setValue("TELEFONO",registro[0]['TELEFONO']);
			f_retenciones.setValue("FECHA_DOCUMENTO",registro[0]['FECHA_RECEPCION']);
			f_retenciones.setValue("ID_M_PROVEEDORES",registro[0]['ID_M_PROVEEDORES']);
			f_retenciones.setValue("NOMBRE_VENDEDOR",'');
			f_retenciones.setValue("ID_X_M_DOCUMENTOS",registro[0]['ID_X_M_DOCUMENTOS']);      

			var xmonto = unformat(registro[0]['NETO']);
			xmonto = parseFloat(xmonto.toFixed(2));	  
			f_retenciones.setValue("XNETO",mask.format(xmonto));

			f_retenciones.setValue("XSUBTOTAL", mask.format(registro[0]['MONTO_BRUTO']));
			f_retenciones.setValue("XIMPUESTO", mask.format(registro[0]['MONTO_IMPUESTO']));

			mostrar_formulario(f_retenciones);
		break;
		
		case _enter:
			cancelaTecla(evt);
		break;

		case _supr:
			cancelaTecla(evt);
			var registro = valida_xml(xml, 'ID_TMP_D_CXCCXP');
			if(!registro){
				m_retenciones.setFocus();
				return;
			} 

			var anular = confirm('Desea Eliminar la Retencion');
			if(anular){
				var url 	= server_path + 'herramientas/utiles/actualizar_registro.php';
				var param 	= 'origen=compras_electronicas/m_retenciones&procedimiento=ELIMINAR&ID_TMP_D_CXCCXP=' + registro[0]['ID_TMP_D_CXCCXP'];
				enviar(url,param,'GET');
			}

			m_retenciones.buscar('*');
			m_retenciones.setFocus();
		break;	
		
		case _esc:
			cancelaTecla(evt);
			m_retenciones.ocultar();
			x_m_documentos.setFocus();
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

function t_f_retenciones(objeto, tecla, xml){
	var evt = window.event || e;		
	switch (tecla)
	{
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
			ocultar_formulario(f_retenciones);
			m_retenciones.mostrar();
			m_retenciones.setFocus();
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
			var xnombre_concepto = f_retenciones.getValue("r_CAMPO1");
			f_retenciones.setValue("CAMPO2",xnombre_concepto.substring(0,59));
			var monto_ret=f_retenciones.getValue("NETO");
			if (monto_ret < 0){
				f_retenciones.setValue("NETO",0);
			}
			var registro = f_retenciones.submit();
			if(!registro) return;
			ocultar_formulario(f_retenciones);
			m_retenciones.buscar();
			m_retenciones.mostrar();
			m_retenciones.setFocus();
		break;	
	}
}

function t_f_proveedores(elemento, tecla,e){
	var evt = window.event || e;
	switch (tecla)
	{
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
			ocultar_formulario(f_proveedores);
			x_m_documentos.setFocus();
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
			var registro = f_proveedores.submit();
			if(!registro) return;
			ocultar_formulario(f_proveedores);
			x_m_documentos.actualizar(xID_M_DOCUMENTOS);
			x_m_documentos.setFocus();	
		break;
	}
}

function t_f_concepto(elemento, tecla,e){
	var evt = window.event || e;
	switch (tecla)
	{
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
			ocultar_formulario(f_concepto);
			x_documentos.setFocus();
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
			var registro = f_concepto.submit();	
			ocultar_formulario(f_concepto);
			x_documentos.actualizar(xID_D_DOCUMENTOS);
			x_documentos.setFocus();				
		break;
	}
}

function t_f_comprobante(elemento, tecla,e){
	var evt = window.event || e;
	switch (tecla)
	{
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
			ocultar_formulario(f_comprobante);
			x_m_documentos.setFocus();
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
			ocultar_formulario(f_concepto);
			var comprobante = f_comprobante.getValue('COMPROBANTE');
			if(comprobante!=''){
				alerta.mostrarMensaje('Realizando Consulta....' + comprobante );
				setTimeout(function(){		
					var url   = server_path + 'compras_electronicas/procesar.php';
					var param = 'comprobante=' +  comprobante;
						param +='&rnd=' + createRandomString(32);
						xPID = enviar(url,param,'POST');
						if(xPID.indexOf('Error->') > -1){
							alerta.mostrarMensaje(xPID);
							setTimeout(function(){
								alerta.ocultarMensaje();
								x_m_documentos.buscar(comprobante, '=');
								x_m_documentos.setFocus();															
								
							}, 5000);
						}else{
							alerta.ocultarMensaje();
							x_m_documentos.buscar(comprobante, '=');
							x_m_documentos.setFocus();															
						}														
				}, 1000);								
			}else{
				x_m_documentos.setFocus();								
			}		
		break;
	}
}

function t_x_m_documentos(objeto, tecla, xml,e){
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
			f_comprobante.limpiar();
			mostrar_formulario(f_comprobante);
		break;
		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml, 'ID_X_M_DOCUMENTOS');
			if(!registro) return;
			xID_M_DOCUMENTOS = registro[0]['ID_X_M_DOCUMENTOS'];
			x_documentos.setFocus();
		break;
		case _supr:
			cancelaTecla(evt);
			var registro = valida_xml(xml, 'ID_X_M_DOCUMENTOS');
			if(!registro) return;
			xID_M_DOCUMENTOS = registro[0]['ID_X_M_DOCUMENTOS'];

			var anular = confirm('{$t_documento_anular}');
			if(anular){
				var url = server_path + 'herramientas/utiles/actualizar_registro.php';
				var param =  "origen=compras_electronicas/x_m_documentos&procedimiento=ELIMINAR_DOCUMENTO&ID_X_M_DOCUMENTOS=" + xID_M_DOCUMENTOS;
				enviar(url,param,'GET');
				x_m_documentos.buscar('*');
				x_m_documentos.setFocus();
			}

		break;
		case _esc:
			cancelaTecla(evt);
			Salir();
		break;

		case _f1:
		case _f2:
		case _f3:
		case _f4:
		case _f5:
			cancelaTecla(evt);
		break;
		case _f6:
			cancelaTecla(evt);
			var registro = valida_xml(xml, 'ID_X_M_DOCUMENTOS');
			if(!registro) return;
			xID_M_DOCUMENTOS = registro[0]['ID_M_M_DOCUMENTOS'];
			f_proveedores.buscar(registro[0]['ID_M_PROVEEDORES']);
			mostrar_formulario(f_proveedores);
			
		break;
		case _f7:
		case _f8:
		case _f9:
			cancelaTecla(evt);
		break;
		case _f10:
			cancelaTecla(evt);
			var registro = valida_xml(xml, 'ID_X_M_DOCUMENTOS');
			if(!registro) return;
			if(registro[0]['VERIFICADO']==0){
				alert('Faltan datos al proveedor, complete para poder continuar.');
				f_proveedores.buscar(registro[0]['ID_M_PROVEEDORES']);				
				mostrar_formulario(f_proveedores);
				return;
			}
			
			xID_M_DOCUMENTOS = registro[0]['ID_X_M_DOCUMENTOS'];

			var url   = server_path + "herramientas/genera_xml/genera_xml.php";
			var param = "origen=compras_electronicas/x_m_documentos&procedimiento=BUSCAR_ERRORES&ID_X_M_DOCUMENTOS=" + registro[0]['ID_X_M_DOCUMENTOS'];

			var xml = enviar(url,param,'POST');
			var registro = valida_xml(xml,'CANTIDAD');
			if(!registro) return;
			var xerrores = unformat(registro[0]['CANTIDAD']) ;
			
			if(xerrores == 0){
				f_cierre.buscar(xID_M_DOCUMENTOS);
				mostrar_formulario(f_cierre);
				f_cierre.setValue('MONTO_DOCUMENTO',f_cierre.getValue('NETO'));
				//f_cierre.setValue('NETO',0);				
				f_cierre.setValue('ESTATUS','C');
				Calcular_vencimiento();
			} else {

				alert( 'Verifique los detalles de la compra. existen ' + registro[0]['CANTIDAD'] + ' items sin asociar a un producto o servicio');
				x_m_documentos.setFocus();
			}

		break;
		case _f11:
			cancelaTecla(evt);
			cancelaTecla(evt);
			var registro = valida_xml(xml, 'ID_X_M_DOCUMENTOS');
			if(!registro) return;   
			m_retenciones.xfiltro = registro[0]['ID_X_M_DOCUMENTOS'];
			m_retenciones.buscar();
			m_retenciones.mostrar();
			m_retenciones.setFocus();

		break;		
		case _f12:
			cancelaTecla(evt);
		break;
	}
}


function t_m_productos(objeto, tecla, xml,e){
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;
		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml, 'ID_M_PRODUCTOS');
			if(!registro) return;
			
			if(xSW==1){
				var resp = confirm('Asociar :' + xDESCRIPCION + ' con:\\n' + registro[0]['DESCRIPCION']);
				if(resp)
				{
					var url 	= server_path + "herramientas/utiles/actualizar_registro.php";
					var param 	= 'tabla=M_PROVEEDORES_PRODUCTO&c_ID_M_PRODUCTOS_CSS='+ registro[0]['ID_M_PRODUCTOS']+'&c_ID_M_ALMACENES_CSS='+ xID_M_ALMACENES +'&busca=ID_M_PROVEEDORES_PRODUCTO&xbusca='+ xID_M_PROVEEDORES_PRODUCTO;
					enviar(url,param,'GET');
					x_documentos.actualizar(xID_D_DOCUMENTOS);
					m_productos.ocultar();
					x_documentos.setFocus();
				}
				
			}else{
				var resp = confirm('Desea Asociar todos los productos sin codigo con: \\n'  + registro[0]['DESCRIPCION']);
				if(resp)
				{
					var url = server_path + "herramientas/genera_xml/genera_xml.php";
					var param =  "origen=compras_electronicas/x_m_documentos&procedimiento=ASOCIAR&ID_X_M_DOCUMENTOS=" + xID_M_DOCUMENTOS+'&ID_M_PRODUCTOS=' + registro[0]['ID_M_PRODUCTOS'];
					x = enviar(url,param,'POST');
					x_documentos.actualizar(xID_D_DOCUMENTOS);
					m_productos.ocultar();
					x_m_documentos.setFocus();
				}
			}			

		break;
		case _supr:
			cancelaTecla(evt);
		break;						
		case _esc:
			cancelaTecla(evt);
			m_productos.ocultar();
			x_documentos.setFocus();
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
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;
		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml, 'ID_M_PRODUCTOS');
			if(!registro) return;
			
			m_servicios.ocultar();
			f_concepto.xbusca= xID_M_PROVEEDORES_PRODUCTO;
			f_concepto.setValue('xbusca', xID_M_PROVEEDORES_PRODUCTO);
			f_concepto.setValue('DESCRIPCION', registro[0]['DESCRIPCION']);
			f_concepto.setValue('ID_M_PRODUCTOS', registro[0]['ID_M_PRODUCTOS']);
			
			mostrar_formulario(f_concepto);
			
		break;
		case _supr:
			cancelaTecla(evt);
		break;		
		case _esc:
			cancelaTecla(evt);
			m_servicios.ocultar();
			x_documentos.setFocus();
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


function t_f_cierre(elemento, tecla,e){
	var evt = window.event || e;
	switch (tecla)
	{
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
			ocultar_formulario(f_cierre);
			x_m_documentos.setFocus();
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
			alerta.mostrarMensaje('Cerrando Compra....');
			setTimeout(function(){
				var registro = f_cierre.submit();	
				alerta.ocultarMensaje();			
				ocultar_formulario(f_cierre);
				x_m_documentos.buscar();
				x_m_documentos.setFocus();				
			}, 1000);								
		break;
	}
}

function t_x_documentos(objeto, tecla, xml, e){
	var evt = window.event || e;
	switch (tecla)
	{
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
			x_m_documentos.setFocus();
		break;
		
		case _f1:
			cancelaTecla(evt);
		break;
		case _f2:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_PROVEEDORES_PRODUCTO');
			if(!registro) return;
			xSW = 1;
			xID_M_PROVEEDORES_PRODUCTO 	= registro[0]['ID_M_PROVEEDORES_PRODUCTO'];
			xDESCRIPCION			   	= registro[0]['DESCRIPCION'];
			xID_D_DOCUMENTOS			= registro[0]['ID_D_DOCUMENTOS'];
			m_productos.mostrar();
			m_productos.setFocus();
		break;
		case _f3:
		case _f4:
		case _f5:
		case _f6:
			cancelaTecla(evt);
		break;
		case _f7:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_PROVEEDORES_PRODUCTO');
			if(!registro) return;
			xSW = 1;
			xID_M_PROVEEDORES_PRODUCTO 	= registro[0]['ID_M_PROVEEDORES_PRODUCTO'];
			xDESCRIPCION			   	= registro[0]['DESCRIPCION'];
			xID_D_DOCUMENTOS			= registro[0]['ID_D_DOCUMENTOS'];
			
			f_concepto.buscar(xID_M_PROVEEDORES_PRODUCTO);			
			f_concepto.setValue('ID_M_PRODUCTOS', 'XXXX');					
			f_concepto.setValue('TIPO', 'S');
			f_concepto.setValue('DESCRIPCION', registro[0]['DESCRIPCION']);					
			mostrar_formulario(f_concepto);
			
		break;		
		case _f8:
		case _f9:
			cancelaTecla(evt);
		break;
		case _f10:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_PROVEEDORES_PRODUCTO');
			if(!registro) return;
			xSW = 99;
			m_productos.mostrar();
			m_productos.setFocus();			
		break;		
		case _f11:
		case _f12:
			cancelaTecla(evt);
		break;
	}
}

function mostrar_formulario(xformulario){
    f_activo=xformulario;
    contenedor.setTitle(xformulario.titulo);
    contenedor.setWidth(xformulario.ancho);
    contenedor.setHeight(xformulario.alto);
    centrarObj(contenedor.contenedor);
    contenedor.setLegend(xformulario.leyenda);
    contenedor.mostrar();
    xformulario.mostrar();
    setTimeout('f_activo.setFocus();',10);
}

function ocultar_formulario(xformulario){
    contenedor.ocultar();
    xformulario.ocultar();
    x_documentos.setFocus();
}


function Guardar_formulario(){
    switch (f_activo.nombre)
    {
		case 'f_cierre':
			t_f_cierre('',_f12);
		break;
		case 'f_concepto':
			t_f_concepto('',_f12);
		break;
		case 'f_proveedores':
			t_f_proveedores('',_f12);
		break;
		case 'f_comprobante':
			t_f_comprobante('',_f12);
		break;
    }
}

function CerrarContenedor(){
    switch (f_activo.nombre)
    {
		case 'f_cierre':
			f_cierre.ocultar();
			contenedor.ocultar();
			x_documentos.setFocus();
		break;
		case 'f_concepto':
			t_f_concepto('',_esc);
		break;
		case 'f_proveedores':
			t_f_proveedores('',_esc);
		break;
		case 'f_comprobante':
			t_f_comprobante('',_esc);
		break;			
    }	
}

function Cerrar_formulario(){
    switch (f_activo.nombre)
    {
		case 'f_cierre':
			t_f_cierre('',_esc);
		break;
		case 'f_concepto':
			t_f_concepto('',_esc);
		break;
		case 'f_proveedores':
			t_f_proveedores('',_esc);
		break;
		case 'f_comprobante':
			t_f_comprobante('',_esc);
		break;
    }
}

function Salir(){
	location.href = server_path + 'main/inicio.php';
}

function iniciar(){
	if(xID_M_ALMACENES=='')
	{
		alert('Estacion No Valida. No hay establecido el almacen de compras');
		return false;
	}

	contenedor.inicializa(false);
	centrarObj(contenedor.contenedor);
	

	x_documentos.inicializa(false);
	x_documentos.mostrar();

	x_m_documentos.inicializa(false);
	x_m_documentos.mostrar();
	
	m_productos.inicializa(false);
	m_servicios.inicializa(false);
	
	f_cierre.inicializa();
	f_proveedores.inicializa();
	f_concepto.inicializa();
	f_comprobante.inicializa();
	
	f_retenciones.inicializa(false);
	
	m_retenciones.inicializa(false);
	centrarObj(m_retenciones.contenedor);
	
	
	var xHTML ='&nbsp;<button id="btn_usar_monto" style="HEIGHT: 20px;">Usar Monto</button>';
	$('#celda_f_retenciones_XSUBTOTAL td:nth-child(2)').append(xHTML);
	$('#celda_f_retenciones_XIMPUESTO td:nth-child(2)').append(xHTML);
  
	$('body').on('click', '#btn_usar_monto', function() 
	{
		f_retenciones.setValue('SUB_TOTAL', $(this).prev('input').val());
		f_retenciones.setFocus();
	});
	

	addEvent(frm_f_cierre.FECHA_RECEPCION  , 'blur',   Calcular_vencimiento);
	addEvent(frm_f_cierre.FECHA_RECEPCION  , 'change', Calcular_vencimiento);
	addEvent(frm_f_cierre.FECHA_RECEPCION  , 'keyup',  Calcular_vencimiento);

	addEvent(frm_f_cierre.CREDITO  , 'blur',   Calcular_vencimiento);
	addEvent(frm_f_cierre.CREDITO  , 'change', Calcular_vencimiento);
	addEvent(frm_f_cierre.CREDITO  , 'keyup',         Calcular_vencimiento);
	 
	addEvent(X_M_DOCUMENTOS_INSERT,    "click",   function() { t_x_m_documentos('', _insert, x_m_documentos.elementoXml()) } )
	addEvent(X_M_DOCUMENTOS_ENTER,    "click",   function() { t_x_m_documentos('', _enter, x_m_documentos.elementoXml()) } )
	addEvent(X_M_DOCUMENTOS_SUPR,    "click",   function() { t_x_m_documentos('', _supr,   x_m_documentos.elementoXml()) } )
	addEvent(X_M_DOCUMENTOS_ESC,    "click",   function() { t_x_m_documentos('', _esc,    x_m_documentos.elementoXml()) } )
	addEvent(X_M_DOCUMENTOS_F6,     "click",   function() { t_x_m_documentos('', _f6,     x_m_documentos.elementoXml()) } )
	addEvent(X_M_DOCUMENTOS_F10,     "click",   function() { t_x_m_documentos('', _f10,     x_m_documentos.elementoXml()) } )
	addEvent(X_M_DOCUMENTOS_F11,     "click",   function() { t_x_m_documentos('', _f11,     x_m_documentos.elementoXml()) } )

	addEvent(X_DOCUMENTOS_F2,    "click",   function() { t_x_documentos('', _f2, x_documentos.elementoXml()) } )
	addEvent(X_DOCUMENTOS_F7,    "click",   function() { t_x_documentos('', _f7, x_documentos.elementoXml()) } )
	addEvent(X_DOCUMENTOS_ESC,    "click",   function() { t_x_documentos('', _esc,    x_documentos.elementoXml()) } )


	addEvent(m_productos_ENTER, "click",   function() { t_m_productos('', _enter, m_productos.elementoXml()) } )
	addEvent(m_productos_ESC,   "click",   function() { t_m_productos('', _esc,   m_productos.elementoXml()) } )

	addEvent(m_servicios_ENTER, "click",   function() { t_m_servicios('', _enter, m_servicios.elementoXml()) } )
	addEvent(m_servicios_ESC,   "click",   function() { t_m_servicios('', _esc,   m_servicios.elementoXml()) } )

	addEvent(m_retenciones_INSERT, "click",   function() { t_m_retenciones('', _insert, m_retenciones.elementoXml()) } )
	addEvent(m_retenciones_SUPR, "click",   function() { t_m_retenciones('', _supr, m_retenciones.elementoXml()) } )
	addEvent(m_retenciones_ESC,   "click",   function() { t_m_retenciones('', _esc,   m_retenciones.elementoXml()) } )

	document.onclick = function() { if (parent.menu) parent.menu.reset(); }

	return true;
}

function inicio(){
	x_m_documentos.setFocus();
}

var resp = iniciar();

if(!resp){
	Salir();
}else{
	inicio(0);
}

function Calcular_vencimiento(){
	var xfecha = sumaDias(f_activo.getValue('FECHA_RECEPCION'),f_activo.getValue('CREDITO'));
	if(xfecha){
		f_activo.setValue('VENCE',xfecha);
	}
}

function x_m_documentos_Focus(){
	mostrarLeyenda(0);
}

function x_documentos_Focus(){
	mostrarLeyenda(1);
}


function Calcula_retencion(){
	var xconcepto = f_retenciones.getValue('CAMPO1');
	var x =genera_xml('M_CONCEPTOS_RETEN','PORCENTAJE1,MONTO1','ID_M_CONCEPTOS_RETEN',xconcepto,'=');
	var registro= valida_xml(x,'PORCENTAJE1');
	if(!registro) return;
	f_retenciones.setValue('MONTO_DESCUENTO',registro[0]['MONTO1']);
	f_retenciones.setValue('MONTO_IMPUESTO', mask.format(registro[0]['PORCENTAJE1']));
}

function Guardar_ret(){
	var xnombre_concepto = f_retenciones.getValue("r_CAMPO1");
    f_retenciones.setValue("CAMPO2",xnombre_concepto.substring(0,59));
    var monto_ret=f_retenciones.getValue("NETO");
    if (monto_ret < 0){
    	f_retenciones.setValue("NETO",0);
    }
	var registro = f_retenciones.submit();
	if(!registro) return;
	ocultar_formulario(f_retenciones);
	Calcula_saldo(xproveedor);
	cxp.actualizar(xpadre);
	d_cxp.buscar();
	d_cxp.setFocus();
}

function createRandomString( length ) {    
    var str = "";
    for ( ; str.length < length; str += Math.random().toString( 36 ).substr( 2 ) );
    return str.substr( 0, length );
}

function actualizaDetalles(obj,xml){
	dxml = xml;
	if (td) window.clearTimeout(td);
	td = window.setTimeout('verDetalles(dxml)',300);
}

function verDetalles(xml){
	var registro = valida_xml(xml, 'ID_X_M_DOCUMENTOS');
	if(!registro){
		x_documentos.limpiar();
		return;
	} 
	xID_M_DOCUMENTOS = registro[0]['ID_X_M_DOCUMENTOS'];
	x_documentos.xfiltro = xID_M_DOCUMENTOS;
	x_documentos.buscar();
	Refrescar();
}

function Imprimir(xid){
   impresora.origin = 'reportes/r_detalle_importacion';
   impresora.setParam('ID_M_IMPORTACIONES',xid);
   impresora.showDialog=true;
   impresora.preview();
}

function actualizaPie()
{
    actualizaHTML(x_documentos.elementoXml(),'PIED_');
}

function Refrescar()
{
    var url = server_path + "herramientas/genera_xml/genera_xml.php";
    var param =  "origen=compras_electronicas/x_m_documentos&procedimiento=RESUMEN_FACTURA&ID_X_M_DOCUMENTOS=" + xID_M_DOCUMENTOS;
    x = enviar(url,param,'POST');
    actualizaHTML(x,'PIE');

}

</script>

</body>
</html>

EOT;

?>