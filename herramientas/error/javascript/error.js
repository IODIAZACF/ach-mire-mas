var xmlhttp;
if(window.XMLHttpRequest)
{
  xmlhttp = new XMLHttpRequest();
}
else if(window.ActiveXObject)
{
  xmlhttp = new ActiveXObject("MSXML2.XMLHTTP");
}

function mostrar(objeto,tabla,campos,busca,xbusca,indice)
{

   if (tabla) xurl="../genera_xml/genera_xml.php";

    xparams = "tabla="+tabla;
	if (campos) xparams = xparams + "&campos="+campos;
	if (busca)  xparams = xparams + "&busca="+busca;
	if (xbusca) xparams = xparams + "&xbusca="+xbusca;
	if (indice) xparams = xparams + "&indice="+indice;
    x = enviar(xurl,xparams,'GET');
    contenedor.innerHTML="<PRE>"+x+"</PRE>";

/*    alert(x);*/
/*    prompt("",xurl+'?'+xparams);*/

    data = importXML(xurl+'?'+xparams);

	alert(data);

    xx = data.getElementsByTagName('tabla');
    registros = xx[0].childNodes.length;

    xx = data.getElementsByTagName('registro');
    campos = xx[0].childNodes.length;


    Ncampos = new Array();
    for (i=0; i<campos; i++){
		Ncampos[i] = xx[0].childNodes[i].nodeName;
    }

    mydata = new Array();
    for (i=0; i<registros; i++){
        mydata[i] = new Array();
        for (j=0; j<campos; j++){
		    yy = data.getElementsByTagName(Ncampos[j]);
            mydata[i][j] =  yy[i].childNodes[0].nodeValue;
        }
   //alert(mydata[i]);
   }

 }


function enviar(url, params, metodo)
{
  if (xmlhttp)
  {
    if (metodo == 'GET')
    {
      xmlhttp.open("GET", url+'?'+params, false);
      	xmlhttp.send();
    }
    else
    {
      xmlhttp.open("POST", url, false);
      xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xmlhttp.send(params);
    }
    s = xmlhttp.responseText;
    // if (s.indexOf('OK')>=0) s = 'OK';
    return s;
  }
  else return 'El navegador no es compatible con la aplicación';
}

var xmlDoc;
function importXML(url)
{
	if (document.implementation && document.implementation.createDocument)
	{
        xmlDoc = document.implementation.createDocument("", "", null);
		// xmlDoc.onload = createTable;
	}
	else if (window.ActiveXObject)
	{
		xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
		xmlDoc.onreadystatechange = function () {
			// if (xmlDoc.readyState == 4) createTable()
		};
 	}
	else
	{
		alert('Your browser can\'t handle this script');
		return;
	}
	xmlDoc.load(url);

    return xmlDoc;
}
