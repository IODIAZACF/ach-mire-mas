function alert24()
{
	var xWait = '';
	xWait += '<table id="wait" width="532" border="0" cellpadding="1" cellspacing="1" bordercolor="#666666" style="position:absolute; left:10; top:10; display:none;">';
	xWait += '  <tr>';
	xWait += '    <td width="528" height="22" background="'+ server_path +'/estilo/nblue/img/wait/fondo_titulo.jpg"><span style="font-family:verdana; font-size:14px; color:#FFFFFF; padding-left:5px;">Espere.....</span></td>';
	xWait += '  </tr>';
	xWait += '  <tr>';
	xWait += '    <td bgcolor="#F0F0F0" height="40" align="center"><span id="MsgWait" style="font-family:verdana; font-size:14px; color:#333333;"></span>	</td>';
	xWait += '  </tr>';
	xWait += '  <tr>';
	xWait += '    <td bgcolor="#333333" style="height:1px;"></td>';
	xWait += '  </tr>';
	xWait += '</table>';

	$('body').append(xWait);
}

alert24.prototype.ocultarMensaje = function (msg)
{
	var divWait = document.getElementById('wait');
	if (divWait) divWait.style.display = 'none';		
}  
alert24.prototype.mostrarMensaje = function (msg)
{
  var msgWait = document.getElementById('MsgWait');
  msgWait.innerHTML = msg
  var divWait = document.getElementById('wait');

  divWait.style.left = 100;
  divWait.style.top  = 100;

  divWait.style.display = 'block';
  divWait.style.zIndex=10000000;
}

var alerta = new alert24();

