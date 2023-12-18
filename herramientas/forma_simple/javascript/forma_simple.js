function forma_simple()
{
  this.origen         = '';
  this.error          = '';
  this.scriptLeer     = server_path + 'herramientas/genera_xml/genera_xml.php';
  this.scriptEscribir = server_path + 'herramientas/utiles/actualizar_registro.php';
  this.scriptIni      = server_path + 'herramientas/utiles/ini2xml.php';
  this.ini            = null;
  this.busca          = null;
  this.xbusca         = null;
  this.data           = new Array;
}

forma_simple.prototype.tieneCampo=function(campo)
{
  var existeCampo = false;
  for (var x in this.ini)
  {
    if (x.substr(0,5) == 'CAMPO')
    {
      var xcampo = this.ini[x]['CAMPO'];
      if (xcampo==campo)
	  {
		existeCampo = true;
		return existeCampo;
	  }
    }
  }
  return existeCampo;
}

forma_simple.prototype.armar=function(xorigen)
{
  if(xorigen) this.origen = xorigen;
  if (!this.origen)
  {
  	alert('No se establecio un origen');
    return false;
  }

/* leo el origen y cargo la estructura en this.ini */

  this.xbusca = null;
  var url    = this.scriptIni;
  var params = 'origen='+this.origen;
  var xml    = enviar(url, params, 'POST');
  this.ini   = parseINI(xml);
};

forma_simple.prototype.buscar = function(xbusca)
{
  if(xbusca) this.xbusca = xbusca;
  this.armar();
  var url    =  this.scriptLeer;
  var params =  'tabla='+this.ini['TABLA']['TABLA'];
  var campos = '';

  for (var x in this.ini)
  {
    if (x.substr(0,5) == 'CAMPO')
    {
    if (campos) campos += ',';
      campos += this.ini[x]['CAMPO'];
    }
  }

  if (!campos) campos='*';
  params += '&campos='+campos;
  params += '&busca='+this.ini[x]['INDICE'];
  params += '&xbusca='+xbusca;

  var xml=enviar(url, params,'POST');
  this.data = valida_xml(xml);
  if (this.data[0]) return true;
  else return false;
};

forma_simple.prototype.submit = function()
{
  if(this.xbusca==null)
  {
  	alert('No se establecio valor de xbusca');
    return false;
  }

  var url    =  this.scriptEscribir;
  var params =  'tabla='+this.ini['TABLA']['TABLA'];

  var campos  ='';
  for (var x in this.ini)
  {
    if (x.substr(0,5) == 'CAMPO')
    {
  	 var prefijo ='c';
      var campo = this.ini[x]['CAMPO'];
      if (campos) campos += '&';
      //if (this.data[campo])
      //{
        if (this.ini[x]['GUARDAR']=='0') prefijo ='x';
        campos += prefijo+'_'+ campo + '_'+this.ini[x]['TIPO']+'SS='+this.data[campo];
      //}
    }
  }

  params += '&'+campos;
  params += '&busca=' +this.ini['TABLA']['INDICE'];
  params += '&xbusca='+this.xbusca;
  if (this.ini['TABLA']['ONLOAD'])    params += '&onload='+ this.ini['TABLA']['ONLOAD'];
  if (this.ini['TABLA']['UNLOAD'])    params += '&unload='+ this.ini['TABLA']['UNLOAD'];

  if (debug()) _prompt('forma_simple: ',url+'?'+params);
  var xml = enviar(url, params,'POST');
  var registro = valida_xml(xml);
  if (registro)
  {

    if(registro[0]['ERROR'])
    {
        var xMsg = trim(registro[0]['ERROR']);
        var xE   = trim(xMsg.substring(0,10));
            if(xE=='exception')
        {
                var t    = registro[0]['ERROR'].split('<')[1].split('>');
                alert(xE +  ':'+ t[1] + '\n'+t[0]);
        }
        _prompt('Funcion enviar_forma en Formulario \n  Ruta url para Guardar Registro :'  , url+'?'+params);
        registro = null;
        return false;
    }

    return registro;
  }
  else return false;

};

forma_simple.prototype.eliminar = function()
{
  if(this.xbusca==null)
  {
  	alert('No se establecio valor de xbusca');
    return false;
  }

  var url     = this.scriptEscribir;
  var params  = 'tabla='   +this.ini['TABLA']['TABLA'];
      params += '&busca='  +this.ini['TABLA']['INDICE'];
      params += '&xbusca=-'+this.xbusca;

  var xml = enviar(url, params,'POST');
  var registro = valida_xml(xml);
  if (registro)
  {
	return registro;
  }
  else return false;

};

forma_simple.prototype.mostrar = function()
{
  var xHtml='<style type="text/css">.e1 {font-size: 12px; font-family: Verdana, Arial, Helvetica, sans-serif;}</style>';
  xHtml += '<table border="1" width="550">';
  xHtml += '<tr><td class="e1">Rotulo</td><td class="e1">Campo</td><td class="e1">Valor</td></tr>';
  for (var x in this.ini)
  {
    if (x.substr(0,5) == 'CAMPO')
    {
      	var campo = this.ini[x]['CAMPO'];
        xHtml += '<tr><td class="e1">'+this.ini[x]['ROTULO'] +'</td><td class="e1">'+this.ini[x]['CAMPO'] +'</td><td class="e1">' + this.data[campo] + '</td></tr>';
    }
  }
  xHtml += '<tr><td colspan=3>&nbsp;</td><tr>';
  xHtml += '<tr><td class="e1">tabla</td><td colspan=2 class="e1">' + this.ini['TABLA']['TABLA'] + '</td></tr>';
  xHtml += '<tr><td class="e1">busca</td><td colspan=2 class="e1">' + this.ini['TABLA']['INDICE'] + '</td></tr>';
  xHtml += '<tr><td class="e1">xbusca</td><td colspan=2 class="e1">' + this.xbusca + '</td></tr>';
  xHtml += '</table>';

  var winprops = 'height=400,width=600,resizable=1';
  var w = window.open("","_websf",winprops);
  var d = w.document;
  d.open();
  d.write(xHtml);
  d.close();
  w.focus();
};

forma_simple.prototype.getValue=function(campo)
{
  if (this.data[campo]) return this.data[campo];
  else return null;
};

forma_simple.prototype.setValue=function(campo, valor)
{
  if (this.tieneCampo(campo)) this.data[campo] = valor;
};