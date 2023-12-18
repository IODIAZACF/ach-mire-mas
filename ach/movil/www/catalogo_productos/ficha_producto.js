var r_M_PRODUCTOS;
var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;
if(params){

// Aqui se recogen los parametros que recibe la pagina <<params tipo JSON>>
// params [ parametro ]
// console.log( params );
	
}


console.log('El codigo del producto es :' + r_M_PRODUCTOS.ID_M_PRODUCTOS);

//var imagen = server_path + 'imagenes/productos/' +  r_M_PRODUCTOS.ID_M_PRODUCTOS + '.jpg' ;

verFoto(r_M_PRODUCTOS.ID_M_PRODUCTOS);

$$("#PREPARACION").html(r_M_PRODUCTOS.PREPARACION);
$$("#TITULO").html(r_M_PRODUCTOS.DESCRIPCION);
$$("#CODIGO1").html(r_M_PRODUCTOS.CODIGO1);
$$("#ID_M_PRODUCTOS").html(r_M_PRODUCTOS.ID_M_PRODUCTOS);


function t_ficha_producto(obj, tecla){
	
	if( $(obj).data("registro") ) {
		var registro =  $(obj).data("registro") ;
		r_M_PRODUCTOS = registro;
	}

	switch(tecla){

		case 'enter':
			$$('#ARCHIVO').val('');
			$$('#ARCHIVO').click();
		break;
		
		case 'ins':
		break;

		case 'esc':
			//cargar_pagina('/catalogo/');
		break;
		
		default:
		app.dialog.alert('Opción no programada..!');
		return false;
		

	}
}


function verFoto(id){

	var xrnd  = Math.floor((Math.random() * 100000000) + 1);			
	var imagen = 'catalogo/foto_producto.php?id_m_productos=' + id +'&rnd=' + xrnd;

	$$("#FOTO").css('background-image', 'url(' + imagen + ')' );
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
				
				console.log(data);
				verFoto(r_M_PRODUCTOS.ID_M_PRODUCTOS);
				app.preloader.hide();
				
			});
			
		};
		reader.readAsBinaryString(input.files[0]);
	}
}
