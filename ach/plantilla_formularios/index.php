<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha             = date("d/m/Y");
$ventana            = getvar('ventana','modulo');
$id                 = getvar('id');
$xID_M_CLIENTES     = getvar('id_m_clientes');

$id_m_usuario    = getsession('M_USUARIOS_ID_M_USUARIO');

$my_ini         = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora,jquery');

echo <<<EOT

{$modulo->inicio}

<table border="0">
	<tr>
		<td id="contenido"></td>
	</tr>
</table>

{$modulo->fin}

<script type="text/javascript">

var xID_M_FORMULARIOS = null;
var fecha = '{$xfecha}';

var xID_M_CLIENTES = '{$xID_M_CLIENTES}';

var xproductos =null;
var xservicios =null;

</script>

EOT;

?>


<script type="text/javascript">

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 800;
contenedor.alto        = 400;
contenedor.titulo      = ' ';
contenedor.x           = 100;
contenedor.y           = 100;
contenedor.titulo      = '    ';
contenedor.botonCerrar = true;
contenedor.leyenda     = '  ';
contenedor.ayuda       = 1;
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.modalResult = true;
contenedor.onClose     = Cerrar_contenedor;

p_formularios              = new lista('plantilla_formularios/p_formularios');
p_formularios.nombre       = 'p_formularios';
p_formularios.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
p_formularios.usaCookie    = false;
p_formularios.funcion      = t_p_formularios;
p_formularios.padre        = "contenido";
p_formularios.buscador     = true;
p_formularios.onSelect     = actualizaPie;
p_formularios.filtro       = 'ID_M_FORMULARIOS';

m_formularios             = new lista("plantilla_formularios/m_formularios")
m_formularios.nombre      = "m_formularios";
m_formularios.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_formularios.funcion     = t_m_formularios;
m_formularios.buscador    = true;
m_formularios.modal       = true;
m_formularios.botonCerrar = true;
m_formularios.enter       = 1;
//m_formularios.filtro     ='ID_M_CLIENTES';
//m_formularios.xfiltro    = xID_M_CLIENTES;
m_formularios.filtro     ='ESTATUS';
m_formularios.xfiltro    = 'ACT';


f_campo              = new formulario2('plantilla_formularios/f_campo');
f_campo.nombre       = 'f_campo';
f_campo.funcion      = t_f_campo;
f_campo.padre        = 'contenedor_cuadro';
f_campo.tipo         = 'formulario';

f_formularios              = new formulario2('plantilla_formularios/f_formularios');
f_formularios.nombre       = 'f_formularios';
f_formularios.funcion      = t_f_formularios;
f_formularios.padre        = 'contenedor_cuadro';
f_formularios.tipo         = 'formulario';

d_contactos                   = new lista("plantilla_formularios/d_contactos");
d_contactos.nombre            = "d_contactos";
d_contactos.url               = server_path + "herramientas/genera_xml/genera_xml.php";
d_contactos.funcion           = t_d_contactos;
d_contactos.buscador          = true;
d_contactos.asyncLoad         = false;
d_contactos.filtro        	  = 'IDX;TABLA';
d_contactos.enter      		  = 0;
d_contactos.modal			  = true;
d_contactos.botonCerrar		  = true;

f_d_contactos                  = new formulario2('plantilla_formularios/f_d_contactos');
f_d_contactos.nombre           = 'f_d_contactos';
f_d_contactos.funcion          = t_f_d_contactos;
f_d_contactos.padre            = 'contenedor_cuadro';
f_d_contactos.tipo             = 'formulario';

function t_d_contactos(objeto, tecla, xml, e){
	var evt = window.event || e;
	switch (tecla)	{
		case _insert:
			cancelaTecla(evt);
			d_contactos.ocultar();
			f_d_contactos.limpiar();
			f_d_contactos.setValue('IDX', xID_M_FORMULARIOS);
			f_d_contactos.setValue('TABLA', 'M_FORMULARIOS');
			mostrar_formulario(f_d_contactos);				
		break;
		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml, 'ID_D_CONTACTOS');
			if(!registro){
				d_contactos.setFocus();
				return;
			}
			d_contactos.ocultar();
			f_d_contactos.buscar(registro[0]['ID_D_CONTACTOS']);
			mostrar_formulario(f_d_contactos);
		break;
		case _supr:
			cancelaTecla(evt);
			var registro = valida_xml(xml, 'ID_D_CONTACTOS');
			if(!registro){
				d_contactos.setFocus();
				return;
			}
			var borrar = confirm('{$t_eliminar_registro}');
			if(borrar){
				var url = server_path + "herramientas/utiles/actualizar_registro.php";
				var param =  "origen=plantilla_formularios/d_contactos&procedimiento=ELIMINAR&ID_D_CONTACTOS=" + registro[0]['ID_D_CONTACTOS'];
				enviar(url,param,'GET');
			}
			d_contactos.setFocus();
			d_contactos.buscar('*');
		
		break;

		case _esc:
			cancelaTecla(evt);
			d_contactos.ocultar();
			p_formularios.setFocus();
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


function t_f_d_contactos(elemento, tecla,e){
	var evt = window.event || e;
	switch (tecla){
		case _insert:
			cancelaTecla(evt);
		break;
		case _enter:
		break;
		case _supr:
		break;
	  
		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_d_contactos);
			d_contactos.mostrar();
			d_contactos.setFocus();
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
			var registro = f_d_contactos.submit();
			if(!registro) return;
			ocultar_formulario(f_d_contactos);
			d_contactos.actualizar(registro[0]['ID_D_CONTACTOS']);
			d_contactos.mostrar();
			d_contactos.setFocus();
		break;
	}
}


function t_f_formularios(objeto, tecla, xml){
	var evt = window.event || e;
	switch (tecla){
		case _insert:
			cancelaTecla(evt);
		break;
		case _enter:
		break;
		case _supr:
		break;
		
		case _esc:
			cancelaTecla(evt);
			m_formularios.mostrar();
			ocultar_formulario(f_formularios,m_formularios);
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
			var registro = f_formularios.submit();
			if(!registro)return;
			ocultar_formulario(f_formularios,m_formularios);
			m_formularios.mostrar();
			m_formularios.buscar(registro[0]['NOMBRES'], '=');
		break;
	}
	   
}

function t_m_formularios (objeto, tecla, xml,e){
	var evt = window.event || e;
	switch (tecla){
		case _insert:
			cancelaTecla(evt);
			m_formularios.ocultar();
			f_formularios.limpiar();
			f_formularios.setValue('ID_M_CLIENTES', xID_M_CLIENTES);
			mostrar_formulario(f_formularios);
		break;    
		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_FORMULARIOS');
			if(!registro)return;
			m_formularios.ocultar();
			xID_M_FORMULARIOS = registro[0]['ID_M_FORMULARIOS'];
			p_formularios.xfiltro = xID_M_FORMULARIOS;
			p_formularios.buscar();
			p_formularios.setFocus();
			actualizaDetalles();
		break;

		case _esc:
			cancelaTecla();
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
			var registro = valida_xml(xml,'ID_M_FORMULARIOS');
			if(!registro)return;

			m_formularios.ocultar();
			f_formularios.buscar(registro[0]['ID_M_FORMULARIOS']);
			mostrar_formulario(f_formularios);
		break;
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

function t_p_formularios(objeto, tecla, xml, e){
	var evt = window.event || e;
	switch (tecla){
		case _insert:
			cancelaTecla(evt);
			mostrar_formulario(f_campo);
			f_campo.limpiar();
			f_campo.setValue('ID_M_FORMULARIOS', xID_M_FORMULARIOS);
		break;

		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_P_FORMULARIOS');
			if(!registro)return;
			f_campo.buscar(registro[0]['ID_P_FORMULARIOS']);
			mostrar_formulario(f_campo);
		break;
		
		case _supr:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_P_FORMULARIOS');
			if(!registro) return;
			if(confirm('{$t_eliminar_registro}')){
				var url   = server_path + "herramientas/utiles/actualizar_registro.php";
				var param = "tabla=p_formularios&c_ESTATUS_CSS=INA&busca=ID_P_FORMULARIOS&xbusca=" + registro [0] ['ID_P_FORMULARIOS'];
				var x     = enviar(url,param,'GET');
			}
			p_formularios.buscar('*');
			p_formularios.setFocus();
		break;

		case _esc:
			cancelaTecla(evt);
			Salir();
		break;

		case _f1:
			cancelaTecla(evt);
		break;
		case _f2:
			cancelaTecla(evt);
			d_contactos.xfiltro        	  = xID_M_FORMULARIOS + ';M_FORMULARIOS';
			d_contactos.buscar('*');
			d_contactos.mostrar();
			d_contactos.setFocus();
		break;
		case _f3:
			cancelaTecla(evt);
			m_formularios.mostrar();
			m_formularios.setFocus();
		break;

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

function t_f_campo(objeto, tecla, xml){
	var evt = window.event || e;
	switch (tecla)
	{
		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_campo,p_formularios);
		break;

		case _f12:
			cancelaTecla(evt);
			f_campo.setValue('ID_M_FORMULARIOS',xID_M_FORMULARIOS);
			var registro = f_campo.submit();
			if(!registro)return;
			ocultar_formulario(f_campo,p_formularios);
			p_formularios.buscar();
			p_formularios.setFocus();
		break;
	}
}

function actualizaDetalles(){
	actualizaHTML(m_formularios.elementoXml(),'ENCABEZADO');
}

function actualizaPie(){
	actualizaHTML(p_formularios.elementoXml(),'PIE');
}

function Salir(){
	location.href = server_path + 'main/inicio.php';
}

function mostrar_formulario(xformulario){
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

function ocultar_formulario(xformulario,xobjecto_destino){
    contenedor.ocultar();
    xformulario.ocultar();
    if(xobjecto_destino) xobjecto_destino.setFocus();
}

function Cerrar_contenedor(){
    f_activo.ocultar();
    contenedor.ocultar();
}

function Guardar_formulario(){
    switch (f_activo.nombre){
		case 'f_campo':
			t_f_campo('',_f12);
		break;
		case 'f_formularios':
			t_f_formularios('',_f12);
		break;
		case 'f_d_contactos':
			t_f_d_contactos('',_f12);
		break;		
    }
}

function Cerrar_formulario(){
    switch (f_activo.nombre){
		case 'f_campo':
			t_f_campo('',_esc);
		break;
		case 'f_formularios':
			t_f_formularios('',_esc);
		break;
		case 'f_d_contactos':
			t_f_d_contactos('',_esc);
		break;		
    }
}

function iniciar(){
	console.clear();
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);

	p_formularios.inicializa(false);
	p_formularios.mostrar();

	m_formularios.inicializa(true);
	centrarObj(m_formularios.contenedor);

	f_campo.inicializa();
	f_formularios.inicializa();
	
	d_contactos.inicializa(false);
	centrarObj(d_contactos.contenedor);
	
	f_d_contactos.inicializa();	

	document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	addEvent(INS,  "click",   function() { t_p_formularios('', _insert,  p_formularios.elementoXml()) } );
	addEvent(ENTER,"click",   function() { t_p_formularios('', _enter,   p_formularios.elementoXml()) } );
	addEvent(F2,   "click",   function() { t_p_formularios('', _f2,      p_formularios.elementoXml()) } );
	addEvent(F3,   "click",   function() { t_p_formularios('', _f3,      p_formularios.elementoXml()) } );
	addEvent(SUPR, "click",   function() { t_p_formularios('', _supr,    p_formularios.elementoXml()) } );
	addEvent(ESC,  "click",   function() { t_p_formularios('', _esc,     p_formularios.elementoXml()) } );

	addEvent(m_formularios_INSERT, "click",   function() { t_m_formularios('', _insert, m_formularios.elementoXml()) } );
	addEvent(m_formularios_F6, "click",   function() { t_m_formularios('', _f6, m_formularios.elementoXml()) } );
	addEvent(m_formularios_ENTER, "click",   function() { t_m_formularios('', _enter, m_formularios.elementoXml()) } );
	addEvent(m_formularios_ESC,   "click",   function() { t_m_formularios('', _esc,   m_formularios.elementoXml()) } );
	
	addEvent(d_contactos_INSERT   		, "click",   function() { t_d_contactos('', _insert, d_contactos.elementoXml()) } )
	addEvent(d_contactos_ENTER   		, "click",   function() { t_d_contactos('', _enter, d_contactos.elementoXml()) } )
	addEvent(d_contactos_SUPR   		, "click",   function() { t_d_contactos('', _supr, d_contactos.elementoXml()) } )
	addEvent(d_contactos_ESC   		, "click",   function() { t_d_contactos('', _esc, d_contactos.elementoXml()) } )
	

	return true;

}

function inicio(){
	m_formularios.mostrar();
	m_formularios.setFocus();
}

var resp = iniciar();

if(!resp){
	Salir();
}
else{
	inicio();
}

</script>

</body>
</html>

