<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
$xfecha     = date("d/m/Y");

$xid_m_usuarios = getsession('M_USUARIOS_ID_M_USUARIO');
$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('utiles,auto_tabla,formulario2,forma,tabpane,submodal,jquery,impresora');

echo <<<EOT

{$modulo->inicio}
<table border="0">
	<tr>
		<td id="GRUPO1" ></td>
		<td id="GRUPO2" ></td>
	</tr>
</table>
{$modulo->fin}

<script type="text/javascript">

var xformulario        = null;
var t                  = null;
var xbanco             = null;
var impresora          = new printer();

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 350;
contenedor.alto        = 270;
contenedor.x           = 100;
contenedor.y           = 50;
contenedor.titulo      = 'Edicion';
contenedor.botonCerrar = true;
contenedor.leyenda     = '   ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.ayuda       = 502;
contenedor.modal            = true;

m_bancos              = new lista("maestro_bancos/m_bancos")
m_bancos.nombre              = "m_bancos";
m_bancos.padre               = "GRUPO1";
m_bancos.buscador     = true;
m_bancos.url          = server_path + "herramientas/genera_xml/genera_xml.php";
m_bancos.funcion             = t_m_bancos;
m_bancos.onSelect     = actualizar_detalle;
m_bancos.onFocus      = m_bancos_focus;

d_banco                 = new lista("maestro_bancos/d_banco")
d_banco.nombre          = "d_banco";
d_banco.padre           = "GRUPO2";
d_banco.url             = server_path + "herramientas/genera_xml/genera_xml.php";
d_banco.funcion         = t_d_banco;
d_banco.buscador        = true;
d_banco.noOcultarCombos = true;
d_banco.filtro          = 'ID_M_BANCOS';
d_banco.onFocus                    = d_banco_focus;

f_bancos                         = new formulario2('maestro_bancos/f_bancos');
f_bancos.nombre       = 'f_bancos';
f_bancos.padre        = 'contenedor_cuadro';
f_bancos.funcion      = t_f_bancos;

f_d_bancos                         = new formulario2('maestro_bancos/f_d_bancos');
f_d_bancos.nombre       = 'f_d_bancos';
f_d_bancos.padre        = 'contenedor_cuadro';
f_d_bancos.funcion      = t_f_d_bancos;

function m_bancos_focus()
{
        mostrarLeyenda(0);
}

function d_banco_focus()
{
        mostrarLeyenda(1);
}

function actualizar_detalle (obj,xml)
{
    if (t) window.clearTimeout(t);
          t = window.setTimeout('act_timer()',500);
}

function act_timer()
{
    var xml = m_bancos.elementoXml();
    var registro = XML2Array(xml);
         if(!registro[0] || !registro[0]['ID_M_BANCOS'])
    {
            d_banco.xfiltro = '';
        d_banco.limpiar();
    }
    else
    {
            xbancos=registro[0]['ID_M_BANCOS'];
            d_banco.xfiltro = xbancos;
        d_banco.buscar('*');
    }
    m_bancos_focus();
}

function t_m_bancos(objeto, tecla, xml,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _enter:
            cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_M_BANCOS')
        if(!registro)return;
        xbanco=registro[0]['ID_M_BANCOS'];
        mostrarLeyenda(1);
            d_banco.setFocus();
            break;

    case _insert:
        cancelaTecla(evt);
        f_bancos.limpiar();
        mostrar_formulario(f_bancos);
        f_bancos.setFocus();
            break;

    case _f6:
            cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_M_BANCOS');
        if(!registro) return;
        xbanco=registro[0]['ID_M_BANCOS'];
        f_bancos.buscar(xbanco);
        mostrar_formulario(f_bancos);
        f_bancos.setFocus();
            break;

    case _supr:
        cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_M_BANCOS');
        if(!registro) return;
        var eliminar = confirm('{$t_eliminar_registro}');
        if(eliminar)
        {
          var url = server_path + 'herramientas/utiles/actualizar_registro.php';
          var param = 'tabla=M_BANCOS&c_ESTATUS_CSS=INA&busca=ID_M_BANCOS&xbusca='+ registro[0]['ID_M_BANCOS'];
          var x = enviar(url,param,'POST');
        }
        m_bancos.buscar('*');
        mostrarLeyenda(0);
        break;

    case _esc:
         cancelaTecla(evt);
         Salir();
         break;

    case _f4:
         cancelaTecla(evt);
         var registro = valida_xml(xml,'ID_M_BANCOS');
         if(!registro)return;
         Imprimir(registro[0]['ID_M_BANCOS']);
         break;

    case _f3:
    case _f5:
    case _f12:
         cancelaTecla(evt);
         break;
  }
}

function t_d_banco(objeto, tecla, xml,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _insert:
            cancelaTecla(evt);
               f_d_bancos.limpiar();
            f_d_bancos.setValue('ID_M_BANCOS',xbanco);
                mostrar_formulario(f_d_bancos);
        f_d_bancos.setFocus();
            break;

    case _supr:
                cancelaTecla(evt);
        var registro = XML2Array(xml);
        if(!registro[0]['ID_I_TIPO_BANC']) return;
                  var eliminar = confirm('{$t_eliminar_registro}');
                   if(eliminar)
                   {
                var url = server_path + 'herramientas/utiles/actualizar_registro.php';
                var param = 'tabla=I_TIPO_BANC&c_ESTATUS_CSS=INA&busca=ID_I_TIPO_BANC&xbusca='+ registro[0]['ID_I_TIPO_BANC'];
                var x = enviar(url,param,'POST');
        }
                d_banco.buscar('*');
        d_banco.setFocus();
        mostrarLeyenda(1);
            break;

        case _enter:
                cancelaTecla(evt);
                var registro = valida_xml(xml,'ID_I_TIPO_BANC');
        if(!registro) return;
        f_d_bancos.buscar(registro[0]['ID_I_TIPO_BANC']);
        mostrar_formulario(f_d_bancos);
        setTimeout('f_d_bancos.setFocus();',10);
                break;

    case _esc:
            cancelaTecla(evt);
        mostrarLeyenda(1);
            m_bancos.setFocus();
            break;
  }

}

function t_f_bancos(obj, tecla, evt, e)
{
   var evt = window.event || e;

  switch (tecla)
  {
    case _f12: // F12
            cancelaTecla(evt);
            var registro =f_bancos.submit();
            if(!registro) return false;
        ocultar_formulario(f_bancos);
        m_bancos.actualizar(xbanco);
            m_bancos.setFocus();
    break;
    case _esc://Salir
        ocultar_formulario(f_bancos);
        m_bancos.setFocus();
              break;
  }
}

function t_f_d_bancos(obj, tecla, evt, e)
{
   var evt = window.event || e;

  switch (tecla)
  {
    case _f12: // F12
            cancelaTecla(evt);
            var registro =f_d_bancos.submit();
            if(!registro) return false;
        ocultar_formulario(f_d_bancos);
        d_banco.actualizar(registro[0]['ID_I_TIPO_BANC']);
            d_banco.setFocus();
    break;
    case _esc://Salir
        ocultar_formulario(f_d_bancos);
        d_banco.setFocus();
              break;
  }
}

function Imprimir(reg)
{
  impresora.origin="reportes/r_cheque_prueba";
  impresora.setParam("ID_M_BANCOS",reg);
  impresora.print();
  //impresora.preview();
  m_bancos.setFocus();
}

function Guardar_formulario()
{
    switch(f_activo.nombre)
    {
        case 'f_bancos':
           t_f_bancos('',_f12);
           break;
        case 'f_d_bancos':
           t_f_d_bancos('',_f12);
           break;
    }
}

function Cerrar_formulario()
{
    switch(f_activo.nombre)
    {
        case 'f_bancos':
           t_f_bancos('',_esc);
           break;
        case 'f_d_bancos':
           t_f_d_bancos('',_esc);
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

function ocultar_formulario(xformulario)
{
    xformulario.ocultar();
    contenedor.ocultar();
    f_activo=null;
}

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function iniciar()
{
    contenedor.inicializa();
    centrarObj(contenedor.contenedor);

    m_bancos.inicializa(false);
    m_bancos.mostrar();
    m_bancos.buscar('*');

    d_banco.inicializa(false);
    d_banco.mostrar();

    f_bancos.inicializa(false);
    f_d_bancos.inicializa(false);

    addEvent(M_BANCOS_ENTER, "click",   function() { t_m_bancos('', _enter,  m_bancos.elementoXml()) } )
    addEvent(M_BANCOS_INS,   "click",   function() { t_m_bancos('', _insert, m_bancos.elementoXml()) } )
    addEvent(M_BANCOS_F4,    "click",   function() { t_m_bancos('', _f4,     m_bancos.elementoXml()) } )
    addEvent(M_BANCOS_SUPR,  "click",   function() { t_m_bancos('', _supr,   m_bancos.elementoXml()) } )
    addEvent(M_BANCOS_F6,    "click",   function() { t_m_bancos('', _f6,     m_bancos.elementoXml()) } )
    addEvent(M_BANCOS_ESC,   "click",   function() { t_m_bancos('', _esc,    m_bancos.elementoXml()) } )

    addEvent(D_BANCO_INS,    "click",   function() { t_d_banco('', _insert,	d_banco.elementoXml()) } )
    addEvent(D_BANCO_ENTER,  "click",   function() { t_d_banco('', _enter,	d_banco.elementoXml()) } )
    addEvent(D_BANCO_SUPR,   "click",   function() { t_d_banco('', _supr,  	d_banco.elementoXml()) } )
    addEvent(D_BANCO_ESC,    "click",   function() { t_d_banco('', _esc,   	d_banco.elementoXml()) } )

    document.onclick=function() { if (parent.menu) parent.menu.reset(); }

    return true;
}
function inicio(recargar)
{
    mostrarLeyenda(0);
    m_bancos.setFocus();
}

var resp = iniciar();
if(resp)
{
        inicio(0);
}else
{
        Salir();
}

</script>

EOT;

?>