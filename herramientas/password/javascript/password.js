function password(name)
{
  this.name   = name;
  this.width  = 0;
  this.height = 0;
  this.left   = 0;
  this.top    = 0;

  this.title    = 'Login';
  this.caption  = '';
  this.table    = '';
  this.field    = '';
  this.login    = true;
  this.modal    = false;
  this.url      = '';
  this.remoto   = 0;
  this.db       = '';

  this.callFunc  = null;  // - evento al validar. parametros: func(valido) --> valido = true/false
  this.mask      = null;
  this.container = null;
  this.divTop    = null;
  this.divCont   = null;
  this.divBottom = null;
}

password.prototype.init = function()
{
	if (this.modal)
	{
		this.mask = document.createElement('DIV');
		this.mask.style.display = 'none';
		this.mask.className     = 'login_mask';
		this.mask.id            = this.name + '_mask';
		this.mask.innerHTML     = '&nbsp;';
		this.mask.parent        = this.name;
		document.body.appendChild(this.mask);
	}

	this.container = document.createElement('DIV');
	this.container.id = this.name+'_container';
	this.container.className = 'login';

	document.body.appendChild(this.container);



	var arrCaption = new Array;
	if (this.caption)
	{
		arrCaption = this.caption.split(',');
	}

	var loginText = '';
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

	var Url=new URL("", location);
	var url_img = Url.origin + '/' + Url.pathname.split('/')[1]  + '/imagenes/' + this.db;

	html = '';
	html += '<section id="' + this.name + '_table" class="hero is-fullheight">';
	html += '	<div class="hero-body">';
	html += '		<div class="container has-text-centered">';
	html += '			<div class="column is-4 is-offset-4">';
	html += '				<div class="box">';
	html += '					<p class="subtitle is-5">Acceso al sistema</p>';
	html += '					<br />';
	html += '					<form>';

	if (this.login){

	html += '						<div class="field">';
	html += '							<p class="control has-icons-left has-icons-right">';
	html += '							<input autocomplete="off" class="input is-medium" type="user" placeholder="' + loginText + '"name="' + this.name + '_login" id="' + this.name + '_login"  style="height:40px;" >';
	html += '							<span class="icon is-medium is-left">';
	html += '								<i class="fas fa-user"></i>';
	html += '							</span>';
	html += '							</p>';
	html += '						</div>';
	
	}
	
	html += '						<div class="field">';
	html += '							<p class="control has-icons-left">';
	html += '								<input autocomplete="off" class="input is-medium" type="password" placeholder="' + pwdText + '" name="'+this.name+'_xbusca" id="'+this.name+'_xbusca"  style="height:40px;">';
	html += '								<span class="icon is-small is-left">';
	html += '									<i class="fas fa-lock"></i>';
	html += '								</span>';
	html += '								</p>';
	html += '						</div>';
	html += '					</form>';
	html += '					<div id="mensaje" class="is-danger is-size-6" ></div>';
	html += '					<div id="logo" class="" style="height:200px;"><img src="' + url_img + '_logo_reporte.jpg" style="width: 180px"></div>';
	html += '					<button id="btn_ok" class="bot-login button is-block is-large is-fullwidth" style=" ">Ingresar</button><br />';
						

	html += '					<div id="mensaje" class="subtitle is-7 has-text-weight-bold" >by sistemas24.com</div>';
	
	html += '					<input name="' + this.name + '_remoto" id="' + this.name+'_remoto" type="hidden" value="' + this.remoto + '">';
	html += '					<input name="' + this.name + '_db"     id="' + this.name+'_db"     type="hidden" value="' + this.db     + '">';

	html += '				</div>';
	html += '			</div>';
	html += '		</div>';
	html += '	</div>';
	html += ' </section>';


	
	this.container.innerHTML = html;
	var xname = this.name;
	
	$("#btn_ok").on('click', function(){
		
		call( xname , true);
		
		
	});


	obj = eval( this.name + '_login');
	obj.onkeypress = KeyEnter;
	obj = eval(this.name + '_xbusca');
	obj.onkeypress = KeyEnter;
	var o = eval('btn_ok');
	if (o)
	{
		o.pwdObj=this.name;
		o.onkeydown=kd;
	}
};

function kd(e)
{
	var evt     = window.event || e;
	var KeyCode = evt.keyCode || evt.which;
	var Elem    = evt.srcElement || evt.target;
	if (KeyCode==13)
	{
		call(Elem.pwdObj,true);
	}
}

function call(loginName, accept)
{
	
	login = eval(loginName);
	
	if (!accept)
	{
		if (login.callFunc) login.callFunc(accept);
		return false;
	}

	if (!login.url)
	{
		alert('No se especific� la propiedad URL para el objeto login');
		return false;
	}

	var url    = login.url;
	var params = '';

	if (login.login) params += 'login='+document.getElementById(login.name + '_login').value;
	if (params) params += '&';
	params += '&remoto='+document.getElementById(login.name + '_remoto').value;
	params += '&db='+document.getElementById(login.name + '_db').value;
	params += '&password='+document.getElementById(login.name + '_xbusca').value;

	var xml = enviar(url, params, 'POST');
	
	registro = XML2Array(xml);
	if (!registro.length)
	{
		alert('Error al buscar la informacion');
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
		//edson
		$("#mensaje").html(mensaje);
		
		setTimeout(function(){
			$("#mensaje").html('');
			$("#" + loginName + '_xbusca' ).val('');	
		},3000);
		
		var obj = document.getElementById(login.name + '_login');
		obj.select();
		obj.focus();
		result = false;
	}
		else
	{
		result = true;
	}

	
	if (login.callFunc) login.callFunc(true);
	if (registro[0]['URL']) document.location.href = registro[0]['URL'];
}

password.prototype.show = function()
{
	if (this.mask) this.mask.style.display="block";
	this.container.style.display="block";
};

password.prototype.hide = function()
{
	if (this.mask) this.mask.style.display="none";
	this.container.style.display="none";
};

password.prototype.setFocus = function()
{

	if (this.login){
		$("#" + this.name + '_login').focus(); 
		$("#" + this.name + '_login').select();	
	} 
	else {
		$("#" + this.name + '_xbusca').focus();
		$("#" + this.name + '_xbusca').select();

	}		

};

password.prototype.clear = function()
{
	obj = document.getElementById(this.name + '_xbusca');
	obj.value="";
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
}