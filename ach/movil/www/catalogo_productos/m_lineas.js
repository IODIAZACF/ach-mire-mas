var r_M_PRODUCTOS;
var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;

if(params){

	// Aqui se recogen los parametros que recibe la pagina <<params tipo JSON>>
	// params [ parametro ]
	//console.log( params );

}

buscar_grid('M_LINEAS', '*');


function t_M_LINEAS(obj, tecla){
	
	if( $(obj).data("registro") ) {
		var registro =  $(obj).data("registro") ;
		r_M_LINEAS = registro;
	}

	switch(tecla){

		case 'enter':
			cargar_pagina('/catalogo_productos/', r_M_LINEAS);
		break;
		
		case 'esc':
			cargar_pagina('/menu_principal/');
		break;

		
		default:
		app.dialog.alert('Opción no programada..!');
		return false;
		

	}
}

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
			    params+= '&id_m_productos=' + r_M_LINEAS.ID_M_PRODUCTOS;			

			app.preloader.show();
			//prompt('',url + '?'+ params);
			
			enviar2(url , params, 'post', function(data){
				
				//console.log(data);
				//console.log(r_M_LINEAS);
				verFoto(r_M_LINEAS);
				app.preloader.hide();
				
			});
			
		};
		reader.readAsBinaryString(input.files[0]);
	}
	
	
	
}
