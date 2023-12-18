


function removeItem(array, item)
{
  var rest = array.slice(item + 1 || array.length);
  if (array.length>0) array.length = array.length - 1;
  return array;
};

GridAct = null;
var timerId = null;
var txtAsc = '<div class="ascendente"></div>';
var txtDes = '<div class="descendente"></div>';
var anchoDefecto = 100;
var posPieDefecto = 1;
var mimgw = 16;
var mimgh = 16;

function getWheel(e){

  if (GridAct && GridAct.pieTimer) window.clearTimeout(GridAct.pieTimer);

  var evt = window.event || e;
  if (evt.type == "mousewheel" )
  {
    if (evt && evt.wheelDelta )
    {
      var msge = "";
      if (evt.wheelDelta > 0)
      {
        GridAct.anterior();
        msge = "Wheel rolled up";
      }
      else if (evt.wheelDelta < 0)
      {
        GridAct.siguiente();
        msge = "Wheel rolled down";
      }
      else
      {
        msge = "Not up or Down";
      }
//      window.status = msge;
    }
  }
}

/*-----------------------------------------------------------------------------
keyHandler: Manejador de teclado para el Grid
-----------------------------------------------------------------------------*/

function keyHandler(e)
{
    if (GridAct && GridAct.pieTimer) window.clearTimeout(GridAct.pieTimer);
    var evt = window.event || e;
    var KeyCode        = evt.keyCode || evt.which;
    var Elem           = evt.srcElement || evt.target;
    var isShiftPressed = evt.shiftKey;
    var isCtrlPressed  = evt.ctrlKey;
    var isAltPressed   = evt.altKey;

    var valRet = false;

    switch (KeyCode)
    {
      case 13:
        if (GridAct.campoIndice)
        {
          var reg = XML2Array(GridAct.elementoXml());
          if (reg && reg[0] && reg[0][GridAct.campoIndice])
          {
            setCookie(GridAct.campoIndice, reg[0][GridAct.campoIndice]);
          }
        }
        GridAct.actualizaDestinos();
        cancelaTecla(evt);
        break;
      case 40: // abajo
        GridAct.siguiente();
        cancelaTecla(evt);
        break;
      case 38: // arriba
        GridAct.anterior();
        cancelaTecla(evt);
        break;
      case 39: // izq
        GridAct.nextColumn();
        cancelaTecla(evt);
        break;
      case 37: // der
        GridAct.prevColumn();
        cancelaTecla(evt);
        break;
      case 33://PgUp
        if (!evt.ctrlKey) GridAct.paginaAnterior();
        else GridAct.irPagina(GridAct.pagina-1, true);
        cancelaTecla(evt);
        break;
      case 34://PgDn
        if (!evt.ctrlKey) GridAct.paginaSiguiente();
        else GridAct.irPagina(GridAct.pagina+1);
        cancelaTecla(evt);
        break;
      case 36:
        if (!evt.ctrlKey) GridAct.primero();
        else GridAct.irPagina(1);
        cancelaTecla(evt);
        break;
      case 35:
        if (!evt.ctrlKey) GridAct.irUltimo();
        else GridAct.irPagina(GridAct.paginas, true);
        cancelaTecla(evt);
        break;
      case 17:
        break;
      default:
        if (GridAct.buscador)
        {
          obj = GridAct.doc.getElementById(GridAct.nombre+'_buscador');
          obj.select();
          var valRet = true;
        }
        break;
    }
        // if (GridAct.funcion) GridAct.funcion(GridAct, KeyCode, GridAct.elementoXml());
    if (GridAct.funcion) GridAct.funcion(GridAct.nombre, KeyCode, GridAct.elementoXml(), evt);
    return valRet;
};

/*-----------------------------------------------------------------------------
lista(nombre, x, y, alto, ancho)
        Crea el objeto de grid...
    nombre = nombre del objeto en javascript, usado para accesar otros
             objetos que tengan como prefijo/sufijo este nombre
    x,y = posicion (en px)
    alto,ancho = dimensiones (en px)
-----------------------------------------------------------------------------*/

function lista(origen, nombre, x, y, alto, ancho)
{
  this.xDoc       = null;
  this.indice     = -1;     //--- indice del registro actual (0..n-1)
  this.nombre     = nombre ? nombre : "grid";//--- nombre
  this.url        = server_path + "herramientas/genera_xml/genera_xml.php"; // url por defecto para buscar la info
  this.params     = "";     //--- parametros para la url de this.url
  this.turl       = "";     //--- url para pasar los parametros completos
  this.tabla_xml  = "";     //--- Tabla para la consulta
  this.campos_xml = "*";    //--- campos a consultar (sep por comas)
  this.busca_xml  = "";     //--- campos para realizar la busqueda (sep por comas)
  this.patron_xml = "";     //-- patron de busqueda para los campos (ver validaBusca)
  this.xbusca     = "";     //--- valor que seró buscado en busca_xml
  this.limite     = "";     //--- cantidad maxima de registros a mostrar
  this.rotulos    = "";     //--- rotulos de las columnas
  this.campos     = "";     //--- campos a mostrar consulta (sep por coma)
  this.tipos      = "";     //--- tipos de dato de cada campo (sep por coma. ej: 'N,C,N')
  this.hora       = "24";	//--- establece el formato de hora 12 o 24
  this.medidas    = "";     //--- medidas (en px) para los anchos de las columnas (sep por comas)
  this.leyendas   = new Array;
  this.leyenda    = "";     //--- leyenda para el grid
  this.msg_SinRegistro = '<font color="#FF0000"><b>&nbsp;&nbsp;No se encontraron registros...</b></font>';

  this.funcion    = "";     //--- funcion que recibe todos los eventos de teclado
  this.espera     = true;
  this.noOcultarCombos=false;


//------  propiedades usadas solo internamente -------
  this.contenedor = null;
  this.cuadro     = null;
  this.encab      = null;
  this.arreglo    = null;
  this.tabla      = null;
  this.xtabla     = null;
  this.xbody      = null;
  this.xcampos    = new Array;
  this.rows       = new Array;
  this.x          = (x ? x : 0);
  this.y          = (y ? y : 0);
  this.alto       = (alto ? alto : 200);
  this.ancho      = (ancho ? ancho : 400);
  this.curOrdCol  = -1;
  this.curOrdType = 'A';
  this.buscandoPag= false;
  this.request    = null;
  this.asyncLoad  = false;
  this.imageLoad  = null;

  this.onSelect = null;
  this.xml      = '';
  this.arrayOrd = null;
  this.ultimo   = '';
  this.buscador = null; //--- propiedad para mostrar el buscador (input + combo + boton)
  this.inferior = null; // --- contiene el buscador..
  this.infHeight = 0;

  this.inputBuscador = null;
  this.combo         = null;

  this.mensaje     = "";
  this.titulo      = "";
  this.titHeight   = 0;
  this.botonCerrar = false;
  this.onClose     = null;
  this.onFocus     = null;
  this.onLoad      = null; // onLoad(grid, xml)

  this.arrayPie = new Array;

  this.pie = null;
  this.pies = new Array;
  this.pieHeight = 0;
  this.modal = false;
  this.mascara = null;
  this.ayuda = null;
  this.bayuda = null;
  this.senter = null;
  this.orden = null;

  this.enter = 0;
  this.tieneCond = false;

  if (origen) this.origen = origen;
  else this.origen  = false;

  this.getDestinos = new Array;

  this.usaFrame  = false;
  this.doc       = null; //---- documento donde esta contenido el grid...
  this.iframe    = null;
  this.leyHeight = 0;
  this.cerrar    = null;
  this.padre = null;
  this.elemPadre = null;
  this.myahref = null;
  this.focused = false;
  this.filtro = '';
  this.xfiltro = '';
  this.xoperadores = '';
  this.valor='';
  this.operador =null;

  this.myold_id= null;
  this.mybusca = null;
  this.myxbusca= null;
  this.mytot   = null;
  this.mypag   = null;
  this.mypags  = null;

//------------------------------------ Barra de Status -------------------
  this.status = true;
  this.statusHeight = 0;
  this.cuadroResult = null;
  this.cuadroPosic  = null;
//----- cuadro de botones de posicion ------

  this.btnPrim   = null;
  this.btnPagAnt = null;
  this.btnAnt    = null;

  this.textPosic = '';

  this.btnUlt        = null;
  this.btnPagSig     = null;
  this.btnSig        = null;
  this.colAct        = 0;
  this.griddiv       = null;
  this.divleyenda    = null;
  this.divtitulo     = null;
  this.divTitulo     = null;
  this.panel         = 0;
  this.divPanel      = null;
  this.usaCookie     = true;
  this.campoIndice   = '';
  this.comboBusca    = '';
  this.async         = false;
  this.modoTecla     = 0;
  this.arrBusca      = new Array;
  this.indexBusca    = null;
  this.divExtra      = null;
  this.usaLeyenda    = true;
  this.origenForm    = null;
  this.pieTimer      = null;
  this.timeout       = 500;
  this.ActualizarPie = true;
  this.xconfig       = null;

//-- agregado por Marco: 31/05/2006 -------------------------------------------
  this.pagina     = 0;      //--- pagina a mostrar
  this.paginas    = 0;      //--- pagina a mostrar
  this.totRegs    = 0;
//-----------------------------------------------------------------------------

//-- agregado por Maco: 12/05/2008---------------------------------------------
  this.usaMarcas      = false;
  this.marcas         = new Array;
  this.caracterMarca  = 'X';
  this.imagenMarca    = '';
//-----------------------------------------------------------------------------

  this.usaSombra      = false;
  this.sombra         = null;
  this.caracteres     = 0;


//----- agregado por marco 05/01/2009 --------

  this.usaFiltroEstatus = false;
  this.FiltroEstatus = '';
  this.xFiltroEstatus = '';
  this.oldSelItem = null;
  this.indiceForm = null;

//----- agregado por Marco 11/06/2009 --------
  this.vPaddingTop    = 3;
  this.hPaddingRight  = 3;
  this.vPaddingBottom = 3;
  this.hPaddingLeft   = 3;
  this.rowHeight      = 16;
  this.border         = 2;
  this.tableBorder    = 0;
  this.mouseMark      = false;
  this.visible		  = false;

  this.switchColors   = false;

  if (this.origen)
  {
    this.leeOrigen();
  }

}

/*------------------------------------------------------------------------------
quickSort = funcion especifica para ordenar la lista de registros...
------------------------------------------------------------------------------*/

function quickSort(objArray,idx,aod,tipo,ogrid) { // oldArray = oThis.xbody.childNodes
  procesoQS(objArray,idx,aod,0,objArray.length-1,tipo,ogrid);
}

function procesoQS(objArray,idx, aod,ini,fin,tipo,ogrid)
{
  var i = ini;
  var j = fin;
  var tmp;

  if (i > j) return;

  var c = objArray[Math.floor(( i + j )/2)].childNodes[idx].firstChild.nodeValue;
  if ((!c) && ((tipo=="N") || (tipo=="I"))) c = 0;

  do {
    if (aod == "A") {
      if ((tipo == "N") || (tipo=="I"))
      {
        while ((i < fin) && (parseFloat(c) > parseFloat(objArray[i].childNodes[idx].firstChild.nodeValue))) i++;
        while ((j > ini) && (parseFloat(c) < parseFloat(objArray[j].childNodes[idx].firstChild.nodeValue))) j--;
      }
      else
      {
        while ((i < fin) && (c > objArray[i].childNodes[idx].firstChild.nodeValue)) i++;
        while ((j > ini) && (c < objArray[j].childNodes[idx].firstChild.nodeValue)) j--;
      }
    }
    else
    {
      if ((tipo == "N") || (tipo=="I"))
      {
        while ((i < fin) && (parseFloat(c) < parseFloat(objArray[i].childNodes[idx].firstChild.nodeValue))) i++;
        while ((j > ini) && (parseFloat(c) > parseFloat(objArray[j].childNodes[idx].firstChild.nodeValue))) j--;
      }
      else
      {
        while ((i < fin) && (c < objArray[i].childNodes[idx].firstChild.nodeValue)) i++;
        while ((j > ini) && (c > objArray[j].childNodes[idx].firstChild.nodeValue)) j--;
      }
    }

    if ( i < j ) {
      tmpInd = ogrid.arrayOrd[i];
      ogrid.arrayOrd[i] = ogrid.arrayOrd[j];
      ogrid.arrayOrd[j] = tmpInd;

      for (k=0;k<objArray[i].childNodes.length;k++)
      {
        var tmp = objArray[i].childNodes[k].firstChild.nodeValue;
        objArray[i].childNodes[k].firstChild.nodeValue = objArray[j].childNodes[k].firstChild.nodeValue;
        objArray[j].childNodes[k].firstChild.nodeValue = tmp;
        //-- color
        if (!objArray[i].childNodes[k].styles) objArray[i].childNodes[k].styles = '';
        if (!objArray[j].childNodes[k].styles) objArray[j].childNodes[k].styles = '';
        tmp = objArray[i].childNodes[k].styles.color;
        objArray[i].childNodes[k].styles.color = objArray[j].childNodes[k].styles.color;
        objArray[j].childNodes[k].styles.color = tmp;
        //-- tab
        tmp = objArray[i].childNodes[k].styles.paddingLeft;
        objArray[i].childNodes[k].styles.paddingLeft = objArray[j].childNodes[k].styles.paddingLeft;
        objArray[j].childNodes[k].styles.paddingLeft = tmp;
      }
    }

    if ( i <= j ) {
      i++;
      j--;
    }
  }
  while (i <= j);

  if (ini < j) procesoQS(objArray,idx,aod,ini,j,tipo,ogrid);
  if (i < fin) procesoQS(objArray,idx,aod,i,fin,tipo,ogrid);
}
/*----------------------------------------------------------------------------*/

lista.prototype.cambiaColor = function (estilo)
{
  this.contenedor.className = estilo;
};

lista.prototype.inicializa = function(data)
{
  if (data=='undefined') data=true;
  this.doc = document;
  this.armar(this.doc, data);
//  if (this.xbusca) this.Buscar(this.xbusca);
};

function arPos(arr, elem)
{
  for (var i in arr)
  {
    if (arr[i]==elem)
    {
      return i;
    }
  }
  return false;
}

lista.prototype.filtroEstatus = function(estatus, nobuscar)
{
  if (!this.usaFiltroEstatus) return false;

  var _st=_(this.nombre+'_estatus_'+estatus);
  if (_st) _st.checked=true;

  if (estatus=='*')
  {
    this.FiltroEstatus='';
    this.xFiltroEstatus='';
  }
  else
  {
    this.pagina=0;
    this.FiltroEstatus='ESTATUS';
    this.xFiltroEstatus=estatus;
  }

  if (!nobuscar)
  {
    this.buscar();
    this.setFocus();
  }
};

function agregFiltro(grid, parentElem, opname, opvalue)
{
  var st=grid.doc.createElement('span');
  parentElem.appendChild(st);
  st.style.vAlign='middle';
  st.style.cursor='pointer';
  st.innerHTML='<input type="radio" onclick="'+grid.nombre+'.filtroEstatus(\''+opvalue+'\')" name="'+grid.nombre+'_rec_estatus" id="'+grid.nombre+'_estatus_'+opvalue+'" value="'+opvalue+'"><span onclick="'+grid.nombre+'.filtroEstatus(\''+opvalue+'\')">'+opname + '</span>&nbsp;&nbsp;';
}

/*------------------------------------------------------------------------------
funcion lista.armar()
        Crea los divs y tablas iniciales para la lista...
------------------------------------------------------------------------------*/
lista.prototype.armar = function(doc, data)
{
  var oThis  = this;

  this.doc = doc;
  if (!this.nombre)
  {
    alert('Error. No se ha designado un nombre al Grid');
    exit;
  }

  xGrids[this.nombre] = this;

  if (!this.doc.body)
  {
    window.setTimeout(this.nombre+'.armar('+this.nombre+'.doc,'+data+')',10);
    return false;
  }

  if (this.padre) this.elemPadre = this.doc.getElementById(this.padre);

  if (this.modal)
  {
    this.mascara = this.doc.createElement('DIV');
    this.mascara.style.display = 'none';
    this.mascara.className = 'grid_mascara';
    this.mascara.setAttribute('id',this.nombre + '_popupMask');
    this.mascara.innerHTML = '&nbsp;';
    this.mascara.parent = this.nombre;
    this.mascara.onclick = mascaraClick;

    this.doc.body.appendChild(this.mascara);
  }

  this.contenedor = this.doc.createElement("div");
  this.contenedor.setAttribute("id","grid_container_"+this.nombre);
//  this.contenedor.style.display='none';
  this.contenedor.className      = "grid_contenedor";
  this.contenedor.padre = this.nombre;
  this.contenedor.onclick = contClick;

  if ((this.x) && (this.y))
  {
    this.contenedor.style.left     = this.x;
    this.contenedor.style.top      = this.y;
    this.contenedor.style.position = "absolute";
  }

  var lastpos = this.contenedor.style.position; // ocultar sin style.display

  this.contenedor.style.position = 'absolute';
  this.contenedor.style.left = 100000;

  this.contenedor.style.height   = this.alto;
  this.contenedor.style.width    = this.ancho;

  if (this.usaFrame)
  {
    this.contenedor.style.left     = 0;
    this.contenedor.style.top      = 0;
  }

  if (this.elemPadre) this.elemPadre.appendChild(this.contenedor);
  else this.doc.body.appendChild(this.contenedor);

  var oform = document.createElement('form');
  oform.onsubmit = nullfunc;
  oform.style.marginBottom = '0';

  this.contenedor.appendChild(oform);

  if (this.titulo)
  {
    divTitulo = this.doc.createElement("div");
    divTitulo.padre = this.nombre;

    oform.appendChild(divTitulo);

    esquina1 = this.doc.createElement('div');
    esquina1.className = 'grid_esquina1';

    esquina = this.doc.createElement('div');
    esquina.className = 'grid_esquina';

    esquina2 = this.doc.createElement('div');
    esquina2.className = 'grid_esquina2';

    divTitulo.appendChild(esquina1);
    divTitulo.appendChild(esquina2);

    barraTitulo = this.doc.createElement('div');
    barraTitulo.className = 'grid_barra_titulo';

    divTitulo.appendChild(barraTitulo);

    this.divtitulo = divTitulo;

    tit = this.doc.createElement('DIV');
    tit.className = 'grid_titulo';
    tit.innerHTML = this.titulo;

    barraTitulo.appendChild(esquina);
    barraTitulo.appendChild(tit);

    this.divTitulo = tit;

    controls = this.doc.createElement('DIV');
    controls.className = 'grid_controles';

    if (this.ayuda || this.botonCerrar)
    {
      l_controls = document.createElement('span');
      l_controls.className = 'grid_controls_left';
      r_controls = document.createElement('span');
      r_controls.className = 'grid_controls_right';
    }

    if (r_controls) barraTitulo.appendChild(r_controls);
    barraTitulo.appendChild(controls);
    if (l_controls) barraTitulo.appendChild(l_controls);

    if (this.ayuda)
    {
      senter = this.doc.createElement('span');
      senter.id = 'enter_'+this.enter;

      bayuda = this.doc.createElement('span');
      bayuda.className = 'grid_ayuda';
      bayuda.id = 'ayuda_'+this.ayuda;
      bayuda.padre = this.nombre;
      bayuda.onclick = clickAyuda;
      this.bayuda = bayuda;
      controls.appendChild(senter);
      controls.appendChild(bayuda);
    }

    if (this.botonCerrar)
    {
      bcerrar = this.doc.createElement('span');
      bcerrar.className = 'grid_cerrar';
      bcerrar.padre = this.nombre;
      bcerrar.onclick = clickCerrar;
      this.cerrar = bcerrar;
      controls.appendChild(bcerrar);
    }
    this.titHeight = divTitulo.offsetHeight;
  }

  this.encab = this.doc.createElement('table');

  otBody = this.doc.createElement('tbody');
  this.encab.appendChild(otBody);
  this.encab.onmousewheel   = getWheel;
  otRow  = this.doc.createElement('tr');
  otBody.appendChild(otRow);

  if (typeof(this.rotulos) == 'string') rotulos = this.rotulos.split(",");
  else if (typeof(this.rotulos) == 'object') rotulos = this.rotulos;

  var meds = this.medidas.split(',');
  var mTipos = this.tipos.split(',');
  var sum = 0;

  this.divExtra = this.doc.createElement('div');
  this.divExtra.id        = this.nombre + '_extra';

  this.divExtra.className = 'grid_cuadro_activo';

//-- encabezado -----

//--- agregado por Marco 12/05/2008 para columna de "marcas" ------------------
  if (this.usaMarcas)
  {
    var oCell = this.doc.createElement('td');
    oCell.className = 'grid_encab';
    oCell.innerHTML = '*';
    otRow.appendChild(otCell);
  }
//-----------------------------------------------------------------------------

  for (var i=0;i<rotulos.length;i++)
  {
    var otCell = this.doc.createElement('td');
    otCell.className = 'grid_encab';
    otCell.id = 'r'+this.nombre+'_'+i;

    var rPos = 'left';
	switch (mTipos[i])
	{
	  case 'N':
	  case 'N3':
      case 'N4':
      case 'N5':
      case 'N6':
	  case 'I':
        rPos = 'right';
      	break;
        rPos = 'left';
      default:
	}
    if (!meds[i]) meds[i] = 80;
    otCell.style.width = meds[i] + 'px';
    otCell.innerHTML = '<span style="float:'+ rPos +'">'+rotulos[i]+'</span><span id="'+this.nombre+'_asc_'+i+'" class="ascendente" style="display:none"></span><span id="'+this.nombre+'_des_'+i+'" class="descendente" style="display:none"></span>';
    otCell.style.cursor = "pointer";
    otCell.parent = this.nombre;
    otRow.appendChild(otCell);
    otCell.onclick = clickCelda;
  }


  this.cuadro = this.doc.createElement("div");

  this.cuadro.style.overflow = "hidden";
  this.cuadro.onkeydown      = keyHandler;
  this.cuadro.onmousewheel   = getWheel;

  this.griddiv = this.doc.createElement('div');
  this.griddiv.style.overflow = 'hidden';
  this.griddiv.style.width = this.ancho;
  this.griddiv.className = 'grid_cuadro_activo';
  oform.appendChild(this.griddiv);

  this.griddiv.appendChild(this.encab);
  this.griddiv.appendChild(this.cuadro);

  if (this.status)
  {
    this.barraStatus = this.doc.createElement('TABLE');
    this.barraStatus.parent = this.nombre;
    oform.appendChild(this.barraStatus);

    var hBody = this.doc.createElement('TBODY');
    var hTR   = this.doc.createElement('TR');

    this.barraStatus.appendChild(hBody);
    hBody.appendChild(hTR);

    this.barraStatus.border = 0;
    this.barraStatus.width = '100%';
    this.barraStatus.className = 'grid_status';
    this.barraStatus.setAttribute('id',this.nombre + '_popupstatus');

    this.cuadroResult = this.doc.createElement('TD');
    this.cuadroResult.style.whiteSpace = 'nowrap';
    this.cuadroResult.style.overflow   = 'hidden';
    this.cuadroResult.style.textOverflow='ellipsis';

    this.cuadroResult.innerHTML = '&nbsp;';

    this.cuadroPosic  = this.doc.createElement('TD');
    this.cuadroPosic.align = 'right';
    this.cuadroPosic.nowrap = 'nowrap';

    hTR.appendChild(this.cuadroResult);
    hTR.appendChild(this.cuadroPosic);

    this.btnPrim = this.doc.createElement('SPAN');
    this.btnPrim.className = 'first';
    this.btnPrim.parent = this.nombre;
    this.btnPrim.onmousedown = btnPrimero;

    this.btnPagAnt = this.doc.createElement('SPAN');
    this.btnPagAnt.className = 'back';
    this.btnPagAnt.parent = this.nombre;
    this.btnPagAnt.onmousedown = btnPagAnterior;

    this.btnAnt = this.doc.createElement('SPAN');
    this.btnAnt.className = 'prev';
    this.btnAnt.parent = this.nombre;
    this.btnAnt.onmousedown = btnAnterior;

    this.textPosic = this.doc.createElement('SPAN');

    this.btnSig = this.doc.createElement('SPAN');
    this.btnSig.className = 'next';
    this.btnSig.parent = this.nombre;
    this.btnSig.onmousedown = btnSiguiente;

    this.btnPagSig = this.doc.createElement('SPAN');
    this.btnPagSig.className = 'forward';
    this.btnPagSig.parent = this.nombre;
    this.btnPagSig.onmousedown = btnPagSiguiente;

    this.btnUlt = this.doc.createElement('SPAN');
    this.btnUlt.className = 'last';
    this.btnUlt.parent = this.nombre;
    this.btnUlt.onmousedown = btnUltimo;

    this.cuadroPosic.appendChild(this.btnPrim);
    this.cuadroPosic.appendChild(this.btnPagAnt);
    this.cuadroPosic.appendChild(this.btnAnt);

    this.cuadroPosic.appendChild(this.textPosic);

    this.cuadroPosic.appendChild(this.btnSig);
    this.cuadroPosic.appendChild(this.btnPagSig);
    this.cuadroPosic.appendChild(this.btnUlt);
  }

  oform.appendChild(this.divExtra);

  if (this.buscador)
  {
    this.inferior = this.doc.createElement("div");
    this.inferior.id = this.nombre + '_inferior';
    this.inferior.className = 'grid_buscador';
    this.inferior.style.width = this.ancho;

    oform.appendChild(this.inferior);

    var acampos = this.campos.split(',');
    var arotulos = this.rotulos.split(',');


    var hsubtabla = this.doc.createElement('table');
    hsubtabla.width = '100%';
    var htr = this.doc.createElement('tr');
    var htbody = this.doc.createElement('tbody');
    var hcelda1 = this.doc.createElement('td');
    var hcelda2 = this.doc.createElement('td');
    hcelda2.width='100%';
    var hcelda3 = this.doc.createElement('td');

    this.inferior.appendChild(hsubtabla);

    hsubtabla.appendChild(htbody);
    htbody.appendChild(htr);
    htr.appendChild(hcelda1);
    htr.appendChild(hcelda2);
    htr.appendChild(hcelda3);

    var hbuscador = this.doc.createElement('input');
    hbuscador.type='text';
    hbuscador.style.width = '100%';
    hbuscador.id = this.nombre + '_buscador';
    hbuscador.className = 'campo_activo';

    hcelda2.appendChild(hbuscador);

    var hboton = this.doc.createElement('button');

    hboton.id=this.nombre+'_boton';
    hboton.innerHTML = 'Buscar';
    hboton.className = 'boton_activo';

    hboton.onclick = buscClick;

    hcelda3.appendChild(hboton);
    if (this.usaFiltroEstatus && this.enter<=0)
    {
      var hfiltrost=this.doc.createElement('div');
      this.inferior.appendChild(hfiltrost);

      agregFiltro(this, hfiltrost, 'Activos', 'ACT');
      agregFiltro(this, hfiltrost, 'Inactivos', 'INA');
      agregFiltro(this, hfiltrost, 'Todos', '*');
    }

    hbuscador.onkeydown = buscKeyDown;
    hbuscador.onfocus = buscFocus;

    this.inputBuscador = hbuscador;
  }

  //-- fin de buscador

  if (this.arrayPie.length > 0)
  {
    this.pie = this.doc.createElement('div');
    oform.appendChild(this.pie);
    this.pie.className = 'grid_pie';
    this.pie.style.width = this.ancho;
    this.pie.style.overflow = 'hidden';

    var tam = 0;

    for (var i in this.arrayPie)
    {
      var oTabla = this.doc.createElement('table');
      var oTBody = this.doc.createElement('tbody');
      var oRow = this.doc.createElement('tr');

      this.pie.appendChild(oTabla);
      oTabla.appendChild(oTBody);
      oTBody.appendChild(oRow);

      for (var j in this.arrayPie[i])
      {
        var oCell = this.doc.createElement('td'); // ROTULO

        var anchos = this.arrayPie[i][j].ancho.split(',');
        if (anchos.length == 1) var tag = 'div';
        else var tag = 'span';

        var oDiv1  = this.doc.createElement(tag); // CELDA ROTULO
        var oDiv2  = this.doc.createElement(tag); // CELDA VALOR
        var pref = '';

        if (this.arrayPie[i][j].tipo=='N' || this.arrayPie[i][j].tipo=='I' || this.arrayPie[i][j].tipo=='N3' || this.arrayPie[i][j].tipo=='N4' || this.arrayPie[i][j].tipo=='N5' || this.arrayPie[i][j].tipo=='N6')
        {
          pref = '_num';
        }

        oDiv1.className = 'grid_rotulo_pie';
        oDiv2.className = 'grid_contenido_pie' + pref;

        oDiv1.innerHTML = this.arrayPie[i][j].rotulo;
        oDiv2.innerHTML = '&nbsp;';

        if (anchos.length == 1)
        {
          oDiv1.style.width = anchos[0] + 'px';
          oDiv2.style.width = anchos[0] + 'px';
        }
        else
        {
          oDiv1.style.width = anchos[0] + 'px';
          oDiv2.style.width = anchos[1] + 'px';
        }

        this.pies[j] = oDiv2;
        oDiv2.id = this.nombre + '_PIE_' + j;
        //alert(oDiv2.id);
        /*---- truco para mostrar info larga con "..." (marco: jun 2008)----*/
        oDiv2.style.overflow='hidden';
        oDiv2.style.whiteSpace='nowrap';
        oDiv2.style.textOverflow='ellipsis';
        /*-------------------------------------------------*/

        oRow.appendChild(oCell);
        oCell.appendChild(oDiv1);
        oCell.appendChild(oDiv2);

        if (tag == 'span') tam += 18;
        else tam += 36;
      }
    }
  }

  if (this.leyendas.length)
  {
    var HTMLleyenda  = '<div class="tabla_leyenda">';
        //HTMLleyenda  += '<div">';

    HTMLleyenda += '<table class="tabla_leyenda">';
    HTMLleyenda += '<tr>';

    for (var j=0;j<this.leyendas.length;j++)
    {
      if (this.leyendas[j].enter)
      { if (this.leyendas[j].enter == this.enter)
        {
          if(this.leyendas[j].ancho=='undefined' || !this.leyendas[j].ancho) this.leyendas[j].ancho = '80';
          var xtmp = this.leyendas[j].ancho.split(',');
          this.leyendas[j].ancho = xtmp[0];
          //HTMLleyenda += '<td id="'+ this.nombre +'_LEYENDA'+(j+1)+'" onselectstart="return false;" style="width:'+  this.leyendas[j].ancho +'px"; class="td_leyenda_inactiva" onmouseup="this.className=\'td_leyenda_activa\'" onmouseover="this.className=\'td_leyenda_activa\'" onmousedown="this.className=\'td_leyenda_click\'" onmouseout="this.className=\'td_leyenda_inactiva\'">'+this.leyendas[j].html+'</td>';
          HTMLleyenda += '<td id="'+ this.nombre +'_'+ this.leyendas[j].nombre  +'" onselectstart="return false;" style="width:'+  this.leyendas[j].ancho +'px"; class="td_leyenda_inactiva" onmouseup="this.className=\'td_leyenda_activa\'" onmouseover="this.className=\'td_leyenda_activa\'" onmousedown="this.className=\'td_leyenda_click\'" onmouseout="this.className=\'td_leyenda_inactiva\'">'+this.leyendas[j].html+'</td>';
        }
      }
    }

    HTMLleyenda += '</tr>';
    HTMLleyenda += '</table>';

    //HTMLleyenda += '</span">';
    HTMLleyenda += '</div>';

    this.leyenda = HTMLleyenda;

    if (this.usaLeyenda)
    {
      var oDiv = this.doc.createElement('DIV');
      oDiv.className = 'grid_leyenda';
      oDiv.innerHTML = this.leyenda;
      oform.appendChild(oDiv);
      this.leyHeight = oDiv.offsetHeight;
      this.divleyenda = oDiv;
      //alert(this.divleyenda.innerHTML);
    }
    else
    {
      this.leyHeight = 0;
    }
  }

  this.myahref = this.doc.createElement("A");
  this.myahref.href = "";
  this.myahref.innerHTML = "";

  this.myahref.onkeydown = keyHandler;
  this.myahref.onkeypress = nullfunc;

  this.myahref.padre = this.nombre;

  this.contenedor.appendChild(this.myahref);

  this.statusHeight = this.barraStatus.offsetHeight;

  this.cuadro.innerHTML = "";
  this.cuadro.style.top = (this.y + this.encab.offsetHeight + 2) + "px";
  this.cuadro.style.height = (this.alto - this.encab.offsetHeight - 2) + "px";

  var hb = 0;
  this.infHeight = 0;
  if (this.buscador)
  {
    this.infHeight = this.inferior.offsetHeight;
    hb = this.infHeight;
  }

  var hp = 0;


  if (this.arrayPie.length)
  {
    this.pieHeight = this.pie.offsetHeight;
    hp = this.pieHeight;
  }

  if (this.panel && (parseInt(this.panel) > 0))
  {
    this.divPanel = this.doc.createElement('DIV');
    this.divPanel.id = this.nombre + '_divpanel';
    this.divPanel.className='grid_panel';
    this.divPanel.style.position = 'absolute';
    this.divPanel.style.width = this.panel;
    this.divPanel.style.height = this.griddiv.offsetHeight + 6;
    this.divPanel.style.left = parseInt(this.cuadro.offsetWidth) - this.panel;
    this.divPanel.style.top = 0;
    this.divPanel.style.overflow = 'auto';
    this.griddiv.appendChild(this.divPanel);
    this.cuadro.style.width = this.griddiv.offsetWidth - this.divPanel.offsetWidth - 5;
  }

  if (this.rows.length > 0) this.resalta(this.rows[0],this);

  if (this.buscador && hcelda1)
  {
    var hcriterio = this.doc.createElement('select');
    hcriterio.name = this.nombre + '_busca';
    hcriterio.id = this.nombre + '_busca';
    hcriterio.style.zIndex = -20;
    hcriterio.className = 'campo_activo';

    var opt = this.doc.createElement('option');
    opt.innerHTML = '*';
    opt.value = this.busca_xml;

    hcriterio.appendChild(opt);

    if (this.comboBusca)
    {
      var xcombo = this.comboBusca.split(',');
      for (var i=0;i<xcombo.length;i++)
      {
        var opcion = xcombo[i].split(':');
        var rot = opcion[0];
        var val = opcion[1] ? opcion[1] : opcion[0];

        var opt = this.doc.createElement('option');
        opt.innerHTML = rot;
        opt.value = val;
        if (this.valor && (opt.value==this.valor)) opt.selected=true;
        hcriterio.appendChild(opt);
      }
    }
    else
    {
      for (var i=0;i<acampos.length;i++)
      {
        var opt = this.doc.createElement('option');
        opt.innerHTML = arotulos[i];
        opt.value = acampos[i];
        if (this.valor && (opt.value==this.valor)) opt.selected=true;
        hcriterio.appendChild(opt);
      }
    }
    this.combo = hcriterio;
    hcelda1.appendChild(this.combo);
  }

  var difAlt = this.cuadro.offsetHeight - hb - hp - this.leyHeight - this.titHeight - this.statusHeight - 2;
  difAlt = difAlt > 0 ? difAlt : 0;
  this.cuadro.style.height = difAlt;


  if (this.panel)
  {
    this.divPanel.style.height = this.griddiv.offsetHeight + 6;
  }

  this.contenedor.style.zIndex = 0;

  this.contenedor.style.display='none';
  this.contenedor.style.position=lastpos;
  this.contenedor.style.left = this.x;

  if (data != false)
  {
    if (this.campoIndice && this.usaCookie)
    {
      var indice = getCookie(this.campoIndice);

      if (indice)
      {
        this.buscar(indice, '=');
      }
    }
  }

  if(this.mensaje) this.cuadroResult.innerHTML='<div class="grid_mensaje">' + this.mensaje + '</div>';

  if (this.usaFiltroEstatus)
  {
    this.filtroEstatus('ACT',true);
  }
}
/*--------------------------------- FIN DE ARMAR -----------------------------



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

function getArrIndex(arr,value)
{
  var v = -1;
  for (i in arr)
  {
    if (arr[i] == value) return i;
  }
  return v;
}

/*------------------------------------------------------------------------------
funcion lista.buscar( texto, operador)
        Pone el valor a buscar en xbusca y manda a generar la lista
------------------------------------------------------------------------------*/
lista.prototype.buscar = function(texto, operador, irUltimo, msgEspera)
{
  if (texto && texto != '*' && texto.length < this.caracteres)
  {
    alert('Es necesaria una descripción de más de '+this.caracteres+' letras\npara realizar la búsqueda');
    this.setFocus();
    return false;
  }

  if (this.asyncLoad)
  {
    if (this.buscandoPag)
    {
      if (!this.request) return false;
      this.request.abort();
    }
    this.buscandoPag=true;
  }

  var xml;

  this.indexBusca    = null;

  if (this.curOrdCol >= 0)
  {
    var obj1 = this.doc.getElementById(this.nombre+'_asc_'+this.curOrdCol);
    var obj2 = this.doc.getElementById(this.nombre+'_des_'+this.curOrdCol);
    obj1.style.display = 'none';
    obj2.style.display = 'none';
    this.curOrdCol = -1;
  }

  if (this.buscador && texto)
  {
    this.pagina = 0;
    this.paginas = 0;
    if (!this.asyncLoad) this.cuadroResult.innerHTML = '-> "'+texto.toUpperCase()+'"';
  }
  else if (!this.asyncLoad) this.cuadroResult.innerHTML = '';

  if (this.asyncLoad)
  {
    if (!msgEspera) msgEspera='...';

    this.cuadroResult.innerHTML = msgEspera;
    var myhtml=this.cuadro.innerHTML;
    this.showLoading();
  }
  if (texto)
  {
    this.xbusca = texto.toUpperCase();
    if (getArrIndex(this.arrBusca)<0) this.arrBusca[this.arrBusca.length] = this.xbusca;
  }

  var x = this.contenedor.offsetLeft;
  var y = this.contenedor.offsetTop;

  if (!this.asyncLoad)
  {
    if (this.xtabla) this.xtabla.style.display = 'none';

    if (this.async)
    {
      var msg    = 'Buscando "'+this.xbusca+'"';
      var func='';

      if (typeof(this.buscador) == 'string') func = this.buscador + '(\''+this.xbusca+'\')';
      else func = this.nombre + '.actualizaXml(\''+operador+'\')';

      var tiempo = 10;
      x = parseInt(x) + 10;
      y = parseInt(y) + 60;
      waitExec(msg,func,tiempo, x, y);
    }
    else
    {
      if (typeof(this.buscador)=='string')
      {
        var f = eval(this.buscador);
        return f(this.xbusca);
      }
      else return this.actualizaXml(operador);
    }
  }
  else
  {
    if (typeof(this.buscador)=='string')
    {
      var f = eval(this.buscador);
      return f(this.xbusca);
    }
    else
    {
      this.actualizaXml(operador, irUltimo);
    }
  }
};
function actualizaCont(xml, objName, irUlt)
{
  //*****if (debug()) _prompt('Este es el xml retornado: ',xml);
  var grd=eval(objName);
  if (grd)
  {
    grd.creaLista(xml);

    if (grd.buscador) grd.inputBuscador.value = '';
    if (grd.rows.length > 0)
    {
      grd.primero();
    }
    else
    {
            grd.textPosic.innerHTML = '&nbsp;0 / 0&nbsp;';
            grd.selecciona('<tabla></tabla>');
    }
    grd.actualizaPie();

    if (grd.xbusca && (grd.xbusca != '*')) grd.cuadroResult.innerHTML='-> "'+grd.xbusca+'". ';
    else grd.cuadroResult.innerHTML='';

    if (irUlt) grd.irUltimo();

    if (grd.myold_id)
    {
      grd.totRegs = parseInt(trim(grd.totRegs));
      try
      {
        grd.mytot = parseInt(trim(grd.mytot+''));
        grd.totRegs=parseInt(trim(grd.mytot+''));
      }
      catch(e){}
      finally{}

      grd.pagina=grd.mypag;
      grd.paginas=grd.mypags;
      grd.busca_xml=grd.mybusca;
      grd.xbusca_xml=grd.myxbusca;

      grd.localiza(grd.campoIndice, grd.myold_id);
      grd.actualizaPie();
      grd.resalta(grd.rows[grd.indice],grd);

      grd.mytot=null;
      grd.mypag=null;
      grd.mypags=null;
      grd.mybusca=null;
      grd.myxbusca=null;
      grd.myold_id=null;
    }

    if (grd.onLoad)
    {
      grd.onLoad(grd, xml);
    }
    grd.buscandoPag=false;

    if (grd.imageLoad) delete grd.imageLoad;
  }
};

lista.prototype.actualizaXml = function(operador,irUlt)
{
//--
  //alert('actualizaXml');
  if (this.params)
  {
    var xurl=this.url;
    var par='';
    if (this.orden) par+='&orden='+this.orden;
    //alert('leeUrl');
    var xml = this.leeUrl(xurl, this.params + par);
  }
  else
  {
    //alert('leeXML');
    var xml = this.leeXml(this.xbusca,operador,irUlt);
    if (!this.asyncLoad)
    {
      if (this.xbusca && (this.xbusca != '*')) this.cuadroResult.innerHTML='-> "'+this.xbusca+'". ';
      else this.cuadroResult.innerHTML='';
      // Edson 01-12-2010
      if(this.mensaje) this.cuadroResult.innerHTML='<div class="grid_mensaje">' + this.mensaje + '</div>';

      // if (this.orden) this.cuadroResult.innerHTML+='Ordenado por '+this.orden.toUpperCase();
    }
  }

//--

  if (!this.asyncLoad)
  {
    this.creaLista(xml);

    if (this.buscador) this.inputBuscador.value = '';
    if (this.rows.length > 0)
    {
      this.primero();
    }
    else
    {
      this.textPosic.innerHTML = '&nbsp;0 / 0&nbsp;';
      this.selecciona('<tabla></tabla>');
    }

    if (irUlt)
    {
      this.irUltimo();
    }
    this.actualizaPie();
    if (this.onLoad)
    {
      this.onLoad(this, xml);
    }
  }
  return xml;

}

/*------------------------------------------------------------------------------
funcion lista.leeXml()
 Funcion interna para realizar la lectura del xml (depende de la func "enviar()")
    retorna = el xml del resultado...
------------------------------------------------------------------------------*/
lista.prototype.leeXml = function(texto, operador, irUlt)
{
  if (this.url == "") alert(this.nombre + ': Debe especificar la propiedad URL para realizar las búsquedas');
  if (this.tabla_xml == "") alert(this.nombre + ': Debe especificar la propiedad TABLA_XML para realizar las búsquedas');
  if (this.busca_xml == "") alert(this.nombre + ': Debe especificar la propiedad BUSCA_XML para realizar las búsquedas');

  var patrones = this.patron_xml;
  var busca  = this.busca_xml.split(',');
  var xbusca = this.busca_xml;

  if (xbusca) xbusca=analizaBusqueda(busca, patrones, texto);
  if (!xbusca) xbusca  = this.busca_xml;
  if ((this.buscador) && (this.combo.options.selectedIndex != 0))
  {
    if (!this.oldSelItem) xbusca = this.combo.options[this.combo.options.selectedIndex].value;
  }

  texto=texto.replace('+','%2B');

  if (texto.substr(0,1)==',') texto=texto.substr(1);
  if (texto.substr(0,1)=='=')
  {
    var xtexto = texto.substr(1);
    var xresp = isValidDate(xtexto,true);
    if(xresp) texto = '='+fechaMDY(xtexto);
  }
  var parametros = 'tabla=' + this.tabla_xml + '&campos=' +

  escape(this.campos_xml) + '&busca=' + xbusca + '&xbusca=' + texto;

  if (this.orden) parametros += '&orden='+this.orden;
  if (this.limite) parametros += '&limite='+this.limite;

//-- agregado por Marco: 31/05/2006 -------------------------------------------
  if (this.pagina) parametros += '&pagina='+(parseInt(this.pagina)-1);
  if (this.paginas) parametros += '&paginas='+(parseInt(this.paginas));
//-----------------------------------------------------------------------------

  var flt='';
  var xflt='';

  if (this.filtro && this.xfiltro)
  {
    flt = this.filtro;
    xflt=this.xfiltro;
  }

  if ((this.FiltroEstatus) && (this.xFiltroEstatus))
  {
    if (flt) flt +=';';
    if (xflt) xflt+=';';
    flt  += this.FiltroEstatus;
    xflt += this.xFiltroEstatus;
  }

  if (flt && xflt) parametros += '&filtro='+flt+'&xfiltro='+xflt+'&xoperadores='+this.xoperadores;

  if (this.operador) parametros += '&operador=' + this.operador; /*eduardo revisar marco*/

  if (operador) parametros += '&operador=' +operador;

//-- agregado por Marco: 04/09/2006 -------------------------------------------
  if (this.origen && this.tieneCond && (this.url.indexOf('origen=')<0))
  {
    parametros += '&origen='+this.origen;
  }
//-----------------------------------------------------------------------------

  var xurl=this.url.split('?');
  var murl=this.url;

  if (xurl.length>1)
  {
    murl=xurl[0];
    parametros=xurl[1]+'&'+parametros;
  }
  if (this.xconfig) parametros+='&xconfig='+this.xconfig;

  if (debug()) _prompt(this.nombre+": Autotabla leeXml():", murl + "?" + parametros);

  var xml = '';
  if (!this.asyncLoad)
  {
    xml = enviar(this.url,parametros,"get");
  }
  else
  {
    this.showLoading();
    this.request = enviarAsinc(this.url,parametros,'actualizaCont','',this.nombre, irUlt);
  }

  if ((this.buscador) && (this.oldSelItem))
  {
    this.combo.options.selectedIndex = this.oldSelItem;
    this.oldSelItem = null;
  }
  return xml;

};

lista.prototype.showLoading=function()
{
    var xs = this.cuadro.scrollLeft; // valores de scroll actual
    var ys = this.cuadro.scrollTop;

    var xx=parseInt((this.ancho-mimgw)/2) + xs;
    var yy=parseInt((parseInt(this.cuadro.style.height)-mimgh)/2) + ys;

  //-
    this.imageLoad = this.doc.createElement('IMG');
    this.cuadro.appendChild(this.imageLoad);
    this.imageLoad.src=server_path+'imagenes/utiles/loading.gif';
    this.imageLoad.style.position='absolute';
    this.imageLoad.style.top=yy;
    this.imageLoad.style.left=xx;
   //-
}

/*------------------------------------------------------------------------------
funcion lista.creaLista()
   Realiza la busqueda y genera la lista con los nuevos registros encontrados
------------------------------------------------------------------------------*/
lista.prototype.creaLista = function(xml)
{
  var oTabla = null;
  var oTBody = null;
  var oRow  = null;
  var oCell = null;
  var linea = "";
  var c     = "";
  var oThis = this;

//-- agregado por Marco: 31/05/2006 -------------------------------------------
  this.pagina = 0;
  this.paginas = 0;
//-----------------------------------------------------------------------------

  this.rows = new Array;
  this.indice = -1;

  freeDOM_Obj(this.cuadro);
  this.xbody = null;
  this.xtabla = null;

  var mTipos = this.tipos.split(',');

   this.xml = xml;

   var uxmlDoc = null;

   try
   {
     xmlDoc.loadXML(this.xml);
   }
   catch(e)
   {
     if (document.implementation.createDocument)
     {
       var parser = new DOMParser();
       uxmlDoc = parser.parseFromString(this.xml, "text/xml");
     }
     if((xmlDoc == null) && (uxmlDoc == null))
     {
       alert("XML Doc Load Failed");
       return;
     }
   }

   if (!uxmlDoc) uxmlDoc = xmlDoc;

   this.xDoc = uxmlDoc;

//-- agregado por Marco: 31/05/2006 -------------------------------------------

   var nodoPaginas = uxmlDoc.getElementsByTagName('paginas');

   if (nodoPaginas && nodoPaginas.length && nodoPaginas[0].firstChild)
   {
     this.paginas = nodoPaginas[0].firstChild.nodeValue;
     var nodoPagActual = uxmlDoc.getElementsByTagName('pagina');
     if (nodoPagActual)
     {
       this.pagina = parseInt(nodoPagActual[0].firstChild.nodeValue) + 1;
     }
   }

   var nodoTotRegs = uxmlDoc.getElementsByTagName('totalregistros');

   if (nodoTotRegs && nodoTotRegs.length && nodoTotRegs[0].firstChild)
   {
     this.totRegs = nodoTotRegs[0].firstChild.nodeValue;
   }
//-----------------------------------------------------------------------------

   var meds    = this.medidas.split(',');
   var acampos = this.campos.split(',');

  this.arreglo = uxmlDoc.getElementsByTagName("tabla");

  if (this.arreglo.length > 0) var regs = this.arreglo[0].getElementsByTagName("registro");
  else regs=0;

  if (!regs) var registros = 0; else var registros = regs.length;
  this.arrayOrd = new Array;

  if (registros == 0)
  {
    this.cuadro.innerHTML = this.msg_SinRegistro;
    if (this.onSelect)
    {
        if(GridAct && (GridAct.nombre != this.nombre)) return;
        this.onSelect(this.nombre, '<tabla></tabla>');
    }
  }
  else
  {
    var oTabla = this.doc.createElement('table');
    this.cuadro.appendChild(oTabla);
    oTabla.cellSpacing = this.border;
    oTabla.border = this.tableBorder;
    oTabla.style.borderCollapse = 'collapse';

    oTabla.padre = this.nombre;
    oTabla.onclick = tablaClick;
    oTabla.onmouseup = clearSel;

    var oTBody = this.doc.createElement('tbody');
    oTabla.appendChild(oTBody);
  }

  for (var i=0; i < registros; i++)
  {
      var regClr = regs[i].getAttribute('COLOR');
      var regTab = regs[i].getAttribute('TAB');

      this.arrayOrd[i] = i;
      var oRow = this.doc.createElement("tr");
      oTBody.appendChild(oRow);
      oRow.setAttribute("id", this.nombre+"_"+i);
      oRow.padre = this.nombre;

      if (this.switchColors)
      {
        if (1&i) oRow.style.backgroundColor = '#F4F4F4';
      }

      oRow.ondblclick = oRow.onmousedown = funcData;

      for (var j=0; j < acampos.length; j++)
      {
        var objNodo = uxmlDoc.getElementsByTagName(acampos[j]);
        var oCell = this.doc.createElement("td");
        oRow.appendChild(oCell);

        if (!meds[j]) meds[j] = 80;
        oCell.style.width = meds[j] + 'px';
        oCell.width = meds[j] + 'px';
        oCell.height = this.rowHeight + 'px';
        oCell.padre = this.nombre;
        oCell.className = 'grid_celda';
        oCell.style.padding = this.vPaddingTop + 'px '+this.hPaddingRight+'px '+this.vPaddingBottom + 'px '+this.hPaddingLeft+'px';

        if ((mTipos[j] == 'N') || (mTipos[j] == 'I') || (mTipos[j] == 'N3') || (mTipos[j] == 'N4') || (mTipos[j] == 'N5') || (mTipos[j] == 'N6')) oCell.setAttribute('align','right');
        if ((mTipos[j] == 'T') || (mTipos[j] == 'D') || (mTipos[j] == '%')) oCell.setAttribute('align','center');
        oCell.style.cursor = "pointer";
        oCell.id = this.nombre+'_c_'+j;

        var xvalor = '';
        var fldClr = '';
        var fldTab = '';
        var fldImg = '';

        try
        {
          if((!objNodo) || (!objNodo.length) || (!objNodo[i].firstChild)) xvalor = '';
          else
          {
               var elem = objNodo[i].firstChild;
               switch (elem.nodeType)
               {
                 case 4:
                   xvalor = elem.data;
                   break;
                 case 3:
                   xvalor = elem.nodeValue;
                   break;
                 default:
                   xvalor = '';
                }

               var fldClr = objNodo[i].getAttribute('COLOR');
               var fldTab = objNodo[i].getAttribute('TAB');
               var fldImg = objNodo[i].getAttribute('IMG');
          }
        }
        catch(e)
        {
          alert('Error al establecer el campo: '+acampos[j]);
        }
        finally {}


        if (regClr) oCell.style.color = regClr;
        if (fldClr) oCell.style.color = fldClr;

        var vTab = '';
        if (regTab) vTab = regTab;
        if (fldTab) vTab = fldTab;

        if (vTab)
        {
          oCell.style.paddingLeft = vTab;
          oCell.style.width = (parseInt(oCell.style.width) - parseInt(vTab) + 3) + 'px';
        }

        if (fldImg)
        {
          oCell.innerHTML = '<center><img src="'+fldImg+'" alt="'+xvalor+'"></center>';
        }
        else if (fldImg==='')
        {
          oCell.innerHTML = '&nbsp;';
        }
        else
        {
          switch (mTipos[j])
          {
            case '%':
              xvalor=parseFloat(xvalor);
              if (xvalor>=100)
              {
                xvalor=100;
                xcolor='#00FF00';
              }
              else if(xvalor<50) xcolor='#FF3333';
              else xcolor='#FFBB55';

              xvalor = htmlPorcentaje(meds[j]-4, 8, xvalor, xcolor, '#888888');
              oCell.innerHTML = xvalor;
              break;
            case 'H':
              if(this.hora==12) xvalor = mask_Hora(xvalor);
              oCell.innerHTML = '<center>'+xvalor+'</center>';
              break;

            case 'I': // ENTERO
              var tmp = xvalor.split(".");
              if (tmp.length>0) xvalor = tmp[0];
              oCell.innerHTML = xvalor;
              break;

            case 'N': // 2 DECIMALES
              xvalor = parseFloat(unformat(xvalor)).toFixed(2);
              oCell.innerHTML = xvalor;
              break;

            case 'N3':
              xvalor = parseFloat(unformat(xvalor)).toFixed(3);
              oCell.innerHTML = xvalor;
              break;

            case 'N4':
              xvalor = parseFloat(unformat(xvalor)).toFixed(4);
              oCell.innerHTML = xvalor;
              break;

            case 'N5':
              xvalor = parseFloat(unformat(xvalor)).toFixed(5);
              oCell.innerHTML = xvalor;
              break;

            case 'N6': // 3 DECIMALES
              xvalor = parseFloat(unformat(xvalor)).toFixed(6);
              oCell.innerHTML = xvalor;
              break;

            default:
              oTn = document.createTextNode(xvalor);
              oCell.appendChild(oTn);
              break;
          }
        }
      }

      if (i==0)
      {
        this.indice = 0;
      }

      this.rows[this.rows.length] = oRow;
  }

  if (registros != 0)
  {
    this.xtabla = oTabla;
    this.xbody  = oTBody;
    this.primero();
  }
  else
  {
    this.xtabla = null;
    this.xbody  = null;
  }

  this.actualizaPie();
};


/*------------------------------------------------------------------------------
funcion lista.resalta(oTarget, oThis)
        Resalta el elemento segun el registro seleccionado
    oTarget = elemento seleccionado (puede ser la fila, la celda o el contenido)
    oThis = objeto lista al cual pertenece el elemento seleccionado.
------------------------------------------------------------------------------*/
lista.prototype.resalta = function (oTarget, oThis)
{
//    GridAct = oThis;

    var altAparte = this.alto - this.infHeight - this.titHeight - this.pieHeight - this.encab.offsetHeight - this.leyHeight - this.statusHeight - 2;
    var cell = null;

    if (!oTarget) return false;
    if (oTarget.tagName != "TR") while (oTarget.tagName != "TD") oTarget = oTarget.parentNode;
    if (oTarget.tagName == "TD")
    {
      this.colAct = oTarget.id.replace(this.nombre + '_c_','');
      oTarget = oTarget.parentNode;
    }

    if (oThis.indice >= 0)
    {
      oThis.disableRow();
    }

    //oTarget.className = 'grid_row_activo';
    var acampos = this.campos.split(',');

    for (var l=0; l<acampos.length; l++)
    {
      oTarget.childNodes[l].className = 'grid_row_activo';
    }

    try
    {
      oTarget.childNodes[this.colAct].className = 'grid_celda_resaltada';
    }
    catch(e)
    {
      //alert(oTarget.tagName);
    }
    finally{}

    oThis.indice = oTarget.id.substring(oThis.nombre.length+1,1000);
    var item = parseInt(oThis.indice)+1;

    if (this.limite)
    {
      if (!this.pagina) this.pagina=1;
      var ubic1 = (this.limite * (this.pagina-1)) + parseInt(item);
      var tot1  = this.totRegs ? this.totRegs : oThis.rows.length;
      oThis.textPosic.innerHTML = '&nbsp;'+ ubic1 + ' / ' + tot1+'&nbsp;';
    }

//    oThis.textPosic.innerHTML = '&nbsp;'+item + ' / ' + oThis.rows.length+'&nbsp;';


    var yy=this.cuadro.scrollTop;
    if (oTarget.offsetTop > (this.cuadro.scrollTop + altAparte))
    {
      while (oTarget.offsetTop > (yy + altAparte)) yy+=(oTarget.offsetHeight + 2);
      this.cuadro.scrollTop = yy;
    }
    else if (oTarget.offsetTop < this.cuadro.scrollTop)
    {
      while (oTarget.offsetTop < yy) yy-=(oTarget.offsetHeight + 2);
    }

    if ((oTarget.offsetTop + oTarget.offsetHeight) > (yy + altAparte))
    {
      yy+=(oTarget.offsetHeight + 2);
    }
    this.cuadro.scrollTop=yy;

    cell = oTarget.childNodes[this.colAct];

    try
    {
        if ((cell.offsetLeft + cell.offsetWidth) >  (this.griddiv.scrollLeft + this.griddiv.offsetWidth))
        {
          this.griddiv.scrollLeft += cell.offsetWidth + 2;
        }

        if (cell.offsetLeft < this.griddiv.scrollLeft)
        {
          this.griddiv.scrollLeft -= (cell.offsetWidth + 2);
        }
    }
    catch(e)
    {}
    finally
    {}

    var myxml = this.elementoXml();
    this.actualizaPie();
    if (this.onFocus)
    {
        if(GridAct && (GridAct.nombre != this.nombre)) return;
        this.onFocus(this);
    }

    if (oThis.index >= 0) oThis.focus();
    this.selecciona(myxml);

};

lista.prototype.abrePagina = function(irUltimo)
{
  if (!this.asyncLoad)
  {
    this.cuadroResult.innerHTML = 'Abriendo Página '+(this.pagina)+' de '+this.paginas+'. Espere...';
    var func = this.nombre + '.buscar();';
    if (irUltimo) func += this.nombre + '.irUltimo();'
    setTimeout(func, 5);
  }
  else
  {
    var msg = 'Abriendo Página '+(this.pagina)+' de '+this.paginas+'. Espere...';
    this.buscar(null,null,irUltimo, msg);
  }
};

/*------------------------------------------------------------------------------
Funcion lista.paginaAnterior()
        Retrocede una pagina en la lista...
------------------------------------------------------------------------------*/
lista.prototype.paginaAnterior = function()
{
  if (this.indice < 0) return false;

  if (this.modoTecla == 1)
  {
    if ((this.pagina) && (this.paginas))
    {
      if (this.pagina > 1)
      {
        this.pagina--;
        this.abrePagina(true);
      }
    }
    return false;
  }

//-- agregado por Marco: 31/05/2006 ------------------------------------------
  if (this.indice == 0)
  {
    if ((this.pagina) && (this.paginas))
    {
      if (this.pagina > 1)
      {
        this.pagina--;
        this.abrePagina(true);
      }
    }
    return false;
  }
//------------------------------------------------------------------------------

  this.disableRow();
  var posCuadro = this.cuadro.scrollTop;
  var ind = this.indice;
  var fijo = this.indice;

  var curPos = this.rows[ind].offsetTop;
  var altAparte = this.alto - this.encab.offsetHeight - this.infHeight - this.pieHeight - this.titHeight - this.leyHeight - this.statusHeight - 2;

  if (curPos <= this.cuadro.scrollTop + 2)
  {
    while (ind > 0)
    {
        ind--;
        if (ind>=0)
        {
                if (this.cuadro.scrollTop - this.rows[ind].offsetHeight-2 >= 0)
                {
                  this.cuadro.scrollTop -= this.rows[ind].offsetHeight+2
                }
        }
        else
            {
              ind = 0;
              break;
            }
            alturaTope = this.cuadro.scrollTop + altAparte;
            if (this.rows[fijo].offsetTop + this.rows[fijo].offsetHeight > alturaTope)
            {
          ind++;
              break;
            }
    }
  }
  while ((--ind>0) && ((this.rows[ind].offsetTop - this.cuadro.scrollTop - 2) > 0));

  ind = (ind < 0) ? 0 : ind;
  this.cuadro.scrollTop = this.rows[ind].offsetTop - 2;
  this.resalta(this.rows[ind], this);
  return false;
};


/*------------------------------------------------------------------------------
Funcion lista.paginaSiguiente()
        Avanza una pagina en la lista...
------------------------------------------------------------------------------*/
lista.prototype.paginaSiguiente = function()
{
  if (this.indice < 0) return false;

  if (this.modoTecla == 1)
  {
        if ((this.pagina) && (this.paginas))
        {
          if (this.pagina < this.paginas)
          {
            this.pagina++;
            this.abrePagina();
          }
          else this.irUltimo();
        }
        return false;
  }

//-- modificado por Marco: 31/05/2006 ------------------------------------------
//    if (this.indice >= this.rows.length-1) return false; (modificado)

    if (this.indice >= this.rows.length-1)
    {
      if ((this.pagina) && (this.paginas))
      {
        if (this.pagina < this.paginas)
        {
          this.pagina++;
          this.abrePagina();
        }
        else this.irUltimo();
      }
      return false;
    }

//------------------------------------------------------------------------------

  this.disableRow();

  var posCuadro = this.cuadro.scrollTop;
  var ind = this.indice;
  var fijo = this.indice;
  var curPos = this.rows[ind].offsetTop;
  var altAparte = this.alto - this.encab.offsetHeight - this.infHeight - this.pieHeight - this.titHeight - this.leyHeight - this.statusHeight - 2;

  if (curPos <= this.cuadro.scrollTop + 2)
  {
    while ((ind < this.rows.length-1) && (this.rows[ind].offsetTop + this.rows[ind].offsetHeight + 2 >= this.cuadro.scrollTop + altAparte))
    {
      this.cuadro.scrollTop += this.rows[ind++].offsetHeight+2;
    }
    ind--;
  }
  else
  {
    while ((ind < this.rows.length-1) && (this.rows[fijo].offsetTop + 2 > this.cuadro.scrollTop))
    {
      this.cuadro.scrollTop += this.rows[ind++].offsetHeight+2;
    }
  }

  if ((ind<0) && (this.rows.length>0)) ind = 0;

  while ((++ind<this.rows.length-1) && ((this.rows[ind].offsetTop + this.rows[fijo].offsetHeight + 2) < this.cuadro.scrollTop + altAparte));
  ind--;

  ind = (ind > this.rows.length-1) ? this.rows.length-1 : ind;
  this.resalta(this.rows[ind], this);
  return false;
};

/*------------------------------------------------------------------------------
Funcion lista.mostrar()
  muestra la lista...
------------------------------------------------------------------------------*/
lista.prototype.mostrar = function ()
{
  this.visible		  = false;
  if (!this.contenedor)
  {
    window.setTimeout(this.nombre+'.mostrar();', 10);
    return false;
  }

  this.ocultarCombos();
  if (this.modal) this.mascara.style.display = "block";
  this.contenedor.style.display = "block";
  setTimeout(this.nombre+'.reset()', 50);
};

/*------------------------------------------------------------------------------
Funcion lista.ocultar()
  oculta la lista...
------------------------------------------------------------------------------*/
lista.prototype.ocultar = function ()
{
  this.visible = false;
  if (this.usaSombra && this.sombra)
  {
    borraSombra(this.sombra);
  }

  if (this.asyncLoad && this.buscandoPag)
  {
    this.buscandoPag=false;
    if (this.request) this.request.abort();
  }
  if (this.onClose) this.onClose(this);
  this.contenedor.style.display = "none";
  if (this.modal) this.mascara.style.display = "none";
  this.mostrarCombos();

};

/*------------------------------------------------------------------------------
Funcion lista.anterior()
  Retrocede un registro en la lista
------------------------------------------------------------------------------*/
lista.prototype.anterior = function ()
{
    if (this.indice > 0)
    {
      var n=this.indice;
      this.resalta(this.rows[--n],this);
      if (!this.asyncLoad) if (n==0) this.cuadro.scrollTop = 0;
    }
};

/*------------------------------------------------------------------------------
Funcion lista.siguiente()
  Avanza un registro en la lista
------------------------------------------------------------------------------*/

lista.prototype.siguiente = function ()
{
    if ((this.indice >= 0) && (this.indice < this.rows.length-1))
    {
      var n=this.indice;
      this.resalta(this.rows[++n], this);
    }
};

/*------------------------------------------------------------------------------
Funcion lista.primero()
  Va al primer registro en la lista...
------------------------------------------------------------------------------*/
lista.prototype.primero = function()
{
  if (this.rows.length > 0) this.resalta(this.rows[0],this);
  this.cuadro.scrollTop = 0;
};

/*------------------------------------------------------------------------------
Funcion lista.ultimo()
  Va al ltimo registro en la lista...
------------------------------------------------------------------------------*/
lista.prototype.irUltimo = function()
{
  this.cuadro.scrollTop = this.cuadro.scrollHeight;
  if (this.rows.length > 0) this.resalta(this.rows[this.rows.length-1],this);
};

lista.prototype.nextColumn = function()
{
  if ((this.rows.length > 0) && (this.colAct < this.rows[0].childNodes.length-1))
  {
    this.disableRow();
    this.colAct++;
    this.resalta(this.rows[this.indice],this);
  }
};

lista.prototype.prevColumn = function()
{
  if ((this.rows.length > 0) && (this.colAct > 0))
  {
    this.disableRow();
    this.colAct--;
    this.resalta(this.rows[this.indice],this);
  }
};

lista.prototype.disableRow = function()
{
  if (this.rows.length)
  {
    for (var i=0;i<this.rows[this.indice].childNodes.length;i++)
    {
      this.rows[this.indice].childNodes[i].className = 'grid_celda';
    }
//    this.rows[this.indice].className = 'grid_row_inactivo';
    this.rows[this.indice].className = null;
  }
};

/*------------------------------------------------------------------------------
Funcion lista.focus()
  Pone el foco en la lista...
------------------------------------------------------------------------------*/
lista.prototype.setFocus = function()
{
  if (this.onFocus) this.onFocus(this);

  if (!this.buscador)
  {
    try
    {
      this.myahref.tabIndex = 0;
      window.setTimeout(this.nombre+'.myahref.focus()', 10);
    }
    catch(e){}
  }
  else
  {
    if (!this.inputBuscador)
    {
      window.setTimeout(this.nombre+'.setFocus()', 10);
      return false;
    }
    setTimeout('try{'+this.nombre+".inputBuscador.focus()}catch(e){}",10);
    this.combo.style.display = 'block';
  }
  zIndexGral += 2;
  this.contenedor.style.zIndex = zIndexGral;
  if (this.modal) this.mascara.style.zIndex = zIndexGral-1;

  GridAct = this;
  GridAct.griddiv.className = 'grid_cuadro_activo';
  GridAct.focused = true;

  var n = 0;
  for (i in xGrids) n++;

  if (n > 1)
  {
    revisarGrids(this.nombre);
    this.selecciona(this.elementoXml());
  }


};

/*------------------------------------------------------------------------------
Funcion lista.elementoXml()
  Retorna el xml del registro seleccionado...
------------------------------------------------------------------------------*/
lista.prototype.elementoXml = function()
{
  var xml = '<tabla><registro>';

  if (this.indice >= 0)
  {
    if (this.arreglo.length > 0) var regs = this.arreglo[0].getElementsByTagName("registro");

    for (j=0; j<regs[0].childNodes.length; j++)
    {
      nodo = regs[0].childNodes[j].nodeName;
      objNodo = this.arreglo[0].getElementsByTagName(nodo);

      if (objNodo.length)
      {
        try
        {
          if ((this.arrayOrd.length>0) && (objNodo[this.arrayOrd[this.indice]].firstChild) && (objNodo[this.arrayOrd[this.indice]].firstChild.nodeValue))
          {
            valor = objNodo[this.arrayOrd[this.indice]].firstChild.nodeValue;
          }
          else valor = '';
        }
        catch(e)
        { valor=''; }
        finally
        {}
        xml = xml + '<'+nodo+'><![CDATA[' + valor + ']]></' + nodo + '>';
      }
    }
  }
  xml = xml + '</registro></tabla>';
  //_prompt('elemento ',xml);
  this.ultimo = xml;
  return xml;
};

function cambiaCriterio(valor, grid)
{
  GridAct = grid;
  grid.busca_xml = valor;
  obj = GridAct.doc.getElementById(grid.nombre + '_buscador');
  obj.value = '';
//  obj.focus();
}

function gridBuscar(valor, grid)
{
  GridAct = grid;
  obj = GridAct.doc.getElementById(grid.nombre + '_buscador');
  obj.select();
  grid.buscar(valor);
}

function gridFocus()
{
  if (!GridAct.buscador)
  {
    setTimeout('GridAct.myahref.focus()',10);
  }
  else
  {
    setTimeout('GridAct.inputBuscador.focus()',10);
  }
}

function teclasBuscador(e, grid)
{
    var evt = window.event || e;
    var KeyCode        = evt.keyCode || evt.which;
    var Elem           = evt.srcElement || evt.target;
    var isShiftPressed = evt.shiftKey;
    var isCtrlPressed  = evt.ctrlKey;
    var isAltPressed   = evt.altKey;

    var valRet = false;

    switch (KeyCode)
    {
      case 40:
        if (isCtrlPressed && grid.arrBusca.length)
        {
          if (grid.indexBusca == null)
          {
            grid.indexBusca = 0;
          }
          else if (grid.indexBusca < grid.arrBusca.length-1)
          {
            grid.indexBusca++;
          }
          grid.inputBuscador.value = grid.arrBusca[grid.indexBusca];
          return false;
        }
        grid.siguiente();
        Elem.value = '';
        KeyCode = 0;
        GridAct = grid;
        gridFocus();
        break;
      case 38:
        if (isCtrlPressed && grid.arrBusca.length)
        {
          if (grid.indexBusca == null)
          {
            grid.indexBusca = grid.arrBusca.length-1;
          }
          else if (grid.indexBusca > 0)
          {
            grid.indexBusca--;
          }
          grid.inputBuscador.value = grid.arrBusca[grid.indexBusca];
          return false;
        }
        grid.anterior();
        Elem.value = '';
        KeyCode = 0;
        GridAct = grid;
        gridFocus();
        break;
      case 39:
        if (isCtrlPressed)
        {
          grid.nextColumn();
          if(e)           //Moz
          {
              e.preventDefault();
              e.stopPropagation();
          }
          if(window.event)    //IE
          {
              window.event.returnValue = false;
          }
        }
        KeyCode = 0;
        break;
      case 37:
        if (isCtrlPressed)
        {
          grid.prevColumn();
          if(e)           //Moz
          {
              e.preventDefault();
              e.stopPropagation();
          }
          if(window.event)    //IE
          {
              window.event.returnValue = false;
          }
        }
        KeyCode = 0;
        break;
      case 33:
        cancelaTecla(evt);
        if (!isCtrlPressed) grid.paginaAnterior();
        else grid.irPagina(grid.pagina-1, true);
        Elem.value = '';
        KeyCode = 0;
        break;
      case 34:
        if (!isCtrlPressed) grid.paginaSiguiente();
        else grid.irPagina(grid.pagina+1);
        Elem.value = '';
        KeyCode = 0;
        break;
      case 36:
        if (!isCtrlPressed) grid.primero();
        else grid.irPagina(1);
        Elem.value = '';
        KeyCode = 0;
        break;
      case 35:
        if (!isCtrlPressed) grid.irUltimo();
        else grid.irPagina(grid.paginas, true);
        Elem.value = '';
        KeyCode = 0;
        break;
      case 13:

        if (Elem.value)
        {
          grid.buscar(Elem.value);
          KeyCode=0;
        }
        else
        {
          if (grid.campoIndice)
          {
            var reg = XML2Array(grid.elementoXml());
            if (reg && reg[0] && reg[0][grid.campoIndice])
            {
              setCookie(grid.campoIndice, reg[0][grid.campoIndice]);
            }
          }
        }
        Elem.select();
        break;
      default:
        if (!grid.arreglo || !grid.arreglo.length) grid.cuadro.innerHTML='';
        break;
    }
        // if (grid.funcion) grid.funcion(grid, KeyCode, grid.elementoXml());
    if (grid.funcion) grid.funcion(grid.nombre, KeyCode, grid.elementoXml());
    return false;
};

lista.prototype.ocultaCombo = function()
{
  this.combo.style.display = 'hidden';
};

lista.prototype.actualizaPie = function()
{
  if(this.ActualizarPie)
  {
	  if (this.pie)
	  {
	    if (this.pieTimer)
	    {
	      clearTimeout(this.pieTimer);
	    }
	    if (this.timeout) this.pieTimer = setTimeout(this.nombre + '.actPie()', this.timeout);
	    else this.actPie();
	  }
	}
};

lista.prototype.actPie = function()
{
  try
  {
    var xml = this.elementoXml();
    var registro = XML2Array(xml);

    for (i in this.pies)
    {
        if (registro[0][i]) // Edson : Colocar aqui evaluacion del tipo de pie para los decimales
        {

          //var xtmpo = registro[0][i];
          //var xtmpo = unformat(xtmpo);

          //alert(this.arrayPie.length);

          //var tmp = xvalor.split(".");
          //if (tmp.length>1) xvalor = tmp[0]+ '.' + tmp[1].substr(0,2);


          // if (this.arrayPie[i][j].tipo=='N')
          //alert(this.arrayPie[1][1].tipo);
          //alert(this.arrayPie[1]);
          //alert(this.arrayPie.length);



          this.pies[i].innerHTML = registro[0][i]+"&nbsp;";
          this.pies[i].alt=registro[0][i];
          this.pies[i].title=registro[0][i];
        }
        else
        {
          this.pies[i].innerHTML = "&nbsp;";
          this.pies[i].alt="";
          this.pies[i].title="";
        }
    }
  }
  catch(e)
  {}
  finally
  {}
}

lista.prototype.agregaPie = function(rotulo, campo, posicion, ancho, tipo)
{
  var arr = new Array;
  arr.rotulo = rotulo+'';
  arr.ancho  = ancho+'';
  arr.tipo   = tipo+'';

  if (!this.arrayPie[posicion-1]) this.arrayPie[posicion-1] = new Array;
  this.arrayPie[posicion-1][campo] = arr;
};

lista.prototype.destruir = function ()
{
  borraHijos(this.xbody);
  borraHijos(this.xtabla);
  borraHijos(this.cuadro);
  borraHijos(this.contenedor);
};

/*------------------------------------------------------------------------------
Funcion lista.leeOrigen()
  Rellena el grid usando la informacion en un INI de maestro
------------------------------------------------------------------------------*/
lista.prototype.leeOrigen = function ()
{
  if (this.origen)
  {
    var eurl   = server_path + 'herramientas/utiles/ini2xml.php';
    var eparam = 'origen=' + this.origen;

    var x = enviar(eurl,eparam,'POST');
    //alert(x);
    try
    {
      var ini = parseINI(x);
    }
    catch(e)
    {
      if (debug()) _prompt('LeeOrigen en auto_tabla\nRuta para leer el INI',eurl+'?'+eparam);
      alert('No se pudo cargar el origen "'+this.origen+'"');
      return false;
    }
    finally
    {}

    if (!ini)
    {
      alert('No se encontró el origen ("'+this.origen+'")');
      return null;
    }

    //alert(this.origen);
    if (ini['VENTANA']['ASINCRONO'])
    {
        //this.asyncLoad  = ini['VENTANA']['ASINCRONO']==0 ? false:true;
    }
    if (ini['VENTANA']['MENSAJE']) this.mensaje = ini['VENTANA']['MENSAJE'];
    if (ini['VENTANA']['TITULO']) this.titulo = ini['VENTANA']['TITULO'];
    if (ini['VENTANA']['ANCHO'])  this.ancho  = ini['VENTANA']['ANCHO'];
    else alert('No se especificó VENTANA->ANCHO en el archivo "'+this.origen +'". Defecto: '+this.ancho);
    if (ini['VENTANA']['ALTO'])   this.alto   = ini['VENTANA']['ALTO'];
    else alert('No se especificó VENTANA->ALTO en el archivo "'+this.origen +'". Defecto: '+this.alto);
    if (ini['VENTANA']['AYUDA'])  this.ayuda  = ini['VENTANA']['AYUDA'];

    if (ini['TABLA']['TABLA'])    this.tabla_xml  = ini['TABLA']['TABLA'];
    else alert('No se especificó TABLA->TABLA en el archivo "'+this.origen +'"');
    if (ini['TABLA']['CAMPOS'])   this.campos_xml = ini['TABLA']['CAMPOS'];
    else alert('No se especificó TABLA->CAMPOS en el archivo "'+this.origen +'"');
    if (ini['TABLA']['BUSCA'])    this.busca_xml  = ini['TABLA']['BUSCA'];
    else alert('No se especificó TABLA->BUSCA en el archivo "'+this.origen +'"');

    if (ini['TABLA']['PATRON'])   this.patron_xml  = ini['TABLA']['PATRON'];
    if (ini['TABLA']['FILTRO'])   this.filtro   = ini['TABLA']['FILTRO'];
    if (ini['TABLA']['XFILTRO'])  this.xfiltro  = ini['TABLA']['XFILTRO'];
    if (ini['TABLA']['XOPERADORES'])  this.xoperadores  = ini['TABLA']['XOPERADORES'];
    if (ini['TABLA']['LOCALIZA']) this.xbusca      = ini['TABLA']['LOCALIZA'];
    if (ini['TABLA']['LIMITE'])   this.limite      = ini['TABLA']['LIMITE'];
    if (ini['TABLA']['CARACTERES']) this.caracteres = ini['TABLA']['CARACTERES'];

    if (ini['TABLA']['ORDEN'])    this.orden       = ini['TABLA']['ORDEN'];
    if (ini['TABLA']['INDICE'])   this.campoIndice = ini['TABLA']['INDICE'];
    if (ini['TABLA']['MEMORIZA']) this.usaCookie   = (ini['TABLA']['MEMORIZA'] != '0');
    if (ini['TABLA']['COMBO'])    this.comboBusca  = ini['TABLA']['COMBO'];
    if (ini['TABLA']['VALOR'])    this.valor       = ini['TABLA']['VALOR'];
    if (ini['TABLA']['HORA'])     this.hora        = ini['TABLA']['HORA'];

    if (ini['TABLA']['ESTATUS'])  this.usaFiltroEstatus = (ini['TABLA']['ESTATUS'] != '0');
    if (this.enter) this.usaFiltroEstatus = false;

    if (ini['FORMULARIO'] && ini['FORMULARIO']['ORIGEN']) this.origenForm  = ini['FORMULARIO']['ORIGEN'];
    if (ini['FORMULARIO'] && ini['FORMULARIO']['TABLA'])  this.tablaGuardar = ini['FORMULARIO']['TABLA'];


    this.hora

    if (ini['CONDICION1'])        this.tieneCond = true;

    var n = 0;
    var arot = new Array;
    var acam = new Array;
    var atip = new Array;
    var aanc = new Array;

    while(ini['COLUMNA' + (n+1)])
    {
         var xcolumna = 'COLUMNA' + (n+1);
         arot[n] = ini[xcolumna]['ROTULO'];
         acam[n] = ini[xcolumna]['CAMPO'];
         if (!acam[n]) alert('No se especificó '+xcolumna+'->CAMPO en el archivo "'+this.origen +'"');
         atip[n] = ini[xcolumna]['TIPO'];
         if (!atip[n]) alert('No se especificó '+xcolumna+'->TIPO en el archivo "'+this.origen +'"');
         aanc[n] = ini[xcolumna]['ANCHO'];
         if (!aanc[n])
         {
           alert('No se especificó '+xcolumna+'->ANCHO en el archivo "'+this.origen +'". Defecto: '+anchoDefecto);
           ini[xcolumna]['ANCHO'] = anchoDefecto;
         }
         n++;
    }

    this.rotulos    = arot.join(",");
    this.campos     = acam.join(",");
    this.tipos      = atip.join(",");
    this.medidas    = aanc.join(",");

    n = 0;
    arot = new Array;
    acam = new Array;
    atip = new Array;
    aanc = new Array;
    apos = new Array;

    this.arrayPie = new Array;

    while(ini['PIE' + (n+1)])
    {
         var xpie = 'PIE' + (n+1);
         if (!ini[xpie]['CAMPO']) alert('No se especificó '+xpie+'->CAMPO en el archivo "'+this.origen +'"');

         if (!ini[xpie]['POSICION'])
         {
           alert('No se especificó '+xpie+'->POSICION en el archivo "'+this.origen +'". Defecto:'+posPieDefecto);
           ini[xpie]['POSICION'] = posPieDefecto;
         }

         if (!ini[xpie]['ANCHO'])
         {
           alert('No se especificó '+xpie+'->ANCHO en el archivo "'+this.origen +'". Defecto:'+anchoDefecto);
           ini[xpie]['ANCHO'] = anchoDefecto;
         }

         this.agregaPie(ini[xpie]['ROTULO'], ini[xpie]['CAMPO'], ini[xpie]['POSICION'], ini[xpie]['ANCHO'], ini[xpie]['TIPO']);
         n++;
    }

    n = 0;

    this.leyendas = new Array;

    while(ini['LEYENDA' + (n+1)])
    {
         var xleyenda = 'LEYENDA' + (n+1);
         var htmlLeyenda = '<span class="td_leyenda"><b>[' + ini[xleyenda]['TECLA'] + ']</b><br>' + ini[xleyenda]['ROTULO'] + '</span>';

         this.leyendas[n] = new Array;
         this.leyendas[n].html   = htmlLeyenda;
         this.leyendas[n].enter  = ini[xleyenda]['ENTER'];
         this.leyendas[n].nombre = ini[xleyenda]['NOMBRE'];
         this.leyendas[n].ancho  = ini[xleyenda]['ANCHO'];
         n++;
    }

  }


};


/*------------------------------------------------------------------------------
Funcion lista.leeUrl(url,params)
  Llena el grid con la data en xml retornada por url + params
------------------------------------------------------------------------------*/
lista.prototype.leeUrl = function(url, params)
{
  this.url = url;
  this.params = params;
  if (this.xconfig) this.params+='&xconfig='+this.xconfig;

  if (this.paginas) params += '&pagina=' + (this.pagina-1);

  if (debug()) _prompt('URL enviada por Auto_tabla funcion "LeeUrl":', url + '?' + params);

  if (!this.asyncLoad)
  {
    var xml = enviar(url,params,'GET');
    //*****if (debug()) _prompt('Este es el xml retornado: ',xml);

    if (this.async)
    {
      var x = this.contenedor.offsetLeft;
      var y = this.contenedor.offsetTop;

      this.xml = xml;
      var msg    = 'Buscando "'+this.xbusca+'"';
      var func=this.nombre+'.creaLista('+this.nombre+'.xml)';
      var tiempo = 10;
      x = parseInt(x) + 10;
      y = parseInt(y) + 60;
      waitExec(msg,func,tiempo, x, y);
    }
    else
    {
      this.creaLista(xml);
	  if (this.onLoad)
	  {
	  	this.onLoad(this, xml);
	  }
    }
    return xml;
  }
  else
  {
    var msg = '';
    if (this.paginas) msg = 'Abriendo Página '+(this.pagina)+' de '+this.paginas+'. Espere...';

    var xs = this.cuadro.scrollLeft; // valores de scroll actual
    var ys = this.cuadro.scrollTop;

    var myhtml=this.cuadro.innerHTML;
    var xx=parseInt((this.ancho-mimgw)/2) + xs;
    var yy=parseInt((parseInt(this.cuadro.style.height)-mimgh)/2) + ys;

    this.cuadro.innerHTML=myhtml + '<img src="'+server_path+'imagenes/utiles/loading.gif" style="position:absolute; top:'+yy+'; left:'+xx+';">';
    this.request = enviarAsinc(url,params,'actualizaCont','',this.nombre);

    return true;
  }
};

lista.prototype.setZIndex = function(indice)
{
  if (!indice) this.contenedor.zIndex = ++zIndexGral;
  else this.contenedor.zIndex = indice;
};

lista.prototype.actualizaDestinos = function ()
{
  if (this.getDestinos.length > 0)
  {
    var registro = XML2Array(this.elementoXml());
    for (i=0;i<this.getDestinos.length;i++);
    {
      this.getDestinos[i].valor = registro[0][this.getDestinos[i].campo];
    }
  }
};

/*------------------------------------------------------------------------------
------------------------------------------------------------------------------*/

lista.prototype.agregaDestino = function (objeto, campo)
{
  this.getDestinos[this.getDestinos.length].objeto = objeto;
  this.getDestinos[this.getDestinos.length].campo = campo;
  this.getDestinos[this.getDestinos.length].valor = "";
};



lista.prototype.ocultarCombos2 = function()
{
    this.arrCombos = new Array;

    var all = (document.all || document.getElementsByTagName('*'));

    if (this.frame) var xframeactual = this.frame.name;
    else xframeactual = '';

    for(var i = 0; i < all.length; i++)
    {
      if((all[i].tagName.toUpperCase() == "SELECT") && (all[i].style.display!="none"))
      {
         if(all[i].form!=null)
         {
            if(all[i].form.parentNode.parentNode.id != 'f_' + this.nombre)
                 {
                   this.arrCombos[this.arrCombos.length] = all[i];
                   all[i].style.display="none";
            }
         }
         else
         {
                   this.arrCombos[this.arrCombos.length] = all[i];
                   all[i].style.display="none";
         }

      }
      else if ((all[i].tagName.toUpperCase() == 'IFRAME') && (xframeactual != all[i].name))
      {
        var obj = all[i].contentWindow.document;
        var myall = (obj.all || obj.getElementsByTagName('*'));

        if (xframeactual != all[i].name) //alert(document.all[i].name);
        {
          for (var j=0;j<myall.length;j++)
          {
            if ((myall[j].tagName.toUpperCase() == 'SELECT') &&
              (myall[j].style.display != "none"))
            {
              this.arrCombos[this.arrCombos.length] = myall[j];
              myall[j].style.display = "none";
            }
          }
        }
      }
    }
};

lista.prototype.ocultarCombos = function()
{
    if (this.noOcultarCombos) return;
    this.arrCombos = new Array;

    var sels = document.getElementsByTagName('SELECT');
    for (var i=0;i<sels.length;i++)
    {
      if (((!sels[i].form || sels[i].form.parentNode.id != 'grid_container_'+this.nombre)) && (sels[i].style.display!="none"))
      {
        this.arrCombos[this.arrCombos.length] = sels[i];
        sels[i].style.display="none";
      }
    }

   sels = document.getElementsByTagName('OBJECT');

   if (sels && sels.length)
   {
     for (var i=0;i<sels.length;i++)
     {
       if (sels[i].Form)
       {
          if ((sels[i].Form != 'frm_'+this.nombre) && (sels[i].style.display!="none"))
          {
            this.arrCombos[this.arrCombos.length] = sels[i];
            sels[i].style.display="none";
          }
       }
     }
   }

};

lista.prototype.mostrarCombos = function()
{
  if (this.arrCombos && this.arrCombos.length)
  {
    for (var i=0;i<this.arrCombos.length;i++)
    {
      this.arrCombos[i].style.display = 'block';
    }
  }
};

lista.prototype.limpiar = function()
{
  borraHijos(this.xbody);
  borraHijos(this.xtabla);
  borraHijos(this.cuadro);
  limpiarElementos(this.nombre+'_PIE');
  this.xml = '';
  this.pagina     = 0;      //--- pagina a mostrar
  this.paginas    = 0;      //--- pagina a mostrar
  this.totRegs    = 0;

  this.indice=-1;
  this.textPosic.innerHTML = '&nbsp;0 / 0&nbsp;';
  this.cuadro.scrollTop=0;

};

function revisarGrids(xnombre)
{
  for (i in xGrids)
  {
    if (xGrids[i].nombre == xnombre)
    {
      xGrids[i].focused = true;
      xGrids[i].griddiv.className = 'grid_cuadro_activo';

    }
    else
    {
      xGrids[i].focused = false;
      xGrids[i].griddiv.className = 'grid_cuadro_inactivo';

    }
  }
}

lista.prototype.cambiarOrigen = function(origen)
{
  this.origen = origen;
  this.arrayPie = new Array;
  borraHijos(this.contenedor);
  this.leeOrigen();
  this.contenedor.parentNode.removeChild(this.contenedor);
  this.inicializa();
};

lista.prototype.leeValor = function(fila, columna)
{
  if ((fila<0) || (fila>this.rows.length-1)) return false;
  if (!this.rows[fila]) return false;
  if (!this.rows[fila].childNodes.length || (columna > this.rows[fila].childNodes.length-1)) return false;
  return this.rows[fila].childNodes[columna].firstChild.nodeValue;
};

lista.prototype.escribeValor = function(fila, columna, valor)
{
  if ((fila<0) || (fila>this.rows.length-1)) return false;
  if (!this.rows[fila]) return false;
  if (!this.rows[fila].childNodes.length || (columna > this.rows[fila].childNodes.length-1)) return false;
  this.rows[fila].childNodes[columna].firstChild.nodeValue = valor;
};

lista.prototype.elementoArray = function ()
{
  var xml = this.elementoXml();
  return XML2Array(xml);
};

lista.prototype.addTimeoutFunction = function(func, time)
{
  window.setTimeout(func, time);
};

lista.prototype.actualizaOrden = function(campo)
{
      //prompt('', this.xml);
      var acampos = this.campos.split(',');
      var xt = campo.substring(campo.length-2);
      if(xt=='_H') campo = campo.substring(0, campo.length-2);
      this.orden=campo;
      if (this.curOrdType=='D') this.orden+=' DESC';
      this.pagina=0;
      this.actualizaXml(this.xoperador);
//---
      this.primero();
      this.setFocus();
};

lista.prototype.resaltar = function(busca, xbusca)
{
    if (this.arreglo.length > 0) var regs = this.arreglo[0].getElementsByTagName("registro");
    var indice=0;

    for (j=0; j<regs[0].childNodes.length; j++)
    {
      nodo = regs[0].childNodes[j].nodeName;
      objNodo = this.arreglo[0].getElementsByTagName(nodo);

      if (objNodo.length)
      {
        if ((objNodo[this.arrayOrd[this.indice]].firstChild) && (objNodo[this.arrayOrd[this.indice]].firstChild.nodeValue))
        {
          valor = objNodo[this.arrayOrd[this.indice]].firstChild.nodeValue;
        }
        else valor = '';
        if ((nodo = busca) && (valor = xbusca)) indice = j;
      }
    }
    this.resalta(this.rows[indice],this);
}

function actualizarStatus(nombre, texto)
{
  obj = document.getElementById(nombre);
  obj.innerHTML = texto;
}

function contClick(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;

  if (Elem.padre)
  {
    var padre = eval(Elem.padre);
    if ((Elem != padre.cerrar) && (Elem != padre.bayuda))
    {
      padre.setFocus();
    }
  }

}

function clickAyuda(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;
  var codAyuda = Elem.id.substring(6);
  var Enter = Elem.id.substring(6);
  ayuda(codAyuda, GridAct.enter, GridAct.titulo, GridAct.origen);
}

function nullfunc(e)
{
  var evt = window.event || e;
  cancelaTecla(evt);
  return false;
}

function clickCerrar(e)
{
    var evt = window.event || e;
    var Elem = evt.srcElement || evt.target;

    if(e)           //Moz
    {
        e.preventDefault();
        e.stopPropagation();
    }
    if(window.event)    //IE
    {
        window.event.returnValue = false;
    }

    if (Elem.padre)
    {
       var padre = eval(Elem.padre);
       padre.ocultar();
       if (padre.funcion) padre.funcion(Elem.padre, 27, padre.elementoXml());
    }
}

function clickCelda(e)
{
      var evt = window.event || e;
      var Elem = evt.srcElement || evt.target;

      while ((Elem.tagName != 'TD') || (!Elem.id)) Elem = Elem.parentNode;


      var ot = Elem.id.substring(1,Elem.id.lastIndexOf('_'));

      var mobj = eval(ot);
      var idx = Elem.id.substring(mobj.nombre.length+2,1000);

      if (mobj.onFocus) mobj.onFocus(mobj);

      mobj.disableRow();
      mobj.colAct = idx;
      mobj.resalta(mobj.rows[mobj.indice], mobj);

      var xrotulos = mobj.rotulos.split(",");
      var xtipos = mobj.tipos.split(",");

      if ((!mobj.xbody) || (!mobj.xbody.childNodes)) return;

      if (mobj.curOrdCol != idx)
      {
        mobj.curOrdType = 'A';
        if (mobj.curOrdCol >= 0)
        {
          var obj1 = mobj.doc.getElementById(mobj.nombre+'_asc_'+mobj.curOrdCol);
          var obj2 = mobj.doc.getElementById(mobj.nombre+'_des_'+mobj.curOrdCol);
          obj1.style.display = 'none';
          obj2.style.display = 'none';
        }
        obj1 = mobj.doc.getElementById(mobj.nombre+'_asc_'+idx);

        obj1.style.display = 'block';
        obj1.style.cursor = 'pointer';
        mobj.curOrdCol = idx;
      }
      else
      {
        if (mobj.curOrdType == 'A')
        {
          mobj.curOrdType = 'D';
          var obj1 = mobj.doc.getElementById(mobj.nombre+'_asc_'+mobj.curOrdCol);
          var obj2 = mobj.doc.getElementById(mobj.nombre+'_des_'+mobj.curOrdCol);
          obj1.style.display = 'none';
          obj2.style.display = 'block';
          obj2.style.cursor = 'pointer';
        }
        else
        {
          mobj.curOrdType = 'A';
          var obj1 = mobj.doc.getElementById(mobj.nombre+'_asc_'+mobj.curOrdCol);
          var obj2 = mobj.doc.getElementById(mobj.nombre+'_des_'+mobj.curOrdCol);
          obj1.style.display = 'block';
          obj2.style.display = 'none';
          obj1.style.cursor = 'pointer';
        }
      }

//      quickSort(mobj.xbody.childNodes,idx,mobj.curOrdType,xtipos[idx],mobj);
// MARCO Marzo 2008: ordenamiento por query

      var acampos = mobj.campos.split(',');
      var x=mobj.contenedor.offsetLeft+10;
      var y=mobj.contenedor.offsetTop+5;

      mobj.cuadroResult.innerHTML='Ordenando por '+acampos[idx];
      setTimeout(mobj.nombre+'.actualizaOrden(\''+acampos[idx]+'\')', 50);

/*      var acampos = mobj.campos.split(',');
      mobj.orden=acampos[idx];
      if (mobj.curOrdType=='D') mobj.orden+=' DESC';
      mobj.pagina=0;
      mobj.actualizaXml(mobj.xoperador);
//---
      mobj.primero();
      mobj.setFocus();*/
}

function btnPrimero(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;

  var parent = eval(Elem.parent);
  parent.primero();
  GridAct=parent;
  gridFocus();
}

function btnPagAnterior(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;

  var parent = eval(Elem.parent);
  parent.paginaAnterior();
  GridAct=parent;
  gridFocus();
}

function btnAnterior(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;
  var parent = eval(Elem.parent);
  parent.anterior();
  GridAct=parent;
  gridFocus();
}

function btnSiguiente(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;
  var parent = eval(Elem.parent);
  parent.siguiente();
  GridAct=parent;
  gridFocus();
}

function btnPagSiguiente(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;
  var parent = eval(Elem.parent);
  parent.paginaSiguiente();
  GridAct=parent;
  gridFocus();
}

function btnUltimo(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;
  var parent = eval(Elem.parent);
  parent.irUltimo();
  GridAct=parent;
  gridFocus();
}

function buscKeyDown(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;

  var nom = Elem.id.substring(0,Elem.id.length-9);
  GridAct = eval(nom);
  teclasBuscador(e, GridAct);
}

function buscFocus(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;

  var nom = Elem.id.substring(0,Elem.id.length-9);
  GridAct = eval(nom);
  revisarGrids(GridAct.nombre);
//  GridAct.selecciona(GridAct.elementoXml());  // esto repite el debug. Marco Sep 2008
}

function buscClick(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;

  var nom = Elem.id.substring(0,Elem.id.length-6);
  GridAct = eval(nom);
  if (GridAct.onFocus) GridAct.onFocus(GridAct);
  GridAct.inputBuscador.select();
  GridAct.buscar(GridAct.inputBuscador.value);
}

function criterioClick(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;
  var nom = Elem.id.substring(0,Elem.id.length-6);
  GridAct = eval(nom);
  GridAct.busca_xml = Elem.value;
  GridAct.inputBuscador.value = '';
  try
  {
    GridAct.inputBuscador.focus();
  }
  catch(e){}
}

function getColFromTD(td)
{
  var p=td.id.lastIndexOf('_');
  var n=td.id.substr(p+1,1000);
  if (!n) n=-1;
  return parseInt(n);
}

function tablaClick(e)
{
    var evt = window.event || e;
    var Elem = evt.srcElement || evt.target;


    if(e)           //Moz
    {
        e.preventDefault();
        e.stopPropagation();
    }
    if(window.event)    //IE
    {
        window.event.returnValue = false;
    }

    if (Elem.tagName=='TD')
    {
      var p=Elem.id.lastIndexOf('_');
      var cn=Elem.id.substr(0,p+1);
      cn=cn.replace('_c_','');
      var tobj=eval(cn);
      tobj.colAct = getColFromTD(Elem);
    }

    if (Elem.padre)
    {
      var padre = eval(Elem.padre);
      if ((Elem != padre.cerrar) && (Elem != padre.bayuda))
      {
        padre.setFocus();
      }
    }
}

function mascaraClick(e)
{
    var evt = window.event || e;
    var Elem = evt.srcElement || evt.target;

    if(e)           //Moz
    {
        e.preventDefault();
        e.stopPropagation();
    }
    if(window.event)    //IE
    {
        window.event.returnValue = false;
    }
    if (Elem.parent)
    {
      var parent = eval(Elem.parent);
      parent.setFocus();
    }
}

lista.prototype.asignaPadre = function ()
{
  return this;
};

function freeDOM_Obj(DOMobj)
{
    try
    {
      if (DOMobj.childNodes)
      {
          for (var i=DOMobj.childNodes.length-1;i>=0;i--)
          {
              freeDOM_Obj(DOMobj.childNodes[i]);
              DOMobj.removeChild(DOMobj.childNodes[i]);
          }
      }
    }
    catch(e){}
    finally
    {
            DOMobj = null;
    }
}

function funcData(e)
{
  if(e)           //Moz
  {
     e.preventDefault();
     e.stopPropagation();
  }
  if(window.event)    //IE
  {
     window.event.returnValue = false;
     window.event.cancelBubble = true;
  }

  e = e || window.event;
  var oTarget = e.target || e.srcElement;

  var ndx=0;
  while (oTarget.tagName != 'TR')
  {
    if (oTarget.tagName == 'TD')
    {
      ndx = getColFromTD(oTarget);
    }
    oTarget = oTarget.parentNode;
  }
  var padre = eval(oTarget.padre);

  if (padre)
  {
    if ((e.type == "mousedown") || (e.type == "dblclick"))
    {
       padre.disableRow();
       padre.colAct=ndx;
       padre.resalta(oTarget, padre);
       if (e.type == "dblclick")
       {
         padre.actualizaDestinos();

          if (padre.campoIndice)
          {
            var reg = XML2Array(padre.elementoXml());
            if (reg && reg[0] && reg[0][padre.campoIndice])
            {
              setCookie(padre.campoIndice, reg[0][padre.campoIndice]);
            }
          }
         if (padre.funcion) padre.funcion(padre.nombre, 13, padre.elementoXml());
       }
    }
    else if (e.type == "mouseover")
    {
      padre.resalta(oTarget, padre);
    }
  }
}

lista.prototype.reset = function ()
{
  var difAlt = this.alto;
  if (this.inferior)
  {
    difAlt -= this.inferior.offsetHeight;
    this.infHeight = this.inferior.offsetHeight;
  }
  if (this.pie)
  {
    difAlt -= this.pie.offsetHeight;
    this.pieHeight = this.pie.offsetHeight;
  }
  if (this.usaLeyenda && this.divleyenda)
  {
    difAlt -= this.divleyenda.offsetHeight;
    this.leyHeight = this.divleyenda.offsetHeight;
  }
  if (this.divtitulo)
  {
    difAlt -= this.divtitulo.offsetHeight;
    this.titHeight = this.divtitulo.offsetHeight;
  }
  if (this.encab)       difAlt -= this.encab.offsetHeight;
  if (this.barraStatus)
  {
    difAlt -= this.barraStatus.offsetHeight - 2;
    this.statusHeight = this.barraStatus.offsetHeight;
  }

  difAlt = difAlt > 0 ? difAlt : 0;
  this.cuadro.style.height = difAlt;
  this.contenedor.style.height=this.alto;

  if (this.usaSombra)
  {
    var x=this.contenedor.style.left;
    x=parseFloat(x.replace('px',''));
    var y=this.contenedor.style.top;
    y=parseFloat(y.replace('px',''));
    var w=this.contenedor.style.width;
    w=parseFloat(w.replace('px',''));
    var h=this.contenedor.style.height;
    h=parseFloat(h.replace('px',''));
    this.sombra = new creaSombra(x, y, w, h);
  }

}

lista.prototype.resetLeyenda = function ()
{
  if (this.leyendas.length)
  {
    var oDiv = this.doc.createElement('DIV');
    oDiv.className = 'grid_leyenda';

    var HTMLleyenda  = '<div class="tabla_leyenda">';
    for (var j=0;j<this.leyendas.length;j++)
    {
      if (this.leyendas[j].enter == this.enter) HTMLleyenda += this.leyendas[j].html;
    }
    HTMLleyenda += '</div>';
    this.leyenda = HTMLleyenda;
    this.divleyenda.innerHTML = this.leyenda;
  }
}

lista.prototype.setTitle = function (titulo)
{
  this.divTitulo.innerHTML = titulo;
};

lista.prototype.selecciona = function (xml)
{
  if (this.onSelect)
  {
    if(GridAct && (GridAct.nombre != this.nombre))
    {
    	//alert('selecciona');
        return;
    }
    this.onSelect(this.nombre, xml);
  }
};

lista.prototype.simpleXml = function()
{
  var xml = '<tabla><registro>';

  if (this.indice >= 0)
  {
    if (this.arreglo.length > 0) var regs = this.arreglo[0].getElementsByTagName("registro");
    var acampos = this.campos.split(',');

    for (j=0; j<acampos.length; j++)
    {
      objNodo = this.arreglo[0].getElementsByTagName(acampos[j]);
      if (objNodo.length)
      {
        if ((objNodo[this.arrayOrd[this.indice]].firstChild) && (objNodo[this.arrayOrd[this.indice]].firstChild.nodeValue))
        {
          valor = objNodo[this.arrayOrd[this.indice]].firstChild.nodeValue;
        }
        else valor = '';
        xml = xml + '<'+nodo+'><![CDATA[' + valor + ']]></' + nodo + '>';
      }
    }
  }
  xml = xml + '</registro></tabla>';
  return xml;
}

function clearSel()
{
  document.selection.empty();
}

lista.prototype.resaltaFila = function(nFila)
{
  if (nFila==0) this.cuadro.scrollTop=0;
  this.resalta(this.rows[nFila],this);
};

lista.prototype.localiza = function(campo, valor)
{
  if (debug()) _prompt('LOCALIZA: '+campo+'='+valor);
  var regs   = 0;
  var xvalor = '';
  var npos   = -1;

  if(this.getValue(campo) == valor) return false;

  if (this.arreglo && this.arreglo.length > 0) var regs = this.arreglo[0].getElementsByTagName("registro");
  if (!regs) return;

  var registros = this.arreglo[0].getElementsByTagName("registro");

  for (var i=0;i<registros.length;i++)
  {
    var objNodo = registros[i].getElementsByTagName(campo);
    elem = objNodo[0].firstChild;

    switch (elem.nodeType)
    {
      case 4:
        xvalor = elem.data;
        break;
      case 3:
        xvalor = elem.nodeValue;
        break;
      default:
        xvalor = '';
    }
    if (xvalor == valor)
    {
      npos = i;
      break;
    }
  }
  if (npos<0) this.resaltaFila(0);
  else this.resaltaFila(npos);

  return npos;
};

var old_busca;
var old_xbusca;
var old_id;
var old_tot;
var old_pag;
var old_pags;

lista.prototype.actualizar = function(myid)
{
  //alert('elementoxml: '+ this.nombre + '\n'+this.elementoXml());
  var r=XML2Array(this.elementoXml());
  var xindice=r[0][this.campoIndice];

  //alert('este es mi indice='+this.campoIndice+','+xindice);
  if (this.asyncLoad)
  {
    this.myold_id = xindice;
    this.mybusca  = this.busca_xml;
    this.myxbusca = this.xbusca_xml;
    this.mytot    = this.totRegs;
    this.mypag    = this.pagina;
    this.mypags   = this.paginas;
  }
  else
  {
    var old_busca=this.busca_xml;
    var old_xbusca=this.xbusca_xml;
    var id='';
    var tot=this.totRegs;
    var pag=this.pagina;
    var pags=this.paginas;
  }
  var id='';

  var r=XML2Array(this.xml);

  for (i=0;i<r.length;i++) id+=r[i][this.campoIndice]+',';
  id+=myid;

  this.busca_xml=this.campoIndice;
  //alert('este es el campo indice = '+this.campoIndice);

  this.pagina=0;
  this.paginas=0;

  if ((this.buscador) && (this.combo.options.selectedIndex != 0))
  {
    this.oldSelItem = this.combo.options.selectedIndex;
    this.combo.options.selectedIndex = 0;
  }
  else this.oldSelItem = null;

  var xml=this.leeXml(id,'IN');
/*  this.busca_xml=old_busca;
  this.xbusca_xml=old_xbusca;*/

  if (!this.asyncLoad)
  {
    this.creaLista(xml);
    this.totRegs=tot;
    this.pagina=pag;
    this.paginas=pags;
    this.busca_xml=old_busca;
    this.xbusca_xml=old_xbusca;

    this.localiza(this.campoIndice, myid);
    this.actualizaPie();
    this.resalta(this.rows[this.indice],this);
  }

};

lista.prototype.getValue=function(campo)
{
  var acampos = this.campos.split(',');
  var xml = this.elementoXml();
  var registro = XML2Array(xml);
  if (registro && registro[0] && registro[0][campo]) return registro[0][campo];
  else return null;
};

lista.prototype.irPagina=function(n, ultimo)
{
  n=parseInt(n);
  this.pagina=parseInt(this.pagina);

  if ((n>=1)&&(n<=this.paginas)&&(n!=this.pagina))
  {
    this.pagina=n;
    this.abrePagina(ultimo);
  }
  else if(ultimo) this.irUltimo();

};

lista.prototype.elimina=function()
{
  if (this.indice < 0) return false;
  if (!this.rows.length) return false;

  var uxmlDoc = null;

   try
   {
     xmlDoc.loadXML(this.xml);
   }
   catch(e)
   {
     if (document.implementation.createDocument)
     {
       var parser = new DOMParser();
       uxmlDoc = parser.parseFromString(this.xml, "text/xml");
     }
     if((xmlDoc == null) && (uxmlDoc == null))
     {
       alert("XML Doc Load Failed");
       return;
     }
   }

   if (!uxmlDoc) uxmlDoc = xmlDoc;

   var xmltabla = uxmlDoc.getElementsByTagName("tabla");

  if (xmltabla.length > 0)
  {

    var xmlreg = xmltabla[0].getElementsByTagName("registro")[this.indice];
    if (xmlreg) uxmlDoc.documentElement.removeChild(xmlreg);

    if (this.totRegs)
    {
      var uxml=uxmlDoc.xml;
      var p1=uxml.indexOf('<totalregistros>')+16;
      var p2=uxml.indexOf('</totalregistros>');
      var uxml=uxml.substring(0,p1)+(this.totRegs-1)+uxml.substring(p2);
    }

    this.creaLista(uxml);

    this.setFocus();
    this.resalta(this.rows[this.indice],this);
  }

};

lista.prototype.getCell=function(campo)
{
  var idx=-1;
  var acampos = this.campos.split(',');
  for (var i=0;i<acampos.length;i++)
  {
    if (acampos[i].toUpperCase()==campo.toUpperCase())
    {
      idx=i;
      break;
    }
  }
  if (idx<0) return null;
  return this.rows[this.indice].childNodes[idx];
};

lista.prototype.setXMLValue=function(campo, valor)
{
  if (this.arreglo.length > 0) var regs = this.arreglo[0].getElementsByTagName("registro");

  for (var j=0; j<regs[0].childNodes.length; j++)
  {
      nodo = regs[0].childNodes[j].nodeName;
      objNodo = this.arreglo[0].getElementsByTagName(nodo);
      if (nodo.toUpperCase()==campo.toUpperCase())
      {
        try
        {
          if ((this.arrayOrd.length>0) && (objNodo[this.arrayOrd[this.indice]].firstChild))
          objNodo[this.arrayOrd[this.indice]].firstChild.nodeValue = valor;
          return true;
        }
        catch(e)
        {}
        finally
        {}
      }
  }
  return false;
};

lista.prototype.setValue = function(campo, valor)
{
  var cell=this.getCell(campo);
  if (!cell) return false;
  if (!this.setXMLValue(campo, valor)) return false;
  cell.innerHTML = valor;
  return true;
};

lista.prototype.replace = function(id, campo, valor)
{
  var cell=this.getCell(campo);
  if (!cell) return false;
  if (!this.setXMLValue(campo, valor)) return false;
  cell.innerHTML = valor;
  return true;
};

function validaBusca(valor, patron)
{
  if (patron.substr(0,1)=='*') return true;
  if (!patron) return true;

  if (patron=='F') patron='NNNN-NN-NN';
  var res=true;
  var stop=false;
  var ult='?';

  for (var i=0;i<Math.max(patron.length,valor.length); i++)
  {
    if (!enAdelante) var val=patron.substr(i,1);
    if (!enAdelante && val=='_')
    {
      val=ult;
      var enAdelante = true;
    }

    switch (val)
    {
      case '9':
        var isnum=('123456789'.indexOf(valor.substr(i,1))>=0);
        res = res && isnum;
        break;
      case 'N':
        var isnum=('0123456789./'.indexOf(valor.substr(i,1))>=0);
        res = res && isnum;
        break;
      case 'L':
        var iscar=('ABCDEFGHIJKLMNOPQRSTUVWXYZÑÁÉÍÓÚ./- '.toUpperCase().indexOf(valor.substr(i,1).toUpperCase())>=0);
        res = res && iscar;
        break;
      case 'X':
        var iscar=('ABCDEFGHIJKLMNOPQRSTUVWXYZÑÁÉÍÓÚ0123456789.-/ '.toUpperCase().indexOf(valor.substr(i,1).toUpperCase())>=0);
        res = res && iscar;
        break;
      case '*':
      case ',':
        res = res && true;
        stop=true;
        break;
      case '?':
        res = res && true;
        break;
      default:
        res = res && (valor.substr(i,1).toUpperCase()==patron.substr(i,1).toUpperCase());
        break;
    }
    if (!enAdelante) ult=patron.substr(i,1);
    if (stop) break;
  }
  return res;
}

function analizaBusqueda(aCampos, patrones, valor)
{
  var xCampos = '';
  if (!patrones) return aCampos.join(',');
  var aPatron = patrones.split(',');

  for (var i in aCampos)
  {
    var cond1=!aPatron[i];
    if (cond1)
    {
      if (xCampos) xCampos += ',';
      xCampos += aCampos[i];
    }
    else
    {
      var cond2=validaBusca(valor, aPatron[i]);
      //alert(valor + '-' + aPatron[i] +'-'+ cond2)
      if (cond2)
      {
        if (xCampos) xCampos += ',';
        xCampos += aCampos[i];
      }
    }
  }
 // alert('retornando: '+xCampos);
  return xCampos;
}

lista.prototype.setPadding=function(l,t,r,b)
{
  this.vPaddingTop    = t;
  this.hPaddingRight  = r;
  this.vPaddingBottom = b;
  this.hPaddingLeft   = l;
};