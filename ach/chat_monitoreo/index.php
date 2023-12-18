<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha 	= date("d/m/Y");
$ventana 	= getvar('ventana','modulo');
$id 		= getvar('id');
$hora     	= date("H:i");

$xempresa   		= getsession('CONFIGURACION_NOMBRES');
$nombre_usuario 	= getsession('M_USUARIOS_NOMBRES');
$id_m_usuario    	= getsession('M_USUARIOS_ID_M_USUARIO');

$my_ini 	= new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('utiles,formulario2,forma,auto_tabla,submodal,password,forma_simple');

echo <<<EOT
<!--script type="text/javascript" src="EventSource.js"></script-->

{$modulo->inicio}

<table border="0">
	<tr valign="top">
		<td id="GRUPO1"></td>
		<td id="GRUPO2"></td>
	</tr>
</table>

{$modulo->fin}

<script type="text/javascript">

/* PUSH */
/*
var str = document.URL;
var tmp = str.split("/");
var url = tmp[2].split(":");

xid_push = 'chat';
var push_path = tmp[0] + '//' + url[0] + ':7780/subscribe?events=' + xid_push;


var es = new EventSource(push_path);

es.addEventListener("open", function(event){
	
	
	
});

es.addEventListener("message", function(event){
	
		xchat=xID_M_CHAT;
		m_chat.buscar('*');

		setTimeout(
			function(){
				m_chat.localiza('ID_M_CHAT',xchat);
			}
		,400);

		if(xID_M_CHAT){
			d_chat.buscar('*');
		}
		
	
});


es.addEventListener("error", function(event){
	
	
	
});
*/


var opcion	   = '';
var usuario    = '{$nombre_usuario}';
var id_usuario ='{$id_usuario}';
var xempresa   ='{$xempresa}';
var fecha      = '{$xfecha}';
var xnumero    = null;
var hora       = '{$hora}';
var tiempo1=0;
var t1;
var pXML,tXML;
var filtroAct;
var t             = null;

var xID_M_CHAT = null;
var xCANAL = null;
var xORIGEN = null;
var xIDENTIFICADOR = null;
var xVIA = 'OUT';
var xFECHA_CHAT_H = null;
var fs = new forma_simple();

//se crea el contenedor
contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.alto        = 1;
contenedor.ancho       = 1;
contenedor.titulo      = ' ';
contenedor.botonCerrar = true;
contenedor.leyenda     = '   ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.ayuda       = 1;
contenedor.onClose     = Cerrar_contenedor;

// Se Crea el Grid de las Detalle De Los Mensajes
m_chat           = new lista("chat_monitoreo/m_chat")
m_chat.nombre    = "m_chat";
m_chat.padre     = "GRUPO1";
m_chat.funcion   = t_m_chat;
m_chat.onSelect  = actualizaPie;
m_chat.url       = server_path + "herramientas/genera_xml/genera_xml.php";
m_chat.buscador  = true;
m_chat.enter     = 0;
m_chat.botonCerrar = true;
m_chat.onLoad	 = m_chat_load;
m_chat.asyncLoad = true;
m_chat.usaCookie = false;


d_chat            = new lista('chat_monitoreo/d_chat');
d_chat.nombre     = 'd_chat';
d_chat.url        = server_path + 'herramientas/genera_xml/genera_xml.php';
d_chat.usaCookie  = false;
d_chat.funcion    = t_d_chat;
d_chat.padre      = "GRUPO2";
//d_chat.onSelect   = resetCuenta1;
d_chat.buscador   = true;
//d_chat.onFocus    = d_chatFocus;
d_chat.filtro     = 'ID_M_CHAT';
d_chat.asyncLoad  = true;

f_comando 			   = new formulario2('chat_monitoreo/f_comando');
f_comando.nombre       = 'f_comando';
f_comando.funcion      = t_f_comando;
f_comando.padre        = 'contenedor_cuadro';
f_comando.tipo		   = 'formulario';

f_edicion 			   = new formulario2('chat_monitoreo/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.tipo		   = 'formulario';

f_edicion_resp 			   = new formulario2('chat_monitoreo/f_edicion_resp');
f_edicion_resp.nombre      = 'f_edicion_resp';
f_edicion_resp.funcion     = t_f_edicion_resp;
f_edicion_resp.padre       = 'contenedor_cuadro';
f_edicion_resp.tipo		   = 'formulario';

f_extra = new formulario2('chat_monitoreo/f_extra');
f_extra.nombre       = 'f_extra';
f_extra.funcion      = t_f_extra;
f_extra.padre        = 'd_chat_extra';
f_extra.tipo         = 'formulario';

f_alias 			   = new formulario2('chat_monitoreo/f_alias');
f_alias.nombre       = 'f_alias';
f_alias.funcion      = t_f_alias;
f_alias.padre        = 'contenedor_cuadro';
f_alias.tipo		   = 'formulario';

function m_chat_load(){
	if(m_chat.rows.length==0){
		d_chat.limpiar();
	}
}

function d_chatFocus()
{
	mostrarLeyenda(1)
	var url = server_path + 'herramientas/utiles/actualizar_registro.php';
	var params = "origen=chat_monitoreo/d_chat&procedimiento=LEIDO&ID_M_CHAT=" + xID_M_CHAT;
	var x=enviar(url,params,'POST');
	d_chat.buscar('*');

}

function f_extraFocus()
{
       mostrarLeyenda(2)
}

function actualizaPie(obj,xml)
{
  dxml = xml;
  if (t) window.clearTimeout(t);
  t = window.setTimeout('act_timer(dxml)',300);
}

function act_timer(xml)
{
    var registro = valida_xml(xml,'ID_M_CHAT');
    if(!registro)return;
    xnumero = registro[0]['ID_M_CHAT'];
    d_chat.xfiltro = registro[0]['ID_M_CHAT'];
    d_chat.buscar();

    mostrarLeyenda(0);
    actualizaHTML(xml,'PIE');
	
	xID_M_CHAT = registro[0]['ID_M_CHAT'];
	xCANAL = registro[0]['CANAL'];
	xORIGEN = registro[0]['ORIGEN'];
	xIDENTIFICADOR = registro[0]['IDENTIFICADOR'];
	xVIA = 'OUT';
	xFECHA_CHAT_H = registro[0]['FECHA_CHAT_H'];
}

function t_m_chat(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
  case _enter:
    cancelaTecla(evt);
    var registro = valida_xml(xml,'ID_M_CHAT');
    if(!registro)return;
    
	
	d_chat.setFocus();
	setTimeout(function(){ 
		f_extra.setFocus(); 
		}, 250
	);
		
	break;

    case _insert:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_M_CHAT');
		if(!registro)return;
		
		f_edicion_resp.limpiar();
      	f_edicion_resp.setValue('ID_M_CHAT'		,registro[0]['ID_M_CHAT']);
		f_edicion_resp.setValue('CANAL'			,registro[0]['CANAL']);
		f_edicion_resp.setValue('ORIGEN'		,registro[0]['ORIGEN']);
		f_edicion_resp.setValue('IDENTIFICADOR'	,registro[0]['IDENTIFICADOR']);
		f_edicion_resp.setValue('VIA','OUT');
        mostrar_formulario(f_edicion_resp);

      break;
	  
    case _esc: // Esc
    	cancelaTecla(evt);
        Salir();
        break;
    case _f1:		
		cancelaTecla(evt);
	break;
    case _f2:
    cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_M_CHAT');
		if(!registro)return;
		f_comando.limpiar();
      	f_comando.setValue('ID_M_CHAT',registro[0]['ID_M_CHAT']);
		f_comando.setValue('CANAL',registro[0]['CANAL']);
		f_comando.setValue('ORIGEN',registro[0]['ORIGEN']);
		f_comando.setValue('IDENTIFICADOR',registro[0]['IDENTIFICADOR']);	
		f_comando.setValue('VIA','OUT');		
		
        mostrar_formulario(f_comando);

      break;	  

    case _f3:
		cancelaTecla(evt);
		if(!xID_M_CHAT){
			return;
		}
		else{
			f_alias.buscar(xID_M_CHAT);
			mostrar_formulario(f_alias);
			f_alias.setValue('FECHA_CHAT_H',xFECHA_CHAT_H)
		}

		break;	
    case _f4:
    case _f3:
    case _f5:
    case _f7:
    case _f8:
    case _f9:
		cancelaTecla(evt);
		break;
    case _f10:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_M_CHAT');
		if(!registro)return;
		
		if(registro[0]['ESTATUS']=='CERRADO'){
			alert('Este Chat ya está cerrado');
			return;
		}
		
		if(confirm('¿Desea cerrar el chat?')){
			fs.armar('chat_monitoreo/fs_cierre');
	        fs.xbusca = xID_M_CHAT;
            fs.setValue('ESTATUS','CERRADO');
			var registro = fs.submit();
			
			if(!registro)
			{
				alert('Error al cerrar');
				return;
			}
			else{
				m_chat.actualizar(xID_M_CHAT);
			}
		}
	break;
	
    case _f11:
    case _f12:
      cancelaTecla(evt);
      break;
  }
}

function t_d_chat(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _insert:
    cancelaTecla(evt);
    //alert(fecha);
	  var txml =  m_chat.elementoXml();
      var registro = valida_xml(xml,'ID_M_CHAT');
      if(!registro)return;
      {
		f_edicion_resp.limpiar();
      	f_edicion_resp.setValue('ID_M_CHAT',registro[0]['ID_M_CHAT']);
		f_edicion_resp.setValue('CANAL',registro[0]['CANAL']);
		f_edicion_resp.setValue('ORIGEN',registro[0]['ORIGEN']);
		f_edicion_resp.setValue('IDENTIFICADOR',registro[0]['IDENTIFICADOR']);	
		f_edicion_resp.setValue('VIA','OUT');		
        mostrar_formulario(f_edicion_resp);
      }
      break;

    case _enter:
      cancelaTecla(evt);
	  f_extra.setFocus();
      break;

         case _f3: // F3
        case _f4: // F4
        case _f5: // F5
    	cancelaTecla(evt);
        break;

    case _supr:
      cancelaTecla(evt);
      break;

    case _esc:
      cancelaTecla(evt);
      m_chat.setFocus();
      mostrarLeyenda(0);
      break;

    case _f1:
    case _f2:
	
    case _f3:
		cancelaTecla(evt);
		if(!xID_M_CHAT){
			return;
		}
		else{
			f_alias.buscar(xID_M_CHAT);
			mostrar_formulario(f_alias);
			f_alias.setValue('FECHA_CHAT_H',xFECHA_CHAT_H)
		}

		break;	
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

function t_f_extra(elemento, tecla,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc:
	  m_chat.setFocus();
      break;
    case _enter:	  
	case _f12:
		cancelaTecla(evt);
	    if(f_extra.getValue("MENSAJE")!=''){
			f_extra.setValue('ID_M_CHAT',xID_M_CHAT);
			f_extra.setValue('CANAL',xCANAL);
			f_extra.setValue('ORIGEN',xORIGEN);
			f_extra.setValue('IDENTIFICADOR',xIDENTIFICADOR);
			f_extra.setValue('VIA',xVIA);

			var registro = f_extra.submit();
			f_extra.limpiar();
			d_chat.buscar('*');	
			f_extra.setFocus();			
		}
	break;
  }
}
function t_f_comando(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _esc:
    	cancelaTecla(evt);
		ocultar_formulario(f_comando,m_chat);
      	break;

    case _f12:
    	cancelaTecla(evt);
        var registro = f_comando.submit();
		d_chat.buscar();
		ocultar_formulario(f_comando);
      	break;
  }
}

function t_f_edicion(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _esc:
    	cancelaTecla(evt);
		ocultar_formulario(f_edicion,m_chat);
      	break;

    case _f12:
    	cancelaTecla(evt);
        f_edicion_resp.submit();
		ocultar_formulario(f_edicion);
      	break;
  }
}

function t_f_alias(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _esc:
    	cancelaTecla(evt);
		ocultar_formulario(f_alias,m_chat);
      	break;

    case _f12:
    	cancelaTecla(evt);
        f_alias.submit();
		ocultar_formulario(f_alias);
      	break;
  }
}

function t_f_edicion_resp(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _esc:
    	cancelaTecla(evt);
		ocultar_formulario(f_edicion_resp,d_chat);
      	break;

    case _f12:
    	cancelaTecla(evt);
		var registro = f_edicion_resp.submit();
        ocultar_formulario(f_edicion_resp, d_chat);
		d_chat.actualizar(registro[0]['ID_D_CHAT'], '=');		
      	break;
  }
}


function Cuenta1()
{
    tiempo1--;
    if(tiempo1==0)
    {
        pXML=tXML;
        Refrescar();
        setTimeout('ubicar();',300);
        resetCuenta1();

    }
}

function ubicar()
{
    var registro = XML2Array(pXML);
    d_chat.localiza('ID_D_CHAT_ENTRADA',registro[0]['ID_D_CHAT_ENTRADA']);
}

function resetCuenta1()
{
    tiempo1=10;
    clearTimeout(t1);
    t1 = setInterval(Cuenta1,50000);
    tXML = d_chat.elementoXml();
}

function Refrescar()
{
    d_chat.buscar('*');
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_edicion':
			t_f_edicion('',_esc);
		break;

		case 'f_edicion_resp':
			t_f_edicion_resp('',_esc);
		break;

		case 'f_comando':
			t_f_comando('',_esc);
		break;

		case 'f_alias':
			t_f_alias('',_esc);
		break;	
    }
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

function Guardar(valor)
{
/*
  var url=server_path + 'herramientas/sms/sms.php';
  var params='numero='+f_edicion.getValue('NUMERO')+'&mensaje='+f_edicion.getValue('MENSAJE')+'--'+xempresa;
*/
  var envio = f_edicion.getValue('FECHA_ENVIO')+' '+f_edicion.getValue('FECHA_ENVIO_H');
  var aCampos =new Array();
  aCampos['NUMERO']  = new Campo(f_edicion.getValue('NUMERO'));
  aCampos['MENSAJE'] = new Campo(f_edicion.getValue('MENSAJE'));
  aCampos['FECHA_ENVIO'] = new Campo(envio);
  var x = actualizar_registro('D_CHAT',aCampos,'ID_D_CHAT','-1',null,'=');
  var registro = XML2Array(x);
  if(registro[0])
  {
      f_edicion.limpiar();
      ocultar_formulario(f_edicion);
      m_chat.buscar('*');
      m_chat.setFocus();
  }
}

function Guardar2(valor)
{
/*
  var url=server_path + 'herramientas/sms/sms.php';
  var params='numero='+f_edicion.getValue('NUMERO')+'&mensaje='+f_edicion.getValue('MENSAJE')+'--'+xempresa;
*/
  var envio = f_edicion_resp.getValue('FECHA_ENVIO')+' '+f_edicion_resp.getValue('FECHA_ENVIO_H');
  var aCampos =new Array();
  aCampos['NUMERO']  = new Campo(f_edicion_resp.getValue('NUMERO'));
  aCampos['MENSAJE'] = new Campo(f_edicion_resp.getValue('MENSAJE'));
  aCampos['FECHA_ENVIO'] = new Campo(envio);
  var x = actualizar_registro('D_CHAT',aCampos,'ID_D_CHAT','-1',null,'=');
  var registro = XML2Array(x);
  if(registro[0])
  {
      f_edicion_resp.limpiar();
      ocultar_formulario(f_edicion_resp);
      d_chat.buscar('*');
      d_chat.setFocus();
  }
}

function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_edicion':
			t_f_edicion('',_f12);
		break;

		case 'f_edicion_resp':
			t_f_edicion_resp('',_f12);
		break;

		case 'f_comando':
			t_f_comando('',_f12);
		break;

		case 'f_alias':
			t_f_alias('',_f12);
		break;	
    }
}



function Cerrar_contenedor()
{
          f_activo.ocultar();
          contenedor.ocultar();
}

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function verUsuario(aceptado)
{
/*    f_edicion.setValue('USUARIO',usuario);
    f_edicion.setValue('ID_M_USUARIOS1',id_usuario);*/
//    f_edicion.setFocus();
}

function iniciar()
{
    contenedor.inicializa();
	centrarObj(contenedor.contenedor);

     d_chat.inicializa(false);
     d_chat.mostrar();

	f_alias.inicializa();
	f_comando.inicializa();
	f_edicion.inicializa();
    f_edicion_resp.inicializa();
	f_extra.inicializa();
	f_extra.mostrar();

    m_chat.inicializa();
    m_chat.mostrar();
    m_chat.setFocus();
    m_chat.buscar('*');

	
	addEvent(frm_f_extra.MENSAJE, 'focus', f_extraFocus);
	
    addEvent(M_INSERT,  "click",  function() { t_m_chat  ('', _insert,   m_chat.elementoXml()) } )
	addEvent(M_F2,      "click",  function() { t_m_chat  ('', _f2,       m_chat.elementoXml()) } )
	addEvent(M_F3,      "click",  function() { t_m_chat  ('', _f3,       m_chat.elementoXml()) } )
    addEvent(M_ENTER,   "click",  function() { t_m_chat  ('', _enter,    m_chat.elementoXml()) } )
    addEvent(M_ESC,     "click",  function() { t_m_chat  ('', _esc,      m_chat.elementoXml()) } )
	addEvent(M_F10,     "click",  function() { t_m_chat  ('', _f10,      m_chat.elementoXml()) } )

    addEvent(D_INSERT, "click",   function() { t_d_chat('', _insert,d_chat.elementoXml()) } );
	addEvent(D_ENTER,  "click",   function() { t_d_chat('', _enter,d_chat.elementoXml()) } );
	addEvent(D_F2,     "click",   function() { t_m_chat  ('', _f2,  m_chat.elementoXml()) } )
	addEvent(D_F3,     "click",   function() { t_m_chat  ('', _f3,  m_chat.elementoXml()) } )
    addEvent(D_ESC,    "click",   function() { t_d_chat('', _esc,   d_chat.elementoXml()) } );
	
	addEvent(F_ESC,   "click",   function() { t_f_extra('', _esc,   d_chat.elementoXml()) } );
	addEvent(F_F12,   "click",   function() { t_f_extra('', _f12,   d_chat.elementoXml()) } );
	addEvent(F_ENTER, "click",   function() { t_f_extra('', _enter, d_chat.elementoXml()) } );

	document.onclick=function() {if (parent.menu) parent.menu.reset();}
}

iniciar();


function inicio()
{
    m_chat.buscar('*');
    m_chat.setFocus();
    mostrarLeyenda(0);
	m_chat.localiza('ID_M_CHAT','0017');
}

</script>


</body>
</html>

EOT;

?>