<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha         = date("d/m/Y");
$ventana         = getvar('ventana','modulo');
$id                 = getvar('id');

$id_m_usuario    = getsession('M_USUARIOS_ID_M_USUARIO');

$my_ini         = new ini('modulo');
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
		<td id="GRUPO1" width="70%"></td>
		<td id="MODULO_RESUMEN" width="30%" rowspan="2" class="grid_contenedor" style="padding: 2px; vertical-align: text-top;">
			<div class="grid_encab" style="width: 345px; height: 25px;">Resumen</div>
			<div style="overflow-y: scroll; height:300px;">
				<table border="0">
					<tr>
						<td colspan="2" style="width: 330px; background: #e6e6e6; font-weight: bold; text-align: center; border: solid 1px #e6e6e6; font-size: 10px;">Datos Generales</td>
					<tr>
				</table>
				<table>
					<tr>
						<td class="grid_rotulo_pie"    style="width: 80px;">Sexo:</td>
						<td class="grid_contenido_pie" style="width: 235px;" id="RESUMEN_SEXO_D">&nbsp;</td>
					</tr>
					<tr>
						<td class="grid_rotulo_pie"    style="width: 80px;">Genero:</td>
						<td class="grid_contenido_pie" style="width: 235px;" id="RESUMEN_GENERO_D">&nbsp;</td>
					</tr>
					<tr>
						<td class="grid_rotulo_pie"    style="width: 80px;">Tipo persona:</td>
						<td class="grid_contenido_pie" style="width: 235px;" id="RESUMEN_TIPO_PERSONA_D">&nbsp;</td>
					</tr>
					<tr>
						<td class="grid_rotulo_pie"    style="width: 80px;">Pais:</td>
						<td class="grid_contenido_pie" style="width: 235px;" id="RESUMEN_PAIS_D">&nbsp;</td>
					</tr>
					<tr>
						<td class="grid_rotulo_pie"    style="width: 80px;">Orientacion Sexual:</td>
						<td class="grid_contenido_pie" style="width: 235px;" id="RESUMEN_ORIENTACION_SEX_D">&nbsp;</td>
					</tr>
					<tr>
						<td class="grid_rotulo_pie"    style="width: 80px;">Grupo Etareo:</td>
						<td class="grid_contenido_pie" style="width: 235px;" id="RESUMEN_GRUPO_ETAREO">&nbsp;</td>
					</tr>					
					<tr>
						<td class="grid_rotulo_pie"    style="width: 80px;">Actividad:</td>
						<td class="grid_contenido_pie" style="width: 235px;" id="RESUMEN_ACTIVIDAD_D">&nbsp;</td>
					</tr>					
					<tr>
						<td class="grid_rotulo_pie"    style="width: 80px;">Discapacidad:</td>
						<td class="grid_contenido_pie" style="width: 235px;" id="RESUMEN_DISCAP_D">&nbsp;</td>
					</tr>					
				</table>

			</div>
		</td>
	</tr>
</table>

{$modulo->fin}

<script type="text/javascript">

var fecha = '{$xfecha}';

var impresora 		= new printer();
var mask      		= new Mask('#,###.##', 'number');
var xID_M_USUARIOS 	= '{$id_m_usuario}';


</script>

EOT;

?>

<script type="text/javascript">

//se crea el contenedor
contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 900;
contenedor.alto        = 600;
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

contenedor_upload      = new submodal();
contenedor_upload.nombre      = 'contenedor_upload';
contenedor_upload.ancho       = 1200;
contenedor_upload.alto        = 600;
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

m_lpa              = new lista('auditoria_atendidas/m_lpa');
m_lpa.nombre       = 'm_lpa';
m_lpa.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
m_lpa.usaCookie    = false;
m_lpa.funcion      = t_m_lpa;
m_lpa.padre        = "GRUPO1";
m_lpa.buscador     = true;
m_lpa.onSelect     = actualizaResumen;

function actualizaResumen()
{
	var xml = m_lpa.elementoXml();
	var registro = valida_xml(xml,'ID_M_LPA');
	if (!registro)
	{
		limpiarElementos('RESUMEN');
		return;
	}
	actualizaHTML(xml,'RESUMEN_');
}

//copiado aqui solo para el upload

function creaLeyenda()
{
  var l = '';
  l += '<center><table class="tabla_leyenda">';
  l += '<tr>';
  l += etiqLeyenda('ESC', '{$t_salir}', '90', 'Cerrar()');
  l += '</tr>';
  l += '</table></center>';
  return l;
}

function Cerrar()
{
    contenedor_upload.ocultar();
    m_lpa.buscar('*');
    m_lpa.setFocus();
}

/*
function etiqLeyenda(tecla, texto, ancho, accion)
{
  var e = ' <td onselectstart="return false;" style="width:'+ancho+'px"; class="td_leyenda_inactiva" onmouseup="this.className=\\'td_leyenda_activa\\'" onmouseover="this.className=\\'td_leyenda_activa\\'" onmousedown="this.className=\\'td_leyenda_click\\'" onmouseout="this.className=\\'td_leyenda_inactiva\\'" onclick="'+accion+'">[<B>'+tecla+'</B>]<br>'+texto+'</td>';
  return e;
}
*/

function t_m_lpa(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _insert:
		break;

    case _enter:
		cancelaTecla(evt);
		break;

		case _supr:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_M_LPA');
		if(!registro) return;
		if(confirm('{$t_eliminar_registro}'))
		{
			var url   = server_path + "herramientas/utiles/actualizar_registro.php";
			var param = "origen=auditoria_atendidas/m_lpa&procedimiento=ELIMINAR_ITEM&ID_M_LPA=" + registro[0]['ID_M_LPA'];	
			var x     = enviar(url,param,'POST');
		}
		m_lpa.buscar('*');
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
		cancelaTecla(evt);
	break;
    case _f9:
      cancelaTecla(evt);
	  contenedor_upload.destruir();
	  contenedor_upload.url = server_path  + 'auditoria_atendidas/upload.php';
	  contenedor_upload.inicializa();
	  centrarObj(contenedor_upload.contenedor);
	  contenedor_upload.mostrar();
	  contenedor_upload.setFocus();
      break;


    case _f10:
    case _f11:
    case _f12:
		cancelaTecla(evt);
	break;
  }
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
     case 'f_x_cobro':
       t_f_x_cobro('',_f12);
       break;    		 	   
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
     case 'f_x_cobro':
       t_f_x_cobro('',_esc);
       break;   
	}
}

function iniciar()
{
	contenedor_upload.inicializa();

	contenedor.inicializa();
	centrarObj(contenedor.contenedor);

	m_lpa.inicializa(false);
	m_lpa.mostrar();

	document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	addEvent(F9,   		"click",   function() { t_m_lpa('', _f9,     m_lpa.elementoXml()) } );
	addEvent(SUPR,  	"click",   function() { t_m_lpa('', _supr,    m_lpa.elementoXml()) } );
	addEvent(ESC,  		"click",   function() { t_m_lpa('', _esc,    m_lpa.elementoXml()) } );

	return true;
}

function inicio()
{
	m_lpa.buscar('*');
	m_lpa.setFocus();
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


