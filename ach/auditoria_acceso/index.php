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
$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora,jquery');

echo <<<EOT

{$modulo->inicio}
<table>
	<tr>
		<td id="contenido">
		</td>
	</tr>
</table>
{$modulo->fin}

<script type="text/javascript">

var xvalida = null;
var fecha = '{$xfecha}';
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
contenedor.ayuda       = 1;
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.modalResult = true;
contenedor.onClose     = Cerrar_contenedor;

// Se Crea el Grid Base
d_sesiones               = new lista('auditoria_acceso/d_sesiones');
d_sesiones.nombre       = 'd_sesiones ';
d_sesiones.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
d_sesiones.usaCookie    = false;
d_sesiones.funcion      = tecla_doc;
d_sesiones.padre        = "contenido";
d_sesiones.buscador     = true;
d_sesiones.onSelect     = actualizaPie;
d_sesiones.filtro       = 'ID_M_USUARIOS';

var resp = iniciar();

if(!resp)
{
     Salir();
}
else
{
     inicio();
}

function tecla_doc(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {

    case _f9:
      cancelaTecla(evt);
      d_sesiones.buscar('*');
      d_sesiones.mostrar();
      d_sesiones.setFocus();
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


function actualizaDetalles()
{
     actualizaHTML(m_principal.elementoXml(),'ENCABEZADO');
}

function actualizaPie()
{
     actualizaHTML(d_sesiones.elementoXml(),'PIE');
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

function iniciar()
{
     contenedor.inicializa();
     centrarObj(contenedor.contenedor);

     d_sesiones.inicializa(false);
     d_sesiones.mostrar();

     document.onclick=function() { if (parent.menu) parent.menu.reset(); }

     addEvent(ESC,  "click",   function() { tecla_doc('', _esc,     d_sesiones.elementoXml()) } );

     return true;

}

function inicio()
{
     d_sesiones.mostrar();
     d_sesiones.setFocus();
     d_sesiones.buscar();
}



</script>

</body>
</html>

EOT;

?>