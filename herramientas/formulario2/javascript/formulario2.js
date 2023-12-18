var FormularioAct = null;
var FormularioAct = null;
var timerId = null;
var gTabbableTags = new Array("A","BUTTON","TEXTAREA","INPUT","IFRAME");
var fTabIndex = 0;

var tipo_formulario = 0;
var tipo_adicional  = 1;
var tipo_maestro    = 2;
var nObj = 0;
var currFormObj = null;
var FormTimeOut = null;
var currStyle = 'campo_activo';
var xfiltro_maestro = null;

function trim(str) {
    str = str.replace(/^\s+/, '');
        for (var i = str.length - 1; i >= 0; i--) {
                if (/\S/.test(str.charAt(i))) {
                        str = str.substring(0, i + 1);
                        break;
                }
        }
        return str;
}


/*-----------------------------------------------------------------------------
keyHandler: Manejador de teclado para el Formulario
-----------------------------------------------------------------------------*/
function FormHandler(e)
{
    var evt = window.event || e;
    var KeyCode        = evt.keyCode || evt.which;
    var Elem           = evt.srcElement || evt.target;
    var isShiftPressed = evt.shiftKey;
    var isCtrlPressed  = evt.ctrlKey;
    var isAltPressed   = evt.altKey;

    var f = Elem;
    while (f.tagName != 'FORM')
    {
      f = f.parentNode;
    }

    var fm = eval(f.id.replace('frm_',''));

    var valRet = true;
    switch (KeyCode)
    {
      case 13:
		handleEnter( Elem, null, isShiftPressed, isCtrlPressed);
        if (!isShiftPressed) valRet = false;
        else
        {
          return true;
        }
        break;

      case 40:
		break;

      case 27:
        valRet = false;
        break;

      case 33:
        break;

      case 34:
        break;

      default:
        break;
    }

    if (fm.funcion) fm.funcion(Elem, KeyCode, evt);
    return valRet;

};

function formulario2(origen, nombre, x, y, alto, ancho)
{
  this.ini        = new Array;
  this.oForm      = "";
  this.formulario = null;

  var uniqId = new Date().getTime();

  this.nombre     = null;
  this.Armado     = false;
  this.dato       = 'xml';
  this.nombreForm = new Array;
  this.url        = new Array;
  this.aObjetos   = new Array;
  this.arrGrupos  = new Array;
  this.arrPanel   = new Array;
  this.rotulo     = new Array;
  this.tipoPanel  = new Array;
  this.panel      = 0;
  this.xbusca     = new Array;     //--- valor que ser· buscado en busca_xml
  this.valores    = new Array;
  this.leyenda    = "";     //--- leyenda para el form
  this.botones    = "";     //--- botones para el form

  this.funcion    = null;     //--- funcion que recibe todos los eventos de teclado

//------  propiedades usadas solo internamente -------

  this.cuadro     = null;
  this.xtabla     = null;
  this.xbody      = null;
  this.xcampos    = new Array;
  this.rows       = new Array;
  this.x          = (x ? x : 0);
  this.y          = (y ? y : 0);
  this.alto       = alto;
  this.ancho      = ancho;
  
  	
  this.infHeight = 0;

  this.titulo      = "";
  this.titHeight   = 0;
  this.botonCerrar = false;
  this.onClose     = null;

  this.doc         = null; //---- documento donde est· contenido el grid...
  this.leyHeight   = 0;
  this.botHeight   = 0;
  this.arrCombos   = null;
  this.interpretar = false;
  this.iframe      = null;
  this.cerrar      = null;
  this.ayuda       = 0;
  this.padre       = null;
  this.elemPadre   = null;
  this.tabla       = '';
  this.tablaBuscar = '';
  this.indice      = null;
  this.origen      = origen;
  this.abort       = false;
  this.validator   = null;
  this.accion      = null;
  this.divTitulo   = null;
  this.crearInput  = crearInput;
  this.crearBoton  = crearBoton;
  this.crearSelect = crearSelect;
  this.crearFormula = crearFormula;
  this.crearCalculo = crearCalculo;
  this.crearSelectTabla = crearSelectTabla;
  this.crearBotonMaestro = crearBotonMaestro;
  this.tipo = 'formulario';
  this.script = server_path + 'herramientas/utiles/actualizar_registro.php';
  this.initAux = '';
  this.initVal = '';
  this.xconfig = null;

  this.filtro = '';
  this.xfiltro = '';
  this.passFlag=false;
  this.submodal='';
  this.noOcultarCombos=false;
  this.onExit = null;
  this.onNextError=null;
  this.enviarTodo=false;
  this.onload   = ''; //trigger para los formularios "antes de ejecutar"
  this.unload   = ''; //trigger para los formularios "despues de ejecutar"

  this.xbusca[0]     = '-1';
}

formulario2.prototype.inicializa = function()
{
  this.doc = document;
  this.nombreForm[0] = this.nombre;

  if (this.origen)
  {
     switch(this.tipo)
     {
       case 'adicional':
         this.url[0] = server_path  + 'herramientas/utiles/ini2xml.php?tabla=P_MAESTROS&campos=*&busca=TABLA&xbusca=' + this.origen;
         this.leeOrigen(this.url[0]);
         break;
       case 'formulario':
         this.url[0] = server_path  + 'herramientas/utiles/ini2xml.php?origen=' + this.origen;
         this.leeOrigen(this.url[0]);
         break;
       case 'db':
         this.leeOrigen(this.origen);
         break;
     }
  }

  this.armar(this.doc);
  this.preparar(0);
  this.passFlag=false;
}

/*------------------------------------------------------------------------------
funcion forma.armar()
        Crea los divs y tablas iniciales para la forma...
------------------------------------------------------------------------------*/
formulario2.prototype.armar = function(doc)
{
  oThis  = this;
  this.validator = new Array;

  this.doc = doc;

  if (!this.cuadro)
  {
    if (!this.nombre)
    {
      alert('Error. [' + this.origen + '] No se ha designado un nombre al Formulario');
      exit;
    }

    if (!this.doc.body)
    {
      window.setTimeout(this.nombre+'.armar('+this.nombre+'.doc)',50);
      return false;
    }

    if (this.padre) this.elemPadre = this.doc.getElementById(this.padre);

    this.cuadro = this.doc.createElement("div");
    this.cuadro.style.display = 'none';
    if (this.padre)  this.elemPadre.appendChild(this.cuadro);
    else this.doc.body.appendChild(this.cuadro);

	
    this.cuadro.className = 'fondo_formulario'; // Crear un Estilo de Fondo del Formulario
    this.cuadro.onkeydown = FormHandler;

    this.cuadro.innerHTML = "";
    this.cuadro.style.top = (this.y) + "px";

    var d = this.alto - this.titHeight - this.leyHeight - this.botHeight - 110;
    var difAlt = d;

    difAlt = difAlt > 0 ? difAlt : 0;
    //Marco 2021
    if (!difAlt) difAlt="auto";
    else this.cuadro.style.height="100%"; //maxHeight = difAlt; 
    //this.cuadro.style.height = difAlt;

    this.cuadro.style.width  = "auto"; //this.ancho;


  }
  var dHTML = this.creaForma(0);

  this.formulario = document.createElement('form');

  this.formulario.setAttribute('data-origen', this.origen);
  this.formulario.className = 'origen';
  this.formulario.setAttribute('id', 'frm_' + this.nombre);
  this.formulario.setAttribute('name', 'frm_' + this.nombre);
  this.formulario.setAttribute('autocomplete', 'off');
  this.formulario.innerHTML = dHTML;
  this.cuadro.appendChild(this.formulario);
  //prompt('',this.cuadro.outerHTML);
  this.limpiar();
  this.panel = 0;
  
  $("#frm_"+ this.nombre + " input, textarea" ).on('focus', function() {
	  
	  $(this).select();
	  
  });  
  
  
}

/*------------------------------------------------------------------------------
Funcion lista.leeOrigen()
  Rellena el grid usando la informacion en un INI de maestro
------------------------------------------------------------------------------*/
formulario2.prototype.leeOrigen = function (url)
{
    if (!this.panel) this.panel = 0;

    //if(debug()) _prompt('Funcion leeOrigen en Formulario \n Url Origen para formulario en el panel '+ this.panel , url);

    if (!this.panel) this.panel = 0;

    var xurl   = url.split('?');
    eurl   = xurl[0];
    if (xurl[1]) eparam = xurl[1];
    else eparam = '';

    //== MARCO : OCRUBRE 2021

    var ini="";
    if (!!window.inis && inis[this.origen]) {
      ini = inis[this.origen];
    }
    else {
      var x = enviar(eurl,eparam,'POST');
	  //  alert(x);
      //*****if(debug()) _prompt('Retornado: ',x);
      //if (debug()) _prompt('URL enviada por formulario funcion "leeOrigen":', eurl + '?' + eparam);
      ini = parseINI(x);
    }
    //====
    
    if (!ini)
    {
      alert('No se encontrÛ el origen ("'+ this.origen + '")');
      return null;
    }

    this.ini[this.panel] = ini;

    if(ini['VENTANA'])
    {
      if (ini['VENTANA']['TITULO']) this.titulo = ini['VENTANA']['TITULO'];
      //if (ini['VENTANA']['ANCHO'])  this.ancho  = this.ancho?this.ancho:ini['VENTANA']['ANCHO'];
      if (ini['VENTANA']['ANCHO'])  this.ancho  = ini['VENTANA']['ANCHO']?ini['VENTANA']['ANCHO'] : this.ancho;
      if (ini['VENTANA']['ALTO'])   this.alto   = ini['VENTANA']['ALTO']?ini['VENTANA']['ALTO'] : this.alto;
      if (ini['VENTANA']['AYUDA'])  this.ayuda  = ini['VENTANA']['AYUDA'];
	  
		//if( this.alto < 200) this.alto = 200;
		//console.log(this.alto);  	
	  
	  
//      if (debug()) alert('alto: '+this.alto+', ancho: '+this.anchof);
    }

    if (ini['TABLA'])
    {
      if (ini['TABLA']['TABLA'])    this.tabla   = ini['TABLA']['TABLA'].split(',')[0];
      if (ini['TABLA']['INDICE'])   this.indice  = ini['TABLA']['INDICE'];
      if (ini['TABLA']['ONLOAD'])   this.onload  = ini['TABLA']['ONLOAD'];
      if (ini['TABLA']['UNLOAD'])   this.unload  = ini['TABLA']['UNLOAD'];
    }

    var arObjetos = new Array;
    n = 0;
	
    //while(ini['CAMPO' + (n+1)])
	for (var el in ini) 
    {
         //xcolumna = 'CAMPO' + (n+1);
		 if(el.substr(0,5)!='CAMPO') continue;
		 xcolumna = el;
         posic = ini[xcolumna]['POSICION'];
         if (!posic) posic=1;
         if (!arObjetos[posic]) arObjetos[posic] = new Array;
         numElem = arObjetos[posic].length;

         arObjetos[posic][numElem] = new Array;
         xCAMPO = 'CAMPO' + (n+1);
         arObjetos[posic][numElem]['ROTULO']        = ini[xcolumna]['ROTULO'];
         arObjetos[posic][numElem]['CAMPO']         = ini[xcolumna]['CAMPO'];
         arObjetos[posic][numElem]['TIPO']          = ini[xcolumna]['TIPO'];
         arObjetos[posic][numElem]['LONGITUD']      = ini[xcolumna]['LONGITUD'];
         arObjetos[posic][numElem]['ANCHO']         = ini[xcolumna]['ANCHO'];
         arObjetos[posic][numElem]['ALTO']          = ini[xcolumna]['ALTO'];
         arObjetos[posic][numElem]['FORMA']         = ini[xcolumna]['FORMA'];
         arObjetos[posic][numElem]['MASCARA']       = ini[xcolumna]['MASCARA'];
         arObjetos[posic][numElem]['OPCIONES']      = ini[xcolumna]['OPCIONES'];
         arObjetos[posic][numElem]['REQUERIDO']     = ini[xcolumna]['REQUERIDO'];
         arObjetos[posic][numElem]['VALOR']         = ini[xcolumna]['VALOR'];
         arObjetos[posic][numElem]['TABLA']         = ini[xcolumna]['TABLA_DATA'];
         arObjetos[posic][numElem]['CAMPO_GUARDAR'] = ini[xcolumna]['CAMPO_GUARDAR'];
         arObjetos[posic][numElem]['CAMPO_MOSTRAR'] = ini[xcolumna]['CAMPO_MOSTRAR'];
         arObjetos[posic][numElem]['CAMPO_HIJO']    = ini[xcolumna]['CAMPO_HIJO'];
         arObjetos[posic][numElem]['CAMPO_PADRE']   = ini[xcolumna]['CAMPO_PADRE'];
         arObjetos[posic][numElem]['CAMPO_ORDEN']   = ini[xcolumna]['CAMPO_ORDEN'];
         arObjetos[posic][numElem]['RELACIONADO']   = ini[xcolumna]['RELACIONADO'];
         arObjetos[posic][numElem]['RTF']           = ini[xcolumna]['RTF'];
         arObjetos[posic][numElem]['KEYPRESS']      = ini[xcolumna]['KEYPRESS'];
         arObjetos[posic][numElem]['KEYDOWN']       = ini[xcolumna]['KEYDOWN'];
         arObjetos[posic][numElem]['ENVIAR']        = ini[xcolumna]['ENVIAR'];
         arObjetos[posic][numElem]['POSICION']      = ini[xcolumna]['POSICION'];
         arObjetos[posic][numElem]['ORIGEN']        = ini[xcolumna]['ORIGEN'];
         arObjetos[posic][numElem]['FUNCION']       = ini[xcolumna]['FUNCION'];
         arObjetos[posic][numElem]['FILTRO']        = ini[xcolumna]['FILTRO'];
         arObjetos[posic][numElem]['XFILTRO']       = ini[xcolumna]['XFILTRO'];
         arObjetos[posic][numElem]['LEER']          = ini[xcolumna]['LEER'];
         arObjetos[posic][numElem]['XCONFIG']       = ini[xcolumna]['XCONFIG'];
         arObjetos[posic][numElem]['TABULACION']    = ini[xcolumna]['TABULACION'];
         n++;
    }

    var agrupos = new Array;

    n=0;
    while(ini['GRUPO' + (n+1)])
    {
       xgrupo = 'GRUPO'+ (n+1) ;
           agrupos[n] = new Array;
           agrupos[n]['ROTULO'] = ini[xgrupo]['ROTULO'];
           agrupos[n]['DESDE']  = ini[xgrupo]['LINEA_DESDE'];
           agrupos[n]['HASTA']  = ini[xgrupo]['LINEA_HASTA'];
       n++;
    }


    this.arrGrupos[this.panel] = agrupos;

    n = 0;

 var l = '';


    /*
	</div>
</div>

*/

    HTMLleyenda  = '';
	HTMLleyenda  = '<div class="tabla_leyenda">';

    while(ini['LEYENDA' + (n+1)])
    {
	xleyenda = 'LEYENDA' + (n+1);
	if(ini[xleyenda]['ENTER']==0)
	{
		if(ini[xleyenda]['FUNCION']==undefined){
			xfuncion = 't_' + this.nombre + '(\'\',_' + ini[xleyenda]['TECLA'].toLowerCase() +');';
		}else{
			xfuncion = ini[xleyenda]['FUNCION'] +'();';
		}
		
		var xtancho = parseFloat(ini[xleyenda]['ANCHO']);

		if(ini[xleyenda]['ICONO']) xtancho = xtancho + 30;
		
		HTMLleyenda  += '<div onselectstart="return false;" style="width:'+ xtancho +'px;" class="td_leyenda_inactiva" onclick="'+ xfuncion +'">';

		if(ini[xleyenda]['ICONO']){
			HTMLleyenda  += '<span class="icono_leyenda"><i class="' + ini[xleyenda]['ICONO'] +'"></i></span>';
		}

		HTMLleyenda  += '	<span class="texto_leyenda">';
		HTMLleyenda  += '		<div class="tecla_leyenda">';
		HTMLleyenda  += '			['+ ini[xleyenda]['TECLA'] + ']';
		HTMLleyenda  += '		</div>';
		HTMLleyenda  += '		<div class="rotulo_leyenda">';
		HTMLleyenda  += '			' + ini[xleyenda]['ROTULO'];
		HTMLleyenda  += '		</div>';
		HTMLleyenda  += '	</span>';
        HTMLleyenda += '</div>';
		

	}
	n++;
    }

	HTMLleyenda += '</div>';

    if(n>0) this.leyenda = HTMLleyenda;

    this.aObjetos[this.panel] = arObjetos;
    this.Armado = true;

};



/*------------------------------------------------------------------------------
funcion borraHijos(obj)
        Elimina todos los tag's hijos de un elemento html...

------------------------------------------------------------------------------*/
function borraHijos(obj)
{
  if (!obj) return;
  for (i=obj.childNodes.length-1;i>=0;i--)
  {
    obj.removeChild(obj.childNodes[i]);
  }
}

/*------------------------------------------------------------------------------
funcion forma.rellenar_forma2()
luis nunez.....
------------------------------------------------------------------------------*/
formulario2.prototype.rellenar_forma2 = function(xbusca)
{
  var url    = server_path+'herramientas/genera_xml/genera_xml.php';
  var params = 'operador==&tabla='  +this.tabla;
      params+= '&campos='+ this.indice+','+this.filtro +',VALOR_TEXTO,VALOR_NUMERO,VALOR_FECHA,ROTULO';
  	  params+= '&busca=' + this.indice;
      params+= '&xbusca='+ xbusca;
  var xml = enviar(url,params,'POST');
  var reg=XML2Array(xml);
  var i;
  var j;
  var k;

    for (k in this.ini[0])
    {
    	var xobj = this.ini[0][k];
        var ncampo = substr(xobj['CAMPO'],1);
	    for (j in reg)
	    {
            if(ncampo==reg[j][this.filtro])
            {

                var xtipo = substr(xobj['CAMPO'],0,1);
                var cdata = '';
                switch(xtipo)
                {
                	case 'C':
                    	cdata = 'VALOR_TEXTO';
                    break;
                    case 'N':
                    	cdata = 'VALOR_NUMERO';
                    break;
                    case 'D':
                    	cdata = 'VALOR_FECHA';
                    break;
                }
                this.setValue(xobj['CAMPO'], reg[j][cdata]);
//----------------------------------------------------------
                if (xobj['FORMA'] == 'BOTON_MAESTRO')
          		{
                    this.setValue('r_'+ xobj['CAMPO'], reg[j]['ROTULO']);
	          	}
                break;
//-------------------------------------------------
            }
        }
    }
}
/*
------------------------------------------------------------------------------
funcion forma.buscar()
        Poen el valor a buscar en xbusca y manda a generar la lista
------------------------------------------------------------------------------
*/
formulario2.prototype.buscar = function(xbusca, xcampo)
{

  if(this.tipo=='db')
  {
  	this.rellenar_forma2(xbusca);
    return;
  }
  if (!xcampo) var xcampo = this.indice;

  // Leemos los valores del primer formulario
    var objForm =  document.getElementById('frm_'+this.nombreForm[0]);
    //document.forms[this.nombreForm[0]];
    objForm.reset();

    var url = server_path + 'herramientas/genera_xml/genera_xml.php';
    var xcampos = this.indice;
    var xObjetos = this.aObjetos[0];

    if (xObjetos && (xObjetos.length))
    {
      for (i=1;i<xObjetos.length;i++)
      {
        elem = xObjetos[i];
        if(elem && elem.length)
        {
           for (j=0;j<elem.length;j++)
           {
             if (elem[j]['LEER']!='0')
             {
               if (xcampos) xcampos += ',';
               if (elem[j]['FORMA'] == 'FORMULA')
               {
                 xcampos += elem[j]['CAMPO'] + ':' + elem[j]['VALOR'];
               }
               else
               {
                 xcampos += elem[j]['CAMPO'];
               }
             }
           }
        }
      }
    }

    var xtabla=this.tabla;
    if (this.tablaBuscar) xtabla=this.tablaBuscar;

    window.status=this.tablaBuscar;

    var params = 'tabla='+xtabla+'&campos='+xcampos+'&busca='+xcampo+'&xbusca='+unformat2(xbusca)+'&operador==&dale=1';

    if (this.filtro && this.xfiltro)
    {
      params += '&filtro='+this.filtro+'&xfiltro='+this.xfiltro;
    }

    if (this.xconfig)
    {
      params += '&xconfig='+this.xconfig;
    }
 //    alert(params);

  if(this.tipo=='adicional')
  {
    var objForm = document.getElementById('frm_'+this.nombreForm[0]);
    objForm.reset();
    objForm['IDX'].value = xbusca;


    var url = server_path + 'herramientas/genera_xml/genera_xml.php';
    var params = 'tabla=A_'+this.tabla+'&campos=*&busca=IDX&xbusca='+xbusca+'&operador==&dale=2';

    //if(debug()) _prompt('Funcion Buscar en Formulario \n Url para obtener los datos del Panel 1', url+'?'+params);

    var x = enviar(url, params, 'POST');

    var registro = XML2Array(x);
    if (registro.length) objForm['xbusca'].value = registro[0]['IDX'];

    this.rellenar(registro, 1);

    if (!registro.length)
    {
      this.xbusca[0] = '-1';
      objForm['xbusca'].value = -1;
    }
  }
  else
  {
    //if(debug()) _prompt('Funcion Buscar en Formulario \n Url para obtener los datos del Panel '+this.panel, url+'?'+params);
    var x = enviar(url, params, 'POST');
	
	var registro = XML2Array(x);
    try
    {
      this.xbusca[0] = registro[0][this.indice];
      objForm['xbusca'].value = this.xbusca[0];

      this.rellenar(registro, 0);
      this.creaValores();
    }
    catch(e){
		_prompt('formulario2.js', 'Funcion: buscar - Objeto: ' + this.nombre + ' Origen: ' +  this.origen + ' - ' + getStackTrace(), url + '?' + params);
    }
    finally
    {}
  }
  return x;

};



formulario2.prototype.rellenar = function(registro, panel){
    var panel = 0;

    var xini  = this.ini[panel];
    var xtipo = this.tipoPanel[panel];
    var nom_form = 'frm_'+this.nombreForm[panel];
    var objForm = document.getElementById(nom_form);

    if(registro.length)
    {
        n=0 ;
        while(xini['CAMPO' + (n+1)])
        {

          var xcolumna = 'CAMPO' + (n+1);
          var xforma = xini[xcolumna]['FORMA'];
          var xcampo = xini[xcolumna]['CAMPO'];
          var xpadre = xini[xcolumna]['CAMPO_PADRE'];
          var xtipo  = xini[xcolumna]['TIPO'];
          var xopc   = xini[xcolumna]['OPCIONES'];
          var xmascara = xini[xcolumna]['MASCARA'];
          if (xmascara) xmascara = xmascara.toLowerCase();

          if (registro[0][xcampo]) var xvalor = registro[0][xcampo];
          else if (xini[xcolumna]['VALOR']&&xini[xcolumna]['LEER']==0) var xvalor=xini[xcolumna]['VALOR'];
          else var xvalor = '';

//----------------------------------------

          switch (xtipo)
          {
            case 'I':
              var tmp = xvalor.split(".");
              if (tmp.length>0) xvalor = tmp[0];
              break;

            case 'N': // 2 DECIMALES
              xvalor = parseFloat(unformat(xvalor)).toFixed(2);
              break;

            case 'N3':
              xvalor = parseFloat(unformat(xvalor)).toFixed(3);
              break;

            case 'N4':
              xvalor = parseFloat(unformat(xvalor)).toFixed(4);
              break;

            case 'N5':
              xvalor = parseFloat(unformat(xvalor)).toFixed(5);
              break;

            case 'N6':
              xvalor = parseFloat(unformat(xvalor)).toFixed(6);
              break;
			  
			case 'D':
			  var t = xvalor.split('/');
			  if( t.length > 1 ){
				xvalor = t[2] + '-' + t[1] + '-' + t[0];
			  }
				
			break;
			  
          }

//-------------------------------------

          if (xmascara)
          {
            switch (xtipo)
            {
              case 'N':
                var mask = new Mask(xmascara, 'number');
                xvalor = mask.format(xvalor);
                break;
			  case 'D':
				break;
				
              default:
                var mask = new Mask(xmascara);
                xvalor = mask.format(xvalor);
                break;
            }
          }

          switch (xforma)
          {
              case 'SELECCION_TABLA':
                 objForm[xcampo].value = xvalor;
                 if ((!objForm[xcampo].value) && (objForm[xcampo].options.length > 0))
                 {
                   objForm[xcampo].value = objForm[xcampo].options[0].value;
                 }
                 filtros(objForm[xcampo], this.xconfig);
                 var mfiltro  = xini[xcolumna]['FILTRO'];
                 var mxfiltro  = xini[xcolumna]['XFILTRO'];

                 break;

              case 'BOTON':
                 break;

              case 'BOTON_MAESTRO':
                 var xbusca   = xvalor;
                 if(!xbusca) break;

                 objForm[xcampo].value = xvalor;
                 var mmostrar = xini[xcolumna]['CAMPO_MOSTRAR'];
                 var morigen  = xini[xcolumna]['ORIGEN'];
                 var mfiltro  = xini[xcolumna]['FILTRO'];
                 var mxfiltro  = xini[xcolumna]['XFILTRO'];
                 var mxconfig  = xini[xcolumna]['XCONFIG'];
				 
				 if(xfiltro_maestro){
                 	var mxfiltro  = xfiltro_maestro;
                 }
                 else  var mxfiltro  = xini[xcolumna]['XFILTRO']
				 
                 var murl     = server_path + 'herramientas/utiles/ini2xml.php';
                 var mparam   = 'origen=' + morigen ;
                 var x = enviar(murl, mparam, 'POST');

                 var mini     = parseINI(x);
                 var mtabla   = mini['TABLA']['TABLA'];
                 var busca    = mini['TABLA']['INDICE'];

                 var url = server_path + 'herramientas/genera_xml/genera_xml.php';
                 var mparam   = 'tabla=' + mtabla;
                     mparam  += '&campos=*';
                     mparam  += '&busca='+busca;
                     mparam  += '&xbusca='+xbusca;
                     mparam  += '&operador==&dale=2';

                 if (mfiltro && mxfiltro)
                 {
                     mparam  += '&filtro='+mfiltro;
                     mparam  += '&xfiltro='+mxfiltro;
                 }
                 if (mxconfig || this.xconfig) mparam += '&xconfig='+(mxconfig?mxconfig:this.xconfig);

                 var x = enviar(url, mparam, 'POST');
                 //if (debug()) _prompt('resultado:', x);

                 var mregistro = valida_xml(x,mmostrar);
                 if(mregistro)
                 {
                     objForm['r_'+xcampo].value = mregistro[0][mmostrar];
                 }
                 break;

              case 'TEXTAREA_EDITABLE2':
                 objForm[xcampo].value = xvalor;
                 if(xvalor) objForm[xcampo].style.display = 'block';
                 else objForm[xcampo].style.display = 'none';
                 break;

              case 'FECHA':
				 objeto = objForm[xcampo];
                 objeto.formObj = this.nombre;
                 objeto.oldValue = xvalor;
                 objeto.value = xvalor;

                 break;

				 default:
                 objeto = objForm[xcampo];
                 objeto.formObj = this.nombre;
                 objeto.oldValue = xvalor;
                 objeto.value = xvalor;
				 
                 if (xforma=='MARCA')
                 {
                   var mopc=xopc.split(',');
                   objeto.value = mopc[0];
                   if (mopc[0]==xvalor) objeto.checked=true;
                   else objeto.checked=false;
                 }
                 if (objeto.autoSuggest)
                 {
                   getOptions(null,objeto);
                 }
                 break;
          }
          n++;
        }
     }

     if (this.initVal) eval(this.initVal);


};



/*------------------------------------------------------------------------------
funcion forma.creaFor7ma()
   Realiza la busqueda y genera la lista con los nuevos registros encontrados
------------------------------------------------------------------------------*/
formulario2.prototype.creaForma = function(panel)
{
  this.valores[panel] = new Array;

  var xObjetos = this.aObjetos[panel];

  borraHijos(this.cuadro);
  try
  {
    if (this.xbody) this.xbody.parentNode.removeChild(this.xbody);
  }
  catch(e)
  {
  }
  finally
  {
    this.xbody = null;
  }

  //this.doc = doc;
  if (!this.nombre)
  {
    alert('Error. No se ha designado un nombre al Formulario');
    exit;
  }

  var xposicion = 0;
  var jHTML = new Array;
  var xgrupo = -1;
  var elemGrupo = -1;

  var oTabla = document.createElement('table');
  var oTbody = document.createElement('tbody');
  var oTr    = document.createElement('tr');

  oTd = document.createElement('td');

  if (this.tipo == 'adicional')
  {
    oTd.innerHTML = '<input name="tabla" id="tabla" type="hidden" value="A_' + this.tabla + '">';
  }
  else oTd.innerHTML = '<input name="tabla" id="tabla" type="hidden" value="' + this.tabla + '">';
  oTr.appendChild(oTd);

  oTd = document.createElement('td');
  oTd.innerHTML = '<input name="busca" id="busca" type="hidden" value="' + this.indice + '">';
  oTr.appendChild(oTd);

  oTd = document.createElement('td');
  oTd.innerHTML = '<input name="xbusca" id="xbusca" type="hidden" value="' + this.xbusca + '">';
  oTr.appendChild(oTd);

  oTd = document.createElement('td');
  oTd.innerHTML = '<input name="filtro" id="filtro" type="hidden" value="' + this.filtro + '">';
  oTr.appendChild(oTd);

  oTd = document.createElement('td');
  oTd.innerHTML = '<input name="onload" id="onload" type="hidden" value="' + this.onload + '">';
  oTr.appendChild(oTd);

  oTd = document.createElement('td');
  oTd.innerHTML = '<input name="unload" id="unload" type="hidden" value="' + this.unload + '">';
  oTr.appendChild(oTd);

  oTd = document.createElement('td');
  oTd.innerHTML = '<input name="xfiltro" id="xfiltro" type="hidden" value="' + this.xfiltro + '">';
  oTr.appendChild(oTd);
  switch (this.tipo)
  {
  	case 'adicional':
		oTd = document.createElement('td');
	    var txHTML = '<input name="c_IDX_CSN" id="IDX" type="hidden" value="">';
            txHTML+='<input name="forma" id="forma" type="hidden" value="1">';
        oTd.innerHTML = txHTML;
	    oTr.appendChild(oTd);
        this.enviarTodo=true;
    break;
    case 'db':
		oTd = document.createElement('td');
	    oTd.innerHTML = '<input name="forma" id="forma" type="hidden" value="2">';
	    oTr.appendChild(oTd);
    break;
  }


  oTbody.appendChild(oTr);
  oTabla.appendChild(oTbody);
  var mHTML = oTabla.outerHTML;
  var tHTML = '';

  this.initAux = '';
  this.initVal = '';

  if (xObjetos && (xObjetos.length))
  {
     for (i=1;i<xObjetos.length;i++)
     {
        elem = xObjetos[i];
        if(elem)
        {
           xposicion = i;
           oTabla = document.createElement('table');
		   oTabla.setAttribute('id', 'LINEA' + i);
           oTbody = document.createElement('tbody');
           oTr    = document.createElement('tr');	   

           for ( j = 0; j<elem.length; j++)
           {
              oTd = document.createElement('td');

              if(elem[j]['RELACIONADO']) elem[j]['TIPO'] = 'R';

              var xrotulo  = elem[j]['ROTULO']   ? elem[j]['ROTULO']  : '';
              var xcampo   = elem[j]['CAMPO']    ? elem[j]['CAMPO']   : '';
              var xvalor   = elem[j]['VALOR']    ? elem[j]['VALOR']   : '';
              var xancho   = elem[j]['ANCHO']    ? elem[j]['ANCHO']   : '';
              var xtipo    = elem[j]['TIPO']     ? elem[j]['TIPO']    : '';
              var xforma   = elem[j]['FORMA']    ? elem[j]['FORMA']   : '';
              var xpos     = elem[j]['POSICION'] ? elem[j]['POSICION']: '';
              var xenvia   = elem[j]['ENVIAR']   ? elem[j]['ENVIAR']  : 1;
              var xopc     = elem[j]['OPCIONES'] ? elem[j]['OPCIONES']: '';
              var xfiltro  = elem[j]['FILTRO']   ? elem[j]['FILTRO']  : '';
              var xxfiltro = elem[j]['XFILTRO']  ? elem[j]['XFILTRO'] : '';
              var xlongitud= elem[j]['LONGITUD'] ? elem[j]['LONGITUD']: '';
              var xmascara = elem[j]['MASCARA']  ? elem[j]['MASCARA'] : '';
              var xfuncion = elem[j]['FUNCION']  ? elem[j]['FUNCION'] : '';
              var xconfig  = elem[j]['XCONFIG']  ? elem[j]['XCONFIG'] : '';

              if ((xtipo=='M') || (xforma=='MARCA'))
              {
                xtipo='C';
                xforma='MARCA';
              }

              //if ( debug() ) console.log( 'Origen:' + this.nombre + ' - CAMPO = ' + xcampo + ' - #' + ( j+1 ) + ' - ROTULO = ' + xrotulo );

              oTd.id = 'celda_'+this.nombre + '_'+xcampo;

              if (xforma == '')
              {
				alert('Error en el Formulario: [' + this.nombre + '] \n\nEl campo [' + xcampo + '] No tiene definido la propiedad FORMA');
				break;
				return;
              }

              if (xpos == '')
              {
				 alert('Error en el Formulario: [' + this.nombre + '] \n\nEl campo [' + xcampo + '] No tiene definida la propiedad POSICION');
                 break;
                 return;
              }

              switch (xforma)
              {
                 case 'CALCULO':
                    oTd.innerHTML = this.crearCalculo(
                    xrotulo,
                    xcampo,
                    xvalor,
                    xtipo,
                    xancho,
                    xlongitud,
                    xmascara,
                    xenvia);
                    break;

                 case 'FORMULA':
                    oTd.innerHTML = this.crearFormula(
                    xrotulo,
                    xcampo,
                    xvalor,
                    xtipo,
                    xancho,
                    xlongitud);
                    break;
                 case 'SELECCION_SIMPLE':
                    oTd.innerHTML = this.crearSelect(
                    xrotulo,
                    xcampo,
                    xvalor,
                    elem[j]['OPCIONES'],
                    xtipo,
                    xenvia,
                    xancho);
                    break;

                 case 'SELECCION_TABLA':
                    campos = elem[j]['CAMPO_GUARDAR']+ ',' + elem[j]['CAMPO_MOSTRAR'];
                    oTd.innerHTML = this.crearSelectTabla(
                    xrotulo,
                    xcampo,
                    xvalor,
                    elem[j]['TABLA'],
                    campos,
                    elem[j]['CAMPO_ORDEN'],
                    xtipo,
                    elem[j]['CAMPO_PADRE'],
                    xenvia,
                    xancho,
                    xfiltro,
                    xxfiltro);
                    break;

                 case 'BOTON':
                    oTd.innerHTML = this.crearBoton(
                    xrotulo,
                    xcampo,
                    xancho,
                    elem[j]['FUNCION'],
                    xenvia);
                    break;

                 case 'BOTON_MAESTRO':
                    oTd.innerHTML = this.crearBotonMaestro(
                    xrotulo,
                    xcampo,
                    xvalor,
                    xancho,
                    elem[j]['ORIGEN'],
                    elem[j]['CAMPO_GUARDAR'],
                    elem[j]['CAMPO_MOSTRAR'],
                    xtipo,
                    xenvia,
                    xfiltro,    /* creado por Marco Julio 2006 */
                    xxfiltro,
                    xfuncion,  /* creado por Marco Agosto 2006 */
                    xconfig);  /* creado por Marco Octubre 2007 */
                    break;

                 default:
                    //alert('defaul');
                    oTd.innerHTML = this.crearInput(
                    xrotulo,
                    xcampo,
                    xforma,
                    xvalor,
                    xancho,
                    elem[j]['ALTO'],
                    elem[j]['LONGITUD'],
                    xtipo,
                    xenvia,
                    xopc);
                    break;

              }
              //para separar los campos cuando estan en la misma linea..luis nuÒez
              if(j>0)
              {
                var xoTd = document.createElement('td');
                    var anchos = xancho.split(',');
                    if (anchos.length > 1)
                    {
                        xoTd.width='25px';
                }
                                xoTd.innerHTML ='';
                                oTr.appendChild(xoTd);
              }
              oTr.appendChild(oTd);


           }
           oTbody.appendChild(oTr);
           oTabla.appendChild(oTbody);

           jHTML[i]= oTabla.outerHTML;
        }

     }
  }

  if (this.arrGrupos)
  {
     var grupo = this.arrGrupos[panel];

     if (grupo && grupo.length)
     {
        for (i=0;i< grupo.length;i++)
        {
			xdesde = grupo[i]['DESDE'];
			xhasta = grupo[i]['HASTA'];

			//alert(this.nombre+'_grupo'+(i+1));
			
			if( grupo[i]['ROTULO'] != undefined ){
				tHTML = '<fieldset name="'+this.nombre+'_grupo'+(i+1)+'" id="'+this.nombre+'_grupo'+(i+1)+'"><legend>' + grupo[i]['ROTULO'] + '</legend>' + '\n';
			  
			}	else {
				tHTML = '<fieldset name="'+this.nombre+'_grupo'+(i+1)+'" id="'+this.nombre+'_grupo'+(i+1)+'">' + '\n';
			}

			for(j=parseInt(xdesde);j<=parseInt(xhasta);j++)
			{

				if(jHTML[j])
				{
					tHTML += jHTML[j] + '\n';
					jHTML[j] = "";
				}

			}
			//tHTML += '</fieldset><br id="'+this.nombre+'_br'+(i+1)+'">' + '\n';
			tHTML += '</fieldset>' + '\n';
			jHTML[xdesde] = tHTML;

        }
     }
  }

  tHTML = jHTML.join('');


//  var fHTML = '<form style="margin-bottom:0" autocomplete="off" onsubmit="return false;" id="frm_' + this.nombreForm[panel] + '" name="'+ this.nombreForm[panel] + '">' + mHTML + tHTML + '</form>';
  var fHTML = mHTML + tHTML;
 // prompt('', fHTML);
  return fHTML;

}

/*------------------------------------------------------------------------------
Funcion forma.mostrar()
  muestra la forma...
------------------------------------------------------------------------------*/
formulario2.prototype.mostrar = function ()
{
   if (this.submodal)
   {
     var sm=eval(this.submodal);
     if (sm && sm.modal && sm.mascara)
     {
       zIndexGral +=1;
       sm.mascara.style.zIndex=zIndexGral;
     }

     if (sm && sm.contenedor)
     {
       zIndexGral +=1;
       sm.contenedor.style.zIndex=zIndexGral;
     }
   }

   this.cuadro.style.display = 'block';
   var objForm = document.getElementById('frm_'+this.nombreForm[this.panel]);

   for (var i=0; i<objForm.length; i++)
   {
      var obj = objForm[i];
      if(obj.tagName=='SELECT')
      {
        obj.style.display = 'block';
      }

   }

//  this.deshabilitarTabs();
  this.ocultarCombos();

  this.cuadro.scrollTop = 0;

   if (this.initAux)
   {
     eval(this.initAux);
   }

};

formulario2.prototype.mostrarCombos = function()
{
  if (this.noOcultarCombos) return;
  if (this.arrCombos && this.arrCombos.length)
  {
    for (var i=0;i<this.arrCombos.length;i++)
    {
      this.arrCombos[i].style.display = 'block';
    }
  }
};

formulario2.prototype.ocultarCombos = function()
{
   if (this.noOcultarCombos) return;
   this.arrCombos = new Array;
   sels = document.getElementsByTagName('SELECT');

   if (sels && sels.length)
   {
     for (var i=0;i<sels.length;i++)
     {
       if (sels[i].form)
       {
          if (((sels[i].form.name != 'frm_'+this.nombreForm[0]) && (sels[i].style.display!="none")) && ((sels[i].form.name != 'frm_'+this.nombreForm[1]) && (sels[i].style.display!="none")))
          {
            this.arrCombos[this.arrCombos.length] = sels[i];
            sels[i].style.display="none";
          }
       }
     }
   }

   sels = document.getElementsByTagName('OBJECT');

   if (sels && sels.length)
   {
     for (var i=0;i<sels.length;i++)
     {
       if (sels[i].Form)
       {
          if (((sels[i].Form != 'frm_'+this.nombreForm[0]) && (sels[i].style.display!="none")) && ((sels[i].Form != 'frm_'+this.nombreForm[1]) && (sels[i].style.display!="none")))
          {
            this.arrCombos[this.arrCombos.length] = sels[i];
            sels[i].style.display="none";
          }
       }
     }
   }

};


/*------------------------------------------------------------------------------
Funcion forma.ocultar()
  muestra la forma...
------------------------------------------------------------------------------*/
formulario2.prototype.ocultar = function ()
{
  this.cuadro.style.display = 'none';
  this.mostrarCombos();
  if (this.onClose) this.onClose(this);
};

formulario2.prototype.destruir = function ()
{
  borraHijos(this.xbody);
  borraHijos(this.xtabla);
  borraHijos(this.cuadro);
};

formulario2.prototype.setFocus = function()
{
  if(!this.Armado)
  {
    alert('No se puede establecer el focus al objeto '+ this.nombre);
    return false;
  }

    var indice = this.panel;
    var xfoco = 0;

    FormularioAct = this;
    zIndexGral += 2;

    if(indice>1) return true;

    var objForm  = document.getElementById('frm_'+this.nombreForm[this.panel]);
    var fobjeto  = null;
    var ofobjeto = null;

    if (!objForm) return false;

    for (var i=0;i<objForm.length;i++)
    {
           if(objForm[i].name)
       {
          var xnombre = objForm[i].name;
         // if(xnombre.substr(xnombre.length-1,1)=='N') objForm[i].tabIndex="-1";

          if((xnombre.substr(xnombre.length-1,1)=='S')&&(!fobjeto)&&(objForm[i].parentNode.style.display!='none'))
          {
             if (objForm[i].autoSuggest) fobjeto = objForm[i].autoSuggest.textbox;
             else fobjeto = objForm[i];
             xfoco = 1;
          }
       }
    }

    if(!fobjeto)
    {
       setTimeout('frm_'+this.nombre + ".setFocus()",50);
       return false;
    }

    if ((fobjeto.tagName!='SELECT') && (fobjeto.tagName!='BUTTON')) fobjeto.select();
    fobjeto.focus();
    if(fobjeto.tagName=="TEXTAREA") fobjeto.value = fobjeto.value;
    return;

};



formulario2.prototype.deshabilitarTabs = function()
{
        var panel = 0;
        this.gTabIndexes = new Array;
        if (document.all) {
                var i = 0;
                for (var j = 0; j < gTabbableTags.length; j++) {
                        var tagElements = document.getElementsByTagName(gTabbableTags[j]);

                        for (var k = 0 ; k < tagElements.length; k++) {

                                this.gTabIndexes[i] = tagElements[k].tabIndex;
                                if((tagElements[k].form) && (tagElements[k].form.name!=this.nombreForm[0]) && (tagElements[k].form.name!=this.nombreForm[1]) )
                                {
                                   tagElements[k].tabIndex="-1";
                                }
                                i++;

                        }
                }
        }
};

formulario2.prototype.restauraTabs = function()
{
        if (document.all) {
                var i = 0;
                for (var j = 0; j < gTabbableTags.length; j++) {
                        var tagElements = document.getElementsByTagName(gTabbableTags[j]);
                        for (var k = 0 ; k < tagElements.length; k++) {
                                tagElements[k].tabIndex = this.gTabIndexes[i];
                                tagElements[k].tabEnabled = true;
                                i++;
                        }
                }
        }
};



formulario2.prototype.datepicker = function(campo, formato)
{

};

formulario2.prototype.limpiar = function()
{
  if(!this.Armado)
  {
    alert('No se puede limpiar el objeto '+ this.nombre);
    return false;
  }
  var panel = this.panel; // Cambiar por Panel Actual

  for (var j=0;j<this.nombreForm.length;j++)
  {
    var objForm = document.getElementById('frm_'+this.nombreForm[j]);

    if(!objForm) continue;

    objForm.reset();

    for (var i=0; i<objForm.length; i++)
    {
       if(objForm[i].id)
       {
          objForm[i].formObj = this;
          if (objForm[i].oldValue) objForm[i].oldValue = objForm[i].value;
          if (objForm[i].tagName == 'SELECT') filtros(objForm[i], this.xconfig);
       }
    }
  }


  for (var i=0;i<this.ini.length;i++)
  {
     var n = 0;
     var xini = this.ini[i];
     var objForm = document.getElementById('frm_'+this.nombreForm[i]);


        while(xini['CAMPO' + (n+1)])
        {
			var xcolumna = 'CAMPO' + (n+1);
			var xforma = xini[xcolumna]['FORMA'];
			var xcampo = xini[xcolumna]['CAMPO'];
			var xvalor = xini[xcolumna]['VALOR'] ?? null;
        
			switch (xforma)
			{
			case 'TEXTAREA_EDITABLE2':
				objForm[xcampo].style.display = 'none';
				break;

			case 'FECHA':
				if ( xvalor == "HOY" ) { 
					var t = server_date.split('/');
					if( t.length > 1 ){
					  xvalor = t[2] + '-' + t[1] + '-' + t[0];
					}
					objForm[xcampo].value = xvalor;
					
				}
				break;
			}
			n++;
        }
  }
  this.xbusca[0] = '-1';
  objForm['xbusca'].value = '-1';

  this.creaValores();

};


formulario2.prototype.creaValores = function(){
	
	var objForm = document.getElementById('frm_'+this.nombreForm[0]);

	this.valores[0] = new Array;

	if(!objForm) return false;
	for (var i=0;i<objForm.length;i++){
		if(objForm[i].id){
			this.valores[0][objForm[i].id] = objForm[i].value;
		}
	}
};

formulario2.prototype.validar = function()
{
    if ((this.validator) && (this.validator[0]))
    {
      return this.validator[0].validar();
    }
    else
    {
      return true;
    }
}


formulario2.prototype.submit = function(panel)
{
    if(!panel) panel = this.panel;
    if (!this.validar()) return false;


    if (this.accion)
    {
      var obj = document.getElementById('frm_'+this.nombreForm[panel]);
      this.accion(obj);
      return false;
    }

    var registro = new Array;

    var objForm0 = document.forms['frm_'+this.nombreForm[0]];
    var xindice = this.indice;

    var registro = enviar_forma(objForm0, this.valores[0], this.script, this.xconfig, this.enviarTodo,this.dato);


    try
    {
      if(registro[0][xindice])
      {
        objForm0['xbusca'].value = registro[0][xindice];
        this.xbusca[0] = registro[0][xindice];
        registro[0]['UNICO'] = registro[0][xindice];
        this.creaValores();
      }
    }
    catch(e)
    {
//      alert('Este es el indice: '+xindice);
    }
    finally
    {}

    return registro;

};

function selCheck(objID)
{
  var obj=_(objID);
  if (obj.type.toUpperCase()=='CHECKBOX') obj.checked = !obj.checked;
  else if (!obj.checked) obj.checked=true;
  obj.focus();
}

function crearInput(rotulos, name, forma, valor, ancho, alto, longitud, tipo, envia, opc)
{
    var xHtml = "";
        if(!name)  name  = '';
        if(!forma) forma = '';
        if(!valor) valor = '';
        if(!ancho) ancho = '';
        if(!alto)  alto  = '';

    var anchoValor = ancho;
    var anchoRotulo = null;

    var anchos = ancho.split(',');

    if (anchos.length > 1)
    {
      ancho  = anchos[1];
      anchoRotulo = anchos[0];
    }

    var sep = '';
    var fin = '';
    var old_rotulos=rotulos;

    if (anchoRotulo)
    {
		var tmp = '';
		tmp  = '';
		tmp += '<table cellpadding="0" cellspacing="0">';
		tmp += '<tr>';
		tmp += '<td class="rotulo_formulario" data-campo="' + name + '" width="' + anchoRotulo + 'px">';
		tmp += rotulos;
		
		rotulos = tmp;
		
		sep = '</td><td>';
		fin = '</td></tr></table>';
    } else {
		
		var tmp = '';
		tmp  = '';
		tmp += '<div class="rotulo_formulario" data-campo="' + name + '" style="width:' + ancho + 'px; margin-bottom: 3px;">';
		tmp += rotulos;
		tmp += '</div>';
		tmp += '<div>';
		
		rotulos = tmp;
		sep = '';
		fin = '</div>';	
	}

    if(envia != 0) envia = 1;
     if(tipo!='L')
    {
            var estilo  = ' class="campo_inactivo" onfocus="javascript:currFormObj=this;"';
    }
    else
    {
        var estilo  = ' class="campo_inactivo_minusculas" onfocus="javascript: currFormObj=this;"; ';
    }

    var xid     = 'id = "' + name + '"';

    var prefijo = (envia!=0) ? 'c_' : 'x_';
    switch (forma)
    {
       case 'FECHA':
          if (valor==="HOY") {
            var hoy=(new Date).toISOString();
            hoy=hoy.replace(/([0-9]{4})\-([0-9]{2})\-([0-9]{2}).*/, '$1-$2-$3');
            valor=hoy;
          }
          xfoco = 'S';
		  ancho =parseInt(ancho) + 20;
          xname  = 'name ="'+ prefijo + name + '_' + tipo + 'S' + xfoco + '"';
		  //xHtml = rotulos + sep + '<input ' + estilo + xid + xname +' type="date" value="' + valor + '" maxlength="' + longitud + '" style="width:' + ancho + 'px">' + fin;
		  xHtml = rotulos + sep + '<input ' + estilo + xid + xname +' type="date" value="' + valor + '" maxlength="' + longitud + '" style="" data-tipo="' + tipo +'">' + fin;
          break;

       case 'HORA':

          xfoco = 'S';
		  tipo = 'C';
		  ancho =parseInt(ancho) + 30;
          xname  = 'name ="'+ prefijo + name + '_' + tipo + 'S' + xfoco + '"';
          //xHtml = rotulos + sep + '<input ' + estilo + xid + xname +' type="text" value="' + valor + '" maxlength="' + longitud + '" style="width:' + ancho + 'px">' + fin;
		  xHtml = rotulos + sep + '<input ' + estilo + xid + xname +' type="time" value="' + valor + '" maxlength="' + longitud + '" style="width:' + ancho + 'px" data-tipo="' + tipo +'">' + fin;
          break;

		  case 'OCULTO':
          xfoco = 'N';
          xname  = 'name ="'+  prefijo + name + '_' + tipo + 'S' + xfoco + '"';
          xHtml = '<input ' + xid + xname + ' type="hidden" value="' + valor + '" maxlength="' + longitud + '" style="width:' + ancho + 'px" data-tipo="' + tipo +'">' + fin;
          break;

       case 'TEXTO_EDITABLE':
          xfoco = 'S';
          var tc='R';
          if (tipo!='T') tc=tipo;
          xname  = 'name ="'+ prefijo + name + '_' + tc + 'S' + xfoco + '"';
          xHtml = rotulos + sep + '<input ' + estilo + xid + ' ' + xname + ' type="text" value="' + valor + '" maxlength="' + longitud + '" style="width:' + ancho + 'px;" autocomplete="off" data-tipo="' + tipo + '" >' + fin;

		  break;

       case 'TEXTO_SUGERIDO':
          xfoco = 'S';
          xname = 'name ="'+ prefijo + name + '_' + tipo + 'S' + xfoco + '"';
          xHtml =  rotulos + sep + '<input ' +  xid + xname + ' type="hidden" value="' + valor + '" maxlength="' + longitud + '" style="width:' + ancho + 'px;" data-tipo="' + tipo +'">'+ fin;
          break;

       case 'TEXTO_NOEDITABLE':
          xfoco = 'N';
          xname  = 'name ="'+ prefijo + name + '_' + tipo + 'S' + xfoco + '"';
          xHtml = rotulos + sep + '<input ' + estilo + xid + xname + ' type="text" value="' + valor + '" maxlength="' + longitud + '" style="width:' + ancho + 'px" readonly data-tipo="' + tipo +'">'+ fin;
          break;

       case 'PASSWORD':
          xfoco = 'S';
          xname  = 'name ="'+ prefijo + name + '_' + tipo + 'S' + xfoco + '"';
          xHtml = rotulos + sep + '<input ' + estilo + xid + xname + ' type="password" value="' + valor + '" maxlength="' + longitud + '" style="width:' + ancho + 'px" data-tipo="' + tipo +'" data-tipo="' + tipo +'">' + fin;
          break;

       case 'TEXTAREA_EDITABLE':
		  xfoco = 'S';
          xname  = 'name ="'+ prefijo + name + '_' + tipo + 'S' + xfoco + '"';
          xHtml = rotulos + sep + '<textarea onkeypress="tecla_text();" ' + estilo + xid + xname + ' type="text" value="' + valor + '" maxlength="' + longitud + '" style="width:' + ancho + 'px; height:' + alto + 'px" autocomplete="off" data-tipo="' + tipo +'">'+ valor +'</textarea>' + fin;
          break;

       case 'TEXTAREA_NOEDITABLE':
          xfoco = 'N';
          xname  = 'name ="'+ prefijo + name + '_' + tipo + 'S' + xfoco + '"';
          xHtml = rotulos + sep + '<textarea onkeypress="tecla_text();" ' + estilo + xid + xname + ' type="text" value="' + valor + '" maxlength="' + longitud + '" style="width:' + ancho + 'px; height:' + alto + 'px" readonly data-tipo="' + tipo +'">'+ valor +'</textarea>' + fin;
          break;

       case 'TEXTAREA_EDITABLE2':
          xfoco = 'N';
          xname  = 'name ="'+ prefijo + name + '_' + tipo + 'S' + xfoco + '"';
          xHtml = '<button type="button" name="x_'+name+'_BSS" style="width:' + ancho + 'px;" onclick="mostrar_campo(\'' + name + '\')">' + rotulos + '</button><br><textarea onkeypress="tecla_text();" ' + estilo + xid + xname + ' type="text" value="' + valor + '" maxlength="' + longitud + '" style="display:none; width:' + ancho + 'px; height:' + alto + 'px"></textarea>';
          break;

       case 'NUMERO_EDITABLE':
          xfoco = 'S';
          xname  = 'name ="'+ prefijo + name + '_' + tipo + 'S' + xfoco + '"';
          xHtml = rotulos + sep + '<input ' + estilo + xid + xname + xfoco + ' type="text" value="' + valor + '" maxlength="' + longitud + '" style="width:' + ancho + 'px; text-align:right;" autocomplete="off" data-tipo="' + tipo +'">' + fin;
          break;

       case 'NUMERO_NOEDITABLE':
          xfoco = 'N';
          xname  = 'name ="'+ prefijo + name + '_' + tipo + 'S' + xfoco + '"';
          xHtml = rotulos + sep + '<input ' + estilo + xid + xname + ' type="text" value="' + valor + '" maxlength="' + longitud + '" style="width:' + ancho + 'px; text-align:right;" readonly data-tipo="' + tipo +'">' + fin;
          break;

       case 'RTF_EDITABLE':
          xfoco = 'S';
          xid = name;
          xname  = prefijo + name + '_' + tipo + 'S' + xfoco;
		  //marco          xHtml = rotulos + sep + '<textarea ' + xid + xname + '" style="width:' + ancho + 'px; height:' + alto + 'px">' + valor + '</textarea>' + fin;
          xHtml = loadRichEditor(xid+'_RTF', ancho, alto, valor);
          xHtml += '<input type="hidden" name="'+xname+'" id="'+name+'" value="" data-tipo="' + tipo +'">'+ fin
          break;

       case 'MARCA':
          var mopc=opc.split(',');
          xfoco = 'S';
          var mname=prefijo + name + '_' + tipo + 'S' + xfoco;
          xname  = 'name ="'+ mname + '"';
          xHtml = '<input ' + estilo + xid + xname + ' type="checkbox" value="' + mopc[0] + '"' + (mopc[0]==valor?' checked':'')+ ' style="cursor:pointer;"><span onclick="selCheck(\''+name+'\')" style="cursor:pointer;" data-tipo="' + tipo +'"> ' + old_rotulos+'</span>';
          break;

    }
	
    return xHtml;

};


function crearBoton(rotulos, name, ancho, accion, envia)
{
    var estilo = ' class="boton_inactivo" ';

    if(envia != 0) envia = 1;

    xfoco = 'S';
    var xname  = 'name ="x_' + name + '_CS' + xfoco + '"';

    var f = '';
    if (envia != 0) f = 'if (!frm_'+this.nombre + '.submit()) return false; ';

    xHtml = '<br><button type="button" onclick="' + f + accion + '" id="' +  name + '"'+ xname + estilo + ' style="width:' + ancho + 'px;" >' + rotulos + '</button>';
    return xHtml;
}

function IsNumeric(sText)
{
   var ValidChars = "0123456789.";
   var IsNumber=true;
   var Char;
   var s='';

   s=sText;
//   alert('Marco: texto: '+s);

   for (var i = 0; i < sText.length && IsNumber == true; i++)
      {
      Char = sText.charAt(i);
      if (ValidChars.indexOf(Char) == -1)
         {
         IsNumber = false;
         }
      }
   return IsNumber;

   }


function crearCalculo(rotulos, name, valor, tipo, ancho, longitud, mascara, envia)
{
    var align = '';
    if ((tipo == 'N') || (tipo == 'I'))
    {
       align = 'text-align:right;';
       var pp = mascara.indexOf('.');
       var decimales = 0;

       if (pp >= 0)
       {
          var decimales = mascara.length - pp - 1;
       }

    }

    var xHtml = '';
    var estilo  = ' class="campo_inactivo" onfocus="javascript: currFormObj=this;" ';
    var xid     = 'id = "' + name + '"';

    var prefijo = (envia!=0) ? 'c_' : 'x_';

    var xfoco = 'N';
    var xname  = 'name ="'+ prefijo + name + '_' + tipo + 'S' + xfoco + '"';



//    var regExpr = /\b(\w+)\b/g;

    var regExpr = /\b[a-zA-Z 0-9\_\,\.·ÈÌÛ˙¡…Õ”⁄—Ò]+\b/g;
    var ar = valor.match(regExpr);
    var vars=new Array;

    var n=0;

//    alert('Marco: valor='+valor);
    for (var i=0;i<ar.length;i++)
    {
      if (ar[i]!='')
      {
        if (!vars[ar[i]])
        {
          if (IsNumeric(ar[i])) vars[ar[i]]=ar[i];
          else vars[ar[i]]=String.fromCharCode(96+(++n));
        }
      }
      valor=valor.replace(ar[i], vars[ar[i]]);
    }

//    alert('Marco: resultado='+valor);

    var func = '';

    var s='';

    if (ar.length > 0)
    {

      func = 'document[\''+name+'_cambio\'] = function() {\n';
      func += 'var obj = '+this.nombre+'.formulario[\''+name+'\'];\n';

      for (var i = 0; i < ar.length; i++)
      {
        if ((ar[i].substr(0,1).toUpperCase() >= 'A') && (ar[i].substr(0,1).toUpperCase() <= 'Z'))
        {
           if (s.indexOf(ar[i]) < 0)
           {
             s += '-'+ar[i]+'-';
             func += 'var o'+vars[ar[i]] + ' = '+this.nombre+'.formulario[\''+ar[i]+'\'];\n';
             func += 'var '+vars[ar[i]] + ' = 0;\n';
             func += 'var n = o'+vars[ar[i]]+'.value;\n';
             func += 'while (n.indexOf(\',\') >= 0) n = n.replace(\',\',\'\');\n';
             func += 'if (o'+vars[ar[i]]+') '+vars[ar[i]]+' = parseFloat(n);\n';
             this.initAux += 'addEvent('+this.nombre+'.formulario[\''+ar[i]+'\'], "keyup", document[\''+name+'_cambio\']);\n';
           }
        }
      }
      func += '\n  var res = '+valor+';';

      if (decimales)
      {
        var dec = '1';
        for (i=0;i<decimales;i++) dec += '0';
        func += '\n  res = Math.round(res * '+dec+')/'+dec+';';
      }

      func += '\n if (isNaN(res)) res=0;\n';
      func += '\n if (res == "Infinity") res=0;\n';

      func += '\n obj.value = res;\n}\n\n';
/*
      //agregado por luis nunez verificar marco.....
      func += '\n var mask = new Mask(\''+ mascara +'\', \'number\');\n';
      func += '\n xvalor = mask.format(res);\n';
      func += '\n obj.value = xvalor;\n}\n\n';
          ///fin
      */

      this.initVal += '\n document[\''+name+'_cambio\']();';
      this.initAux = func + this.initAux + this.initVal;
    }

    var anchoRotulo = null;
    if (ancho)
    {
      var anchos = ancho.split(',');

      if (anchos.length > 1)
      {
        ancho  = anchos[1];
        anchoRotulo = anchos[0];
      }
    }

    if (anchoRotulo) xHtml = '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="rotulo_formulario" data-campo="' + name + '" style="width:'+anchoRotulo+';">'+rotulos+'</td><td>';
    else xHtml = rotulos + '<br>';

    xHtml += '<input ' + estilo + xid + xname + ' type="text" style="width:' + ancho + 'px; '+align+'" readonly maxlength="'+longitud+'px" data-tipo="' + tipo + '">';

    if (anchoRotulo) xHtml += '</td></tr></table>';


    return xHtml;
}


function crearFormula(rotulos, name, valor, tipo, ancho)
{
    var align = '';
    if ((tipo == 'N') || (tipo == 'I'))
    {
       align = 'text-align:right;';
    }

    var xHtml = '';
    var estilo  = ' class="campo_inactivo" onfocus="javascript:currFormObj=this;"';
    var xid     = 'id = "' + name + '"';
    var prefijo = 'x_';
    var xfoco = 'N';
    var xname  = 'name ="'+ prefijo + name + '_' + tipo + 'S' + xfoco + '"';

    var anchoRotulo = null;
    if (ancho)
    {
      var anchos = ancho.split(',');

      if (anchos.length > 1)
      {
        ancho  = anchos[1];
        anchoRotulo = anchos[0];
      }
    }

    if (anchoRotulo) xHtml = '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="rotulo_formulario" data-campo="' + name + '" style="width:'+anchoRotulo+';">'+rotulos+'</td><td>';
    else xHtml = rotulos + '<br>';

    xHtml += '<input ' + estilo + xid + xname + ' type="text" value="' + valor + '" style="width:' + ancho + 'px; '+align+'" readonly>';

    if (anchoRotulo) xHtml += '</td></tr></table>';

    return xHtml;
}

function crearSelect(rotulos, name, valor, opciones, tipo, envia, ancho)
{
	var xHtml = "";
	if(!name) name   = '';
	if(!valor) valor  = '';
	if(!opciones) opciones = '';
	if(envia != 0) envia = 1;

	var anchoValor = ancho;
	var anchoRotulo = null;

	if (ancho)
	{
	  var anchos = ancho.split(',');

	  if (anchos.length > 1)
	  {
		ancho  = anchos[1];
		anchoRotulo = anchos[0];
	  }
	}


	var prefijo = (envia!=0) ? 'c_' : 'x_';

	var xid    = 'id = "' + name + '"';
	var xfoco  = 'S';
	xname  = 'name ="'+ prefijo + name + '_' + tipo + 'S' + xfoco + '"';

	var estilo = '';
	var estilo = ' class="campo_inactivo" ';
	var aopciones = opciones.split(',');

	var avalores  = new Array;
	var amostrar  = new Array;
	var valores;

	for (var i=0;i < aopciones.length;i++)
	{
	 valores = aopciones[i].split(':');
	 amostrar[i] = valores[0];
	 avalores[i] = valores[0];
	 if (valores.length > 1) avalores[i] = valores[1];
	}

	/* modificado por Marco Agosto 2008 */
	/*
	if (anchoRotulo) 
	xHtml  = '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="rotulo_formulario" data-campo="' + name + '" style="width:'+anchoRotulo+';">'+rotulos + '</td><td><select style="width:'+ancho+'px;" size="1" ' + estilo + xid + xname +'>';
	else xHtml  = rotulos + '<br><select size="1" ' + estilo + xid + xname +'>';
	*/
	if (anchoRotulo) {
		
		xHtml  =  '<table border="0" cellpadding="0" cellspacing="0">';
		xHtml += '<tr>';
		xHtml += '<td class="rotulo_formulario" data-campo="' + name + '" style="width:' + anchoRotulo + ';">' + rotulos + '</td>';
		xHtml += '<td>';
		xHtml += '<select style="width:' + ancho + 'px;" size="1" ' + estilo + xid + xname +'>';
		
	}
	else {
		xHtml  = '<div class="rotulo_formulario" data-campo="' + name + '" style="width:' + ancho + 'px; margin-bottom: 3px;">' + rotulos + '</div>';
		xHtml += '<select size="1" ' + estilo + xid + xname +' style=" width: ' + ancho + 'px;">';
	}

	xHtml += '<option value="" selected>--</option>';
	for(k=0;k<aopciones.length;k++)
	{
		select = "";
		if (valor==avalores[k]) select = "selected";
		xHtml += '<option ' + select + ' value="' + avalores[k] + '">' + amostrar[k] + '</option>';
	}
	xHtml +='</select>';

	if (anchoRotulo) xHtml  += '</td></tr></table>';

	return xHtml;


};

function crearSelectTabla(rotulos, name, valor, tabla, campos, orden,tipo,padre, envia, ancho,xfiltro,xxfiltro)
{
   var xHtml = "";
   if(!name) name   = '';
   if(!valor) valor = '';
   if(!tabla) tabla = '';
   if(!campos) campos = '';
   if(!orden) orden = '';
   if(envia != 0) envia = 1;

   var prefijo = (envia!=0) ? 'c_' : 'x_';

   xid    = 'id = "' + name + '"';
   xfoco  = 'S';
   xname  = 'name ="'+ prefijo + name + '_' + tipo + 'S' + xfoco + '"';

   var anchoRotulo = null;
   if (ancho)
   {
     var anchos = ancho.split(',');
     if (anchos.length > 1)
     {
       ancho  = anchos[1];
       anchoRotulo = anchos[0];
     }
   }

   var estilo = ' class="campo_inactivo" ';

   var mcampos=campos;

   if (orden)
   {
     var isin=false;
     var zcampos=campos.split(',');
     for (var i=0;i<zcampos.length;i++)
     {
       if (orden==zcampos[i]) isin=true;
     }
     if (!isin) campos+=','+orden;
   }

   /* modificado por Marco Agosto 2008 */
   if (anchoRotulo) xHtml  = '<table border="0" cellpadding="0" cellspacing="0"><tr><td class="rotulo_formulario" data-campo="' + name + '" style="width:'+anchoRotulo+';">' + rotulos + '</td><td><select style="width:'+ancho+'px;" data-tabla="'+ tabla +'" data-campos="'+ campos +'" data-orden="'+ orden +'" data-xconfig="'+ this.xconfig +'" data-filtro="'+ xfiltro +'" data-xfiltro="'+ xxfiltro +'" size="1" ' + estilo + xid + xname +' data-tipo="' + tipo + '">';
   else xHtml  = '<div class="rotulo_formulario" style="margin-bottom: 3px;" data-campo="' + name + '">' + rotulos + '</div><select style="width:'+ancho+'px;" data-tabla="'+ tabla +'" data-campos="'+ campos +'" data-orden="'+ orden +'" data-xconfig="'+ this.xconfig +'" data-filtro="'+ xfiltro +'" data-xfiltro="'+ xxfiltro +'" size="1" ' + estilo + xid + xname +' data-tipo="' + tipo + '">';

   /**/

   xHtml += '<option value="">--</option>';


   var url = server_path + 'herramientas/genera_xml/genera_xml.php';
   var params = 'tabla='+tabla+'&campos='+campos+'&orden='+orden;
   if (this.xconfig) params += '&xconfig='+this.xconfig;
   if (xfiltro) params+='&filtro='+xfiltro+'&xfiltro='+xxfiltro;
   var aData = new Array;
   if (!padre)
   {
      var m = enviar(url, params, 'post');
      aData = XML2Array(m);
   }

   var acampos = mcampos.split(',');
   for(var k=0;k<aData.length;k++)
   {
     var select = "";
     if(valor==aData[k][acampos[0]]) select = "selected";

     var xcampo = "";
     for (var l=1; l<acampos.length;l++)
     {
        xcampo += aData[k][acampos[l]] + ' ';
     }

     xHtml += '<option ' + select + ' value="' + aData[k][acampos[0]] + '">' + xcampo + '</option>';
   }

   if (aData.length == 0)
   {
      xHtml += '<option value="" selected>--</option>';
   }

   xHtml +='</select>';
   if (anchoRotulo) xHtml  += '</td></tr></table>';

   return xHtml;

};

formulario2.prototype.setXfiltro = function(campo, valor)
{
  var panel = 0;
  var nombre_form = 'frm_'+this.nombreForm[panel];
  var xinput =  '#' + nombre_form + ' #'+campo;
  $(xinput).data('xfiltro', valor);
  
}

formulario2.prototype.inputReload = function(campo)
{

  var panel = 0;
  var nombre_form = 'frm_'+this.nombreForm[panel];
  var tabla = $('#' + nombre_form + ' #'+campo).data('tabla');
  var campos = $('#' + nombre_form + ' #'+campo).data('campos');
  var orden = $('#' + nombre_form + ' #'+campo).data('orden');
  var xconfig = $('#' + nombre_form + ' #'+campo).data('xconfig');
  var filtro = $('#' + nombre_form + ' #'+campo).data('filtro');
  var xfiltro = $('#' + nombre_form + ' #'+campo).data('xfiltro');
  var mcampos=campos;


   var url = server_path + 'herramientas/genera_xml/genera_xml.php';
   var params = 'tabla='+tabla+'&campos='+campos+'&orden='+orden;
   if (xconfig) params += '&xconfig='+xconfig;
   if (xfiltro) params+='&filtro='+filtro+'&xfiltro='+xfiltro;
   var aData = new Array;

   var m = enviar(url, params, 'post');
   aData = XML2Array(m);

  $('#' + nombre_form + ' #'+campo).empty();

   xHtml = '<option value="" selected>--</option>';
   $('#' + nombre_form + ' #'+campo).append(xHtml);

   var acampos = mcampos.split(',');
   for(var k=0;k<aData.length;k++)
   {
     var select = "";
     if(valor==aData[k][acampos[0]]) select = "selected";

     var xcampo = "";
     for (var l=1; l<acampos.length;l++)
     {
        xcampo += aData[k][acampos[l]] + ' ';
     }

     xHtml = '<option ' + select + ' value="' + aData[k][acampos[0]] + '">' + xcampo + '</option>';
     $('#' + nombre_form + ' #'+campo).append(xHtml);
   }

}


function infoInicialBotonMaestro(origen, campo_guardar, campo_mostrar, valor)
{
   var url=server_path + 'herramientas/utiles/ini2xml.php?origen='+origen;
   var x=enviar(url,'','POST');
   var xarr=parseINI(x);
   var itabla=xarr['TABLA']['TABLA'];
   var icampos=xarr['TABLA']['CAMPOS'];
   var ibusca=campo_guardar;
   var ixbusca=valor;
   var x=genera_xml(itabla, icampos, ibusca, ixbusca, '=');
   var mreg=XML2Array(x);

   var valorMostrar='';
   if (mreg[0]&&mreg[0][campo_mostrar]) valorMostrar=mreg[0][campo_mostrar];
   return valorMostrar;
}

function crearBotonMaestro(rotulos, name, valor, ancho, origen, campo_guardar, campo_mostrar, tipo, envia, filtro, xfiltro, xfuncion, xconfig)
{
   var xHtml = "";

   if(!name) name   = '';
   if(!valor) valor = '';
   mvalor='';

   var params = '"' +origen + '","' + valor + '","' + campo_guardar + '","' + campo_mostrar + '","' + name + '","' + 'r_'+ name +'","'+'b_'+ name +'","' + this.nombreForm[this.panel] + '","' + filtro + '","' + xfiltro + '","' + xfuncion+'","'+this.nombre+'"';
   if ((this.xconfig) && (xconfig)) params += ',"'+this.xconfig+'"';
   if (xconfig) params += ',"'+xconfig+'"';

   var _p=params;
   do
   {
     _p=_p.replace('"','|');
   } while (_p.indexOf('"')>=0);

   var estilo = ' class="campo_inactivo" onkeypress="javascript:_enfocaBot(this.id,\''+_p+'\');"';

   if (valor) var valorm=infoInicialBotonMaestro(origen, campo_guardar, campo_mostrar, valor);

   if(!envia) envia = 1;

   var prefijo = (envia!=0) ? 'c_' : 'x_';

   if (!valorm) valorm='';

   var anchoRotulo = null;
   
    var anchos = ancho.split(',');

    if (anchos.length > 1)
    {
      ancho  = anchos[1];
      anchoRotulo = anchos[0];
    }

    if (anchoRotulo)
    {
		var tmp = '';
		tmp  = '';
		tmp += '<table cellpadding="0" cellspacing="0">';
		tmp += '<tr>';
		tmp += '<td class="rotulo_formulario" data-campo="' + name + '" width="' + anchoRotulo + 'px">';
		tmp += rotulos;
		
		rotulos = tmp;
		
		sep = '</td><td>';
		fin = '</td></tr></table>';
    } else {
		
		var tmp = '';
		tmp  = '';
		tmp += '<div class="rotulo_formulario" data-campo="' + name + '" style="width:' + ancho + 'px; margin-bottom: 3px;">';
		tmp += rotulos;
		tmp += '</div>';
		tmp += '<div>';
		
		rotulos = tmp;
		sep = '';
		fin = '</div>';	
	}

   xHtml = rotulos + sep + '<input ' + estilo + ' id="r_' + name + '" name="r_'+ name + '_' +tipo + 'SN" type="text" value="' + valorm + '" style="width:' + (ancho - 26) + 'px;" readonly data-filtro="'+ filtro +'" data-tipo="' + tipo + '">';
   xHtml += '<input data-xfiltro="'+ xfiltro +'" data-filtro="'+ filtro +'"  id="' + name + '" name="'+ prefijo + name + '_'+ tipo + 'SN" type="hidden" value="' + valor + '" data-tipo="' + tipo + '">';
   xHtml += '<button type="button" class="boton_maestro_abrir" onkeypress="return false;" id="b_' + name + '" name="b_' + name + '_BSS" onClick=javascript:crearMaestro(this,' + params + ') > <i class="fa-solid fa-window-restore"></i> </button>';
   xHtml += '<button type="button" class="boton_maestro_limpiar" onClick="javascript:eliminaCont(this,\''+name+'\',\''+xfuncion+'\' )" > <i class="fa-solid fa-xmark"></i> </button>' + fin;
   
   return xHtml;
};

function _enfocaBot(_id,params)
{
  var evt = window.event || e;
  var KeyCode        = evt.keyCode || evt.which;
  var Elem           = evt.srcElement || evt.target;

  do
   {
     params=params.replace('|','"');
   } while (params.indexOf('|')>=0);

  var id=_id.replace('r_','b_');
  if (KeyCode==32)
  {
    cancelaTecla(evt);
    var func='crearMaestro(document.getElementById(\''+id+'\'),' + params + ')';
    eval(func);
  }
}

function crearMaestro(btn, origen, valor, campo_guardar, campo_mostrar, nombre_guardar, nombre_mostrar, nombre_boton, nombre_form, filtro, xfiltro, xfuncion, objform, xconfig)
{

  var xinput =  '#frm_' + objform + ' #'+ nombre_boton.replace('b_', '');
  xfiltro = $(xinput).data('xfiltro');

if(!valor) valor = '';

hgrid            = new lista(origen);
hgrid.nombre     = 'hgrid';
hgrid.enter      = 1;
hgrid.x          = 1;
hgrid.y          = 1;
hgrid.onClose    = function ()
                   {
                     var objFormu = document.getElementById('frm_'+nombre_form);
                     objFormu[nombre_boton].focus();
                   }

hgrid.form        = eval(objform);
hgrid.url         = server_path + "herramientas/genera_xml/genera_xml.php";
hgrid.funcion     = t_hgrid;
hgrid.buscador    = true;
hgrid.botonCerrar = true;
hgrid.modal       = true;
hgrid.filtro      = filtro;
hgrid.xfiltro     = xfiltro;
hgrid.isBotonMaestro = true;

function t_hgrid(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc:
      cancelaTecla(evt);
      hgrid.ocultar();
      hgrid.destruir();
      delete hgrid;
        break;
    case _enter:
       var registro=XML2Array(xml);
       var objFormu = document.getElementById('frm_'+nombre_form);

       if (!registro||!registro[0]||!registro[0][campo_guardar]||registro[0][campo_guardar]=='NULL')
       {
			//alert('aqui ' + campo_guardar );
         return false;
       }

       if (registro[0][campo_mostrar]) objFormu[nombre_mostrar].value = registro[0][campo_mostrar];
       else objFormu[nombre_mostrar].value = '';

	   $( objFormu[nombre_mostrar] ).change();
	   
       if (registro[0][campo_guardar]) objFormu[nombre_guardar].value = registro[0][campo_guardar];
       else objFormu[nombre_guardar].value = '';

       $( objFormu[nombre_guardar] ).change();
	   
	   hgrid.ocultar();
       objFormu[nombre_boton].focus();
       if (xfuncion)
       {
         var f = eval(xfuncion);
         f(hgrid.form, xml);
       }

       if (hgrid.form.initVal) eval(hgrid.form.initVal);
       hgrid.destruir();
        }
}

if (xconfig) hgrid.xconfig = xconfig;

hgrid.inicializa();


var btnNombre = btn.id;
btnNombre = btnNombre.replace('b_','');
var edit = document.getElementById(btnNombre);

if (edit.value) hgrid.buscar(edit.value,'=');
//else hgrid.buscar('*');

var xresp = document.getElementById('hgrid_ENTER');
if(!xresp)
{
    alert('No esta definida LEYENDA (ENTER) ENTER = 1');
    return false;
}

var xresp = document.getElementById('hgrid_ESC');
if(!xresp)
{
    alert('No esta definida LEYENDA (ESC) ENTER = 1');
    return false;
}

addEvent(hgrid_ENTER, "click", function() { t_hgrid('', _enter, hgrid.elementoXml()) } );
addEvent(hgrid_ESC, "click",   function()  { t_hgrid('', _esc,   hgrid.elementoXml()) } );


hgrid.mostrar();
centrarObj(hgrid.contenedor);
hgrid.setFocus();

};


function enviar_forma(oForm, arrValores, script, xconfig, sendall,dato)
{
    var registro = new Array;
    var aQuery = new Array;
    var objForm = oForm;
    var xform = eval(oForm.name.replace('frm_',''));
    var j = 0;

    var bandera = false;

    if (!arrValores)
    {
      bandera = true;
    }

    for (var i=0;i<objForm.length;i++)
    {
              if(objForm[i].name && (objForm[i].name.substr(0,2)!='x_') && (objForm[i].tagName!= 'FIELDSET'))
       {
          var xCarr = objForm[i].id;

          if (arrValores)
          {
            if(!arrValores[xCarr]) arrValores[xCarr]='';

            var xVarr = arrValores[xCarr];
            var xVfor = objForm[i].value;

            if (objForm[i].type && objForm[i].type=='checkbox')
            {
              xVarr='??';
            }

            if ((objForm[i].type)&&
               (objForm[i].type.toUpperCase()=='TEXT') &&
               (objForm[i].className=='campo_activo' || objForm[i].className=='campo_inactivo'))
            {
              var vl=objForm[i].value;
              if (vl) do
              {
                vl=vl.replace('Ò','—');
              } while (vl.indexOf('Ò')>=0)
              objForm[i].value=vl;
            }

            if (((xVarr != xVfor) || sendall) || (objForm[i].name=='tabla') || (objForm[i].name=='busca') || (objForm[i].name=='xbusca') || (xCarr=='IDX') || (objForm['xbusca'].value==-1) || (objForm[i].type=='hidden'))
            {
                  arrValores[xCarr] = xVfor;

                  if (objForm[i].name=='xbusca')
                  {
                    objForm[i].value = unformat2(objForm[i].value);
                  }

                  if ((xVarr != xVfor) || sendall) bandera = true;
                  if(objForm[i].tagName!= 'FIELDSET')
                  {
                          var vx=escape(trim(objForm[i].value));
                  }


                  do
                  {
                    vx=vx.replace('+','%2B');
                  } while (vx.indexOf('+') >= 0)

                  if (objForm[i].type && objForm[i].type.toUpperCase()=='CHECKBOX')
                  {
                    if (!objForm[i].checked) vx = '';
                  }

                  /**** agregado por marco ago-2008 para validar fechas en blanco ***/
                  var nn=objForm[i].name;
                  nn=nn.substr(nn.length-4,2);
                  nn=nn.toUpperCase();
                  if (objForm[i].value==_nofecha&&nn=='_D') vx='';
                  /******/

                  aQuery[j++] = objForm[i].name + '=' + vx;
				  //console.log(aQuery);

            }
          }

       }
    }

    if(!bandera)
    {
      var objFormulario = eval(oForm.name.replace('frm_',''));
      registro[0] = new Array;

      for (var i=0;i<objForm.length;i++)
      {
        registro[0][objForm[i].id] = objForm[i].value;
      }
      registro[0][objFormulario.indice] = unformat2(registro[0]['xbusca']);
      return registro;
    }

    var url = script;
    var params = aQuery.join('&');
    if (xconfig) params += '&xconfig='+xconfig;

    if( debug() ){
		_prompt('formulario2.js', 'Objeto: ' + oForm.name.replace('frm_','') + ' Funcion: enviar_forma - Accion: Guardar Registro :' , url + '?' + params);
	}
	
    
	//alert('enviando.....');
    
	var x = enviar(url, params, 'POST');
	
	if(dato!='xml')
    {
        return x;
    }

    var registro = XML2Array(x);
	
    //if(debug()) _prompt('RESULTADO DE enviar_forma en Formulario \n  Ejecutado al Guardar :'  , x);
    //if(debug()) _prompt('Funcion enviar_forma en Formulario \n  Query Ejecutado al Guardar :'  , registro[0]['QUERY']);

    //if(registro && registro[0] && registro[0]['ERROR'] && trim(registro[0]['ERROR'])) //<-- marco 2021
	if(!registro)
    {
		let re = /\exception\s+[0-9]*\s+.*\<(.*)\>.*\]\]\>\s*\<\/ERROR\>/gs;
		var resp = x.match( re );
		if( resp ){
			var t    = resp[0];
			t = t.split('<')[1];
			t = t.split('>')[0];
			alert( t );
		}

        // Marco 2021-06-17.... Expresi√≥n regular para el mensaje de alerta.
        
        
        //_prompt('Funcion enviar_forma en Formulario \n  Ruta url para Guardar Registro :'  , url+'?'+params);

        registro = null;
    }

    return registro;

}

function getCursorPos(campo) {
    if (document.selection) {// IE Support
        campo.focus();                                        // Set focus on the element
        var oSel = document.selection.createRange();        // To get cursor position, get empty selection range
        oSel.moveStart('character', -campo.value.length);    // Move selection start to 0 position
        campo.selectionEnd = oSel.text.length;                    // The caret position is selection length
        oSel.setEndPoint('EndToStart', document.selection.createRange() );
        campo.selectionStart = oSel.text.length;
    }
    return { start: campo.selectionStart, end: campo.selectionEnd };
}

function setCaretPosition(ctrl, pos)
{

        if(ctrl.setSelectionRange)
        {
                ctrl.focus();
                ctrl.setSelectionRange(pos,pos);
        }
        else if (ctrl.createTextRange) {
                var range = ctrl.createTextRange();
                range.collapse(true);
                range.moveEnd('character', pos);
                range.moveStart('character', pos);
                range.select();
        }
}


function getCaret(el) {
  if (el.selectionStart) {
    return el.selectionStart;
  } else if (document.selection) {
    el.focus();

    var r = document.selection.createRange();
    if (r == null) {
      return 0;
    }

    var re = el.createTextRange(),
        rc = re.duplicate();
    re.moveToBookmark(r.getBookmark());
    rc.setEndPoint('EndToStart', re);

    return rc.text.length;
  }
  return 0;
}




function handleEnter(field, forma, shift,Ctrl) {

    if(!field.name) return;
    if(!forma) forma = 1;

    if (field.tagName=='TEXTAREA' && Ctrl)
    {
      var p=getCaret(field);
      var t1=field.value.substring(0,p);
      var t2=field.value.substring(p,field.value.length);
      field.value=t1+'\n'+t2;
      setCaretPosition(field,p+1);
      return;
    }

    var i;
    var xnext;
    m = field.form.elements.length -1;
    x = 0;
    for (i = 0; i < field.form.elements.length; i++)
    {
        if (field == field.form.elements[i])
        {
            x = i+1;
            break;
        }
    }


 switch(forma)
 {
    case 1:   // Salta al elemento proximo
    xnext = false;
	
	var last_position;
	
	var bandera = false;
		
	$.each( field.form.elements , function( posicion, campo){
			
		last_position = posicion;
		
		var obj_campo = $(this);
		
		if( !focusable( obj_campo ) ) return ;
		
		if( obj_campo.attr('id') == field.id && !bandera){
			bandera = true;
			return;
		}
		
		if( bandera ){
			setTimeout ( function(){
				obj_campo.focus();
				obj_campo.select();
			}, 100);
			
			bandera = false;
			xnext = false;
			
			return false;
		}
	});

	for( i = last_position ; i < field.form.elements.length; i++ ) {
		
		var obj_campo = $(field.form.elements[i]) ;
		if( focusable( obj_campo ) ) {
			xnext = true;
			return false;
		}
		
	}
	
    if (!xnext){
		
      var obj = eval(field.form.name.replace('frm_',''));
      if (obj.onExit){
        obj.onExit(obj);
      }
      else{
        // obj.submit();
      }

    }
    break;

    case -1: // Salta al elemento anterior
		for (j = x; j < field.form.elements.length; j++)
		{
			var anterior = field.form.elements[j-1];
			if (anterior.name != undefined)
			{
				if(anterior.name.substr(anterior.name.length-1,1)=='S')
				{
				   document.selection.empty();
				   anterior.focus();
				   if (anterior.type!="select-one") anterior.select();
				   break;
				}
			}
		}
	break;

 }

 return false;
}

function focusable( obj_campo ){

	var type = obj_campo.type || obj_campo.prop('tagName').toLowerCase();
	
	if( type == 'fieldset' ) return false;
	
	if( obj_campo.hasClass('boton_maestro_limpiar') ) return false;
	
	if( obj_campo.attr('readonly') ) return false;
	
	if( obj_campo.parent().is(":hidden") ) return false;
	
	if( obj_campo.is(":hidden") ) return false;

	return true;	
	
}

function borraHijos(obj)
{
  if (!obj) return;
  for (i=obj.childNodes.length-1;i>=0;i--)
  {
    obj.removeChild(obj.childNodes[i]);
  }
}


formulario2.prototype.preparar = function (panel)
{
   var xini    = this.ini[panel];
   var xnombre = 'frm_'+this.nombreForm[panel];

   var objForm = this.formulario;

   var oForm = new forma(this.nombreForm[panel], 'oForm');
   oForm.parent = this.nombre;

   this.validator[panel] = oForm;
   var n=0;

   if (xini)
   {
      while(xini['CAMPO' + (n+1)])
      {
        var xcolumna = 'CAMPO' + ((n++)+1);
        var xmask    = xini[xcolumna]['MASCARA'];
        var xcampo   = xini[xcolumna]['CAMPO'];
        var xforma   = xini[xcolumna]['FORMA'];
        var xtipo    = xini[xcolumna]['TIPO'];
        var xancho   = xini[xcolumna]['ANCHO'];

        if (xmask) xmask = xmask.toLowerCase();

        var objcampo = document.getElementById(xcampo);
        if(!objcampo) continue;

        var xnombre = objcampo.name;

        if (xnombre)
        {
          if(xnombre.substr(xnombre.length-1,1)=='N') objForm[xcampo].tabIndex="-1";
          else if (objForm[xcampo].tabIndex) objForm[xcampo].tabIndex = ++fTabIndex;
        }

        if (xforma == 'FECHA') //----------------------- mascara --------------/
        {

          if (!xmask) this.validator[panel].mascara(xcampo,'dd/mm/yyyy',xtipo);
          else this.validator[panel].mascara(xcampo,xmask,xtipo);
        }
        else if (xmask || xtipo=='T') this.validator[panel].mascara(xcampo,xmask,xtipo); 


        m=0;

        while (xini[xcolumna]['REGLA'+(m+1)])
        {
          this.validator[panel].validacion(xcampo,xini[xcolumna]['REGLA'+(m+1)], xini[xcolumna]['ALERTA'+((m++)+1)]  );
        }

        if (xforma == 'SELECCION_TABLA')
        {
          var xpadre = xini[xcolumna]['CAMPO_PADRE'];
          var xtabla = xini[xcolumna]['TABLA_DATA'];
          var xcampo_mostrar = xini[xcolumna]['CAMPO_MOSTRAR'];
          var xcampo_guardar = xini[xcolumna]['CAMPO_GUARDAR'];
          var xbusca = xini[xcolumna]['CAMPO_DEPENDENCIA'];
          var xorden = xini[xcolumna]['CAMPO_ORDEN'];

          if (xpadre)
          {
             var xDep = this.validator[panel].dependencia(objForm[xcampo].id, null, objForm);
             xDep.tabla  = xtabla;
             xDep.campo_guardar = xcampo_guardar;
             xDep.campo_mostrar = xcampo_mostrar;
             xDep.orden = xorden;
             xDep.busca = xbusca;
             xDep.padre = xpadre;
          }
        }


        if (xforma == 'TEXTO_SUGERIDO')
        {
           var xtabla = xini[xcolumna]['TABLA_DATA'];
           var xcampo_mostrar = xini[xcolumna]['CAMPO_MOSTRAR'];
           var xcampo_guardar = xini[xcolumna]['CAMPO_GUARDAR'];
           var xbusca = xini[xcolumna]['CAMPO_DEPENDENCIA'];
           var xdep   = xini[xcolumna]['CAMPO_PADRE'];


           var oSuggest = new autoSuggest(objForm[xcampo]);
           oSuggest.table       = xtabla;
           oSuggest.field       = xcampo_guardar;
           oSuggest.showField   = xcampo_mostrar;
           oSuggest.filterField = xbusca;
           oSuggest.filterObj   = document.getElementById(xdep);
           oSuggest.width       = xancho;
           oSuggest.setTabIndex(fTabIndex);

           oSuggest.init();
        }
      }
   }

   this.validator[panel].inicializa();
}

formulario2.prototype.xfiltro_grid  = function(campo, valor, aData, panel){
	if(!valor) return false;

		
	//arreglo_xfiltro[campo] = valor;
	xfiltro_maestro = valor;
};

formulario2.prototype.setValue = function(campo, valor, aData, panel){

	
  if (!panel) panel = 0;

  var form = document.forms[ 'frm_' + this.nombreForm[panel] ];
  var obj = form[campo];
	
  if (!obj) return;

  if (obj.tagName != 'SELECT')
  {
	  if( $(obj).data('tipo') == 'D' ){
		  var t = valor.split('/');
		  if(t.length>1){
			  valor = t[2] + '-' + t[1] + '-' + t[0];
		  }
	  }
	  
	
	var xdecimal = 0;
	
	
	var tmp = $(obj).data('tipo');
	
	if( tmp === undefined || tmp == '') tmp = 'C';
	
	//console.log(tmp);
	if( !isNaN(tmp) ) tmp = tmp.toString();
	
	var xtipo    = tmp.substring(0,1); 
	var xdecimal = tmp.substring(2,1).trim();
	
	if( xdecimal == '') xdecimal = 0;
	
	//console.log(campo + '->' + xtipo + '=' + valor);

	var xvalor = valor;
	
	if( !xtipo  ) return;
	
	switch (xtipo){
		case 'I': // ENTERO
			if( !xvalor ) xvalor = 0;
			
			var tmp = xvalor.split(".");
			if (tmp.length>0) xvalor = tmp[0];
			break;

		case 'N': // NUMERICO
			if( !xvalor ) xvalor = 0;
			if( !xdecimal || xdecimal == 0 ) xdecimal = 2;
			xvalor = parseFloat(unformat(xvalor)).toFixed(xdecimal);
			break;

		case 'F': // FORMATEADO
			if( !xvalor ) xvalor = 0;
			xvalor = parseFloat(unformat(xvalor)).toFixed(xdecimal);
			xvalor = format( xvalor , xdecimal ) ;
			break;

		default:
			break;
	}
	
	//console.log(campo + '=' + xvalor);
	
	valor = xvalor;
    obj.value = valor;
	
	
  }
  else
  {
    if ((aData) && (aData.length)){
		
		$(obj).empty();
		for (var i = 0; i < aData.length; i++){
			$(obj).append('<option value="' + aData[i].valor + '">' + aData[i].texto + '</option>' );
		}
    }
	
    obj.value = valor;
	filtros(obj, this.xconfig);
  }
};

formulario2.prototype.getValue = function(campo, panel){

	if (!panel) panel = 0;
	var form = this.formulario;
	
	if( !$('#' + form.name + ' #' + campo ).length ){
		console.log('Campo ' + form.name.replace('frm_', '' ) +  '.' + campo + ' No Existe ' + getStackTrace() );
		
		return null;
	}
	
	var obj = form[campo];

	var xvalor = obj.value;

	if( $(obj).data("tipo") == "D" ){
		var t = obj.value.split('-');
		if( t.length > 1 ){
		  xvalor = t[2] + '/' + t[1] + '/' + t[0];
		}
	}

	return xvalor;
  
};


formulario2.prototype.maestro2 = function(filtro, origen)
{
            var xbusca  = this.xbusca[0];

//            alert(xbusca);
            if(!xbusca)
            {
              x = this.submit(0);
              if (x) xbusca = this.xbusca[0];
              else return false;
            }

                    var xtabla  = this.tabla;
            // var xfiltro = (this.arrPanel[nindex]['FILTRO'] ? this.arrPanel[nindex]['FILTRO'] : '');
            src = server_path + 'herramientas/maestro/maestro2.php?origen=' +  origen + '&filtro=' + filtro + '&xfiltro=' + xbusca;

            // var oIframe = document.getElementById('miframe'+ nindex);
            if (filtro == 'IDX') src += ',' + xtabla;

            //if(debug()) _prompt('Funcion Entrar en Formulario \n Url para accesar al Maestro Filtrado por el Codigo' + xbusca, src);

            parent.v_auxiliar2.reset();
            parent.v_auxiliar2.leeUrl(src);
            parent.v_auxiliar2.mostrar();

};

formulario2.prototype.maestro3 = function(filtro, origen){
	
            this.submit(0);
            var xbusca  = this.xbusca[0];
                    var xtabla  = this.tabla;
            // var xfiltro = (this.arrPanel[nindex]['FILTRO'] ? this.arrPanel[nindex]['FILTRO'] : '');
            src = server_path + 'herramientas/maestro/maestro3.php?origen=' +  origen + '&filtro=' + filtro + '&xfiltro=' + xbusca;

            // var oIframe = document.getElementById('miframe'+ nindex);
            if (filtro == 'IDX') src += ',' + xtabla;

            //if(debug()) _prompt('Funcion Entrar en Formulario \n Url para accesar al Maestro Filtrado por el Codigo' + xbusca, src);

            parent.v_auxiliar.reset();
            parent.v_auxiliar.leeUrl(src);
            parent.v_auxiliar.mostrar();

};

formulario2.prototype.activarCampo = function(campo)
{
        var obj = document.getElementById(campo);
          obj.removeAttribute("readonly"  , false);

};

formulario2.prototype.desactivarCampo = function(campo)
{
        var obj = document.getElementById(campo);
    obj.setAttribute("readonly" , "readonly" , false)
};


formulario2.prototype.ocultarCampo = function(campo)
{
  ocultarCampo(this.nombre, campo);
};

formulario2.prototype.mostrarCampo = function(campo)
{
  mostrarCampo(this.nombre, campo);
};

formulario2.prototype.ocultarGrupo = function(Grupo)
{
  ocultarGrupo(this.nombre, Grupo);
};

formulario2.prototype.mostrarGrupo = function(Grupo)
{
  mostrarGrupo(this.nombre, Grupo);
};

function tecla_text()
{
   var evt = window.event || e;
   var KeyCode = evt.keyCode || evt.which;
   //if(KeyCode==13&&!tipo) return false;
}

function mostrar_campo(campo)
{
        var obj = document.getElementById(campo);

    if(obj.style.display=='block')
    {
       obj.style.display = 'none';

    }
    else
    {
       obj.style.display = 'block';
       obj.focus();
    }


}


var asAlertObj = null;

function arrayIndexOf(arrOps, value, arrVals)
{
  var ndx = -1;

  for (var i=0;i<arrOps.length;i++)
  {
    if (arrOps[i].toUpperCase() == value.toUpperCase())
    {
      ndx = i;
      break;
    }
  }

  if (arrVals)
  {
    if (ndx >= 0) return arrVals[ndx];
    else return -1;
  }
  else
  {
    return ndx;
  }

}

function autoSuggest(textbox, table, field, showField, filterField, filterObj)
{
  this.hiddenObj   = textbox;
  this.table       = table;
  this.field       = field;
  this.showField   = showField;
  this.filterField = filterField;
  this.filterObj   = filterObj;
  this.value       = '-1';
  this.layer       = null;
  this.options     = new Array;
  this.values      = new Array;
  this.cur         = -1;

  this.confirmCreation = true;
  this.width           = 100;

  this.textbox      = document.createElement('input');
  this.textbox.type = 'text';
  this.textbox.className = this.hiddenObj.className;

  this.setValue(textbox.value);
  textbox.parentNode.appendChild(this.textbox);

  this.hiddenObj.focusElem = this.textbox;
  this.hiddenObj.onfocus = function(e)
  {
    evt = e || window.event;
    Elem = evt.target || evt.srcElement;
    Elem.focusElem.focus();
  }

  this.hiddenObj.zIndex = -10000;
  this.hiddenObj.style.visible = false;

  textbox.autoSuggest = this;
}

autoSuggest.prototype.setTabIndex = function(tabIndex)
{
  this.textbox.tabIndex = tabIndex;
  this.hiddenObj.tabIndex = -1;
};

autoSuggest.prototype.setValue = function (value)
{
  this.hiddenObj.value = value;
  this.value = value;
};

autoSuggest.prototype.createDropDown = function ()
{
    this.layer = document.createElement("div");
    this.layer.style.position = "absolute";
    this.layer.style.display = "none";
    this.layer.autoSuggest = this;
    this.layer.className = "suggestions";
    this.layer.style.width = this.width;
    this.layer.style.zIndex = 10000;

    this.layer.onmousedown =
    this.layer.onmouseup =
    this.layer.onmouseover = function (e) {

        evt = e || window.event;
        Elem = evt.target || evt.srcElement;

        var as = Elem.autoSuggest;

        if (evt.type == "mousedown") {
            as.textbox.value = Elem.firstChild.nodeValue.toUpperCase();
            as.setValue(arrayIndexOf(as.options, as.textbox.value, as.values));
            as.hideSuggestions();
        } else if (evt.type == "mouseover") {
            as.highlightSuggestion(Elem);
        } else {
            as.textbox.focus();
        }
    };

    document.body.appendChild(this.layer);
    getOptions(null, this.textbox);
};

autoSuggest.prototype.init = function()
{
  var msg = '';
  var salir = true;

  if (!this.textbox) msg = 'autoSuggest: Debe especificar un objeto input (propiedad textbox)';
  if (!this.textbox.tagName || (this.textbox.tagName != 'INPUT') || !this.textbox.type || (this.textbox.type.toUpperCase() != 'TEXT')) msg = 'Debe especificar un objeto INPUT de tipo TEXT (propiedad textbox)';
  if (!this.table) msg = 'autoSuggest: Debe especificar el nombre de la tabla (propiedad table)';
  if (!this.field) msg = 'autoSuggest: Debe especificar el nombre del campo (propiedad field)';
  if (!this.showField) msg = 'autoSuggest: Debe especificar el nombre del campo a mostrar (propiedad showField)';
  if (!this.width)
  {
    msg = 'autoSuggest: Debe especificar el ancho del campo a mostrar (propiedad width)\nSe pondr· 100px por defecto...';
    this.width = 100;
    salir = false;
  }

  if (msg)
  {
    alert(msg);
    if (salir) return false;
  }

  this.textbox.style.width  = this.width;

  this.textbox.autoSuggest = this;
  this.textbox.onfocus     = function(e)
  {
    var evt = e || window.event;
    var Elem = evt.target || evt.srcElement;
    var as = Elem.autoSuggest;
    var ndx;

    getOptions(e);

    ndx = arrayIndexOf(as.options, as.textbox.value);

    if (ndx < 0) as.textbox.value = '';

    as.textbox.select();
    as.textbox.className = 'campo_inactivo';
    try
    {
      as.textbox.focus();
    }
    catch(e) {}
    finally {}

  }

  this.textbox.onkeyup     = handleKeyUp;
  this.textbox.onkeydown   = handleKeyDown;
  this.textbox.onblur      = confirmNew;
  this.createDropDown();

};

autoSuggest.prototype.requestSuggestions = function (typeAhead)
{
  var aSuggestions = [];
  var sTextboxValue = this.textbox.value;

  if (sTextboxValue.length > 0)
  {
    var sTextboxValueLC = sTextboxValue.toUpperCase();

    for (var i=0; i < this.options.length; i++)
    {
      var sStateLC = this.options[i].toUpperCase();

      if (sStateLC.indexOf(sTextboxValueLC) == 0)
      {
        aSuggestions.push(sTextboxValue + this.options[i].substring(sTextboxValue.length));
      }
    }
  }
  this.cur = -1;
  this.autosuggest(aSuggestions, typeAhead);
};

function clearSelection()
{
 if (window.getSelection) {window.getSelection().removeAllRanges();}
 else if (document.selection) {document.selection.empty();}
}

autoSuggest.prototype.typeAhead = function (sSuggestion)
{
    if (this.textbox.createTextRange || this.textbox.setSelectionRange)
    {
        clearSelection();
        var iLen = this.textbox.value.length;
        this.textbox.value = sSuggestion.toUpperCase();
        this.setValue(arrayIndexOf(this.options, this.textbox.value, this.values));
        this.selectRange(iLen, sSuggestion.length);
    }
};

autoSuggest.prototype.selectRange = function (iStart, iLength)
{
    if (this.textbox.createTextRange)
    {
        var oRange = this.textbox.createTextRange();
        oRange.moveStart("character", iStart);
        oRange.moveEnd("character", iLength - this.textbox.value.length);
        oRange.select();
    }
    else if (this.textbox.setSelectionRange)
    {
        this.textbox.setSelectionRange(iStart, iLength);
    }
    this.textbox.focus();
};

autoSuggest.prototype.autosuggest = function (aSuggestions, bTypeAhead)
{
  if (aSuggestions.length > 0)
  {
    if (bTypeAhead)
    {
      this.typeAhead(aSuggestions[0]);
    }
    this.showSuggestions(aSuggestions);
  }
  else
  {
    this.hideSuggestions();
  }
  this.setValue(arrayIndexOf(this.options, this.textbox.value, this.values));
};

autoSuggest.prototype.fillSuggestions = function (aSuggestions)
{
    var oDiv = null;
    this.layer.innerHTML = "";

    for (var i=0; i < aSuggestions.length; i++)
    {
        oDiv = document.createElement("div");
        oDiv.style.width = this.width;
        oDiv.autoSuggest = this;
        oDiv.appendChild(document.createTextNode(aSuggestions[i].toUpperCase()));
        this.layer.appendChild(oDiv);
    }
};

autoSuggest.prototype.showSuggestions = function (aSuggestions)
{
    this.fillSuggestions(aSuggestions);
    this.layer.style.left = this.getLeft() + "px";
    this.layer.style.top = (this.getTop()+this.textbox.offsetHeight) + "px";
    this.layer.style.display = "block";
};

autoSuggest.prototype.hideSuggestions = function ()
{
    this.textbox.value = this.textbox.value.toUpperCase();
    this.cur = -1;
    this.layer.style.display = "none";
};

autoSuggest.prototype.getLeft = function ()
{
    var oNode = this.textbox;

    return $(oNode).offset().left;
    /*var iLeft = 0;

    while(oNode.tagName != "BODY") {
        iLeft += oNode.offsetLeft;
        oNode  = oNode.offsetParent;
    }
    return iLeft;*/
};

autoSuggest.prototype.getTop = function ()
{
    var oNode = this.textbox;

    return $(oNode).offset().top;
    /*var iTop = 0;

    while(oNode.tagName != "BODY") {
        iTop += oNode.offsetTop;
        iTop -= oNode.scrollTop;
        oNode = oNode.offsetParent;
    }
    return iTop;*/
};

autoSuggest.prototype.highlightSuggestion = function (oSuggestionNode)
{
//    window.status = oSuggestionNode.parentNode.tagName;
    for (var i=0; i < this.layer.childNodes.length; i++)
    {
        var oNode = this.layer.childNodes[i];
        if (oNode == oSuggestionNode)
        {
            oNode.className = "current"
        }
        else if (oNode.className == "current")
        {
            oNode.className = "";
        }
    }
};

autoSuggest.prototype.nextSuggestion = function ()
{
    var cSuggestionNodes = this.layer.childNodes;

    if (cSuggestionNodes.length > 0 && this.cur < cSuggestionNodes.length-1)
    {
        var oNode = cSuggestionNodes[++this.cur];
        this.highlightSuggestion(oNode);
        this.textbox.value = oNode.firstChild.nodeValue.toUpperCase();
        this.setValue(arrayIndexOf(this.options, this.textbox.value, this.values));
    }
};

autoSuggest.prototype.previousSuggestion = function ()
{
    var cSuggestionNodes = this.layer.childNodes;

    if (cSuggestionNodes.length > 0 && this.cur > 0)
    {
        var oNode = cSuggestionNodes[--this.cur];
        this.highlightSuggestion(oNode);
        this.textbox.value = oNode.firstChild.nodeValue.toUpperCase();
        this.setValue(arrayIndexOf(this.options, this.textbox.value, this.values));
    }
};

autoSuggest.prototype.setTitle = function (titulo)
{
  this.divTitulo.innerHTML = titulo;
};

function handleKeyUp(e)
{
  var evt  = window.event || e;
  var Elem = evt.srcElement || evt.target;
  var KeyCode = evt.keyCode;

  var as = Elem.autoSuggest;

  if ((KeyCode == 8) || (KeyCode == 46))
  {
    as.requestSuggestions(false);
    as.cur = -1;
  }
  else if (KeyCode < 32 || (KeyCode >= 33 && KeyCode < 46) || (KeyCode >= 112 && KeyCode <= 123))
  {
    //-- ignorar
  }
  else as.requestSuggestions(true);

}

function handleKeyDown(e)
{
    var evt = e || window.event;
    var Elem = evt.target || evt.srcElement;
    var KeyCode = evt.keyCode;

    var as = Elem.autoSuggest;

    switch(KeyCode)
    {
        case 38: //up arrow
            as.previousSuggestion();
            break;
        case 40: //down arrow
            as.nextSuggestion();
            break;
        case 13: //enter
			as.textbox.value = as.textbox.value.toUpperCase();
            var valor = arrayIndexOf(as.options, as.textbox.value, as.values);
            as.setValue(valor);
//            document.selection.empty();
            as.hideSuggestions();
            handleEnter(as.hiddenObj);
            break;
    }
}

function hideSuggestions(e)
{
    var evt = e || window.event;
    var Elem = evt.target || evt.srcElement;
    var as = Elem.autoSuggest;
    document.selection.empty();
    as.hideSuggestions();
}

function getOptions(e, Elem)
{
  if (!Elem)
  {
    var evt  = window.event || e;
    var Elem = evt.srcElement || evt.target;
  }

  var as = Elem.autoSuggest;

  as.options = new Array;
  as.values  = new Array;

  var url = server_path + 'herramientas/genera_xml/genera_xml.php';
  var params = 'tabla='+as.table+'&campos='+as.field+','+as.showField+'&orden='+as.showField;

  if (as.filterField && as.filterObj)
  {
    params += '&busca='+as.filterField+'&xbusca='+as.filterObj.value;
  }

  if (as.filterField && as.filterObj)
  {
     if (as.filterObj.value != -1) params += '&operador==&dale=5';
     else return false;
  }

  if (as.filterObj.value)
  {
    var x = enviar(url, params, 'POST');
    var registros = XML2Array(x);

    if (registros.length)
    {
      for (var i=0;i<registros.length;i++)
      {
        var l = as.options.length;
        as.options[l] = registros[i][as.showField];
        as.values[l]  = registros[i][as.field];
      }
    }
    as.textbox.value = arrayIndexOf(as.values,as.hiddenObj.value,as.options);
    if (as.textbox.value < 0)
    {
      as.textbox.value = '';
      as.setValue(-1);
    }
  }
  else
  {
    as.textbox.value = '';
    as.setValue(-1);
  }

  as.fillSuggestions(as.options);
}

function confirmNew(e)
{

  var evt = e || window.event;
  var Elem = evt.target || evt.srcElement;
  var as = Elem.autoSuggest;

  as.hideSuggestions();

  as.textbox.className = 'campo_inactivo';

  if (asAlertObj && (asAlertObj != as)) return true;

  if (as.hiddenObj.formObj.abort) return true;

  if (!as.textbox.value) return true;

  as.value = arrayIndexOf(as.options, as.textbox.value, as.values);

  if (as.value == as.hiddenObj.oldValue) return true;

  if (as.value == -1)
  {
    asAlertObj = as;

    if (confirm('El valor "'+Elem.value+'" no existe.\n\nøDesea crear un nuevo registro con este valor?') == 1)
    {
      var url = server_path + 'herramientas/utiles/actualizar_registro.php';
      params =  'tabla='+as.table;
      params += '&busca='+as.field;
      params += '&xbusca=-1&c_'+as.showField+'_CSS=' + escape(as.textbox.value);

      if (as.filterField && as.filterObj)
      {
        params += '&c_'+as.filterField+'_CSS='+escape(as.filterObj.value);
      }
      /*
	  if (debug())
      {
        _prompt('URL enviada para crear nuevo valor desde el AutoSuggest', url + '?' + params);
      }
	  */
	  
	  x = enviar(url, params, 'POST');
      registro = XML2Array(x);
      if (registro.length)
      {
        as.setValue(registro[0][as.field]);
      }
      asAlertObj = null;
    }
    else
    {
      Elem.select();
      Elem.focus();
    }
  }
  else asAlertObj = null;
};

function fContClick(e)
{
    var evt = window.event || e;
    var Elem = evt.srcElement || evt.target;

    if (Elem.padre)
    {
      var padre = eval(Elem.padre);
      if (Elem != padre.cerrar) padre.setFocus();
    }
};

function fbayuda(e)
{
    var evt = window.event || e;
    var Elem = evt.srcElement || evt.target;
    var codAyuda = Elem.id.substring(6);
    ayuda(codAyuda);
};

function fbcerrar(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;

  if (Elem.padre)
  {
     var padre = eval(Elem.padre);
     padre.ocultar();
     if (padre.onClose)
     {
        padre.onClose(padre);
     }
  }
};

/*
function toggleFocus()
{
  if (FormTimeOut)
  {
    clearTimeout(FormTimeOut);
    if (currFormObj)
    {
      if (currStyle == 'campo_activo') currStyle = 'campo_inactivo';
      else currStyle = 'campo_activo';
      currFormObj.className = currStyle;
      FormTimeOut = setTimeout('toggleFocus()', 500);
    }
  }
}
*/

function ocultarLinea(frm,linea)
{
	$('#frm_' + frm +' #LINEA' + linea).hide();	
	$('#frm_' + frm +' #LINEA' + linea +' [id^="celda_' + frm +'"]').each(function(){
		$('#' + $(this).attr('id')).hide();
	});
}

function mostrarLinea(frm,linea)
{
	$('#frm_' + frm +' #LINEA' + linea).show();
	$('#frm_' + frm +' #LINEA' + linea +' [id^="celda_' + frm +'"]').each(function(){
		$('#' + $(this).attr('id')).show();
		
	});
	
}

function ocultarCampo(frm,campo)
{
  var f = eval(frm);
  var cellid = 'celda_'+f.nombre+'_'+campo;
  var cell = document.getElementById(cellid);
  if(cell) cell.style.display='none';
}

function mostrarCampo(frm,campo)
{
  var f = eval(frm);
  var cellid = 'celda_'+f.nombre+'_'+campo;
  var cell = document.getElementById(cellid);
  if(cell) cell.style.display='block';
}

function ocultarGrupo(frm,Grupo)
{
  var f = eval(frm);
  var cellid = f.nombre + '_grupo' + Grupo;
  //var brid   = f.nombre + '_br' + Grupo;
  var cell   = document.getElementById(cellid);
  
  //var br     = document.getElementById(brid);
  cell.style.display = 'none';
  //br.style.display   = 'none';
 
  for (var i = 0; i < gTabbableTags.length; i++)
  {
    var children = cell.getElementsByTagName(gTabbableTags[i]);
    for (var j=0; j<children.length; j++)
    {
      if (children[j].name)
      {
        var n=children[j].name;
        if (n.substr(n.length-1,1)=='S') n = n.substr(0,n.length-1) + 'O';
        if (n.substr(0,2) == 'c_') n = 'o_'+n.substr(2);
        children[j].name = n;
      }
    }
  }
}

function mostrarGrupo(frm,Grupo)
{
  var f = eval(frm);
  var cellid = f.nombre+'_grupo'+Grupo;
  //var brid   = f.nombre+'_br'+Grupo;
  var cell   = document.getElementById(cellid);
  //var br     = document.getElementById(brid);
  cell.style.display = 'block';
  //br.style.display   = 'block';

  for (var i = 0; i < gTabbableTags.length; i++)
  {
    var children = cell.getElementsByTagName(gTabbableTags[i]);
    for (var j=0; j<children.length; j++)
    {
      if (children[j].name)
      {
        var n=children[j].name;
        if (n.substr(n.length-1,1)=='O') n = n.substr(0,n.length-1) + 'S';
        if (n.substr(0,2) == 'o_') n = 'c_'+n.substr(2);
        children[j].name = n;
      }
    }
  }
}


function deshabilitarCampo(frm,campo)
{
  ocultarCampo(frm,campo);
  var obj = document.getElementById(campo);
}

function eliminaCont(obj,idobj,xfuncion) {
	var tform = obj.form.id;
	$('#'+tform+' input[id='+idobj+']').val('');
	$('#'+tform+' input[id=r_'+idobj+']').val('');

	if (xfuncion) {
		var f = eval(xfuncion);
		f(idobj, 'boton_maestro_limpiar');
	}  
}

function htmlDecode(input) {
  var doc = new DOMParser().parseFromString(input, "text/html");
  return doc.documentElement.textContent;
}

function mostrar_formulario( xformulario, xcontenedor ) {
    xcontenedor.setTitle( xformulario.titulo );
    xcontenedor.setWidth( xformulario.ancho );
    xcontenedor.setHeight( xformulario.alto );
    xcontenedor.setLegend( xformulario.leyenda );
    centrarObj( xcontenedor.contenedor );
    xcontenedor.mostrar();
    xformulario.mostrar();
    f_activo = xformulario;
    window.setTimeout("f_activo.setFocus()", 100);
}

function Guardar_formulario() {
    f_activo.funcion('', _f12);
}

function Cerrar_formulario() {
    f_activo.funcion('', _esc);
}
