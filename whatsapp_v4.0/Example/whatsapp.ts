console.clear();
console.log(process.argv);

/*****  La variable corresponde al puerto **********/
/* node --inspect -r ts-node/register Example/whatsapp.ts 4001
*/

const [,, puerto, identificador] = process.argv;

import { Boom } from '@hapi/boom'
import P from 'pino'
//import pino from 'pino'
import makeWASocket, { AnyMessageContent, delay, DisconnectReason, fetchLatestBaileysVersion, makeInMemoryStore, useSingleFileAuthState, downloadContentFromMessage } from '../src'
import { writeFile } from 'fs/promises'

const express = require('express');
const fetch = require('cross-fetch');
const http = require('http');
const cors = require('cors')
const utf8 = require('utf8');

var Msg = {};
var sock;
var credencial=null;;
var sistema = 'protecseguros';
var ip = '127.0.0.1';

const app = express();
const server = http.createServer(app);
app.use(cors())

app.use(express.json())

console.clear();
const port = puerto
const SESSION_NUMBER_ERROR		=  __dirname + '/../sessions/' + puerto + '/number_error.json';
const FILE_STORE_SESSION 		=  __dirname + '/../sessions/' + puerto + '/store_multi.json';
const FILE_STORE_DATA_CHAT 		=  __dirname + '/../sessions/' + puerto + '/auth_info_multi.json';
const FILE_STORE_DATA_QR_IMAGE 	=  __dirname + '/../sessions/' + puerto + '/qr-code.svg';
const FILE_STORE_DATA_QR_DATA 	=  __dirname + '/../sessions/' + puerto + '/qr-code.json';
const DIR_DATA_DOWNLOAD 		=  __dirname + '/../../whatsapp_download';

// the store maintains the data of the WA connection in memory
// can be written out to a file & read from it
const store = makeInMemoryStore({ logger: P().child({ level: 'debug', stream: 'store' }) })
store.readFromFile(FILE_STORE_SESSION);

// save every 10s
setInterval(() => {
store.writeToFile(FILE_STORE_SESSION)
}, 10_000)

const { state, saveState } = useSingleFileAuthState(FILE_STORE_DATA_CHAT)
//credencial = state.creds.me;

// start a connection
const startSock = async() => {
	// fetch latest version of WA Web
	const { version, isLatest } = await fetchLatestBaileysVersion()
	//console.log(`using WA v${version.join('.')}, isLatest: ${isLatest}`)

	const sock = makeWASocket({
		version,
		connectTimeoutMs: 0,
		//logger: P({ level: 'trace' }),
		printQRInTerminal: true,
		auth: state,
		// implement to handle retries
		getMessage: async key => {
				console.log(key);
			return {
				conversation: 'WS24',
			}
		}
	})

	store.bind(sock.ev)
	
	const sendMessageWTyping = async(msg: AnyMessageContent, jid: string) => {
		await sock.presenceSubscribe(jid)
		await delay(500)

		await sock.sendPresenceUpdate('composing', jid)
		await delay(2000)

		await sock.sendPresenceUpdate('paused', jid)

		await sock.sendMessage(jid, msg)
	}
    
	sock.ev.on('chats.set', item => console.log(` chats.set recv ${item.chats.length} chats (is latest: ${item.isLatest})`))
	sock.ev.on('messages.set', item => console.log(`messages.set recv ${item.messages.length} messages (is latest: ${item.isLatest})`))
	sock.ev.on('contacts.set', item => console.log(`recv ${item.contacts.length} contacts`))
	
	
	//sock.ws.on('CB:receipt', m => console.log('luisman', m, Msg.messages[0]))
	/* llamar o crear un callback al recibir mensajes o data de el logger... 
	sock.ws.on('CB:receipt', (data)=>{
		var Idx = data.attrs.id
		console.log('luisman', data, Msg[Idx])
	} )
	
	*/
	sock.ev.on('messages.upsert', async m => {
		m.messages[0]['msg_to']=null;
		if(credencial){
			m.messages[0]['msg_to'] = credencial;			
		}
		//console.log(JSON.stringify(m, undefined, 2))
		
		
		sock.profilePictureUrl(m.messages[0].key.remoteJid, 'image').then((resp) =>{
			//si esta en el agenda envio la imagen de perfil .... 
			m.messages[0]['key']['url_profile'] = resp;
			send_receptor(m);			
		}).catch((error) => {
			//console.log(error);
			//no esta en la agenda envio sin la imagen de perfil .... 
			send_receptor(m);
		});
	
		const msg = m.messages[0]		
	
		if(!msg.key.fromMe && m.type === 'notify') {
			console.log('replying to', m.messages[0].key.remoteJid)
			//await sock!.sendReadReceipt(msg.key.remoteJid, msg.key.participant, [msg.key.id])
			//await sendMessageWTyping({ text: 'Hello there!' }, msg.key.remoteJid)
		}
	}) 

	//sock.ev.on('messages.update', m => console.log('messages.update', m))
	sock.ev.on('message-receipt.update', m => console.log('message-receipt.update', m))
	sock.ev.on('presence.update', m => console.log('presence.update', m))
	sock.ev.on('chats.update', m => console.log('chats.update', m))
	sock.ev.on('contacts.upsert', m => console.log('contacts.upsert', m))

	sock.ev.on('connection.update', (update) => {
		const { connection, lastDisconnect } = update
		if(connection === 'close') {
			// reconnect if not logged out					
			if((lastDisconnect.error as Boom)?.output?.statusCode !== DisconnectReason.loggedOut) {
				startSock()
			} else {
				console.log('connection closed')
				var xmensaje = {
					"tipo" : "notificacion",
					"proceso" : "session_close",
					"estatus" : "Ok",
					"puerto" : port,
					"identificador" : identificador,
					"mensaje" : "Dispositivo cerro la session"
				};
				notificar_estatus(xmensaje);
				setTimeout (function(){
					process.exit(1);						
				}, 500);						
			}
		}
		
		if(connection === 'open') {
			credencial = state.creds.me;
			var xNumero = credencial.id.split('@')[0].split(':')[0];
			console.log(credencial);
			console.log('Numero: ', xNumero);
			if(xNumero!=identificador){
					var mensaje = {
						"tipo" : "notificacion",
						"proceso" : "session_open",
						"estatus" : "Error",
						"puerto" : port,
						"identificador" : identificador,
						"numero" : xNumero,
						"mensaje" : "Error al vincular el Identificador " + identificador + " con el Numero " + xNumero
					};
					notificar_estatus(mensaje);
					setTimeout (function(){
						process.exit(1);						
					}, 500);						
			}else{
					var mensaje = {
						"tipo" : "notificacion",
						"proceso" : "session_open",
						"estatus" : "Ok",
						"puerto" : port,
						"identificador" : identificador,
						"numero" : xNumero,
						"mensaje" : "Proceso de vinculacion exitoso"
					};
					notificar_estatus(mensaje);
			}
		}
        
		console.log('connection update: ->', update);
		if(update.qr){
			const xQR = require('qr-image')
			let qr_svg = xQR.image(update.qr, { type: 'svg', margin: 4 });
			qr_svg.pipe(require('fs').createWriteStream(FILE_STORE_DATA_QR_IMAGE));				
		}
			
		
		//let qr_svg = QR.image(update.qr, { type: 'svg', margin: 4 });
		/*
		qr_svg.pipe(require('fs').createWriteStream('./puclic/qr-code.svg'));	
		*/

	})
	// listen for when the auth credentials is updated
	sock.ev.on('creds.update', saveState)


	
	return sock
}

let send_receptor = function (m){
	fetch('http://' + ip + '/' + sistema + '/notificaciones_monitor/whatsapp_receptor_baileys.php', {
		method: 'POST',
		body: JSON.stringify(m),
		headers: { 'Content-Type': 'text/plain' }
	}).then(res => res.text())
	  .then(json => console.log(json));		

}

let notificar_estatus = function (m){
		fetch('http://' + ip + '/whatsapp/whatsapp.php', {
			method: 'POST',
			body: JSON.stringify(m),
			headers: { 'Content-Type': 'text/plain' }
		}).then(res => res.text())
		  .then(json => console.log(json)
		  );		

}

app.post('/enviar', (req, res) => {
	console.log('body', req.body);
    var todo;
	const id 		= req.body.numero + '@s.whatsapp.net';
	const mensaje   = utf8.decode ( req.body.mensaje );  
	const xmensaje   = utf8.encode( req.body.mensaje );  
	console.log(xmensaje);
	
	sock.sendMessage(id,  JSON.parse( mensaje ) ).then(( sentMsg )=>{
		//console.log('se envio con estos datos');
		//console.log( sentMsg.WebMessageInfo );
		//console.log( typeof sentMsg );
		//console.log('---------------------------------------');
		todo = {
			"estatus" : "OK",
			"mensaje" : "Mensaje Enviado"
		};    
		res.json( todo );
	}).catch((error)=>{
		console.log(error);
		todo = {
			"estatus" : "Error",
			"mensaje" : "Enviando Mensaje"
		};    
		res.json( todo );
		
	});
	
	
});

server.listen(port, () => {
    console.log('El server esta listo por el puerto ' + port);
})

startSock().then((resp) =>{
	sock = resp;	
}).catch((error) => {
	console.log(error);
});