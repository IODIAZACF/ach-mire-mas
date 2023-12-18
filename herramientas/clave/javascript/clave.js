function clave(name)
{
  this.name  = name;
  this.height= 170;
  this.width = 250;
  this.left  = 0;
  this.top   = 0;

  this.title    = 'Clave';
  this.nivel    = '';
  this.caption  = '';
  this.login    = false;
  this.modal    = true;
  this.url      = server_path + 'herramientas/clave/clave.php';

  this.callFunc  = null;  // evento al validar. parametros: func(valido) --> valido = true/false
  this.mask      = null;
  this.container = null;
  this.divTop    = null;
  this.divCont   = null;
  this.divBottom = null;
  this.generator = null;
  this.noOcultarCombos=false;
  this.arrCombos   = null;
}

clave.prototype.regenerate = function()
{
  this.generator = 3123 + Math.floor(Math.random()*5000);
  var cap=document.getElementById(this.name+'_caption');
  var xnivel='';
  if(this.nivel)
  {
  	xnivel = '<b>Nivel:</b> ' + this.nivel +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
  }
  cap.innerHTML = xnivel + '<b>' + this.caption + ': ' +  '</b>'  + this.generator;
}

clave.prototype.init = function()
{

  borraHijos(this.container);

  if (this.modal)
  {
    this.mask = document.createElement('DIV');
    this.mask.style.display = 'none';
    this.mask.style.zIndex=9999999;
    this.mask.className     = 'login_mask';
    this.mask.id            = this.name + '_mask';
    this.mask.innerHTML     = '&nbsp;';
    this.mask.parent        = this.name;
    document.body.appendChild(this.mask);
  }
  this.container = document.createElement('DIV');
//  this.container.style.display='none';

  if (this.left || this.top) this.container.style.position = 'absolute';

  if (this.left) this.container.style.left = this.left;
  if (this.top)  this.container.style.top  = this.top;

  if (this.width)  this.container.style.width  = this.width;
  if (this.height) this.container.style.height = this.height;

  this.container.id = this.name+'_container';
  this.container.className = 'login';
  this.container.style.zIndex=10000000;
  this.container.style.padding=4;

  document.body.appendChild(this.container);

  this.divTop = document.createElement('div');
  this.container.appendChild(this.divTop);
  this.divTop.className = 'login_title';
  this.divTop.style.width='100%';
  this.divTop.innerHTML = '<b>'+this.title+'</b>';

  this.divCont = document.createElement('div');
  this.container.appendChild(this.divCont);

  var arrCaption = new Array;
  if (this.caption)
  {
    arrCaption = this.caption.split(',');
  }

  var loginText = 'Login';
  var pwdText   = '';

  if (arrCaption.length > 1)
  {
    loginText = arrCaption[0];
    pwdText   = arrCaption[1];
  }
  else
  {
    pwdText   = this.caption;
    loginText = null;
  }

  var html = '<table id="'+this.name+'_table" cellspacing="3" width="100%">';

  html += '<tr><td colspan="2"  id="'+this.name+'_caption" nowrap align="center">'+pwdText+':</td></tr>';

  if (this.login)
  {
	  html += '<tr>';
	  html += '  <td >Llave:</td>';
	  html += '  <td ><input type="text" class="campo_inactivo" onfocus="this.className=\'campo_activo\'" onblur="this.className=\'campo_inactivo\'" name="'+this.name+'_login" id="'+this.name+'_login"></td>';
	  html += '</tr>';
  }

  html += '<tr>';
  html += '  <td>Frase:</td>';
  html += '  <td><input type="clave" class="campo_inactivo" onfocus="this.className=\'campo_activo\'" onblur="this.className=\'campo_inactivo\'" name="'+this.name+'_xbusca" id="'+this.name+'_xbusca"></td>';
  html += '</tr>';
  //prompt('', html);

  this.divCont.innerHTML = html;

  this.divBottom = document.createElement('div');
  this.divBottom.style.height = 50;
  this.container.appendChild(this.divBottom);

  this.divBottom.innerHTML = '<table width="100%"><tr><td align="right"><button id="btn_ok" class="boton_inactivo" onclick="call(\''+this.name+'\',true)">Aceptar</button><button class="boton_inactivo" onclick="call(\''+this.name+'\',false)">Cancelar</button></td></tr></table>';
  var table = document.getElementById(this.name + '_table');

  table.style.height = this.container.offsetHeight - this.divTop.offsetHeight - this.divBottom.offsetHeight;
  if (this.login)
  {
	  obj = eval(this.name + '_login');
	  obj.onkeypress = KeyEnter;
  }
  obj = eval(this.name + '_xbusca');
  obj.onkeypress = KeyEnter;
  var o=eval('btn_ok');
  if (o)
  {
    o.pwdObj=this.name;
    o.onkeydown=kd;
  }
  this.regenerate();
};

function kd(e)
{
  var evt     = window.event || e;
  var KeyCode = evt.keyCode || evt.which;
  var Elem    = evt.srcElement || evt.target;
  if (KeyCode==13)
  {
     if(e)               //Moz
     {
         e.preventDefault();
         e.stopPropagation();
     }
     if(window.event)    //IE
     {
         window.event.returnValue = false;
         window.event.cancelBubble = true;
     }

    call(Elem.pwdObj,true);
  }
}

function call(loginName, accept)
{
  login = eval(loginName);
  if (!accept)
  {
    login.mostrarCombos();
    if (login.callFunc) login.callFunc(accept);
    login.hide();
    return false;
  }

  if (!login.url)
  {
    alert('No se especificó la propiedad URL para el objeto login');
    return false;
  }

  var url    = login.url;
  var params = '';

  //if (login.login) params += 'login='+document.getElementById(login.name + '_login').value;
  params += 'generador='+login.generator;
  if (params) params += '&';
  params += 'frase='+document.getElementById(login.name + '_xbusca').value;
  if (params) params += '&nivel='+ login.nivel;
  if (this.login)
  {
  	if (params) params += '&llave='+ document.getElementById(login.name + '_login').value;

  }

  //prompt('',url +'?'+ params);
  xml = enviar(url, params, 'POST');
  registro = XML2Array(xml);
  //alert(xml);
  if (!registro.length)
  {
    alert('Error al buscar la información');
    var obj = document.getElementById(login.name + '_login');
    obj.select();
    obj.focus();
    return false;
  }

  var mensaje = '';
  var result = true;

  if (registro[0]['MENSAJE']) mensaje = registro[0]['MENSAJE'];

  if (mensaje && (mensaje != 'OK'))
  {
    alert(mensaje);
    var obj = document.getElementById(login.name + '_login');
    if (obj)
    {
      obj.select();
      obj.focus();
    }
    result = false;
    login.clear();
    login.regenerate();
    login.setFocus();
    return false;
  }
  else
  {
    login.mostrarCombos();
    result = true;
  }

  if (login.callFunc) login.callFunc(true,xml);
  if (registro[0]['URL']) document.location.href = registro[0]['URL'];
}

clave.prototype.show = function()
{
  this.ocultarCombos();
  if (this.mask) this.mask.style.display="block";
  this.regenerate();
  this.container.style.display="block";
};

clave.prototype.hide = function()
{
  if (this.mask) this.mask.style.display="none";
  this.container.style.display="none";
};

clave.prototype.setFocus = function()
{
  //alert(this.name + '_login');
  if (this.login) obj = document.getElementById(this.name + '_login');
  else obj = document.getElementById(this.name + '_xbusca');
  obj.select();
};

clave.prototype.clear=function()
{
  var obj=document.getElementById(this.name+'_xbusca');
  if (obj)
  {
    obj.value='';
  }
};



clave.prototype.mostrarCombos = function()
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

clave.prototype.ocultarCombos = function()
{
   this.arrCombos = new Array;
   sels = document.getElementsByTagName('SELECT');
   if (sels && sels.length)
   {
     for (var i=0;i<sels.length;i++)
     {
       if (sels[i].form)
       {
          if (sels[i].style.display!="none")
          {
            this.arrCombos[this.arrCombos.length] = sels[i];
            sels[i].style.display="none";
          }
       }
     }
   }

};



function KeyEnter(e)
{
    var evt = window.event || e;
    var KeyCode        = evt.keyCode || evt.which;
    var Elem           = evt.srcElement || evt.target;

    if (KeyCode == 13)
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

      var eId = Elem.id.substring(Elem.id.length-6);
      var id='';

      if (eId == '_login')
      {
        id = Elem.id.replace('_login','_xbusca');
      }
      else
      {
        id = 'btn_ok';
      }
      var obj = document.getElementById(id);
      obj.focus();
    }
    if (KeyCode == 27)
    {
      var eId = Elem.id.substring(0,Elem.id.length-6);

	  var login = eval(eId);
	  login.mostrarCombos();
	  if (login.callFunc) login.callFunc(false);
	  login.hide();
    }
}


function borraHijos(obj)
{
  if (!obj) return;
  for (i=obj.childNodes.length-1;i>=0;i--)
  {
    obj.removeChild(obj.childNodes[i]);
  }
}