/* PUSH */
var str = document.URL;
var tmp = str.split("/");
var url = tmp[2].split(":");

xid_push = 'chat';
var push_path = 'http://' + url[0] + ':8887/subscribe?events=' + xid_push;

var es = new EventSource(push_path);

es.addEventListener('message', function (e){
  
	var event = JSON.parse(e.data);
	console.log(event);

	var registro = event.data;
	/*
	var actual = $("#IDENTIFICADOR").val();
	var nueva  = registro.IDENTIFICADOR;
	
	if(actual==nueva) {
		insertChat(registro);
	}
	*/

});
