var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;

if(params){
	// Aqui se recogen los parametros que recibe la pagina <<params tipo JSON>>
	// params [ parametro ]
	//console.log( params );
	var r_M_LINEAS = params;
	$("#M_PRODUCTOS").data("xfiltro",r_M_LINEAS.ID_M_LINEAS);
	
} else {
	console.error('Debe recibirse el registro de la LINEA');
	cargar_pagina('/catalogo_lineas/');
}

$("#LINEA").html(r_M_LINEAS.NOMBRES);
buscar_grid('M_PRODUCTOS', '*');

var myPhotoBrowser = app.photoBrowser.create({
	type: 'popup'
});

var notificar = app.notification.create({
  icon: '<i class="icon f7-icons"></i>',
  title: 'Framework7',
  titleRightText: 'now',
  subtitle: 'Notification with close on click',
  text: 'Click me to close',
  closeOnClick: true,
  closeButton: true,
});



function t_M_PRODUCTOS(obj, tecla){
	
	if( $(obj).data("registro") ) {
		var registro =  $(obj).data("registro") ;
		r_M_PRODUCTOS = registro;
	}

	switch(tecla){

		case 'tomar':
			$$('#ARCHIVO').val('');
			$$('#ARCHIVO').click();
		break;

		case 'enter':
			//console.log(registro);
			//cargar_pagina('/ficha_producto/',r_M_PRODUCTOS);
			verFoto(r_M_PRODUCTOS);
			
			//$(".link.photo-browser-prev").data('funcion','t_M_PRODUCTOS');
			//$(".link.photo-browser-prev").data('opcion','tomar');
			
		break;
		
		case 'carrito':
			cargar_pagina('/carrito/');
		break;

		case 'esc':
			app.photoBrowser.destroy();
			cargar_pagina('/catalogo_lineas/');
		break;

		case 'cargar':
			var url = server_path + "herramientas/genera_json/genera_json.php";
			var par = 'tabla=V_CONSULTA_PRECIO&campos=CODIGO1,DESCRIPCION,PRECIO,CANTIDAD';
			
			enviar2(url, par, 'POST', function(data){
				if(data.tabla.registro){
					localStorage.setItem('V_CONSULTA_PRECIO', JSON.stringify(data));
					
				}
			});
		break;

		default:
		app.dialog.alert('Opci�n no programada..!');
		return false;
		

	}
}

function agregar(id, descripcion, linea, precio){
	
	pedido = jQuery.parseJSON( localStorage.getItem("pedido_" + db ) ); 
	
	if(!pedido) pedido = new Array;
	
	pedido.push(
		{"ID_M_PRODUCTOS": id, "DESCRIPCION": descripcion, "LINEA": linea, "PRECIO": precio, "CANTIDAD": 1}
	);		

	localStorage.setItem("pedido_" + db, JSON.stringify(pedido) );
	
	console.log(notificar.params);
	notificar.open({
		icon: '<i class="icon f7-icons">ellipsis</i>',
		title: 'HOlaaaaa',
	});
	
	console.log(pedido);
	
}

Template7.registerHelper('boton', function (id, descripcion, linea, precio){
	var html = '';
	html += '<a href="#" class="button navbar" onclick="agregar(\'' + id + '\', \'' + descripcion + '\', \'' + linea + '\', ' + precio + ' )">';
	html += '<i class="icon f7-icons size-20">';
	html += 'cart';
	html += '</i>'
	html += 'Agregar al carrito';
	html += '</a>';
	return html;
});




function verFoto(a_M_PRODUCTOS){
	myPhotoBrowser.close();
	

	var xrnd  = Math.floor((Math.random() * 100000000) + 1);			
	var img  = 'catalogo/foto_producto.php?id_m_productos=' + a_M_PRODUCTOS['ID_M_PRODUCTOSZ'] +'&rnd=' + xrnd;
	var msg  =  a_M_PRODUCTOS['CODIGO1'] + '<br>';
		msg +=	a_M_PRODUCTOS['DESCRIPCION'];
		 
	var data = [{url: img, caption: msg }];

	//var data = ['https://cdn.framework7.io/placeholder/sports-1024x1024-1.jpg'];
	//console.log(data);
	myPhotoBrowser.params.photos = data;
	myPhotoBrowser.open();
	
	var obj = $(".link.photo-browser-prev").parent();

	var html  = ''; 
		html += '<a href="#" class="tab-link" data-funcion="t_M_PRODUCTOS" data-opcion="tomar">';
		html += '<i class="f7-icons">camera_fill</i>';
		html += '</a>';
		
		html += '		<form action ="./foto_producto.php" id="frm_foto">';
		html += '		<input type="file" id="ARCHIVO" onchange="readURL(this);" style="display:none;"/>';
		html += '		<textarea data-prefijo="a"  data-destino="F" data-campo="ARCHIVO" data-tipo="6" data-enviar="1" id="BASE64_ARCHIVO" style="display:none;"/></textarea>';
		html += '		<input type="text" data-prefijo="n"  data-campo="ARCHIVO" data-tipo="6" data-enviar="1" id="NOMBRE_ARCHIVO" style="display:none;"></input>';
		html += '		</form>';
	$(obj).html(html);

}

function readURL(input) {
	//alert('readURL');
	var XFILE = input.id;

	//console.log(XFILE);

	if (input.files && input.files[0]){
		var reader = new FileReader();
		reader.onload = function (e) {
			$$("#BASE64_"+ XFILE).val(btoa(reader.result));
			$$("#NOMBRE_"+ XFILE).val(input.files[0].name);
			//$$("#FOTO").attr('src', 'data:image/png;base64,' + btoa(reader.result));
			//$$("#FOTO").css('background-image', 'url(data:image/png;base64,' + btoa(reader.result) );
			var url = server_path + 'movil/www/catalogo/foto_producto.php';
			var params = 'img=' + encodeURIComponent(btoa(reader.result));
			    params+= '&id_m_productos=' + r_M_PRODUCTOS.ID_M_PRODUCTOS;			

			app.preloader.show();
			//prompt('',url + '?'+ params);
			
			enviar2(url , params, 'post', function(data){
				
				//console.log(data);
				//console.log(r_M_PRODUCTOS);
				verFoto(r_M_PRODUCTOS);
				app.preloader.hide();
				
			});
			
		};
		reader.readAsBinaryString(input.files[0]);
	}
	
	
	
}
