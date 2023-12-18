<?php
define('Server_Path','../../');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");


$filtro  = getvar('filtro' , '');
$xfiltro = getvar('xfiltro', '');


//$idx   = $filtro;
$tmp   = explode(',',$xfiltro);

$tabla = $tmp[1];
$idx   = $tmp[0];


$origen  = getvar('origen');
$my_ini1 = new ini(Server_Path . $origen);

$titulo1    = $my_ini1->seccion('VENTANA','TITULO');
$indice     = $my_ini1->seccion('TABLA','INDICE');
$xbusca     = $my_ini1->seccion('TABLA','LOCALIZA');
$form_ancho = $my_ini1->seccion('FORMULARIO','ANCHO');
$form_alto  = $my_ini1->seccion('FORMULARIO','ALTO');


$origen2 = $my_ini1->seccion('FORMULARIO','ORIGEN');
$my_ini2 = new ini(Server_Path . $origen2);

//$titulo2 = $my_ini2->seccion('VENTANA','TITULO');

encabezado($my_ini1->seccion('VENTANA','TITULO'));

echo '<body class="maestro2" onload="ocultaCarga()">' . "\n";

cargando();

javascript('utiles,auto_tabla,formulario,forma,tabpane');

echo <<<EOT

<div id="DIV"></div>

<span id="alto"  style="display:none"></span>
<span id="ancho" style="display:none"></span>

<script type="text/javascript">

xGrid             = new lista('{$origen}');
xGrid.nombre      = 'xGrid';
xGrid.modal       = false;
xGrid.url         = server_path + "herramientas/genera_xml/genera_xml.php";
xGrid.funcion     = TeclaGrid;
xGrid.buscador    = true;
xGrid.botonCerrar = true;
xGrid.padre       = "DIV";
xGrid.titulo      = "";

//xGrid.debug     = true;
xGrid.x           = 1;
xGrid.y           = 1;
xGrid.filtro      = '$filtro';
xGrid.xfiltro     = '$xfiltro';
// xGrid.padre       = 'GRID';
xGrid.onClose = function () {parent.v_auxiliar2.ocultar()};
xGrid.inicializa();
xGrid.buscar();
//centrarObj(xGrid.contenedor);

xGrid.mostrar();


xFormulario = new formulario('{$origen2}');
xFormulario.nombre      = 'xFormulario';

xFormulario.x           = 1;
xFormulario.y           = 1;
xFormulario.modal       = true;

xFormulario.funcion     = TeclaForm;
xFormulario.botonCerrar = true;

xFormulario.botones     = "<button onClick='Guardar();'>Aceptar</button><button onClick='xFormulario.ocultar();xGrid.setFocus()'>Cancelar</button>";
xFormulario.inicializa();
centrarObj(xFormulario.contenedor);


function TeclaGrid(objeto, tecla, xml)
{
  switch (tecla)
  {
    case 13:  // Enter Edicion de Registro
      registro = XML2Array(xml);
       // alert(registro[0]['{$indice}']);
      if(registro[0]['{$indice}'])
      {
         xFormulario.buscar(registro[0]['{$indice}']);
         xFormulario.mostrar();
         centrarObj(xFormulario.contenedor);
         xFormulario.setFocus();
         return false;
      }
      break;

    case 27:
      parent.v_auxiliar2.ocultar();
      break;

    case 45: //  Insert Insercion de Registro
      xFormulario.limpiar();
      xFormulario.setValue('TABLA','{$tabla}');
      xFormulario.setValue('{$filtro}','{$idx}');
      xFormulario.mostrar();
      centrarObj(xFormulario.contenedor);
      xFormulario.setFocus();
      break;
  }
}

function Guardar()
{
    var registro = xFormulario.submit();

    if (!registro) return false;
    var indice = registro[0]['UNICO'];

    if(indice)
    {
        xGrid.buscar('*');
    }
    else
    {
    	alert('Ha ocurrido un problema');
    }
    xFormulario.ocultar();
    xFormulario.limpiar();
    xGrid.setFocus();

}

function TeclaForm(objeto, tecla)
{

  switch (tecla)
  {
    case 123: // F12 Guardar
      Guardar();
      break;

    case 13:
      break;

    case 27:
      xFormulario.ocultar();
      xGrid.setFocus();
      break;

  }


}

function Salir(v)
{
  parent.retorno.focus();
  v.onClose = null;
}

document.getElementById('alto').innerHTML = xGrid.contenedor.offsetHeight+35;
document.getElementById('ancho').innerHTML = xGrid.contenedor.offsetWidth+10;

parent.v_auxiliar2.onClose = Salir;

addEvent(xGrid_LEYENDA1, "click",   function() { TeclaGrid('', 45, xGrid.elementoXml()) } )
addEvent(xGrid_LEYENDA2, "click",   function() { TeclaGrid('', 13, xGrid.elementoXml()) } )
addEvent(xGrid_LEYENDA3, "click",   function() { TeclaGrid('', 46, xGrid.elementoXml()) } )
addEvent(xGrid_LEYENDA4, "click",   function() { TeclaGrid('', 27, xGrid.elementoXml()) } )

xGrid.setFocus();

</script>

<span id="TITULO" style="display:none">{$titulo1}</span>

</body>
</html>

EOT;

?>