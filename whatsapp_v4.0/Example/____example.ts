import { Boom } from '@hapi/boom'
import P from 'pino'
//import pino from 'pino'
import makeWASocket, { AnyMessageContent, delay, DisconnectReason, fetchLatestBaileysVersion, makeInMemoryStore, useSingleFileAuthState } from '../src'
//import type { BaileysEventEmitter, Chat, ConnectionState, Contact, GroupMetadata, PresenceData, WAMessage, WAMessageCursor, WAMessageKey } from '../Types'
//import { MessageType, MessageOptions, Mimetype } from '@adiwajshing'

require('dotenv').config();
const express = require('express');
const fetch = require('cross-fetch');
const http = require('http');
const cors = require('cors')
const socketIO = require('socket.io');

var Msg = {};
var sock;

const app = express();
const server = http.createServer(app);
const io = socketIO(server);
app.use(cors())

io.on("connection", (socket) => {
  console.log('connection client');
});

app.use(express.json())
//app.use(express.urlencoded());

console.clear();
const port = process.env.PORT || 3000


// the store maintains the data of the WA connection in memory
// can be written out to a file & read from it
const store = makeInMemoryStore({ logger: P().child({ level: 'debug', stream: 'store' }) })
store.readFromFile('./baileys_store_multi.json')

// save every 10s
setInterval(() => {
	store.writeToFile('./baileys_store_multi.json')
}, 10_000)

const { state, saveState } = useSingleFileAuthState('./auth_info_multi.json')

const credencial = state.creds.me;

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
				conversation: 'hello',
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
		console.log(credencial.id);
		//console.log(JSON.stringify(m, undefined, 2))
		m.messages[0]['msg_to'] = credencial;		
		
		fetch('http://127.0.0.1/protecseguros/notificaciones_monitor/whatsapp_receptor_baileys.php', {
			method: 'POST',
			body: JSON.stringify(m),
			headers: { 'Content-Type': 'text/plain' }
		}).then(res => res.text())
		  .then(json => console.log(json));		
		
	
		const msg = m.messages[0]
		if(!msg.key.fromMe && m.type === 'notify') {
			console.log('replying to', m.messages[0].key.remoteJid)
			await sock!.sendReadReceipt(msg.key.remoteJid, msg.key.participant, [msg.key.id])
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
			}
		}
        
		console.log('connection update:', update);
		//console.log('connection update:', sock.emit('xxx','hola'));
		//console.log('qr.scan', update.qr)
		//console.log('qr.scan', typeof update.qr)
		//const xQR = require('qr-image')
		//let qr_svg = xQR.image(update.qr, { type: 'svg', margin: 4 });
		//qr_svg.pipe(require('fs').createWriteStream('./public/qr-code.svg'));				
		
		//let qr_svg = QR.image(update.qr, { type: 'svg', margin: 4 });
		/*
		qr_svg.pipe(require('fs').createWriteStream('./puclic/qr-code.svg'));	
		*/
		io.emit('qr', update.qr);
	})
	// listen for when the auth credentials is updated
	sock.ev.on('creds.update', saveState)


	
	return sock
}

app.post('/enviar', (req, res) => {
	console.log(req.body);
    var todo;
	const id 		= req.body.numero + '@s.whatsapp.net';
	const mensaje   = req.body.mensaje;  
	//console.log (sock);
	
	sock.sendMessage(id, { text: req.body.mensaje }).then(( sentMsg )=>{
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
		todo = {
			"estatus" : "Error",
			"mensaje" : "Enviando Mensaje"
		};    
		res.json( todo );
		
	});
	
	
});

app.use(express.static('./public'));

 
  
server.listen(port, () => {
    console.log('El server esta listo por el puerto ' + port);
})

startSock().then((resp) =>{
	sock = resp;
	//console.log (sock);
	//console.log ('---fin respuesta---');
	
}).catch((error) => {
	console.log(error);
});