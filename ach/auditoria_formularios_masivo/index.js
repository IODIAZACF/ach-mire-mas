var xID_M_KOBO_FORMULARIOS	= null ;
var dxml   		  			= null;
var t             			= null;
//*************************************//

var fs    = new forma_simple();

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

m_kobo_formularios              = new lista('auditoria_formularios_individual/m_kobo_formularios');
m_kobo_formularios.nombre       = 'm_kobo_formularios';
m_kobo_formularios.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
m_kobo_formularios.usaCookie    = false;
m_kobo_formularios.funcion      = t_m_kobo_formularios;
m_kobo_formularios.padre        = "GRUPO1";
m_kobo_formularios.asyncload    = true;
m_kobo_formularios.buscador     = true;
m_kobo_formularios.onSelect     = actualizar_m_kobo_respuestas;
m_kobo_formularios.onFocus      = m_kobo_formulariosFocus;
m_kobo_formularios.filtro 		='GRUPO';
m_kobo_formularios.xfiltro 		='MASIVO';

m_kobo_respuestas            = new lista('auditoria_formularios_individual/m_kobo_respuestas');
m_kobo_respuestas.nombre     = 'm_kobo_respuestas';
m_kobo_respuestas.url        = server_path + 'herramientas/genera_xml/genera_xml.php';
m_kobo_respuestas.usaCookie  = false;
m_kobo_respuestas.funcion    = t_m_kobo_respuestas;
m_kobo_respuestas.padre      = "GRUPO2";
m_kobo_respuestas.buscador   = true;
m_kobo_respuestas.onFocus    = m_kobo_respuestasFocus;
m_kobo_respuestas.filtro     = 'ID_M_KOBO_FORMULARIOS';

f_m_kobo_formularios 			   = new formulario2('auditoria_formularios_individual/f_m_kobo_formularios');
f_m_kobo_formularios.nombre       = 'f_m_kobo_formularios';
f_m_kobo_formularios.funcion      = t_f_m_kobo_formularios;
f_m_kobo_formularios.padre        = 'contenedor_cuadro';
f_m_kobo_formularios.tipo         = 'formulario';

f_m_kobo_respuestas 			  = new formulario2('auditoria_formularios_individual/f_m_kobo_respuestas');
f_m_kobo_respuestas.nombre       = 'f_m_kobo_respuestas';
f_m_kobo_respuestas.funcion      = t_f_m_kobo_respuestas;
f_m_kobo_respuestas.padre        = 'contenedor_cuadro';
f_m_kobo_respuestas.tipo         = 'formulario';

f_m_kobo_formularios_test 			   = new formulario2('auditoria_formularios_individual/f_m_kobo_formularios_test');
f_m_kobo_formularios_test.nombre       = 'f_m_kobo_formularios_test';
f_m_kobo_formularios_test.funcion      = t_f_m_kobo_formularios_test;
f_m_kobo_formularios_test.padre        = 'contenedor_cuadro';
f_m_kobo_formularios_test.tipo         = 'formulario';

function Crear_archivo() {
	$.download(server_path + 'auditoria_formularios_individual/descargar_formulario.php','ID_M_KOBO_FORMULARIOS='+xID_M_KOBO_FORMULARIOS);
}

function t_m_kobo_formularios(objeto, tecla, xml, e) {
	var evt = window.event || e;
	switch (tecla) 	{
		case _insert:
			cancelaTecla(evt);
		break;

		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_KOBO_FORMULARIOS');
			if(!registro)return;
			m_kobo_respuestas.setFocus();
		break;

		case _supr:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			Salir();
		break;

		case _f1:
		case _f2:
			cancelaTecla(evt);
		break;

		case _f3:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_KOBO_FORMULARIOS');
			if(!registro)return;
			
			f_m_kobo_formularios_test.buscar(registro[0]['ID_M_KOBO_FORMULARIOS']);
			mostrar_formulario(f_m_kobo_formularios_test);
		break;

		case _f4:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_KOBO_FORMULARIOS');
			if(!registro)return;
			waitExec('Realizando Proceso', 'Crear_archivo()', 5, 250, 283);
		break;
		case _f5:
			cancelaTecla(evt);
		break;

		case _f6:
			cancelaTecla(evt);
		break;

		case _f7:
			cancelaTecla(evt);
		break;
		case _f8:
			cancelaTecla(evt);
		break;
		
		case _f9:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_KOBO_FORMULARIOS');
			if(!registro)return;
			if( confirm('Desea pasar el estatus del formulario a Anulado ?') ){
				fs.armar('auditoria_formularios_individual/fs_estatus');
				fs.xbusca = registro[0]['ID_M_KOBO_FORMULARIOS'];
				fs.setValue('ESTATUS','ANULADO');
				var registro = fs.submit();
				if(!registro)
				{
					alert('Error al guardar el registro');
					m_kobo_formularios.setFocus();
					return;
				}
				m_kobo_formularios.actualizar(registro[0]['ID_M_KOBO_FORMULARIOS']);
			}
		break;

		case _f10:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_KOBO_FORMULARIOS');
			if(!registro)return;
			if( confirm('Desea pasar el estatus del formulario a Verificado ?') ){
				fs.armar('auditoria_formularios_individual/fs_estatus');
				fs.xbusca = registro[0]['ID_M_KOBO_FORMULARIOS'];
				fs.setValue('ESTATUS','VERIFICADO');
				var registro = fs.submit();
				if(!registro)
				{
					alert('Error al guardar el registro');
					m_kobo_formularios.setFocus();
					return;
				}
				m_kobo_formularios.actualizar(registro[0]['ID_M_KOBO_FORMULARIOS']);
			}
		break;
		
		case _f11:
			var registro = valida_xml(xml,'ID_M_KOBO_FORMULARIOS');
			if(!registro)return;
			if( confirm('Desea pasar el estatus del formulario a Pendiente ?') ){
				fs.armar('auditoria_formularios_individual/fs_estatus');
				fs.xbusca = registro[0]['ID_M_KOBO_FORMULARIOS'];
				fs.setValue('ESTATUS','PENDIENTE');
				var registro = fs.submit();
				if(!registro)
				{
					alert('Error al guardar el registro');
					m_kobo_formularios.setFocus();
					return;
				}
				m_kobo_formularios.actualizar(registro[0]['ID_M_KOBO_FORMULARIOS']);
			}

		case _f12:
			cancelaTecla(evt);
		break;
	}
}
function t_m_kobo_respuestas(objeto, tecla, xml, e) {
	var evt = window.event || e;
	switch (tecla) {
		case _insert:
			cancelaTecla(evt);
		break;

		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_KOBO_RESPUESTAS');
			if(!registro)return;
			f_m_kobo_respuestas.buscar(registro[0]['ID_M_KOBO_RESPUESTAS']);
			mostrar_formulario(f_m_kobo_respuestas);
		break;

		case _supr:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			m_kobo_formularios.setFocus();
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

function t_f_m_kobo_formularios(objeto, tecla, xml) {
	var evt = window.event || e;
	switch (tecla) {
		case _insert:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_m_kobo_formularios,m_kobo_formularios);
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
			var registro = f_m_kobo_formularios.submit();
			if(!registro)return;
			ocultar_formulario(f_m_kobo_formularios,m_kobo_formularios);
			m_kobo_formularios.buscar();
		break;
	}
}

function t_f_m_kobo_formularios_test(objeto, tecla, xml) {
	var evt = window.event || e;
	switch (tecla) {
		case _insert:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_m_kobo_formularios_test,m_kobo_formularios);
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
			var registro = f_m_kobo_formularios_test.submit();
			if(!registro)return;
			ocultar_formulario(f_m_kobo_formularios_test,m_kobo_formularios);
			m_kobo_formularios.buscar();
		break;
	}
}

function t_f_m_kobo_respuestas(objeto, tecla, xml) {
	var evt = window.event || e;
	switch (tecla) {
		case _insert:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_m_kobo_respuestas,m_kobo_respuestas);
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
			var registro = f_m_kobo_respuestas.submit();
			if(!registro)return;
			ocultar_formulario(f_m_kobo_respuestas,m_kobo_respuestas);
			m_kobo_respuestas.buscar();
		break;
	}
}

function m_kobo_formulariosFocus(){
	mostrarLeyenda(0);
}

function m_kobo_respuestasFocus() {
	mostrarLeyenda(1);
}

function actualizar_m_kobo_respuestas(obj,xml) {
	if (t) window.clearTimeout(t);
	t = window.setTimeout('ver_detalles()',300);
}

function ver_detalles() {
	var xml = m_kobo_formularios.elementoXml();
	var registro = valida_xml(xml,'ID_M_KOBO_FORMULARIOS');
	if(!registro)return;
	xID_M_KOBO_FORMULARIOS = registro[0]['ID_M_KOBO_FORMULARIOS'];
	m_kobo_respuestas.xfiltro = xID_M_KOBO_FORMULARIOS;
	m_kobo_respuestas.buscar('*');
}

function mostrar_formulario(xformulario) {
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

function ocultar_formulario(xformulario,xobjecto_destino) {
	contenedor.ocultar();
	xformulario.ocultar();
	if(xobjecto_destino) xobjecto_destino.setFocus();
}

function Cerrar_contenedor() {
	f_activo.ocultar();
	contenedor.ocultar();
}

function Guardar_formulario() {
    f_activo.funcion ('', _f12 );
}

function Cerrar_formulario() {
    f_activo.funcion('' ,_esc );
}

function Salir() {
	location.href = server_path + 'main/inicio.php';
}

function iniciar() {
	console.clear();
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);

	m_kobo_formularios.inicializa(false);
	m_kobo_formularios.mostrar();

	m_kobo_respuestas.inicializa(false);
	m_kobo_respuestas.mostrar();

	f_m_kobo_formularios.inicializa();
	f_m_kobo_respuestas.inicializa();
	f_m_kobo_formularios_test.inicializa();

	document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	addEvent(GRID_ENTER,	"click",   function() { t_m_kobo_formularios('', _enter, m_kobo_formularios.elementoXml()) } );
	addEvent(GRID_F3,   	"click",   function() { t_m_kobo_formularios('', _f3,  m_kobo_formularios.elementoXml()) } );
	addEvent(GRID_F4,   	"click",   function() { t_m_kobo_formularios('', _f4,  m_kobo_formularios.elementoXml()) } );
	addEvent(GRID_F9,   	"click",   function() { t_m_kobo_formularios('', _f9,  m_kobo_formularios.elementoXml()) } );
	addEvent(GRID_F10,   	"click",   function() { t_m_kobo_formularios('', _f10, m_kobo_formularios.elementoXml()) } );
	addEvent(GRID_F11,   	"click",   function() { t_m_kobo_formularios('', _f11, m_kobo_formularios.elementoXml()) } );
	
	addEvent(GRID_ESC,     	"click",   function() { t_m_kobo_formularios('', _esc, m_kobo_formularios.elementoXml()) } );

	addEvent(DETA_ENTER,   	"click",   function() { t_m_kobo_respuestas('', _enter,  m_kobo_respuestas.elementoXml()) } );
	addEvent(DETA_ESC,     	"click",   function() { t_m_kobo_respuestas('', _esc,    m_kobo_respuestas.elementoXml()) } );

	return true;

}

function inicio() {
	m_kobo_formularios.buscar('*');
	m_kobo_formularios.setFocus();
}

var resp = iniciar();

if(!resp) {
	Salir();
}
else {
	inicio();
}
