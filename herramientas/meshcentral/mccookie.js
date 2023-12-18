// Setup crypto and the master key
var crypto = require('crypto');
var key = Buffer.from('61f8de555caa5e9e070ef79159aab60fcec646fd2e7134c165a7eed1337f4528d0361912063ccaceb9ea7d20b2df62ba562f7c21e740e451982aafede6305227014b2e71d8edfdbfb6266029e18704b6', 'base64');

// Encode and decode a cookie
var cookie = encodeCookie({ a: 3, u: 'user\\edson' }, key)
console.log('encoded', cookie);
var decoded = decodeCookie(cookie, key);
console.log('decoded', decoded);

// Encode an object as a cookie using a key using AES-GCM. (key must be 32 bytes or more)
function encodeCookie(o, key) {
    try {
        o.time = Math.floor(Date.now() / 1000); // Add the cookie creation time
        const iv = Buffer.from(crypto.randomBytes(12), 'binary'), cipher = crypto.createCipheriv('aes-256-gcm', key.slice(0, 32), iv);
        const crypted = Buffer.concat([cipher.update(JSON.stringify(o), 'utf8'), cipher.final()]);
        var r = Buffer.concat([iv, cipher.getAuthTag(), crypted]).toString('base64').replace(/\+/g, '@').replace(/\//g, '$');
        //console.log('Encoded AESGCM cookie: ' + JSON.stringify(o));
        return r;
    } catch (ex) { console.log('ERR: Failed to encode AESGCM cookie due to exception: ' + ex); return null; }
};

// Decode a cookie back into an object using a key using AES256-GCM. Return null if it's not a valid cookie. (key must be 32 bytes or more)
function decodeCookie(cookie, key, timeout) {
    try {
        if (key == null) { key = obj.serverKey; }
        cookie = Buffer.from(cookie.replace(/\@/g, '+').replace(/\$/g, '/'), 'base64');
        const decipher = crypto.createDecipheriv('aes-256-gcm', key.slice(0, 32), cookie.slice(0, 12));
        decipher.setAuthTag(cookie.slice(12, 28));
        const o = JSON.parse(decipher.update(cookie.slice(28), 'binary', 'utf8') + decipher.final('utf8'));
        if ((o.time == null) || (o.time == null) || (typeof o.time != 'number')) { console.log('ERR: Bad cookie due to invalid time'); return null; }
        o.time = o.time * 1000; // Decode the cookie creation time
        o.dtime = Date.now() - o.time; // Decode how long ago the cookie was created (in milliseconds)
        if ((o.expire) == null || (typeof o.expire != 'number')) {
            // Use a fixed cookie expire time
            if (timeout == null) { timeout = 2; }
            if ((o.dtime > (timeout * 60000)) || (o.dtime < -30000)) { console.log('ERR: Bad cookie due to timeout'); return null; } // The cookie is only valid 120 seconds, or 30 seconds back in time (in case other server's clock is not quite right)
        } else {
            // An expire time is included in the cookie (in minutes), use this.
            if ((o.expire !== 0) && ((o.dtime > (o.expire * 60000)) || (o.dtime < -30000))) { console.log('ERR: Bad cookie due to timeout'); return null; } // The cookie is only valid 120 seconds, or 30 seconds back in time (in case other server's clock is not quite right)
        }
        //console.log('Decoded AESGCM cookie: ' + JSON.stringify(o));
        return o;
    } catch (ex) { console.log('ERR: Bad AESGCM cookie due to exception: ' + ex); return null; }
};