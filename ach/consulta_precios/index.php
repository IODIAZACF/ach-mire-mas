<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha 	= date("d/m/Y");
$ventana 	= getvar('ventana','modulo');
$id 		= getvar('id');

$id_m_usuario    = getsession('M_USUARIOS_ID_M_USUARIO');

$my_ini 	= new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo  = new class_modulo('modulo',$onClose);


echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('utiles,auto_tabla,forma,tabpane,submodal,jquery');


echo <<<EOT

{$modulo->inicio}
<table border="0">
	<tr valign="top">
		<td id="GRUPO1"></td>
		<td id="IMAGEN" class="grid_cuadro_inactivo" >
			<table border="0" width="100%">
				<tr >
					<td align="center" style="width:285; height:250; padding: 5px;" class="grid_cuadro_activo">
						<img id="FOTO" name="foto" src="" onerror="sin_imagen(this)" width="280px">
					</td>
				</tr>
				<tr>
					<td align="center" class="grid_cuadro_activo" class="grid_cuadro_activo">
						<hr>
						<span id="FOTO_PRECIO" style="font-size: 25px; color: #ff0000"></span>
						<hr>
					</td>
				</tr>
			</table>
		</td>
		
	</tr>
</table>
{$modulo->fin}


<script type="text/javascript">

var t  = null;
var t2 = null;
var xproducto;

// Se crea el contenedor

m_productos             = new lista("consulta_precios/m_productos")
m_productos.nombre      = "m_productos";
m_productos.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_productos.funcion     = t_m_productos;
m_productos.buscador    = true;
m_productos.padre       = "GRUPO1";
m_productos.onSelect    = ver_imagen;;
m_productos.enter       = 0;
m_productos.modal		= false;
m_productos.botonCerrar = true;
m_productos.panel       = 120;
m_productos.filtro	    = 'TIPO';
m_productos.xfiltro	    = 'P';
m_productos.asyncLoad   = true;

var resp = iniciar();
if(resp)
{
	inicio();
}
else
{
	Salir();
}

function t_m_productos(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _tab:
	   cancelaTecla(evt);
    break;    
	
	case _enter:
	   cancelaTecla(evt);
    break;    
	
	case _insert: // Insertar
	   cancelaTecla(evt);
    break;    

	case _supr:
	   cancelaTecla(evt);
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

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function sin_imagen(img)
{
	img.src = server_path + 'imagenes/productos/0.jpg';
}

function ver_imagen()
{
	
	$("#IMAGEN").show();

	var xml = m_productos.elementoXml();
	var registro = XML2Array(xml);
	if(!registro[0]['ID_M_PRODUCTOS']) return false;
	
	if(!registro[0]) return false;
	var rand_no = Math.ceil(100000000*Math.random())

	var url = server_path + 'imagenes/productos/'+ registro[0]['ID_M_PRODUCTOS'] +'.jpg?rndid='+rand_no ;
	$("#FOTO").attr('src', url);
	$("#FOTO_PRECIO").html( registro[0]['PRECIO1'] );
	
}


function iniciar()
{
	//console.clear();
    m_productos.inicializa(false);
    m_productos.mostrar();
	document.onclick=function() { if (parent.menu) parent.menu.reset(); }
    addEvent(ESC,   "click",   function() { t_m_productos('', _esc, m_productos.elementoXml()) } )
    return true;
}

function inicio(){
	m_productos.setFocus();
}


</script>


</body>
</html>

EOT;

?>