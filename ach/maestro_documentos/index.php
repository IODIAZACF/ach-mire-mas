<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));
$tipo= getvar('tipo');
$modulo = new class_modulo('modulo',$onClose);

$query = new sql();
$query->sql = "select id_m_cajas,campo1,campo2 from m_cajas where ip='". $_SERVER['REMOTE_ADDR'] ."'";
$query->ejecuta_query();
$query->next_record();
$xid_m_cajas = $query->Record['ID_M_CAJAS'];

$tmp_data = explode('_', $query->Record['CAMPO1']);
$impresora_protocolo     = $tmp_data[0];
$impresora_formato  	 = $tmp_data[1];
$xpuerto     	 		 = $query->Record['CAMPO2'];


echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('auto_tabla,utiles,formulario2,forma,tabpane,impresora,submodal');

echo <<<EOT

<script type="text/javascript">
var tipo ='{$tipo}';
var xtipo ;
var id_m_documentos;
var xtiempo            	  = null;
var impresora_protocolo   = '{$impresora_protocolo}';
var impresora_formato     = '{$impresora_formato}';
var xpuerto               = '{$xpuerto}';
var xid_m_cajas           = '{$xid_m_cajas}';

var impresora = new printer();


// Se Crea el Grid del documentos
documentos             = new lista('maestro_documentos/m_documentos_'+ tipo);
documentos.nombre      = 'documentos';
documentos.funcion     = t_documentos;
documentos.buscador    = true;
documentos.botonCerrar = true;
documentos.enter	   = 0;
documentos.url         = server_path + 'herramientas/genera_xml/genera_xml.php';

function t_documentos(objeto, tecla, xml)
{
  var evt = window.event || e;

  switch (tecla)
  {
   case _f4: // F4
      cancelaTecla(evt);

      if (!evt.altKey)
      {
        var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
        if(!registro[0]) return;

        id_m_documentos = registro[0]['ID_M_DOCUMENTOS'];
        xtipo = registro[0]['TIPO'];
        Preview();
      }

      break;

   case _f7: // F7
      cancelaTecla(evt);
      var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
      if(!registro) return;
      id_m_documentos = registro[0]['ID_M_DOCUMENTOS'];
      xtipo      = registro[0]['TIPO'];
      Imprimir();
      break;

   case _esc: //Escape
      cancelaTecla(evt);
      Salir();
      break;
  }
}

function Salir()
{
   parent.proceso.location.href = server_path + 'main/inicio.php';
}

function Preview()
{
    impresora.showDialog=false;
    var reporte = impresora_formato +'_'+  impresora_protocolo;
    if (xtipo=='DEV')
    {
		impresora.origin = 'reportes/r_documento_' + xtipo.toLowerCase()+'_carta_gra';
    }else{
    	impresora.origin = 'reportes/r_documento_' + xtipo.toLowerCase();
    }
    impresora.setParam('ID_M_DOCUMENTOS',id_m_documentos);
	impresora.showDialog=true;
	impresora.preview();
    documentos.setFocus();
}

function Imprimir()
{
    impresora.showDialog=false;
    impresora.setParam('ID_M_DOCUMENTOS',id_m_documentos);
    if(impresora_protocolo != 'PRO')
    {
         //OJO SOLO CONSIDERA LAS FACTURAS....
         if(xid_m_cajas =='')
         {
         	impresora.origin = 'reportes/r_documento_' + xtipo.toLowerCase();
            if(xtipo=='FAC') impresora.origin = 'reportes/r_documento_fact';
	        impresora.showDialog=false;
	        impresora.print();
         }
         else
         {
	         //NO TIENE PROTOCOLO ES UNA IMPRESORA CONVECIONAL.....
	         var reporte = impresora_formato +'_'+  impresora_protocolo;
             if(xtipo=='FAC') xtipo='FACT';
	         impresora.origin = 'reportes/r_documento_'+xtipo.toLowerCase() +'_'+ reporte.toLowerCase();
	         if(impresora_protocolo == 'GRA')
	         {
	            impresora.showDialog=true;
	            impresora.preview();
	         }
	         else
	         {
	            do
	            {
                    //impresora.preview();
                    impresora.setParam('letras',1);
                    impresora.print();
	            }
	            while (confirm("{$t_reimprimir_documento}")==1);
	         }
         }
    }
    else
    {
        var xsufijo = impresora_formato.toLowerCase();
        if(xsufijo=='hka') xsufijo = 'bmc';
        impresora.origin = 'reportes/r_documento_fact_' + xsufijo;
        var res = impresora.printToCom(xpuerto, impresora_formato);

        impresora.printerType = impresora_formato;
        impresora.port        = xpuerto;
        impresora.preview();
        while (!res)
        {
            alert('EpsonPrinterCode: ' + impresora.EpsonPrinterCode() + '\\nEpsonStatus: ' + impresora.EpsonStatus() +  '\\nEpsonLastMessageCode: ' + impresora.EpsonLastMessageCode() +  '\\nEpsonLastMessage: ' + impresora.EpsonLastMessage());
            alert('Error al imprimir en impresora fiscal');
        }
        if (res)
        {
            var docfiscal = impresora.lastNumber;
            var url       = server_path + 'herramientas/utiles/actualizar_registro.php';
            var params = 'tabla=M_DOCUMENTOS&';
            params    += '&busca=ID_M_DOCUMENTOS';
            params    += '&xbusca='+id_m_documentos;
            params    += '&c_DOC_FISCAL_CSS=' + docfiscal;
            var x = enviar(url, params, 'POST');
        }
    }
    documentos.setFocus();
}

function iniciar()
{

    documentos.inicializa();
    centrarObj(documentos.contenedor);
    documentos.mostrar();
    documentos.setFocus();
    document.onclick=function() { if (parent.menu) parent.menu.reset(); }

    addEvent(documentos_DOCUMENTOS_F4, "click",   function() { t_documentos('', _f4,  documentos.elementoXml()) } )
    addEvent(documentos_DOCUMENTOS_F7, "click",   function() { t_documentos('', _f7,  documentos.elementoXml()) } )
    addEvent(documentos_DOCUMENTOS_ESC, "click",  function() { t_documentos('', _esc, documentos.elementoXml()) } )

}
iniciar();
</script>


</body>
</html>

EOT;

?>