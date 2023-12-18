<?php

include_once (Server_Path . "herramientas/utiles/comun.php");
include_once (Server_Path . "herramientas/sql/class/class_sql.php");

encabezado('DBexplorer');
javascript('jquery,utiles');


?>

<body>
	<div id="queryEditor" class="queryEditor">
		<textarea id="iSQL" name="iSQL" rows="4" cols="50">select * from m_usuarios</textarea>
		<br><br>
		<input type="button" value="Ejecutar">
	</div>

<div style = "width:1000px; height: 500px; overflow: auto;">	
<table id="tabla" class="is-fullwidth is-scrollable">
	
	
</table>
</div>


<script type="text/javascript">
$( "#queryEditor" ).click(function() {
	var url 	 = server_path + 'herramientas/dbexplorer/ejecuta_query.php';
	var params   = 'query=' + $('#iSQL').val();
	enviar2(url, params, 'POST', function( data ){

		var fragment = $(document.createDocumentFragment());

		var col = '';
		col += '<thead>';
		col += '<tr>'
		
		$.each(data.arreglo_atributos, function(id, att){
			col += '<th>' + att.NOMBRE + '</th>';
		});		

		col += '</tr>'
		col += '</thead>';
		
		col += '<tbody>';
		$.each(data.registro, function(id, reg){
			col += '<tr>'
			$.each(reg, function(campo, valor){
				col += '<td>' + valor + '</td>';
			});
			col += '</tr>'
		});		
		col += '</tbody>';

		fragment.append(col);

		$('#tabla').append(fragment);

	});
	
 
}); 

 
	/*
	$('#iSQLResult').empty();
	$('#iSQLResult').append('<table id="iSQLDataResult" class="iSQLDataResult"><thead><tr></tr></thead><tbody></tbody></table>');
	$.each(data.arreglo_atributos, function(id, att){
		var col = '<th>' + att.NOMBRE + '</th>';
		$('#iSQLDataResult thead tr').append( col );
	});		
	
	$.each(data.registro, function(id, reg){
		var row = $('<tr></tr>');
		$.each(reg, function(campo, valor){
			var col = '<td>' + valor + '</td>';
			$(row).append( col );				
		});
		$('#iSQLDataResult tbody').append( row );
	});		
	*/
	
	
	
	//console.log(data);
		
		
	
function enviar2(url, params, metodo, callback){
	
	xauth = '';
	llave = '12345';
	params = 'auth=' + xauth + '&' + params + '&db=' + db; 
	
	
	var paso = false;
    var resp= null;
    $.ajax({
    	async: true,
        method: metodo,
	    dataType: "text",
	    url: url,
        data: params,
		headers: {
			'llave': llave,
			'db': db
		},
	    success: function(data)
	    {
			//console.log(data);
			//console.log('conexion establecida ' + Date.now()) ;
            //resp = data;
	    },
	    complete: function(data){
			
			var result;
			
			//console.log(data);
			try {
				result = JSON.parse(data.responseText);
			} 
			catch (e) {
				result = data.responseText;
			}

			if(callback) {
				
				setTimeout( function(){ callback( result ); }, 100);
					
			} else {
				return result;
			}
			
	    },
		statusCode: {
			404: function (xhr) {
					app.preloader.hide();
					app.dialog.alert('No hay Conexion al servidor', tituloAlert)
				}
		}
	});
}

	
	

</script>


</body>
</html>
