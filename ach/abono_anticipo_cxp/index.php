<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/ini/class/class_ini.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$onClose = 'Salir();';
$fecha   = date("d/m/Y");
$ventana = getvar('ventana','modulo');
$id = getvar('id');
$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));
$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('utiles,formulario2,forma,auto_tabla,submodal');

echo <<<EOT

{$modulo->inicio}
<table>
	<tr>
		<td id="GRUPO1"></td>
		<td id="GRUPO2"></td>
	</tr>
</table>
{$modulo->fin}

<script type="text/javascript">

var dxml;
var t;
var xmarcados=0;
var xanticipo=null;
var idx;
var xsaldo_pendiente=0;

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 500;
contenedor.alto        = 300;
contenedor.titulo      = ' ';
contenedor.x           = 1;
contenedor.y           = 1;
contenedor.titulo      = 'titulo';
contenedor.botonCerrar = true;
contenedor.leyenda     = '     ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal 	   = true;


m_anticipos           = new lista("abono_anticipo_cxp/m_anticipos")
m_anticipos.nombre    = "m_anticipos";
m_anticipos.funcion   = t_m_anticipos;
m_anticipos.padre   = "GRUPO1";
m_anticipos.url       = server_path + "herramientas/genera_xml/genera_xml.php";
m_anticipos.enter     = 0;
m_anticipos.filtro    = 'ID_PADRE';
m_anticipos.buscador  = true;
m_anticipos.onSelect  = actualizar_detalle;
m_anticipos.onFocus   = focus_anticipo;


d_cxp           = new lista("abono_anticipo_cxp/d_cxp")
d_cxp.nombre    = "d_cxp";
d_cxp.funcion   = t_d_cxp;
d_cxp.padre   = "GRUPO2";
d_cxp.url       = server_path + "herramientas/genera_xml/genera_xml.php";
d_cxp.enter     = 0;
d_cxp.filtro    = 'IDX;TABLA';
d_cxp.onFocus   = focus_d_cxp;
d_cxp.buscador  = true;


function focus_anticipo()
{
	mostrarLeyenda(0);
}
function focus_d_cxp(obj)
{
    if(!GridAct) return;
    if(GridAct.nombre==obj.nombre) mostrarLeyenda(1);
}
function actualizar_detalle (obj,xml)
{
    if (t) window.clearTimeout(t);
  	dxml = xml;
  	t = window.setTimeout('act_timer(dxml)',300);
}

function act_timer(xml)
{
  	var registro = XML2Array(xml);
 	if(!registro[0] || !registro[0]['IDX'])
    {
        d_cxp.limpiar();
        return;
    }
    idx = registro[0]['IDX'];
    xanticipo =registro[0]['ID_M_ANTICIPOS'];
    d_cxp.xfiltro=idx+';M_PROVEEDORES';
    d_cxp.buscar('*');
    totales();
}
function t_m_anticipos(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _enter: // F12 Guardar
  	  	cancelaTecla(evt);
	  	d_cxp.setFocus();
	  	mostrarLeyenda(1);
      break;
    case _f6:
       var url = server_path + "herramientas/genera_xml/genera_xml.php";
       var param =  "origen=abono_anticipo_cxp/m_anticipos&procedimiento=VERIFICAR_MARCA&ID_M_PROVEEDORES=" + idx ;
       var x = enviar(url,param,'POST');
       var registro = XML2Array(x);
	   if(!registro[0])
       {
         alert("$t_proceso_invalido");
         return false;
       }
       var aut = confirm("{$t_realizar_proceso}");
       var registro = XML2Array(xml);
       if(!registro[0] || !registro[0]['ID_M_ANTICIPOS']) return;
	   xanticipo=registro[0]['ID_M_ANTICIPOS'];
       if(aut)
       {
        	var url = server_path + 'herramientas/utiles/actualizar_registro.php';
            var params = 'tabla=M_ANTICIPOS&c_CONDICION2_CSS=M&busca=ID_M_ANTICIPOS&xbusca='+xanticipo;
        	var x = enviar(url, params, 'POST');
		    d_cxp.limpiar();
        	m_anticipos.actualizar(registro[0]['ID_M_ANTICIPOS']);
       }
      break;
    case _f8:
  		cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_M_ANTICIPOS');
    	if(!registro)return;

        var url = server_path + "herramientas/genera_xml/genera_xml.php";
        var param =  "origen=abono_anticipo_cxp/m_anticipos&procedimiento=VERIFICAR_MARCADOS&ID_M_ANTICIPOS=" + registro[0]["ID_M_ANTICIPOS"] ;
        var x = enviar(url,param,'POST');
        var xregistro = valida_xml(x,'ID_D_CXCCXP');
	    if(xregistro[0])
       {
         alert("$t_proceso_invalido");
         return false;
       }

        var aut = confirm("{$t_realizar_proceso}");
        xanticipo=registro[0]['ID_M_ANTICIPOS'];
        if(aut)
        {
             var url = server_path + 'herramientas/utiles/actualizar_registro.php';
             var params = 'tabla=M_ANTICIPOS&c_CONDICION2_CSS=A&busca=ID_M_ANTICIPOS&xbusca='+xanticipo;
             var x = enviar(url, params, 'POST');
             d_cxp.buscar('*');
             m_anticipos.actualizar(registro[0]['ID_M_ANTICIPOS']);
        }
      break;

    case _esc: // ESC Cerrar Formulario
	  Salir();
      break;
    case _f1:
    case _f2:
    case _f3:
    case _f4:
    case _f5:
    case _f7:
    case _f9:
    case _f10:
    case _f11:
    case _f12:
  	  	cancelaTecla(evt);
      break;

  }


}
function t_d_cxp(objeto, tecla, xml, e)
{
  switch (tecla)
  {
    case 187:
    case 107:
      var registro = XML2Array(xml);
      if(!registro[0] || !registro[0]['ID_D_CXCCXP']) return;
      var xcondicion = unformat(registro[0]['CONDICION4']);
      var xsaldo_factura = unformat(registro[0]['SALDO']);

      if(xcondicion !=0 ) xcondicion=0;
      else
      {
        if(xsaldo_pendiente > xsaldo_factura)
        {
        	xcondicion= xsaldo_factura;
        }
        else
        {
           xcondicion =xsaldo_pendiente;
        }
      }
      var url = server_path + 'herramientas/utiles/actualizar_registro.php';
      var params = 'tabla=D_CXCCXP&c_CONDICION4_RSS='+xcondicion+'&c_ID_M_ANTICIPOS_CSS='+xanticipo+'&busca=ID_D_CXCCXP&xbusca='+registro[0]['ID_D_CXCCXP'];
      var x = enviar(url, params, 'POST');
      d_cxp.actualizar(registro[0]['ID_D_CXCCXP']);
	  totales();
      break;

    case _esc:
	  	mostrarLeyenda(1);
        m_anticipos.setFocus();
      break;

  }
}
function totales()
{
    var url = server_path + "herramientas/genera_xml/genera_xml.php";
    var param =  "origen=abono_anticipo_cxp/m_anticipos&procedimiento=TOTALES&ID_M_PROVEEDORES=" + idx+"&ID_M_ANTICIPOS="+xanticipo; ;
    var x = enviar(url,param,'POST');

    var registro = XML2Array(x);
    xmarcados = registro[0]['TOTAL_MARCADOS'];
    xsaldo    = registro[0]['SALDO_ANTICIPO'];
	xsaldo_pendiente= unformat(xsaldo) - unformat(xmarcados);
    actualizaHTML(x,'PIE');
}

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function iniciar()
{
  contenedor.inicializa();
  centrarObj(contenedor.contenedor);

  m_anticipos.inicializa();
  d_cxp.inicializa();

  addEvent(M_ANTICIPOS_ENTER,  "click", function() { t_m_anticipos ('', _enter , m_anticipos.elementoXml()) } )
  addEvent(M_ANTICIPOS_F6,  "click", function()    { t_m_anticipos ('', _f6, m_anticipos.elementoXml()) } )
  addEvent(M_ANTICIPOS_F8,  "click", function()    { t_m_anticipos ('', _f8 , m_anticipos.elementoXml()) } )
  addEvent(M_ANTICIPOS_ESC,  "click", function()   { t_m_anticipos ('', _esc , m_anticipos.elementoXml()) } )

  addEvent(D_CXP_MAS,  "click", function()         { t_d_cxp ('',  187, d_cxp.elementoXml()) } )
  addEvent(D_CXP_ESC,  "click", function()         { t_d_cxp ('', _esc , d_cxp.elementoXml()) } )

  m_anticipos.buscar('*');
  m_anticipos.mostrar();
  d_cxp.mostrar();
  mostrarLeyenda(0);
  m_anticipos.setFocus();
  document.onclick=function() { if (parent.menu) parent.menu.reset(); }

}

iniciar();

</script>


</body>
</html>

EOT;

?>