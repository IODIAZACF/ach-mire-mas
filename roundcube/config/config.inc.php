<?php

/* Local configuration for Roundcube Webmail */

// ----------------------------------

// SQL DATABASE

// ----------------------------------

// Database connection string (DSN) for read+write operations

// Format (compatible with PEAR MDB2): db_provider://user:password@host/database

// Currently supported db_providers: mysql, pgsql, sqlite, mssql, sqlsrv, oracle

// For examples see http://pear.php.net/manual/en/package.database.mdb2.intro-dsn.php

// Note: for SQLite use absolute path (Linux): 'sqlite:////full/path/to/sqlite.db?mode=0646'

//       or (Windows): 'sqlite:///C:/full/path/to/sqlite.db'

// Note: Various drivers support various additional arguments for connection,

//       for Mysql: key, cipher, cert, capath, ca, verify_server_cert,

//       for Postgres: application_name, sslmode, sslcert, sslkey, sslrootcert, sslcrl, sslcompression, service.

//       e.g. 'mysql://roundcube:@localhost/roundcubemail?verify_server_cert=false'

$config['db_dsnw'] = 'mysql://root:las36horas@localhost/roundcube';

$config['default_host'] = 'ssl://mail.protecseguros.ec';
$config['smtp_server'] = 'tls://mail.protecseguros.ec';
$config['smtp_port'] = 587;
$config['smtp_user'] = '%u';

// SMTP password (if required) if you use %p as the password Roundcube
// will use the current user's password for login
$config['smtp_pass'] = '%p';


// ----------------------------------

// IMAP

// ----------------------------------

// The IMAP host chosen to perform the log-in.

// Leave blank to show a textbox at login, give a list of hosts

// to display a pulldown menu or set one host as string.

// Enter hostname with prefix ssl:// to use Implicit TLS, or use

// prefix tls:// to use STARTTLS.

// Supported replacement variables:

// %n - hostname ($_SERVER['SERVER_NAME'])

// %t - hostname without the first part

// %d - domain (http hostname $_SERVER['HTTP_HOST'] without the first part)

// %s - domain name after the '@' from e-mail address provided at login screen

// For example %n = mail.domain.tld, %t = domain.tld

// WARNING: After hostname change update of mail_host column in users table is

//          required to match old user data records with the new host.

// $config['default_host'] = 'localhost';

// provide an URL where a user can get support for this Roundcube installation

// PLEASE DO NOT LINK TO THE ROUNDCUBE.NET WEBSITE HERE!

$config['support_url'] = '';

// This key is used for encrypting purposes, like storing of imap password

// in the session. For historical reasons it's called DES_key, but it's used

// with any configured cipher_method (see below).

// For the default cipher_method a required key length is 24 characters.

$config['des_key'] = 'lXRBME9advvvTHRzM96iQD11';

// ----------------------------------

// PLUGINS

// ----------------------------------

// List of active plugins (in plugins/ directory)

$config['plugins'] = [];

// Set the spell checking engine. Possible values:

// - 'googie'  - the default (also used for connecting to Nox Spell Server, see 'spellcheck_uri' setting)

// - 'pspell'  - requires the PHP Pspell module and aspell installed

// - 'enchant' - requires the PHP Enchant module

// - 'atd'     - install your own After the Deadline server or check with the people at http://www.afterthedeadline.com before using their API

// Since Google shut down their public spell checking service, the default settings

// connect to http://spell.roundcube.net which is a hosted service provided by Roundcube.

// You can connect to any other googie-compliant service by setting 'spellcheck_uri' accordingly.

$config['spellcheck_engine'] = 'pspell';

//$config['enable_spellcheck'] = false;

# specify IMAP port (STARTTLS setting)
$config['default_port'] = 993;

# specify SMTP auth type
$config['smtp_auth_type'] = 'LOGIN';

# specify SMTP HELO host
$config['smtp_helo_host'] = 'protecseguros.ec';

# specify domain name
#$config['mail_domain'] = '';

# specify UserAgent
$config['useragent'] = 'Server World Webmail';

# specify SMTP and IMAP connection option
$config['imap_conn_options'] = array(
  'ssl'         => array(
    'verify_peer' => false,
    'CN_match' => 'protecseguros.ec',
    'allow_self_signed' => true,
    'ciphers' => 'HIGH:!SSLv2:!SSLv3',
  ),
);

$config['smtp_conn_options'] = array(
  'ssl'         => array(
    'verify_peer' => false,
    'CN_match' => 'protecseguros.ec',
    'allow_self_signed' => true,
    'ciphers' => 'HIGH:!SSLv2:!SSLv3',
  ),
);

