<?php

define('Server_Path','../../');
include_once (Server_Path . 'herramientas/utiles/comun.php');

echo '<body onload="ocultaCarga();">' . "\n";

encabezado('EJEMPLO DE TABPANE');

javascript('utiles,tabpane,formulario2,forma,auto_tabla,submodal');

echo <<<EOT

<script type="text/javascript">

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 630;
contenedor.alto        = 400;
contenedor.x           = 100;
contenedor.y           = 50;
contenedor.titulo      = 'Ficha de Proveedor';
contenedor.botonCerrar = true;
contenedor.leyenda     = "<button onclick=''>F12 - Guardar</button><button onclick=''>ESC - Cerrar</button>";
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.inicializa();
contenedor.mostrar();


tp = new tabPane();
tp.addTab('Paciente', 'pag1');
tp.addTab('Proveedor', 'pag2');
tp.addTab('Direcciones', 'pag3');
tp.parent = 'contenedor_cuadro';
tp.width = contenedor.ancho - 26;
tp.height = contenedor.alto - 70;
tp.onEnter  = f_enter;
tp.onFocus = f_focus;
tp.init();
tp.show();


oFormulario = new formulario2('formularios/f_proveedores');
oFormulario.nombre  = 'oFormulario';
oFormulario.funcion = teclaForm;
oFormulario.padre   = 'pag1';
oFormulario.inicializa();
oFormulario.mostrar();


oFormulario2 = new formulario2('M_PROVEEDORES');
oFormulario2.nombre   = 'oFormulario2';
oFormulario2.funcion  = teclaForm;
oFormulario2.padre    = 'pag2';
oFormulario2.tipo     = 'adicional';
oFormulario2.tabla    = 'A_M_PROVEEDORES';
oFormulario2.indice   = 'IDX';
oFormulario2.inicializa();
oFormulario2.mostrar();


hgrid = new lista('maestros/d_direcciones', 'hgrid');
hgrid.titulo = '';
hgrid.padre = 'pag3';
hgrid.xbusca = 'IDX';
hgrid.url = server_path + 'herramientas/genera_xml/genera_xml.php';
hgrid.funcion = f_grid;
hgrid.inicializa();


contenedor2             = new submodal();
contenedor2.nombre      = 'contenedor2';
contenedor2.ancho       = 500;
contenedor2.alto        = 300;
contenedor2.titulo      = 'Dirección';
contenedor2.botonCerrar = true;
contenedor2.leyenda     = "<button onclick=''>F12 - Guardar</button><button onclick=''>ESC - Cerrar</button>";
contenedor2.usaFrame    = false;
contenedor2.interpretar = false;
contenedor2.inicializa();
centrarObj(contenedor2.contenedor);

fdirecciones = new formulario2('formularios/f_d_direcciones');
fdirecciones.nombre = 'fdirecciones';
fdirecciones.funcion  = f_direcciones;
fdirecciones.padre = 'contenedor2_cuadro';
fdirecciones.inicializa();


tp.setFocus();

function teclaForm(obj, tecla, evt)
{
  switch (tecla)
  {
    case 13:
      break;

    case 27:
      contenedor.ocultar();
      break;

    case 34:
      if (evt.ctrlKey) tp.next();
      break;

    case 33:
      if (evt.ctrlKey) tp.prior();
      break;

    case 123: // -- F12 = guardar
      break;
  }
}


function buscar(id)
{
  oFormulario.buscar(id,'ID_M_PROVEEDORES');
  oFormulario2.buscar(id,'IDX');
  hgrid.filtro = 'IDX';
  hgrid.xfiltro = id+',M_PROVEEDORES';
  hgrid.buscar('*');
  tp.setIndex(0);
  tp.setFocus();
}


function f_enter(index)
{
  switch(index)
  {
    case 0:
      hgrid.ocultar();
      oFormulario.setFocus();
      break;
    case 1:
      hgrid.ocultar();
      oFormulario2.setFocus();
      break;
    case 2:
      hgrid.mostrar();
      hgrid.setFocus();
      GridAct = hgrid;
      break;
  }
}

function f_focus(tabname)
{
  f_enter(this.getIndex());
}

function f_grid(nombre, KeyCode, xml, evt)
{
  switch(KeyCode)
  {
    case 34:
      if (evt.ctrlKey) tp.next();
      break;

    case 45:
      contenedor2.mostrar();
      fdirecciones.mostrar();
      fdirecciones.setFocus();
      break;

    case 33:
      if (evt.ctrlKey) tp.prior();
      break;
  }
}

function f_direcciones(obj, tecla, evt)
{
}

function cambiar(elem)
{
  if (elem.innerHTML == 'ocultar nombre')
  {
    oFormulario.ocultarCampo('NOMBRES');
    elem.innerHTML = 'mostrar nombre';
  }
  else
  {
    oFormulario.mostrarCampo('NOMBRES');
    elem.innerHTML = 'ocultar nombre';
  }
}

function cambiarg(elem)
{
  if (elem.innerHTML == 'ocultar grupo')
  {
    oFormulario.ocultarGrupo(2);
    elem.innerHTML = 'mostrar grupo';
  }
  else
  {
    oFormulario.mostrarGrupo(2);
    elem.innerHTML = 'ocultar grupo';
  }
}

</script>
<button onclick="buscar('0011');">Buscar 0011</button>
<button onclick="cambiar(this);">ocultar nombre</button>
<button onclick="cambiarg(this);">ocultar grupo</button>
</body>

EOT;

cargando();

?>