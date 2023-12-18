<?PHP

/**
 * Transfer file to server via FTP connection
 *
 * @author David - alias 'kisPocok'
 * @author willcodephpforfood@gmail.com [ will.code.php.for.food+ftp.class.php@gmail.com ]
 * @license Absolutly free! You can edit and share it. If you make a better version send to me!
 *
 * @category PHP CLASS
 * @package FILE UPLOAD WITH FTP
 */
class ftp {
	/**
	 * //to the folder the the file will be upload.
	 *
	 * @var string
	 */
	public $ftp_root_dir = "/";

	/**
	 * Your FTP Username
	 *
	 * @example 'kisPocok'
	 * @var string
	 */
	protected $user;

	/**
	 * Your FTP Password
	 *
	 * @example 'MyP455W0rd'
	 * @var string
	 */
	protected $pass;

	/**
	 * Your ftp address
	 *
	 * @example  localhost:3306 or ftp.domain.com or anything else
	 * @var string
	 */
	protected $host;

	/**
	 * This is not nessesery for you. Do not edit.
	 */
	protected $conn;

	/**
	 * This is't nessesery too. Do not edit please.
	 */
	protected $mode = FTP_ASCII;

	/**
	 * This will help me to know connection is already be
	 */
	public $connection = false;
    public $code = 0;

	/**
	 * Auto constructor.
	 * This function will setup the connection with entered datas(username, password and hostname)
	 *
	 * @param string $ftp_user
	 * @param string $ftp_pass
	 * @param string $ftp_host
	 */
	function __construct($ftp_user, $ftp_pass, $ftp_host, $ftp_root_dir="/", $ssl=false) {
		// check settings
		if(!$ftp_user or !$ftp_pass or !$ftp_host) die("Ftp class crashed!");
		// sometimes it is need:
		//if(substr($ftp_host,-1)!="/")$ftp_host = $ftp_host."/";

		// main settings
		$this->ftp_user = $ftp_user;
		$this->ftp_pass = $ftp_pass;
		$this->ftp_host = $ftp_host;
		$this->ftp_root_dir = $ftp_root_dir;
		$this->ssl = $ssl; // secure connection
		$this->connection = false;

		// create connection to ftp
		if(!$this->start_connection()) {
			// error with user/pass/host
			die("Ftp connection failed!");
		}
	}

	/**
	 * This function setting up the connection with ftp server.
	 *
	 * @return boolean
	 */
	protected function start_connection() {
		// SSL connection already included!
		if(!$this->ssl):
			$this->conn = ftp_connect($this->ftp_host);
		else:
			$this->conn = ftp_ssl_connect($this->ftp_host);
		endif;
		if(ftp_login($this->conn, $this->ftp_user, $this->ftp_pass)):
			$this->connection = true;
			$this->select_dir();
			return true;
		else:
			return false;
		endif;
	}

	/**
	 * Change dir in server to target
	 */
	protected function select_dir() {
		ftp_pwd($this->conn);
		ftp_pasv($this->conn, true);
		// change dir in server to target
		ftp_chdir($this->conn, $this->ftp_root_dir);
		ftp_pwd($this->conn);
	}

	/**
	 * This function will move your file to ftp server
	 *
	 * @param your_filename_ $upload_file
	 * @param your_old_file $newfilename (not need)
	 * @return boolean
	 */
	public function upload_file($upload_file, $newfilename=false) {
		if(!$this->connection) die("There are no connection!");
		if(!$newfilename) $newfilename=$upload_file;
		// if local file doesnt exist terminate
		if(!file_exists($upload_file) or !$upload_file) return NULL;

		//$from = fopen($newfilename,"r");
		if(ftp_put($this->conn, $newfilename, $upload_file, $this->mode)):

			return true;
		else:

			return false;
		endif;
	}

    public function ftp_dir($dir)
    {
		if(!$this->connection) die("There are no connection!");
	    if (ftp_mkdir($this->conn, $dir))
        {
	      return true;
	    } else
        {
	     	return false;
            $this->code = "Error occured during la creacion del directorio $dir.";
	    }
    }

	/**
	 * Ths function can help you to use very easy this stuff!
	 *
	 * @param read_more upload_file()
	 * @param read_more upload_file()
	 */
	public function upload_process($upload_file, $newfilename=false) {
		$result = $this->upload_file($upload_file, $newfilename);
		if($result == false) {
			$this->code = "Error occured during the upload.";
		}
		else if($result == NULL){
			$this->code =  "File not exists!<br />\n";
		}
		else {
			$this->code = "200";
		}
	}

	/**
	 * This function logout from server and close the ftp connection.
	 */
	function close_connection() {
		$this->connection = false;
		@ftp_quit($this->conn);
	}

	/**
	 * Close the connection at end of class so not need to close manually
	 */
	function __destruct() {
		$this->close_connection();
	}

}

?>