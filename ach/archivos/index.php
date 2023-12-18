<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha         = date("d/m/Y");
$ventana        = getvar('ventana','modulo');
$id             = getvar('id');

$id_m_usuario = getsession('M_USUARIOS_ID_M_USUARIO');

$query = new sql();
$query->sql = "SELECT ID_M_VENDEDORES,ID_M_USUARIO FROM M_VENDEDORES WHERE ID_M_USUARIO='". $id_m_usuario ."'";
$query->ejecuta_query();
$query->next_record();
$xid_m_vendedores 	 = $query->Record['ID_M_VENDEDORES'];

$id_m_usuarios    = getsession('M_USUARIOS_ID_M_USUARIO');
$db               = getsession('db');

$my_ini         = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";

cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,jquery,clave,upload24,scanner24');

echo <<<EOT

<style type="text/css">
#xxlink { color: red;
	border:2px;
}

</style>


{$modulo->inicio}
<div id="contenido_view2"></div>

<table class="contenido_modulo" border="0" >
	<tr valign="top">
		<td>
			<table >
				<tr>
					<td id="GRID1"> 
					</td>
				</tr>
				<tr>
					<td id="GRID2"> 
					</td>
				</tr>
			</table>
		</td>
		<td id="GRUPO2" style="background-color:#C0C0C0;" align="center" width="900" heigth="490">
			<div id="load_digital_imagenes"><img src="../imagenes/utiles/loading3.gif"></div>
			<div id="digital_imagenes" align="right"></div>
		</td>
	</tr>
</table>

{$modulo->fin}

<script src='jquery.elevatezoom.js'></script>
<script type="text/javascript">

var upload = new upload24();
var scanner = new scanner24();

var xhost = server_path.replace(xdb,'archivos');

var xdb 			= '{$db}';
var xalumnos = null;
var fecha = '{$xfecha}';
var dxml   		  = null;
var t             = null;
var xcarpeta	  = null;
var idx 		  = null;
var idxa          = null;
var xID_M_USUARIOS 		='{$id_m_usuarios}';
var xID_M_VENDEDORES    = '{$xid_m_vendedores}';
var xORIGEN				= null;
var xubicacion    		= null;
xclave            		= new clave('xclave');
var nombre_archivo  	= null;


contenedor_upload      = new submodal();
contenedor_upload.nombre      = 'contenedor_upload';
contenedor_upload.ancho       = 800;
contenedor_upload.alto        = 450;
contenedor_upload.titulo      = ' ';
contenedor_upload.x           = 1;
contenedor_upload.y           = 1;
contenedor_upload.botonCerrar = false;
contenedor_upload.titulo      = 'Cargar Archivo';
contenedor_upload.leyenda     = creaLeyenda();
contenedor_upload.ayuda       = 503;
contenedor_upload.usaFrame    = true;
contenedor_upload.interpretar = false;
contenedor_upload.modal       = true;


//se crea el contenedor
contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 800;
contenedor.alto        = 400;
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

documentos              = new lista('archivos/documentos');
documentos.nombre       = 'documentos';
documentos.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
documentos.usaCookie    = false;
documentos.funcion      = t_documentos;
documentos.padre        = "GRID2";
documentos.buscador     = true;
documentos.onSelect     = actualizaPie;
documentos.filtro       = 'IDX;TABLA;ORIGEN';
documentos.onFocus		= documentosFocus;

m_origenes              = new lista('archivos/m_origenes');
m_origenes.nombre       = 'm_origenes';
m_origenes.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
m_origenes.usaCookie    = false;
m_origenes.funcion      = t_m_origenes;
m_origenes.padre        = "GRID1";
m_origenes.buscador     = false;
m_origenes.onSelect     = actualizaOrigen;
m_origenes.filtro       = 'IDX;TABLA;ORIGEN';
m_origenes.onFocus		= m_origenesFocus;

m_proyectos             = new lista("archivos/m_proyectos")
m_proyectos.nombre      = "m_proyectos";
m_proyectos.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_proyectos.funcion     = t_m_proyectos;
m_proyectos.buscador    = true;
m_proyectos.modal       = true;
m_proyectos.botonCerrar = true;
m_proyectos.enter       = 1;
m_proyectos.usaCookie   = true;
/*
if(xID_M_VENDEDORES){
	m_proyectos.filtro            = 'ID_M_VENDEDORES';
	m_proyectos.xfiltro            = xID_M_VENDEDORES;
}
*/

f_edicion              = new formulario2('archivos/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.tipo         = 'formulario';

f_edicion2              = new formulario2('archivos/f_edicion2');
f_edicion2.nombre       = 'f_edicion2';
f_edicion2.funcion      = t_f_edicion2;
f_edicion2.padre        = 'contenedor_cuadro';
f_edicion2.tipo         = 'formulario';

f_identidad              = new formulario2('archivos/f_identidad');
f_identidad.nombre       = 'f_identidad';
f_identidad.funcion      = t_f_identidad;
f_identidad.padre        = 'contenedor_cuadro';
f_identidad.tipo         = 'formulario';

f_compra              = new formulario2('archivos/f_compra');
f_compra.nombre       = 'f_compra';
f_compra.funcion      = t_f_compra;
f_compra.padre        = 'contenedor_cuadro';
f_compra.tipo         = 'formulario';


function creaLeyenda(){
	var l = '';
	l += '<center><table class="tabla_leyenda">';
	l += '<tr>';
	l += etiqLeyenda('ESC', '{$t_salir}', '90', 'Cerrar()');
	l += '</tr>';
	l += '</table></center>';
	return l;
}

function Cerrar(){
	contenedor_upload.ocultar();
}


function etiqLeyenda(tecla, texto, ancho, accion){
	var e = ' <td onselectstart="return false;" style="width:'+ancho+'px"; class="td_leyenda_inactiva" onmouseup="this.className=\\'td_leyenda_activa\\'" onmouseover="this.className=\\'td_leyenda_activa\\'" onmousedown="this.className=\\'td_leyenda_click\\'" onmouseout="this.className=\\'td_leyenda_inactiva\\'" onclick="'+accion+'">[<B>'+tecla+'</B>]<br>'+texto+'</td>';
	return e;
}


function m_origenesFocus(){
	mostrarLeyenda(0);
}

function documentosFocus(){
	mostrarLeyenda(1);
}



        

function actualizaOrigen(obj,xml)
{
  dxml = xml;
  if (t) window.clearTimeout(t);
  t = window.setTimeout('ver_origen(dxml)',300);
}

function ver_origen(xml)
{
	var registro = valida_xml(xml,'NOMBRE_ORIGEN');
    if(!registro)return;
	
	xORIGEN=registro[0]['NOMBRE_ORIGEN'];
	documentos.xfiltro = idx+';M_PROYECTOS;'+xORIGEN;
	documentos.buscar('*');
	$('#digital_imagenes').empty();
}

function actualizaPie(obj,xml)
{
  dxml = xml;
  if (t) window.clearTimeout(t);
  t = window.setTimeout('act_timer(dxml)',300);
}

function act_timer(xml)
{
    var registro = valida_xml(xml,'ID_D_ARCHIVOS');
    if(!registro)return;
    actualizaHTML(xml,'PIE');
    idxa = registro[0]['ID_D_ARCHIVOS'];
  	archivos(xml);
}


function t_m_proyectos (objeto, tecla, xml,e)
{
   var evt = window.event || e;
   switch (tecla)
   {
    case _enter:
      var registro = valida_xml(xml,'ID_M_PROYECTOS');
      if(!registro)return;

      m_proyectos.ocultar();
      idx = registro[0]['ID_M_PROYECTOS'];
      xcarpeta = 'imagenes/expediente/'+ idx;
      xubicacion = '0011';
	  actualizaDetalles();
	  m_origenes.buscar('*');
	  m_origenes.setFocus();

      break;

    case _esc:
      cancelaTecla();
      if(idx==null)
      {
       Salir();
       return;
      }
      m_proyectos.ocultar();
      documentos.setFocus();
      break;
  }
}

function t_m_origenes(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
	 case _enter:
      cancelaTecla();
	  documentos.setFocus();
      break;
	  
	 case _esc:
      cancelaTecla();
	  Salir();
      break; 
  }
}

function t_documentos(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _supr:
      cancelaTecla(evt);
   /*   var registro = valida_xml(xml,'ID_D_ARCHIVOS');
      if(!registro)return;
      var xdias_registrado= registro[0]['DIAS_REGISTRADO'];
      if(xdias_registrado!=0)
      {
         alert('{$t_operacion_no_permitida}');
         return;
      }
      cancelaTecla(evt);
        pideClave('elimnarFoto',8,true);*/
      eliminar();
      break;
     case _enter:
      cancelaTecla(evt);
     /* var registro = valida_xml(xml,'ID_D_ARCHIVOS');
      if(!registro)return;
      mostrarLeyenda(1);*/
      break;
    case _f1:
    case _f2:
      cancelaTecla(evt);
      break;
    case _f3:
      cancelaTecla(evt);
      m_proyectos.mostrar();
      m_proyectos.setFocus();
      break;
    case _f4:
      	cancelaTecla(evt);
      	var registro = valida_xml(xml,'ID_D_ARCHIVOS');
      	if(!registro)return;
        idxa = registro[0]['ID_D_ARCHIVOS'];
       	scanner.multipagina='0';
        nombre_archivo = registro[0]['NOMBRES'];
    	alert('No hay comunicacion con el dipositivo');
      break;

    case _f5:
      cancelaTecla(evt);
      	var registro = valida_xml(xml,'ID_D_ARCHIVOS');
      	if(!registro)return;
        idxa = registro[0]['ID_D_ARCHIVOS'];
        nombre_archivo = registro[0]['DESCRIPCION'];
    	
		contenedor_upload.destruir();
		contenedor_upload.url = server_path  + 'archivos/upload.php?IDX=' + idxa + '&NOMBRES=' + nombre_archivo  + '&CARPETA=' + xcarpeta + '&DB=' + '{$db}';
		contenedor_upload.inicializa();
		centrarObj(contenedor_upload.contenedor);
		contenedor_upload.mostrar();
		contenedor_upload.setFocus();

      break;
      break;

     case _f6:
      cancelaTecla(evt);
      break;

    case _f7:
      var registro = valida_xml(xml,'IDX');
      if(!registro)return;
	  descargar_imagen();
      break;
    case _f8:
      cancelaTecla(evt);
      var registro = valida_xml(xml,'IDX');
      if(!registro)return;
	  descargar_expediente();
      break;
    case _f9:
      cancelaTecla(evt);
      girar();

      break;
    case _f10:
		cancelaTecla(evt);
		f_identidad.limpiar();
		f_identidad.setValue('IDX',idx);
		f_identidad.setValue('TABLA','M_PROYECTOS');
		f_identidad.setValue('ORIGEN',xORIGEN);
		f_identidad.setValue('FECHA_DOCUMENTO',fecha);
		mostrar_formulario(f_identidad);
	break;
    case _f11:
		cancelaTecla(evt);
		f_compra.limpiar();
		f_compra.setValue('IDX',idx);
		f_compra.setValue('TABLA','M_PROYECTOS');
		f_compra.setValue('ORIGEN',xORIGEN);
		f_compra.setValue('FECHA_DOCUMENTO',fecha);
		mostrar_formulario(f_compra);
	break;

    case _f12:
      cancelaTecla(evt);
      break;
    case _esc:
      cancelaTecla(evt);
	  m_origenes.setFocus();
      
      break;
  }
}

function modUbicacion(confirmado)
{
      if(!confirmado)
      {
        xclave.hide();
        documentos.setFocus();
      }
      else
      {
          var xml=documentos.elementoXml();
          var registro = XML2Array(xml);
          if(!registro[0]) return;
          var xid = registro[0]['ID'];
          xclave.hide();
          f_edicion2.buscar(registro[0]['IDX']);
          mostrar_formulario(f_edicion2);
       }
}

function elimnarFoto(confirmado)
{
      if(!confirmado)
      {
        xclave.hide();
        documentos.setFocus();
      }
      else
      {
          var xml=documentos.elementoXml();
          var registro = XML2Array(xml);
          if(!registro[0]) return;
          var xid = registro[0]['ID'];
          xclave.hide();
          eliminar();
       }
}


function t_f_edicion(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc:
      cancelaTecla(evt);
      ocultar_formulario(f_edicion,documentos);
      break;

    case _f12:
      cancelaTecla(evt);
      var accion = f_edicion.getValue('xbusca');

      var registro = f_edicion.submit();
      if(!registro)return;
      if(accion!='-1')
      {
       documentos.actualizar(accion);
      }
      else documentos.buscar();
      ocultar_formulario(f_edicion,documentos);
      break;
  }
}

function t_f_edicion2(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc:
      cancelaTecla(evt);
      ocultar_formulario(f_edicion2,documentos);
      break;

    case _f12:
      cancelaTecla(evt);
      var registro = f_edicion2.submit();
      if(!registro)return;
      ocultar_formulario(f_edicion2,documentos);
      m_proyectos.actualizar();
      actualizaDetalles();
      break;
  }
}

function t_f_identidad(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc:
      cancelaTecla(evt);
      ocultar_formulario(f_identidad,documentos);
      break;

    case _f12:
      cancelaTecla(evt);
      var accion = f_identidad.getValue('xbusca');

      var registro = f_identidad.submit();
      if(!registro)return;
      if(accion!='-1')
      {
       documentos.actualizar(accion);
      }
      else documentos.buscar();
      ocultar_formulario(f_identidad,documentos);
      break;
  }
}

function t_f_compra(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc:
      cancelaTecla(evt);
      ocultar_formulario(f_compra,documentos);
      break;

    case _f12:
      cancelaTecla(evt);
      var accion = f_compra.getValue('xbusca');

      var registro = f_compra.submit();
      if(!registro)return;
      if(accion!='-1')
      {
       documentos.actualizar(accion);
      }
      else documentos.buscar();
      ocultar_formulario(f_compra,documentos);
      break;
  }
}



function actualizaDetalles()
{
    actualizaHTML(m_proyectos.elementoXml(),'ENCABEZADO');
    var registro = valida_xml(m_proyectos.elementoXml(),'ID_M_PROYECTOS');
    xubicacion = '0011';
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
     case 'f_edicion2':
       t_f_edicion2('',_f12);
       break;
     case 'f_identidad':
       t_f_identidad('',_f12);
       break;	
	 case 'f_compra':
       t_f_compra('',_f12);
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
     case 'f_edicion2':
       t_f_edicion2('',_esc);
       break;
	 case 'f_identidad':
       t_f_identidad('',_esc);
       break;
	 case 'f_compra':
       t_f_compra('',_esc);
       break;	   
    }
}

function iniciar()
{
	 contenedor_upload.inicializa();
	
     contenedor.inicializa();
     centrarObj(contenedor.contenedor);

     m_origenes.inicializa(false);
     m_origenes.mostrar();
	 
	 documentos.inicializa(false);
     documentos.mostrar();

     m_proyectos.inicializa(true);
     centrarObj(m_proyectos.contenedor);

     f_edicion.inicializa();
     f_edicion2.inicializa();
	 f_identidad.inicializa();
	 f_compra.inicializa();

     document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	 addEvent(ORIGEN_ENTER, "click",   function() { t_m_origenes('', _enter,	m_origenes.elementoXml()) } );
	 addEvent(ORIGEN_ESC,   "click",   function() { t_m_origenes('', _esc,      m_origenes.elementoXml()) } );
	 
	 
     addEvent(F3,   "click",   function() { t_documentos('', _f3,      documentos.elementoXml()) } );
     addEvent(F4,   "click",   function() { t_documentos('', _f4,      documentos.elementoXml()) } );
     addEvent(F5,   "click",   function() { t_documentos('', _f5,      documentos.elementoXml()) } );
     addEvent(F7,   "click",   function() { t_documentos('', _f7,      documentos.elementoXml()) } );
     addEvent(F8,   "click",   function() { t_documentos('', _f8,      documentos.elementoXml()) } );
	 addEvent(F9,   "click",   function() { t_documentos('', _f9,      documentos.elementoXml()) } );
	 addEvent(F10,  "click",   function() { t_documentos('', _f10,     documentos.elementoXml()) } );
	 addEvent(F11,  "click",   function() { t_documentos('', _f11,     documentos.elementoXml()) } );
     addEvent(ESC,  "click",   function() { t_documentos('', _esc,     documentos.elementoXml()) } );
     addEvent(SUPR, "click",   function() { t_documentos('', _supr,    documentos.elementoXml()) } );

     addEvent(m_proyectos_ENTER, "click",   function() { t_m_proyectos('', _enter, m_proyectos.elementoXml()) } );
     addEvent(m_proyectos_ESC,   "click",   function() { t_m_proyectos('', _esc,   m_proyectos.elementoXml()) } );

     return true;
}

function inicio()
{
     m_proyectos.mostrar();
	 m_proyectos.setFocus();
     mostrarLeyenda(0);
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

function eliminar()
{
    var resp=confirm('Confirme que desea elimnar la imagen seleccionada');
    if(!resp) return;

    var base_name = $('#img_imagen_view').attr("url-imagen");
    if(typeof(base_name)  === "undefined") return;

    var rand_no = Math.ceil(100000000*Math.random())
    var url     = server_path + 'archivos/eliminar_imagen.php?rndid='+rand_no+ '&imagen='+ base_name +'&ID_D_ARCHIVOS='+idxa;
    var x       = enviar(url,'','GET');
	archivos(documentos.elementoXml());
	documentos.actualizar();
	documentos.setFocus();
}

function girar()
{

    var base_name = $('#img_imagen_view').attr("url-imagen");
    if(typeof(base_name)  === "undefined") return;

    var rand_no = Math.ceil(100000000*Math.random())
    var url     = server_path + 'archivos/rotar_imagen.php?rndid='+rand_no+ '&imagen='+ base_name;
    //prompt('', url );

    var x       = enviar(url,'','GET');
    var smallImage =  server_path + 'archivos/imagen.php?alto=480&rndid='+rand_no+ '&imagen='+ base_name;
    var timg       =  server_path + 'archivos/imagen.php?alto=70&rndid='+rand_no+ '&imagen='+ base_name;
    var largeImage =  '../'+base_name +'?'+rand_no;

    $("#div_galeria_thum .activa").attr('src',timg);

    $('#td_imagen_view').empty();
    $('#td_imagen_view').append('<img onmouseout="unzoom(this.id);" onmouseover="zoom(this.id);" src="' + smallImage + '" data-zoom-image="' + largeImage + '" url-imagen="'+ base_name + '" id="img_imagen_view">');
    //documentos.setFocus();

}

function descargar_imagen()
{

    var base_name = $('#img_imagen_view').attr("url-imagen");
    if(typeof(base_name)  === "undefined") return;

    var rand_no = Math.ceil(100000000*Math.random())
    var url     = server_path + 'archivos/download.php?rndid='+rand_no+ '&imagen='+ base_name;
	
	$.fileDownload(url);	

}

function descargar_expediente(){
    var rand_no = Math.ceil(100000000*Math.random())
    var url     = server_path + 'archivos/download_expediente.php?rndid='+rand_no+ '&CARPETA='+ xcarpeta;
	$.fileDownload(url);	
}

function archivos(xml)
{
    var registro = valida_xml(xml,'ID_D_ARCHIVOS');
    if(!registro){
		console.log('sin d_archivos');
		return false;
	} 

	var rand_no = Math.ceil(100000000*Math.random());
    var url = server_path + 'archivos/lista_imagen.php?rndid='+rand_no+'&archivo='+  xcarpeta +'/'+registro[0]['DESCRIPCION'];
    console.log(url);
    $.getJSON(url,function(data)
    {
         var i=0;
         $('#digital_imagenes').empty();
         if(data)
         {
         	if(typeof(data.archivos)  === "undefined") return;
	        $('#digital_imagenes').append('<table border="1" width="100%" id="table_imagen_view"></table>');
	        $('#table_imagen_view').append('<tr id="tr_imagen_view" height="480">');

            $('#digital_imagenes').hide();
            $('#load_digital_imagenes').show();

            //*********** COLOCO LA PRIMERA IMAGEN EN EN VISOR ******************************

            var tid  = data.archivos[0].id;
            var v_img =  server_path + 'archivos/imagen.php?alto=480&rndid='+rand_no+ '&imagen='+ xcarpeta +'/'+ data.archivos[0].nombre;
            //prompt('', v_img );
            var o_img =  '../' +xcarpeta +'/'+ data.archivos[0].nombre +'?'+rand_no;
            $('#tr_imagen_view').append('<td id="td_imagen_view" width="760px"></td>');
            $('#td_imagen_view').append('<center><img onmouseout="unzoom(this.id);" onmouseover="zoom(this.id);" src="' + v_img + '" data-zoom-image="' + o_img + '" url-imagen="'+ xcarpeta +'/'+ data.archivos[0].nombre + '" id="img_imagen_view"></center>');

            //*********** RECORRO CADA IMAGEN 1 A 1 PARA CREAR LOS THUMNAIL  *************************
            if(data.total>1)
            {
                $('#tr_imagen_view').append('<td id="td_galeria_thum">');
	            $('#td_galeria_thum').append('<div id="div_galeria_thum" style="width:120px; overflow-y: auto; height:480px;"></div>');

                for(i=0;i<data.archivos.length;i++)
	        	{
                    borde=0;
                    classe='inactiva';
                    if(i==0)
                    {
                    	borde=2;
                        classe='activa';
                    }
                    var tid  = data.archivos[i].sec;
	                var t_img =  server_path + 'archivos/imagen.php?ancho=70&rndid='+rand_no+ '&imagen='+ xcarpeta +'/'+ data.archivos[i].nombre;
                    var v_img =  server_path + 'archivos/imagen.php?alto=480&rndid='+rand_no+ '&imagen='+ xcarpeta +'/'+ data.archivos[i].f;
                    var o_img =  data.archivos[0].ruta;
	                var o_img =  data.archivos[i].ruta;
                    $('#div_galeria_thum').append('<center><img src="'+ t_img +'" data-zoom-image="'+ xcarpeta +'/'+ data.archivos[i].nombre + '" id="img_imagen_thum" style="margin-left: 10px; margin-right: 10px; margin-top: 5px; margin-bottom: 5px;" border="'+ borde +'" class="'+ classe +'"></center>');
	        	}
            }
            $("#img_imagen_view").elevateZoom({
                easing : false,
                zoomWindowHeight: 500,
                zoomWindowWidth: 440,
                borderSize: 1,
                zoomWindowPosition: "contenido_view2"
            })

            CheckImageLoadingState();

         }
    });
}

function unzoom(xid){
    $('#'+xid).removeData('elevateZoom');//remove zoom instance from image
    $('.zoomWrapper img.zoomed').unwrap();
    $('.zoomContainer').remove();
}

function zoom(xid){
            $("#"+xid).elevateZoom({
            	easing : false,
                zoomWindowHeight: 500,
                zoomWindowWidth: 440,
                borderSize: 1,
            	zoomWindowPosition: "contenido_view2"
    	  	});

}

$('#digital_imagenes').on('click', '#img_imagen_thum', function (event)
{
	var base_name = $(this).attr("data-zoom-image");
    var rand_no = Math.ceil(100000000*Math.random());
    var v_img =  server_path + 'archivos/imagen.php?alto=480&rndid='+rand_no+ '&imagen='+ base_name;
    var largeImage =  '../'+base_name +'?'+rand_no;
    $('#td_imagen_view').empty();
    $('#td_imagen_view').append('<img onmouseout="unzoom(this.id);" onmouseover="zoom(this.id);" src="' + v_img + '" data-zoom-image="' + largeImage + '" url-imagen="'+ base_name + '" id="img_imagen_view">');

    $("#div_galeria_thum img").each(function(){

       $(this).attr('border','0').attr('class', 'inactiva');
    });
    $(this).attr('border','2').attr('class', 'activa');

 });


function CheckImageLoadingState()
  {
     var counter = 0;
     var length = 0;

     jQuery('#table_imagen_view img').each(function()
     {
        if(jQuery(this).attr('src').match(/(gif|png|jpg|jpeg)$/))
          length++;
     });

     jQuery('#table_imagen_view img').each(function()
     {
        if(jQuery(this).attr('src').match(/(gif|png|jpg|jpeg)$/))
        {
           jQuery(this).load(function()
           {
           }).each(function() {
              if(this.complete) jQuery(this).load();
            });
        }
        jQuery(this).load(function()
        {
           counter++;
           if(counter === length)
           {
              $('#load_digital_imagenes').hide();
              $('#digital_imagenes').show();
           }
        });
     });
  }

$(window).load(function()
{
    var parent_height = $('#load_digital_imagenes').parent().height();
    var image_height = $('#load_digital_imagenes').height();
    var top_margin = (parent_height - image_height)/2;
    $('#load_digital_imagenes').css( 'margin-top' , top_margin);
    $('#load_digital_imagenes').hide();
});






</script>

</body>
</html>

EOT;

?>