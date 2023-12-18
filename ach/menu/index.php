<?php
//session_start();
include ("../config.php");

include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');

$ventana = getvar('ventana','modulo');
$id = getvar('id');


$onClose = 'Salir();';

$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$modulo = new class_modulo('modulo','parent.proceso.src=\''.$onClose.$retornando.'\'');

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

$s_id_usuario=getsession('M_USUARIOS_ID_M_USUARIO');


javascript('utiles,auto_tabla,forma,formulario2,submodal,jquery,forma_simple');

echo <<<EOT

{$modulo->inicio}

<table border="0">
	<tr>
		<td id="GRUPO1"></td>
		<td id="GRUPO2"></td>
	</tr>
</table>



{$modulo->fin}

<script type="text/javascript">

var f_activo    = null;
var t           = null;
var xid_m_menu  = null;
var fs 			= new forma_simple();


</script>

EOT;

?>

<script type="text/javascript">

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 500;
contenedor.alto        = 300;
contenedor.titulo      = ' ';
contenedor.ayuda       = 1;
contenedor.x           = 1;
contenedor.y           = 1;
contenedor.titulo      = 'SUBMODAL 2';
contenedor.botonCerrar = true;
contenedor.leyenda     = '   ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;

m_menu                   = new lista("menu/m_menu")
m_menu.nombre            = "m_menu";
m_menu.padre             = "GRUPO1";
m_menu.url                = server_path + "herramientas/genera_xml/genera_xml.php";
m_menu.funcion           = t_m_menu;
m_menu.buscador          = true;
m_menu.onSelect          = actualizarGrupos;
m_menu.asyncLoad         = false;
m_menu.filtro        	 = 'FILTRO';


menu_grupo               = new lista("menu/menu_grupo")
menu_grupo.nombre        = "menu_grupo";
menu_grupo.padre         = "GRUPO2";
menu_grupo.url           = server_path + "herramientas/genera_xml/genera_xml.php";
menu_grupo.funcion       = t_menu_grupo;
menu_grupo.filtro        = 'ID_M_MENU';
menu_grupo.onFocus       = focus_menu_grupo;

f_padre                  = new formulario2('menu/f_padre');
f_padre.nombre           = 'f_padre';
f_padre.funcion          = t_f_padre;
f_padre.padre            = 'contenedor_cuadro';
f_padre.tipo                          = 'formulario';

f_edicion                       = new formulario2('menu/f_edicion');
f_edicion.nombre                = 'f_edicion';
f_edicion.funcion               = t_f_edicion;
f_edicion.padre                 = 'contenedor_cuadro';
f_edicion.tipo                            = 'formulario';

f_edicion_grupo          = new formulario2('menu/f_edicion_grupo');
f_edicion_grupo.nombre   = 'f_edicion_grupo';
f_edicion_grupo.funcion  = t_f_edicion_grupo;
f_edicion_grupo.padre    = 'contenedor_cuadro';
f_edicion_grupo.tipo         = 'formulario';

m_modulos               = new lista("menu/m_menu_modulo")
m_modulos.nombre        = "m_modulos";
m_modulos.url           = server_path + "herramientas/genera_xml/genera_xml.php";
m_modulos.funcion       = t_m_modulos;
m_modulos.buscador    	= true;
m_modulos.modal       	= true;
m_modulos.botonCerrar 	= true;
m_modulos.enter       	= 1;


function t_m_modulos(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
	case _enter:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_M_MENU');
		if(!registro)return;
		m_modulos.ocultar();
		m_menu.xfiltro = registro[0]['ID_M_MENU'];
		m_menu.buscar('*');
		m_menu.setFocus();
		break;
	case _insert:
		cancelaTecla(evt);
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


function focus_menu_grupo()
{
   mostrarLeyenda(1);
}

function actualizarGrupos()
{
   if (t) clearTimeout(t);
   t = setTimeout('Grupos();',200);
}

function Grupos()
{
   var xml = m_menu.elementoXml();
   var registro = XML2Array(xml);
   if(!registro[0]['ID_M_MENU']) return false;
   if(!registro[0]) return false;
   xid_m_menu = registro[0]['ID_M_MENU'];
   xposicion  = registro[0]['POSICION'];
   menu_grupo.xfiltro = xid_m_menu;
   menu_grupo.buscar('*');
   mostrarLeyenda(0);
}


function t_f_edicion(elemento, tecla,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc: //Escape
      	cancelaTecla(evt);
      	ocultar_formulario(f_edicion);
      	m_menu.setFocus();
      	break;

    case _f12: // F12 Guardar_item
        cancelaTecla(evt);
        var registro = f_edicion.submit();
        if(!registro) return;
        var xid    = registro[0]['ID_M_MENU'];
        ocultar_formulario(f_edicion);
        m_menu.buscar('*');
        m_menu.localiza('ID_M_MENU',xid);
        m_menu.setFocus();
        break;
  }
}

function t_f_edicion_grupo(elemento, tecla,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc: //Escape
      	cancelaTecla(evt);
      	ocultar_formulario(f_edicion_grupo);
      	menu_grupo.setFocus();
     	break;

    case _f12: // F12 Guardar_item
        cancelaTecla(evt);
        var registro = f_edicion_grupo.submit();
        if(!registro) return
        menu_grupo.buscar();
        ocultar_formulario(f_edicion_grupo);
        menu_grupo.setFocus();
      	break;
  }
}


function t_f_padre(elemento, tecla,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc: //Escape
      	cancelaTecla(evt);
      	ocultar_formulario(f_padre);
      	m_menu.setFocus();
      	break;

    case _f12: // F12 Guardar_item
        cancelaTecla(evt);
        var registro = f_padre.submit();
        if(!registro) return;
        var xid    = registro[0]['ID_M_MENU'];
        ocultar_formulario(f_padre);
        m_menu.buscar('*');
        m_menu.localiza('ID_M_MENU',xid);
        m_menu.setFocus();
      	break;
  }
}



function t_m_menu(objeto, tecla, xml, e)
{
        var evt = window.event || e;

          switch (tecla)
          {
            case _insert:
                cancelaTecla(evt);
                var registro = XML2Array(xml);
                if(!registro[0]['ID_M_MENU']) return;

            	f_edicion.limpiar();
            	var xurl = f_edicion.getValue('URL');
            	f_edicion.setValue('ID_PADRE',   registro[0]['ID_M_MENU']);
            	f_edicion.setValue('r_ID_PADRE', registro[0]['ROTULO']);
				f_edicion.setValue('URL','/'+xurl);
            	mostrar_formulario(f_edicion);
				
				
                break;

 
			case _enter:
                cancelaTecla(evt);
                var registro = XML2Array(xml);
                if(!registro[0]['ID_M_MENU']) return;
            	xid_m_menu = registro[0]['ID_M_MENU'];
            	menu_grupo.setFocus();
            	break;

            case _supr:
            	cancelaTecla(evt);
                var registro = XML2Array(xml);
                if(!registro[0]['ID_M_MENU']) return;
            	if(registro[0]['ID_M_MENU']=='') return;

                var url   = server_path + "herramientas/genera_xml/genera_xml.php";
                var param =  "tabla=M_MENU&operador==&campos=ID_M_MENU,ROTULO&busca=ID_PADRE&xbusca=" + registro[0]['ID_M_MENU'];
                var x     = enviar(url,param,'POST');
            	var xregistro = XML2Array(x);
            	if(xregistro.length==0)
            	{
                    var borrar = confirm('{$t_eliminar_registro}');
                    if(borrar)
                    {
                    	var url = server_path + "herramientas/utiles/actualizar_registro.php";
                        var param =  "origen=menu/m_menu&procedimiento=ELIMINAR&ID_M_MENU=" + registro[0]['ID_M_MENU'];
                        enviar(url,param,'GET');
                        waitExec('{$t_realizando_proceso}', 'm_menu.buscar(\'*\')', 5, 250, 283);
                    }
            	}
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
				m_modulos.mostrar();
				m_modulos.setFocus();
				break;
			
            case _f4:
            case _f5:
                cancelaTecla(evt);
                break;
            case _f6:
                cancelaTecla(evt);
                var registro = XML2Array(xml);
                if(!registro[0]['ID_M_MENU']) return;

            	f_edicion.limpiar();
            	f_edicion.buscar(registro[0]['ID_M_MENU']);
            	mostrar_formulario(f_edicion);
				
				var xHtml = '';
					xHtml += '<div style="padding: 5px;">';
					xHtml += '<p>Ingresa en <a href="https://fontawesome.com/v6/icons/?m=free" target="_blank"> https://fontawesome.com/v6/icons/?m=free </a>';
					xHtml += ' , busca el icono deseado, seleccionalo, copia y pega el html en el campo Icono del formulario.</p>';
					xHtml += '<p>Para cambiar la medida, agrega el valor fa-1x o fa-2x o fa-3x </p>';
					xHtml += '</div>';
				$("#celda_f_edicion_CAMPO2").html(xHtml);
				
				var icono = f_edicion.getValue('ICONO');
				var xHtml = '';
					xHtml += '<div style="padding: 5px;">';
					xHtml += '<i  class="' + icono + '" style="color: var(--color-iconos-menu);" ></i>';
					xHtml += '</div>';
				
				$("#celda_f_edicion_CAMPO1").html(xHtml);
				
				$("#ICONO").on('keypress keydown change', function(){
					var icono = f_edicion.getValue('ICONO');
					
					icono = icono.replaceAll('<i','');
					icono = icono.replaceAll('class="','');
					icono = icono.replaceAll('>','');
					icono = icono.replaceAll('"','');
					icono = icono.replaceAll('<','');
					icono = icono.replaceAll('/i','');

					f_edicion.setValue('ICONO', icono);
					
					var xHtml = '';
						xHtml += '<div style="padding: 5px;">';
						xHtml += '<i  class="' + icono + '" style="color: var(--color-iconos-menu);" ></i>';
						xHtml += '</div>';
					$("#celda_f_edicion_CAMPO1").html(xHtml);
				});
				
				
            	break;
            case _f8:
                cancelaTecla(evt);
            	f_padre.limpiar();
            	mostrar_formulario(f_padre);
                break;

            case 187:
            case 107:
            	if(event.shiftKey && tecla==187||event.shiftKey && tecla==107) return;
                cancelaTecla(evt);
                var registro = valida_xml(xml,'ID_M_MENU');
                if(!registro)return;
                if(confirm('{$t_enviar_menu}'))
                {
                	fs.armar('menu/fs_aplica_grupos');
        			fs.xbusca = registro[0]['ID_M_MENU'];
        			fs.setValue('CAMPO1','*');
                	waitExec('{$t_realizando_proceso}', 'aplicaGrupos()', 5, 250, 283);
                }
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

function t_menu_grupo(objeto, tecla, xml, e)
{
        var evt = window.event || e;

          switch (tecla)
          {
            case _enter: // Enter
                cancelaTecla(evt);
                var registro = valida_xml(xml,'ID_I_MENU_GRUP');
            	if(!registro)return;
            	f_edicion_grupo.limpiar();
            	f_edicion_grupo.buscar(registro[0]['ID_I_MENU_GRUP']);
            	mostrar_formulario(f_edicion_grupo);
            	break;
            case _insert: // Insertar
                cancelaTecla(evt);
            	f_edicion_grupo.limpiar();
                f_edicion_grupo.setValue('ID_M_MENU',xid_m_menu);
            	f_edicion_grupo.setValue('POSICION',xposicion);
            	mostrar_formulario(f_edicion_grupo);
            	break;

            case _supr:
                cancelaTecla(evt);
            	var registro = valida_xml(xml,'ID_I_MENU_GRUP');
            	if(!registro)return;
                var borrar = confirm('{$t_eliminar_registro}');
                if(borrar)
                {
                    var url = server_path + "herramientas/utiles/actualizar_registro.php";
                    var param =  "origen=menu/menu_grupo&procedimiento=BORRARI&ID_I_MENU_GRUP=" + registro[0]['ID_I_MENU_GRUP'];
                    enviar(url,param,'POST');
                    waitExec('{$t_realizando_proceso}', 'menu_grupo.buscar(\'*\')', 5, 250, 283);
                }
                menu_grupo.setFocus();
                break;

            case _esc://Escape
                cancelaTecla(evt);
                m_menu.setFocus();
            	mostrarLeyenda(1);
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

function aplicaGrupos()
{
	var registro = fs.submit();
    if(!registro)
    {
        alert('{$t_error_guardar_area}');
        return;
    }
    m_menu.actualizar(xid_m_menu);
}

function Salir()
{
   parent.proceso.src = server_path + 'main/inicio.php';
}

function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_padre':
          t_f_padre('',_f12);
          break;
       case 'f_edicion':
          t_f_edicion('',_f12);
          break;

       case 'f_edicion_grupo':
          t_f_edicion_grupo('',_f12);
          break;
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_padre':
          t_f_padre('',_esc);
          break;

       case 'f_edicion':
          t_f_edicion('',_esc);
          break;

       case 'f_edicion_grupo':
          t_f_edicion_grupo('',_esc);
          break;

    }
}

function mostrar_formulario(xformulario)
{
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

function ocultar_formulario(xformulario)
{
    contenedor.ocultar();
    xformulario.ocultar();
}



function iniciar()
{
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);

	m_menu.inicializa(false);
	m_menu.mostrar();

	menu_grupo.inicializa(false);
	menu_grupo.mostrar();

	m_modulos.inicializa(false);
	centrarObj(m_modulos.contenedor);

	f_padre.inicializa();
	f_edicion.inicializa();
	f_edicion_grupo.inicializa();

	document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	addEvent(M_MENU_INS   , "click",   function() { t_m_menu('', _insert, m_menu.elementoXml()) } )
	addEvent(M_MENU_ENTER , "click",   function() { t_m_menu('', _enter, m_menu.elementoXml()) } )
	addEvent(M_MENU_SUPR  , "click",   function() { t_m_menu('', _supr, m_menu.elementoXml()) } )
	addEvent(M_MENU_ESC   , "click",   function() { t_m_menu('', _esc, m_menu.elementoXml()) } )
	addEvent(M_MENU_F3    , "click",   function() { t_m_menu('', _f3, m_menu.elementoXml()) } )
	addEvent(M_MENU_F6    , "click",   function() { t_m_menu('', _f6, m_menu.elementoXml()) } )
	addEvent(M_MENU_F8    , "click",   function() { t_m_menu('', _f8, m_menu.elementoXml()) } )

	addEvent(M_GRUPO_INSERT , "click",   function() { t_menu_grupo('', _insert, menu_grupo.elementoXml()) } )
	addEvent(M_GRUPO_SUPR   , "click",   function() { t_menu_grupo('', _supr, menu_grupo.elementoXml()) } )
	addEvent(M_GRUPO_ENTER  , "click",   function() { t_menu_grupo('', _enter, menu_grupo.elementoXml()) } )
	addEvent(M_GRUPO_ESC           , "click",   function() { t_menu_grupo('', _esc, menu_grupo.elementoXml()) } )

	addEvent(m_modulos_ENTER , "click",   function() { t_m_modulos('', _enter, m_modulos.elementoXml()) } )
	addEvent(m_modulos_ESC   , "click",   function() { t_m_modulos('', _esc, m_modulos.elementoXml()) } )

	return true;
}

function inicio()
{
    mostrarLeyenda(0);
	m_modulos.buscar();
	m_modulos.mostrar();
    m_modulos.setFocus();

}


var resp = iniciar();
if(resp)
{
        inicio(0);
}
else
{
        Salir();
}

</script>


</body>
</html>
