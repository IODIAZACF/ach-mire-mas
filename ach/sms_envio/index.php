<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$fecha 	= date("d/m/Y");
$ventana 	= getvar('ventana','modulo');
$id 		= getvar('id');

$id_m_usuario    = getsession('M_USUARIOS_ID_M_USUARIO');
$xempresa   = getsession('CONFIGURACION_NOMBRES');
$hora     = date("H:i");
$nombre_usuario = getsession('M_USUARIOS_NOMBRE_COMPLETO');

$my_ini 	= new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora,jquery');

echo <<<EOT

{$modulo->inicio}

<div id="contenido"> </div>

{$modulo->fin}

<script type="text/javascript">

var xvalida    = null;
var opcion	   = null;
var usuario    = '{$nombre_usuario}';
var id_usuario = '{$id_m_usuario}';
var xempresa   = '{$xempresa}';
var fecha      = '{$fecha}';
var hora       = '{$hora}';
var tiempo1	   = 0;
var t1;
var pXML,tXML;
//*************************************//

//se crea el contenedor
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
contenedor.ayuda	   = 1;
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.modalResult = true;
contenedor.onClose     = Cerrar_contenedor;

// Se Crea el Grid Base
d_sms              = new lista('sms_envio/d_sms');
d_sms.nombre       = 'd_sms';
d_sms.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
d_sms.usaCookie    = false;
d_sms.funcion      = t_d_sms;
d_sms.padre        = "contenido";
d_sms.buscador     = true;
d_sms.onSelect     = resetCuenta1;
d_sms.filtro	   = 'ID_M_USUARIOS';
d_sms.xfiltro	   = id_usuario;

// Se Crea el Formulario de Edición
f_edicion 			   = new formulario2('sms_envio/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.tipo		   = 'formulario';

f_agenda 			   = new formulario2('sms_envio/f_agenda');
f_agenda.nombre        = 'f_agenda';
f_agenda.funcion       = t_f_agenda;
f_agenda.padre         = 'contenedor_cuadro';
f_agenda.tipo		   = 'formulario';

f_grupos 			   = new formulario2('sms_envio/f_grupos');
f_grupos.nombre        = 'f_grupos';
f_grupos.funcion       = t_f_grupos;
f_grupos.padre         = 'contenedor_cuadro';
f_grupos.tipo		   = 'formulario';

f_estados 			   = new formulario2('sms_envio/f_estados');
f_estados.nombre       = 'f_estados';
f_estados.funcion      = t_f_estados;
f_estados.padre        = 'contenedor_cuadro';
f_estados.tipo		   = 'formulario';

f_dependencias 		   = new formulario2('sms_envio/f_dependencias');
f_dependencias.nombre  = 'f_dependencias';
f_dependencias.funcion = t_f_dependencias;
f_dependencias.padre   = 'contenedor_cuadro';
f_dependencias.tipo	   = 'formulario';

function t_d_sms(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _insert:
      cancelaTecla(evt);
      f_edicion.limpiar();
      f_edicion.setValue('FECHA_ENVIO',fecha);
      f_edicion.setValue('FECHA_ENVIO_H',hora);
      mostrar_formulario(f_edicion);
      break;

    case _enter:
  	  cancelaTecla(evt);
      var registro = valida_xml(xml,'ID_D_SMS');
      if(!registro)return;
      var reenviar = confirm('{$t_reenviar_sms}');
      if(reenviar)
      {
      	f_edicion.limpiar();
      	f_edicion.setValue('NUMERO',registro[0]['NUMERO']);
      	f_edicion.setValue('MENSAJE',registro[0]['MENSAJE']);
      	f_edicion.setValue('FECHA_ENVIO',fecha);
      	f_edicion.setValue('FECHA_ENVIO_H',hora);
        mostrar_formulario(f_edicion);
      }
      break;

    case _supr:
      cancelaTecla(evt);
      break;

    case _esc:
        cancelaTecla(evt);
        Salir();
      break;

    case _f1: // F1
	  cancelaTecla(evt);
      break;

    case _f2: // F2
	  cancelaTecla(evt);
      break;

    case _f3: // F3
      cancelaTecla(evt);
      break;

    case _f4: // F4
	  cancelaTecla(evt);
      break;

    case _f5: // F5
	  cancelaTecla(evt);
      break;

    case _f6: // F6
	  cancelaTecla(evt);
      f_agenda.limpiar();
      f_agenda.setValue('FECHA_ENVIO',fecha);
      f_agenda.setValue('FECHA_ENVIO_H',hora);
      mostrar_formulario(f_agenda);
      break;

    case _f7: // F7
    cancelaTecla(evt);
    f_grupos.limpiar();
    f_grupos.setValue('FECHA_ENVIO',fecha);
    f_grupos.setValue('FECHA_ENVIO_H',hora);
    mostrar_formulario(f_grupos);
      break;

    case _f8: // F8
	  cancelaTecla(evt);
      f_estados.limpiar();
      f_estados.setValue('FECHA_ENVIO',fecha);
      f_estados.setValue('FECHA_ENVIO_H',hora);
      mostrar_formulario(f_estados);
      break;

    case _f9: // F9
	  cancelaTecla(evt);
      f_dependencias.limpiar();
      f_dependencias.setValue('FECHA_ENVIO',fecha);
      f_dependencias.setValue('FECHA_ENVIO_H',hora);
      mostrar_formulario(f_dependencias);
      break;

    case _f10: // F10
	  cancelaTecla(evt);
      break;

    case _f11: // F10
	  cancelaTecla(evt);
      break;

    case _f12: // F10
	  cancelaTecla(evt);
      break;
  }
}

function t_f_edicion(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _esc: //Escape
    	cancelaTecla(evt);
		ocultar_formulario(f_edicion,d_sms);
      	break;

    case _f12: // F12 Guardar_item
    	cancelaTecla(evt);
        Enviar();
      	break;
  }
}

function t_f_grupos(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _esc: //Escape
    	cancelaTecla(evt);
		ocultar_formulario(f_grupos,d_sms);
      	break;

    case _f12: // F12 Guardar_item
    	cancelaTecla(evt);
        Enviar();
      	break;
  }
}

function t_f_estados(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _esc: //Escape
    	cancelaTecla(evt);
		ocultar_formulario(f_estados,d_sms);
      	break;

    case _f12: // F12 Guardar_item
    	cancelaTecla(evt);
        Enviar();
      	break;
  }
}

function t_f_dependencias(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _esc: //Escape
    	cancelaTecla(evt);
		ocultar_formulario(f_dependencias,d_sms);
      	break;

    case _f12: // F12 Guardar_item
    	cancelaTecla(evt);
        Enviar();
      	break;
  }
}

function t_f_agenda(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _esc: //Escape
    	cancelaTecla(evt);
		ocultar_formulario(f_agenda,d_sms);
      	break;

    case _f12: // F12 Guardar_item
    	cancelaTecla(evt);
        Enviar();
      	break;
  }
}

function Enviar()
{
        var envio = fechaMDY(f_activo.getValue('FECHA_ENVIO'))+' '+f_activo.getValue('FECHA_ENVIO_H');
      	f_activo.setValue('FECHA_ENVIO',envio);
        var registro = f_activo.submit();
        if(!registro)return;
        f_activo.limpiar();
        ocultar_formulario(f_activo,d_sms);
        d_sms.buscar('*');

		/*
        var aCampos =new Array();
        aCampos['NUMERO']  = new Campo(f_activo.getValue('NUMERO'));
        aCampos['MENSAJE'] = new Campo(f_activo.getValue('MENSAJE'));
        aCampos['FECHA_ENVIO'] = new Campo(envio);
        var x = actualizar_registro('D_SMS',aCampos,'ID_D_SMS','-1',null,'=');
        var registro = XML2Array(x);
        if(registro[0])
        {
            f_activo.limpiar();
        	ocultar_formulario(f_activo,d_sms);
            d_sms.buscar('*');
        }
        */
}

function Enviar_grupo()
{
        var envio = fechaMDY(f_grupos.getValue('FECHA_ENVIO'))+' '+f_grupos.getValue('FECHA_ENVIO_H');
        var aCampos =new Array();
        aCampos['ID_M_GRUPOS']  = new Campo(f_grupos.getValue('ID_M_GRUPOS'));
        aCampos['MENSAJE'] = new Campo(f_grupos.getValue('MENSAJE'));
        aCampos['FECHA_ENVIO'] = new Campo(envio);
        var x = actualizar_registro('D_SMS',aCampos,'ID_D_SMS','-1',null,'=');
        var registro = XML2Array(x);
        if(registro[0])
        {
            f_grupos.limpiar();
        	ocultar_formulario(f_grupos,d_sms);
            d_sms.buscar('*');
        }
}

function Enviar_dependencia()
{
        var envio = fechaMDY(f_dependencias.getValue('FECHA_ENVIO'))+' '+f_dependencias.getValue('FECHA_ENVIO_H');
        var aCampos =new Array();
        aCampos['ID_M_DEPENDENCIAS']  = new Campo(f_dependencias.getValue('ID_M_DEPENDENCIAS'));
        aCampos['MENSAJE'] = new Campo(f_dependencias.getValue('MENSAJE'));
        aCampos['FECHA_ENVIO'] = new Campo(envio);
        var x = actualizar_registro('D_SMS',aCampos,'ID_D_SMS','-1',null,'=');
        var registro = XML2Array(x);
        if(registro[0])
        {
            f_dependencias.limpiar();
        	ocultar_formulario(f_dependencias,d_sms);
            d_sms.buscar('*');
        }
}

function Enviar_estado()
{
        var envio = fechaMDY(f_estados.getValue('FECHA_ENVIO'))+' '+f_estados.getValue('FECHA_ENVIO_H');
        var aCampos =new Array();
        aCampos['ID_M_ESTADOS']  = new Campo(f_estados.getValue('ID_M_ESTADOS'));
        aCampos['MENSAJE'] = new Campo(f_estados.getValue('MENSAJE'));
        aCampos['FECHA_ENVIO'] = new Campo(envio);
        var x = actualizar_registro('D_SMS',aCampos,'ID_D_SMS','-1',null,'=');
        var registro = XML2Array(x);
        if(registro[0])
        {
            f_estados.limpiar();
        	ocultar_formulario(f_estados,d_sms);
            d_sms.buscar('*');
        }
}

function actualizaDetalles()
{
	actualizaHTML(m_principal.elementoXml(),'ENCABEZADO');
}

function actualizaPie()
{
	actualizaHTML(d_sms.elementoXml(),'PIE');
}

function Salir()
{
   location.href = server_path + 'main/inicio.php';
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
    xformulario.setFocus();
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
          case 'f_edicion':
          t_f_edicion('',_f12);
          break;

           case 'f_grupos':
          t_f_grupos('',_f12);
          break;

          case 'f_agenda':
          t_f_agenda('',_f12);
          break;

          case 'f_estados':
          t_f_estados('',_f12);
          break;

          case 'f_dependencias':
          t_f_dependencias('',_f12);
          break;


    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
          case 'f_edicion':
          t_f_edicion('',_esc);
          break;

          case 'f_grupos':
          t_f_grupos('',_esc);
          break;

           case 'f_agenda':
          t_f_agenda('',_esc);
          break;

           case 'f_estados':
          t_f_estados('',_esc);
          break;

          case 'f_dependencias':
          t_f_dependencias('',_esc);
          break;
    }
}

function Refrescar()
{
    d_sms.buscar('*');
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
    d_sms.localiza('ID_D_SMS',registro[0]['ID_D_SMS']);
}

function escribeNumero(obj,xml)
{
    var registro = valida_xml(xml,'NUMERO');
    f_edicion.setValue('NUMERO',registro[0]['NUMERO'].replace(/[()-]/gi,""));
}

function resetCuenta1()
{
    tiempo1=10;
    clearTimeout(t1);
    t1 = setInterval(Cuenta1,500);
    tXML = d_sms.elementoXml();

}

function iniciar()
{
      contenedor.inicializa();
      centrarObj(contenedor.contenedor);

      d_sms.inicializa(false);
      d_sms.mostrar();

      f_edicion.inicializa();
      f_grupos.inicializa();
      f_estados.inicializa();
      f_dependencias.inicializa();
      f_agenda.inicializa();

      document.onclick=function() { if (parent.menu) parent.menu.reset(); }

      addEvent(INS, 	"click",   function() { t_d_sms('', _insert,  d_sms.elementoXml()) } );
      addEvent(ENTER, 	"click",   function() { t_d_sms('', _enter,  d_sms.elementoXml()) } );
      addEvent(ESC, 	"click",   function() { t_d_sms('', _esc, 	d_sms.elementoXml()) } );
      addEvent(F6,  	"click",   function() { t_d_sms('', _f6,  	d_sms.elementoXml()) } );
      addEvent(F7,  	"click",   function() { t_d_sms('', _f7,  	d_sms.elementoXml()) } );
      addEvent(F8,  	"click",   function() { t_d_sms('', _f8,  	d_sms.elementoXml()) } );
//      addEvent(F9,  	"click",   function() { t_d_sms('', _f9,  	d_sms.elementoXml()) } );

     return true;

}

function inicio()
{
	d_sms.setFocus();
    d_sms.buscar();
}

var resp = iniciar();

if(!resp)
{
	Salir();
}
else
{
	inicio();
}

</script>

</body>
</html>

EOT;

?>