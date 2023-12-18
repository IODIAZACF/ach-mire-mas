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

<table border="0">
<tr>
<td id="contenido" style="vertical-align: text-top;"></td>
<td class="grid_cuadro_activo">
	<div id="asunto"  class="grid_encab"></div>
	<iframe id="mensaje" scrolling="yes" class="scroll" frameborder="0"></iframe>
</td>
</tr>
</table>

{$modulo->fin}

<style>
.titulo {
    width: 659px;
    height: 50px;
	padding: 20px;
}

.scroll {
    width: 712px;
    height: 500px;
	padding: 0px;
}

</style>

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
d_email              = new lista('email_envio/d_email');
d_email.nombre       = 'd_email';
d_email.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
d_email.usaCookie    = false;
d_email.funcion      = t_d_email;
d_email.padre        = "contenido";
d_email.buscador     = true;
d_email.onSelect  	 = mostrar_mail;
d_email.filtro	   	 = 'ID_M_USUARIOS';
d_email.xfiltro	     = id_usuario;

// Se Crea el Formulario de Edición
f_edicion 			   = new formulario2('email_envio/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.tipo		   = 'formulario';

f_agenda 			 = new formulario2('email_envio/f_agenda');
f_agenda.nombre       = 'f_agenda';
f_agenda.funcion      = t_f_agenda;
f_agenda.padre        = 'contenedor_cuadro';
f_agenda.tipo		 = 'formulario';

f_grupos 			 = new formulario2('email_envio/f_grupos');
f_grupos.nombre       = 'f_grupos';
f_grupos.funcion      = t_f_grupos;
f_grupos.padre        = 'contenedor_cuadro';
f_grupos.tipo		 = 'formulario';



function t_d_email(objeto, tecla, xml, e)
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
      var registro = valida_xml(xml,'ID_D_CORREO');
      if(!registro)return;
      var reenviar = confirm('{$t_reenviar_sms}');
      if(reenviar)
      {
      	f_edicion.limpiar();
      	f_edicion.setValue('DESTINATARIO',registro[0]['DESTINATARIO']);
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
      break;

    case _f9: // F9
	  cancelaTecla(evt);
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
		ocultar_formulario(f_edicion,d_email);
      	break;

    case _f12: // F12 Guardar_item
    	cancelaTecla(evt);
        var envio = fechaMDY(f_activo.getValue('FECHA_ENVIO'))+' '+f_activo.getValue('FECHA_ENVIO_H');
      	f_activo.setValue('FECHA_ENVIO',envio);
        f_activo.submit();
        f_activo.limpiar();
        ocultar_formulario(f_activo,d_email);
        d_email.buscar('*');

        //Enviar();
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
		ocultar_formulario(f_grupos,d_email);
      	break;

    case _f12: // F12 Guardar_item
    	cancelaTecla(evt);
        Enviar_grupo();
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
		ocultar_formulario(f_agenda,d_email);
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
        var aCampos =new Array();
        aCampos['DESTINATARIO']  = new Campo(f_activo.getValue('DESTINATARIO'));
        aCampos['MENSAJE'] = new Campo(f_activo.getValue('MENSAJE'));
        aCampos['FECHA_ENVIO'] = new Campo(envio);
        var x = actualizar_registro('D_CORREO',aCampos,'ID_D_CORREO','-1',null,'=');
        var registro = XML2Array(x);
        if(registro[0])
        {
            f_activo.limpiar();
        	ocultar_formulario(f_activo,d_email);
            d_email.buscar('*');
        }
}

function Enviar_grupo()
{
        var envio = fechaMDY(f_grupos.getValue('FECHA_ENVIO'))+' '+f_grupos.getValue('FECHA_ENVIO_H');
        var aCampos =new Array();
        aCampos['ID_M_GRUPOS']  = new Campo(f_grupos.getValue('ID_M_GRUPOS'));
        aCampos['MENSAJE'] = new Campo(f_grupos.getValue('MENSAJE'));
        aCampos['FECHA_ENVIO'] = new Campo(envio);
        var x = actualizar_registro('D_CORREO',aCampos,'ID_D_CORREO','-1',null,'=');
        var registro = XML2Array(x);
        if(registro[0])
        {
            f_grupos.limpiar();
        	ocultar_formulario(f_grupos,d_email);
            d_email.buscar('*');
        }
}

function actualizaDetalles()
{
	actualizaHTML(m_principal.elementoXml(),'ENCABEZADO');
}

function actualizaPie()
{
	actualizaHTML(d_email.elementoXml(),'PIE');
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
    }
}

function Refrescar()
{
    d_email.buscar('*');
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
    d_email.localiza('ID_D_CORREO',registro[0]['ID_D_CORREO']);
}

function mostrar_mail(obj,xml){
	
	var registro = valida_xml(xml,'ID_D_CORREO');
	if(!registro) return;
		$('#asunto').html("&nbsp;Asunto: " + registro[0]['ASUNTO']);
		
		
		var rand_no = Math.ceil(100000000*Math.random());
		var url = server_path + 'email_envio/email_parser.php?rndid='+rand_no+'&ID_D_CORREO=' + registro[0]['ID_D_CORREO'];
		//setTimeout(function(){ alert("Hello"); }, 3000);		
		$('#mensaje').attr('src', url);
/*
		$.get( url, function( data ) 
		{
		});		
*/		
	
	
}



function escribeDestinatario(obj,xml)
{
    var registro = valida_xml(xml,'DESTINATARIO');
    f_edicion.setValue('DESTINATARIO',registro[0]['DESTINATARIO'].replace(/[()-]/gi,""));
}

function resetCuenta1()
{
    tiempo1=10;
    clearTimeout(t1);
    t1 = setInterval(Cuenta1,500);
    tXML = d_email.elementoXml();

}

function iniciar()
{
      contenedor.inicializa();
      centrarObj(contenedor.contenedor);

      d_email.inicializa(false);
      d_email.mostrar();

      f_edicion.inicializa();
      f_grupos.inicializa();
      f_agenda.inicializa();

      document.onclick=function() { if (parent.menu) parent.menu.reset(); }

      addEvent(INS, 	"click",   function() { t_d_email('', _insert,  d_email.elementoXml()) } );
      addEvent(ENTER, 	"click",   function() { t_d_email('', _enter,  d_email.elementoXml()) } );
      addEvent(ESC, 	"click",   function() { t_d_email('', _esc, 	d_email.elementoXml()) } );
      addEvent(F6,  	"click",   function() { t_d_email('', _f6,  	d_email.elementoXml()) } );
      addEvent(F7,  	"click",   function() { t_d_email('', _f7,  	d_email.elementoXml()) } );

     return true;

}

function inicio()
{
	d_email.setFocus();
    d_email.buscar();
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