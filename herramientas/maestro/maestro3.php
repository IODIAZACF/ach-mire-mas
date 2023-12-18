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

$origen2 = $my_ini1->seccion('FORMULARIO','ORIGEN');
$tabla2 = $my_ini1->seccion('FORMULARIO','TABLA');

$my_ini2 = new ini(Server_Path . $origen2);

$indice2 = $my_ini2->seccion('TABLA','INDICE');

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
xGrid.modal       = true;
xGrid.url         = server_path + "herramientas/genera_xml/genera_xml.php";
xGrid.funcion     = TeclaGrid;
xGrid.buscador    = true;
xGrid.botonCerrar = true;
xGrid.padre       = "DIV";
xGrid.titulo      = "";
// xGrid.debug    = true;
xGrid.x           = 1;
xGrid.y           = 1;
xGrid.filtro      = '$filtro';
xGrid.xfiltro     = '$xfiltro';
// xGrid.padre    = 'GRID';
xGrid.inicializa();
//centrarObj(xGrid.contenedor);
xGrid.onClose = function () {parent.v_auxiliar.ocultar()};
xGrid.mostrar();
xGrid.buscar();


xGrid2             = new lista('{$origen2}');
xGrid2.nombre      = 'xGrid2';
//xGrid2.modal       = false;
xGrid2.url         = server_path + "herramientas/genera_xml/genera_xml.php";
xGrid2.funcion     = TeclaGrid2;
xGrid2.buscador    = true;
xGrid2.botonCerrar = true;
xGrid2.padre       = "DIV";
//xGrid2.debug     = true;
xGrid2.x           = 1;
xGrid2.y           = 1;
xGrid2.modal       = true;
xGrid2.enter       = 1;
xGrid2.botonCerrar = true;
xGrid2.padre       = 'GRID';
xGrid2.inicializa();

function TeclaGrid(objeto, tecla, xml)
{
  switch (tecla)
  {
    case 13:  // Enter Edicion de Registro
      break;

    case 27:
      parent.v_auxiliar.ocultar();
      break;

    case 45: //  Insert Insercion de Registro
      xGrid2.buscar('');
      centrarObj(xGrid2.contenedor);
      xGrid2.mostrar();
      xGrid2.setFocus();
      break;

  }
}

function Salir(v)
{
  parent.retorno.focus();
  v.onClose = null;
}

/*function Guardar()
{
    var registro = xFormulario.submit();

    var indice = registro[0]['UNICO'];

    if(indice)
    {
        xGrid.buscar(indice,"=");
    }
    xFormulario.ocultar();
    xFormulario.limpiar();
    xGrid.setFocus();

}
   */
function TeclaGrid2(objeto, tecla, xml)
{

  switch (tecla)
  {
    case 123: // F12 Guardar
      Guardar();
      break;

    case 13:
       var registro = XML2Array(xml);
       var url = server_path + 'herramientas/utiles/actualizar_registro.php';
       var tabla = '{$tabla2}';
       var busca = -1;
       var campo = '{$filtro}';
       var valor = '{$indice2}';
       var params = 'tabla='+tabla+'&busca='+busca+'&c_'+campo+'_RSS={$xfiltro}&c_'+valor+'_RSS='+registro[0][valor];

      // prompt('',url + '?' + params);
       var xml2 = enviar(url, params, 'GET');
       registro = XML2Array(xml2);
       if(registro[0]['ERROR'])
        {
            if (confirm('Error al Guardar : ' + registro[0]['ERROR'] + '\\n' + registro[0]['QUERY']))
            {
              prompt('Query a ejecutar:\\n',registro[0]['QUERY']);
            }
        }
        xGrid2.ocultar();
        xGrid.buscar('');
        xGrid.setFocus();
      break;

    case 27:
      xGrid2.ocultar();
      xGrid.setFocus();
      break;

  }


}

document.getElementById('alto').innerHTML = xGrid.contenedor.offsetHeight+35;
document.getElementById('ancho').innerHTML = xGrid.contenedor.offsetWidth+12;

document.onclick=function() { if (parent.menu) parent.menu.reset(); }

parent.v_auxiliar.onClose = Salir;
parent.v_auxiliar.scrollLeft = 0;
parent.v_auxiliar.scrollTop = 0;

xGrid.setFocus();

addEvent(xGrid_LEYENDA1, "click",   function() { TeclaGrid('', 45, xGrid.elementoXml()) } )
addEvent(xGrid_LEYENDA2, "click",   function() { TeclaGrid('', 46, xGrid.elementoXml()) } )
addEvent(xGrid_LEYENDA3, "click",   function() { TeclaGrid('', 27, xGrid.elementoXml()) } )

addEvent(xGrid2_LEYENDA5, "click",   function() { TeclaGrid2('', 13, xGrid2.elementoXml()) } )
addEvent(xGrid2_LEYENDA6, "click",   function() { TeclaGrid2('', 27, xGrid2.elementoXml()) } )

</script>

<span id="TITULO" style="display:none">{$titulo1}</span>

</body>
</html>

EOT;

?>