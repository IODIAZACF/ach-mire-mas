<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$ventana = getvar('ventana','modulo');
$id = getvar('id');

$query = new sql();
$query->sql = "SELECT ID_CONFIGURACION FROM CONFIGURACION";

$query->ejecuta_query();
$query->next_record();
$xid_m_configuracion_web 	 = $query->Record['ID_CONFIGURACION'];

$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('auto_tabla,utiles,tabpane,formulario2,forma,submodal');
echo <<<EOT


<script type="text/javascript">

var  xid_m_configuracion_web =  '{$xid_m_configuracion_web}';

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

f_configuracion_web              = new formulario2('configuracion_web/f_configuracion_web');
f_configuracion_web.nombre       = 'f_configuracion_web';
f_configuracion_web.funcion      = t_f_configuracion_web;
f_configuracion_web.padre        = 'contenedor_cuadro';
f_configuracion_web.tipo		 = 'formulario';

function Salir()
{
   location.href = server_path + 'main/inicio.php';
}


function t_f_configuracion_web(elemento, tecla)
{
  switch (tecla)
  {
    case _f12: // Guardar
      var registro = f_configuracion_web.submit();
	  if(!registro[0]) return false;
      alert('$t_configuracion_guardada');
      Salir();
    break;

   case _esc: // Cerrar Formulario
      	ocultar_formulario(f_configuracion_web);
        Salir();
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

function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
     case 'f_configuracion_web':
       t_f_configuracion_web('',_f12);
       break;
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
     case 'f_configuracion_web':
       t_f_configuracion_web('',_esc);
       break;
    }
}


function iniciar()
{
	console.clear();
  contenedor.inicializa();
  centrarObj(contenedor.contenedor);

  f_configuracion_web.inicializa();
  f_configuracion_web.buscar(xid_m_configuracion_web);

  mostrar_formulario(f_configuracion_web);

}

document.onclick=irMenu;

function irMenu()
{
  if (parent.menu) parent.menu.reset();
}

function Cerrar_contenedor()
{
    f_activo.ocultar();
    contenedor.ocultar();
}

iniciar();

</script>

</body>
</html>

EOT;

?>