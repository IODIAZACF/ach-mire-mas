00001 <?php
00002 // vim: set et ts=4 sw=4 fdm=marker:
00003 // +----------------------------------------------------------------------+
00004 // | PHP versions 4 and 5                                                 |
00005 // +----------------------------------------------------------------------+
00006 // | Copyright (c) 1998-2006 Manuel Lemos, Tomas V.V.Cox,                 |
00007 // | Stig. S. Bakken, Lukas Smith, Lorenzo Alberton                       |
00008 // | All rights reserved.                                                 |
00009 // +----------------------------------------------------------------------+
00010 // | MDB2 is a merge of PEAR DB and Metabases that provides a unified DB  |
00011 // | API as well as database abstraction for PHP applications.            |
00012 // | This LICENSE is in the BSD license style.                            |
00013 // |                                                                      |
00014 // | Redistribution and use in source and binary forms, with or without   |
00015 // | modification, are permitted provided that the following conditions   |
00016 // | are met:                                                             |
00017 // |                                                                      |
00018 // | Redistributions of source code must retain the above copyright       |
00019 // | notice, this list of conditions and the following disclaimer.        |
00020 // |                                                                      |
00021 // | Redistributions in binary form must reproduce the above copyright    |
00022 // | notice, this list of conditions and the following disclaimer in the  |
00023 // | documentation and/or other materials provided with the distribution. |
00024 // |                                                                      |
00025 // | Neither the name of Manuel Lemos, Tomas V.V.Cox, Stig. S. Bakken,    |
00026 // | Lukas Smith nor the names of his contributors may be used to endorse |
00027 // | or promote products derived from this software without specific prior|
00028 // | written permission.                                                  |
00029 // |                                                                      |
00030 // | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS  |
00031 // | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT    |
00032 // | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS    |
00033 // | FOR A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE      |
00034 // | REGENTS OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,          |
00035 // | INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, |
00036 // | BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS|
00037 // |  OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED  |
00038 // | AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT          |
00039 // | LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY|
00040 // | WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE          |
00041 // | POSSIBILITY OF SUCH DAMAGE.                                          |
00042 // +----------------------------------------------------------------------+
00043 // | Author: Lorenzo Alberton <l.alberton@quipo.it>                       |
00044 // +----------------------------------------------------------------------+
00045 //
00046 // $Id: ibase.php,v 1.183 2006/07/22 08:01:56 lsmith Exp $
00047
00055 class MDB2_Driver_ibase extends MDB2_Driver_Common
00056 {
00057     // {{{ properties
00058     var $escape_quotes = "'";
00059
00060     var $escape_pattern = "\\";
00061
00062     var $escape_identifier = '';
00063
00064     var $transaction_id = 0;
00065
00066     var $query_parameters = array();
00067     var $query_parameter_values = array();
00068
00069     // }}}
00070     // {{{ constructor
00071
00075     function __construct()
00076     {
00077         parent::__construct();
00078
00079         $this->phptype  = 'ibase';
00080         $this->dbsyntax = 'ibase';
00081
00082         $this->supported['sequences'] = true;
00083         $this->supported['indexes'] = true;
00084         $this->supported['affected_rows'] = function_exists('ibase_affected_rows');
00085         $this->supported['summary_functions'] = true;
00086         $this->supported['order_by_text'] = true;
00087         $this->supported['transactions'] = true;
00088         $this->supported['savepoints'] = true;
00089         $this->supported['current_id'] = true;
00090         $this->supported['limit_queries'] = 'emulated';
00091         $this->supported['LOBs'] = true;
00092         $this->supported['replace'] = false;
00093         $this->supported['sub_selects'] = true;
00094         $this->supported['auto_increment'] = true;
00095         $this->supported['primary_key'] = true;
00096         $this->supported['result_introspection'] = true;
00097         $this->supported['prepared_statements'] = true;
00098         $this->supported['identifier_quoting'] = false;
00099         $this->supported['pattern_escaping'] = true;
00100
00101         $this->options['DBA_username'] = false;
00102         $this->options['DBA_password'] = false;
00103         $this->options['database_path'] = '';
00104         $this->options['database_extension'] = '.gdb';
00105         $this->options['server_version'] = '';
00106     }
00107
00108     // }}}
00109     // {{{ errorInfo()
00110
00118     function errorInfo($error = null)
00119     {
00120         $native_msg = @ibase_errmsg();
00121
00122         if (function_exists('ibase_errcode')) {
00123             $native_code = @ibase_errcode();
00124         } else {
00125             // memo for the interbase php module hackers: we need something similar
00126             // to mysql_errno() to retrieve error codes instead of this ugly hack
00127             if (preg_match('/^([^0-9\-]+)([0-9\-]+)\s+(.*)$/', $native_msg, $m)) {
00128                 $native_code = (int)$m[2];
00129             } else {
00130                 $native_code = null;
00131             }
00132         }
00133         if (is_null($error)) {
00134             $error = MDB2_ERROR;
00135             if ($native_code) {
00136                 // try to interpret Interbase error code (that's why we need ibase_errno()
00137                 // in the interbase module to return the real error code)
00138                 switch ($native_code) {
00139                 case -204:
00140                     if (isset($m[3]) && is_int(strpos($m[3], 'Table unknown'))) {
00141                         $errno = MDB2_ERROR_NOSUCHTABLE;
00142                     }
00143                 break;
00144                 default:
00145                     static $ecode_map;
00146                     if (empty($ecode_map)) {
00147                         $ecode_map = array(
00148                             -104 => MDB2_ERROR_SYNTAX,
00149                             -150 => MDB2_ERROR_ACCESS_VIOLATION,
00150                             -151 => MDB2_ERROR_ACCESS_VIOLATION,
00151                             -155 => MDB2_ERROR_NOSUCHTABLE,
00152                             -157 => MDB2_ERROR_NOSUCHFIELD,
00153                             -158 => MDB2_ERROR_VALUE_COUNT_ON_ROW,
00154                             -170 => MDB2_ERROR_MISMATCH,
00155                             -171 => MDB2_ERROR_MISMATCH,
00156                             -172 => MDB2_ERROR_INVALID,
00157                             // -204 =>  // Covers too many errors, need to use regex on msg
00158                             -205 => MDB2_ERROR_NOSUCHFIELD,
00159                             -206 => MDB2_ERROR_NOSUCHFIELD,
00160                             -208 => MDB2_ERROR_INVALID,
00161                             -219 => MDB2_ERROR_NOSUCHTABLE,
00162                             -297 => MDB2_ERROR_CONSTRAINT,
00163                             -303 => MDB2_ERROR_INVALID,
00164                             -413 => MDB2_ERROR_INVALID_NUMBER,
00165                             -530 => MDB2_ERROR_CONSTRAINT,
00166                             -551 => MDB2_ERROR_ACCESS_VIOLATION,
00167                             -552 => MDB2_ERROR_ACCESS_VIOLATION,
00168                             // -607 =>  // Covers too many errors, need to use regex on msg
00169                             -625 => MDB2_ERROR_CONSTRAINT_NOT_NULL,
00170                             -803 => MDB2_ERROR_CONSTRAINT,
00171                             -804 => MDB2_ERROR_VALUE_COUNT_ON_ROW,
00172                             -904 => MDB2_ERROR_CONNECT_FAILED,
00173                             -922 => MDB2_ERROR_NOSUCHDB,
00174                             -923 => MDB2_ERROR_CONNECT_FAILED,
00175                             -924 => MDB2_ERROR_CONNECT_FAILED
00176                         );
00177                     }
00178                     if (isset($ecode_map[$native_code])) {
00179                         $error = $ecode_map[$native_code];
00180                     }
00181                     break;
00182                 }
00183             } else {
00184                 static $error_regexps;
00185                 if (!isset($error_regexps)) {
00186                     $error_regexps = array(
00187                         '/generator .* is not defined/'
00188                             => MDB2_ERROR_SYNTAX,  // for compat. w ibase_errcode()
00189                         '/table.*(not exist|not found|unknown)/i'
00190                             => MDB2_ERROR_NOSUCHTABLE,
00191                         '/table .* already exists/i'
00192                             => MDB2_ERROR_ALREADY_EXISTS,
00193                         '/unsuccessful metadata update .* failed attempt to store duplicate value/i'
00194                             => MDB2_ERROR_ALREADY_EXISTS,
00195                         '/unsuccessful metadata update .* not found/i'
00196                             => MDB2_ERROR_NOT_FOUND,
00197                         '/validation error for column .* value "\*\*\* null/i'
00198                             => MDB2_ERROR_CONSTRAINT_NOT_NULL,
00199                         '/violation of [\w ]+ constraint/i'
00200                             => MDB2_ERROR_CONSTRAINT,
00201                         '/conversion error from string/i'
00202                             => MDB2_ERROR_INVALID_NUMBER,
00203                         '/no permission for/i'
00204                             => MDB2_ERROR_ACCESS_VIOLATION,
00205                         '/arithmetic exception, numeric overflow, or string truncation/i'
00206                             => MDB2_ERROR_INVALID,
00207                     );
00208                 }
00209                 foreach ($error_regexps as $regexp => $code) {
00210                     if (preg_match($regexp, $native_msg, $m)) {
00211                         $error = $code;
00212                         break;
00213                     }
00214                 }
00215             }
00216         }
00217         return array($error, $native_code, $native_msg);
00218     }
00219
00220     // }}}
00221     // {{{ quoteIdentifier()
00222
00233     function quoteIdentifier($str, $check_option = false)
00234     {
00235         if ($check_option && !$this->options['quote_identifier']) {
00236             return $str;
00237         }
00238         return strtoupper($str);
00239     }
00240
00241     // }}}
00242     // {{{ getConnection()
00243
00251     function getConnection()
00252     {
00253         $result = $this->connect();
00254         if (PEAR::isError($result)) {
00255             return $result;
00256         }
00257         if ($this->in_transaction) {
00258             return $this->transaction_id;
00259         }
00260         return $this->connection;
00261     }
00262
00263     // }}}
00264     // {{{ beginTransaction()
00265
00274     function beginTransaction($savepoint = null)
00275     {
00276         $this->debug('Starting transaction/savepoint', __FUNCTION__, array('is_manip' => true, 'savepoint' => $savepoint));
00277         if (!is_null($savepoint)) {
00278             if (!$this->in_transaction) {
00279                 return $this->raiseError(MDB2_ERROR_INVALID, null, null,
00280                     'savepoint cannot be released when changes are auto committed', __FUNCTION__);
00281             }
00282             $query = 'SAVEPOINT '.$savepoint;
00283             return $this->_doQuery($query, true);
00284         } elseif ($this->in_transaction) {
00285             return MDB2_OK;  //nothing to do
00286         }
00287         $connection = $this->getConnection();
00288         if (PEAR::isError($connection)) {
00289             return $connection;
00290         }
00291         $result = @ibase_trans(IBASE_DEFAULT, $connection);
00292         if (!$result) {
00293             return $this->raiseError(null, null, null,
00294                 'could not start a transaction', __FUNCTION__);
00295         }
00296         $this->transaction_id = $result;
00297         $this->in_transaction = true;
00298         return MDB2_OK;
00299     }
00300
00301     // }}}
00302     // {{{ commit()
00303
00315     function commit($savepoint = null)
00316     {
00317         $this->debug('Committing transaction/savepoint', __FUNCTION__, array('is_manip' => true, 'savepoint' => $savepoint));
00318         if (!$this->in_transaction) {
00319             return $this->raiseError(MDB2_ERROR_INVALID, null, null,
00320                 'commit/release savepoint cannot be done changes are auto committed', __FUNCTION__);
00321         }
00322         if (!is_null($savepoint)) {
00323             $query = 'RELEASE SAVEPOINT '.$savepoint;
00324             return $this->_doQuery($query, true);
00325         }
00326
00327         if (!@ibase_commit($this->transaction_id)) {
00328             return $this->raiseError(null, null, null,
00329                 'could not commit a transaction', __FUNCTION__);
00330         }
00331         $this->in_transaction = false;
00332         $this->transaction_id = 0;
00333         return MDB2_OK;
00334     }
00335
00336     // }}}
00337     // {{{ rollback()
00338
00350     function rollback($savepoint = null)
00351     {
00352         $this->debug('Rolling back transaction/savepoint', __FUNCTION__, array('is_manip' => true, 'savepoint' => $savepoint));
00353         if (!$this->in_transaction) {
00354             return $this->raiseError(MDB2_ERROR_INVALID, null, null,
00355                 'rollback cannot be done changes are auto committed', __FUNCTION__);
00356         }
00357         if (!is_null($savepoint)) {
00358             $query = 'ROLLBACK TO SAVEPOINT '.$savepoint;
00359             return $this->_doQuery($query, true);
00360         }
00361
00362         if ($this->transaction_id && !@ibase_rollback($this->transaction_id)) {
00363             return $this->raiseError(null, null, null,
00364                 'Could not rollback a pending transaction: '.@ibase_errmsg(), __FUNCTION__);
00365         }
00366         $this->in_transaction = false;
00367         $this->transaction_id = 0;
00368         return MDB2_OK;
00369     }
00370
00371     // }}}
00372     // {{{ setTransactionIsolation()
00373
00390     function setTransactionIsolation($isolation, $options = array())
00391     {
00392         $this->debug('Setting transaction isolation level', __FUNCTION__, array('is_manip' => true));
00393         switch ($isolation) {
00394         case 'READ UNCOMMITTED':
00395             $ibase_isolation = 'READ COMMITTED RECORD_VERSION';
00396             break;
00397         case 'READ COMMITTED':
00398             $ibase_isolation = 'READ COMMITTED NO RECORD_VERSION';
00399             break;
00400         case 'REPEATABLE READ':
00401             $ibase_isolation = 'SNAPSHOT';
00402             break;
00403         case 'SERIALIZABLE':
00404             $ibase_isolation = 'SNAPSHOT TABLE STABILITY';
00405             break;
00406         default:
00407             return $this->raiseError(MDB2_ERROR_UNSUPPORTED, null, null,
00408                 'isolation level is not supported: '.$isolation, __FUNCTION__);
00409         }
00410
00411         if (!empty($options['wait'])) {
00412             switch ($options['wait']) {
00413             case 'WAIT':
00414             case 'NO WAIT':
00415                 $wait = $options['wait'];
00416                 break;
00417             default:
00418                 return $this->raiseError(MDB2_ERROR_UNSUPPORTED, null, null,
00419                     'wait option is not supported: '.$options['wait'], __FUNCTION__);
00420             }
00421         }
00422
00423         if (!empty($options['rw'])) {
00424             switch ($options['rw']) {
00425             case 'READ ONLY':
00426             case 'READ WRITE':
00427                 $rw = $options['wait'];
00428                 break;
00429             default:
00430                 return $this->raiseError(MDB2_ERROR_UNSUPPORTED, null, null,
00431                     'wait option is not supported: '.$options['rw'], __FUNCTION__);
00432             }
00433         }
00434
00435         $query = "SET TRANSACTION $rw $wait ISOLATION LEVEL $ibase_isolation";
00436         return $this->_doQuery($query, true);
00437     }
00438
00439     // }}}
00440     // {{{ getDatabaseFile($database_name)
00441
00448     function _getDatabaseFile($database_name)
00449     {
00450         if ($database_name == '') {
00451             return $database_name;
00452         }
00453         $ret = $this->options['database_path'] . $database_name;
00454         if (   strpos($database_name, '.fdb') === false
00455             && strpos($database_name, '.gdb') === false
00456         ) {
00457             $ret .= $this->options['database_extension'];
00458         }
00459         return $ret;
00460     }
00461
00462     // }}}
00463     // {{{ _doConnect()
00464
00471     function _doConnect($database_name, $persistent = false)
00472     {
00473         $user    = $this->dsn['username'];
00474         $pw      = $this->dsn['password'];
00475         $dbhost  = $this->dsn['hostspec'] ?
00476             ($this->dsn['hostspec'].':'.$database_name) : $database_name;
00477
00478         $params = array();
00479         $params[] = $dbhost;
00480         $params[] = !empty($user) ? $user : null;
00481         $params[] = !empty($pw) ? $pw : null;
00482         $params[] = isset($this->dsn['charset']) ? $this->dsn['charset'] : null;
00483         $params[] = isset($this->dsn['buffers']) ? $this->dsn['buffers'] : null;
00484         $params[] = isset($this->dsn['dialect']) ? $this->dsn['dialect'] : null;
00485         $params[] = isset($this->dsn['role'])    ? $this->dsn['role'] : null;
00486
00487         $connect_function = $persistent ? 'ibase_pconnect' : 'ibase_connect';
00488
00489         $connection = @call_user_func_array($connect_function, $params);
00490         if ($connection <= 0) {
00491             return $this->raiseError(MDB2_ERROR_CONNECT_FAILED, null, null,
00492                 'unable to establish a connection', __FUNCTION__);
00493         }
00494
00495         if (function_exists('ibase_timefmt')) {
00496             @ibase_timefmt("%Y-%m-%d %H:%M:%S", IBASE_TIMESTAMP);
00497             @ibase_timefmt("%Y-%m-%d", IBASE_DATE);
00498         } else {
00499             @ini_set("ibase.timestampformat", "%Y-%m-%d %H:%M:%S");
00500             //@ini_set("ibase.timeformat", "%H:%M:%S");
00501             @ini_set("ibase.dateformat", "%Y-%m-%d");
00502         }
00503
00504         return $connection;
00505     }
00506
00507     // }}}
00508     // {{{ connect()
00509
00516     function connect()
00517     {
00518         $database_file = $this->_getDatabaseFile($this->database_name);
00519         if (is_resource($this->connection)) {
00520             if (count(array_diff($this->connected_dsn, $this->dsn)) == 0
00521                 && $this->connected_database_name == $database_file
00522                 && $this->opened_persistent == $this->options['persistent']
00523             ) {
00524                 return MDB2_OK;
00525             }
00526             $this->disconnect(false);
00527         }
00528
00529         if (!PEAR::loadExtension('interbase')) {
00530             return $this->raiseError(MDB2_ERROR_NOT_FOUND, null, null,
00531                 'extension '.$this->phptype.' is not compiled into PHP', __FUNCTION__);
00532         }
00533
00534         if (!empty($this->database_name)) {
00535             $connection = $this->_doConnect($database_file, $this->options['persistent']);
00536             if (PEAR::isError($connection)) {
00537                 return $connection;
00538             }
00539             $this->connection =& $connection;
00540             $this->connected_dsn = $this->dsn;
00541             $this->connected_database_name = $database_file;
00542             $this->opened_persistent = $this->options['persistent'];
00543             $this->dbsyntax = $this->dsn['dbsyntax'] ? $this->dsn['dbsyntax'] : $this->phptype;
00544             $this->supported['limit_queries'] = ($this->dbsyntax == 'firebird') ? true : 'emulated';
00545         }
00546         return MDB2_OK;
00547     }
00548
00549     // }}}
00550     // {{{ setCharset()
00551
00560     function setCharset($charset, $connection = null)
00561     {
00562         if (is_null($connection)) {
00563             $connection = $this->getConnection();
00564             if (PEAR::isError($connection)) {
00565                 return $connection;
00566             }
00567         }
00568
00569         $query = 'SET NAMES '.$this->quote($charset, 'text');
00570         $result = @ibase_query($connection, $query);
00571         if (!$result) {
00572             return $this->raiseError(null, null, null,
00573                 'Unable to set client charset: '.$charset, __FUNCTION__);
00574         }
00575
00576         return MDB2_OK;
00577     }
00578
00579     // }}}
00580     // {{{ disconnect()
00581
00591     function disconnect($force = true)
00592     {
00593         if (is_resource($this->connection)) {
00594             if ($this->in_transaction) {
00595                 $dsn = $this->dsn;
00596                 $database_name = $this->database_name;
00597                 $persistent = $this->options['persistent'];
00598                 $this->dsn = $this->connected_dsn;
00599                 $this->database_name = $this->connected_database_name;
00600                 $this->options['persistent'] = $this->opened_persistent;
00601                 $this->rollback();
00602                 $this->dsn = $dsn;
00603                 $this->database_name = $database_name;
00604                 $this->options['persistent'] = $persistent;
00605             }
00606
00607             if (!$this->opened_persistent || $force) {
00608                 @ibase_close($this->connection);
00609             }
00610         }
00611         return parent::disconnect($force);
00612     }
00613
00614     // }}}
00615     // {{{ _doQuery()
00616
00626     function &_doQuery($query, $is_manip = false, $connection = null, $database_name = null)
00627     {
00628         $this->last_query = $query;
00629         $result = $this->debug($query, 'query', array('is_manip' => $is_manip, 'when' => 'pre'));
00630         if ($result) {
00631             if (PEAR::isError($result)) {
00632                 return $result;
00633             }
00634             $query = $result;
00635         }
00636         if ($this->getOption('disable_query')) {
00637             if ($is_manip) {
00638                 return 0;
00639             }
00640             return null;
00641         }
00642
00643         if (is_null($connection)) {
00644             $connection = $this->getConnection();
00645             if (PEAR::isError($connection)) {
00646                 return $connection;
00647             }
00648         }
00649         $result = @ibase_query($connection, $query);
00650
00651         if ($result === false) {
00652             $err =& $this->raiseError(null, null, null,
00653                 'Could not execute statement', __FUNCTION__);
00654             return $err;
00655         }
00656
00657         $this->debug($query, 'query', array('is_manip' => $is_manip, 'when' => 'post', 'result' => $result));
00658         return $result;
00659     }
00660
00661     // }}}
00662     // {{{ _affectedRows()
00663
00672     function _affectedRows($connection, $result = null)
00673     {
00674         if (is_null($connection)) {
00675             $connection = $this->getConnection();
00676             if (PEAR::isError($connection)) {
00677                 return $connection;
00678             }
00679         }
00680         return (function_exists('ibase_affected_rows') ? @ibase_affected_rows($connection) : 0);
00681     }
00682
00683     // }}}
00684     // {{{ _modifyQuery()
00685
00696     function _modifyQuery($query, $is_manip, $limit, $offset)
00697     {
00698         if ($limit > 0 && $this->supports('limit_queries') === true) {
00699             $query = preg_replace('/^([\s(])*SELECT(?!\s*FIRST\s*\d+)/i',
00700                 "SELECT FIRST $limit SKIP $offset", $query);
00701         }
00702         return $query;
00703     }
00704
00705     // }}}
00706     // {{{ getServerVersion()
00707
00715     function getServerVersion($native = false)
00716     {
00717         $server_info = false;
00718         if ($this->connected_server_info) {
00719             $server_info = $this->connected_server_info;
00720         } elseif ($this->options['server_version']) {
00721             $server_info = $this->options['server_version'];
00722         } elseif ($this->options['DBA_username']) {
00723             $ibserv = @ibase_service_attach($this->dsn['hostspec'], $this->options['DBA_username'], $this->options['DBA_password']);
00724             $server_info = @ibase_server_info($ibserv, IBASE_SVC_SERVER_VERSION);
00725             @ibase_service_detach($ibserv);
00726         }
00727         if (!$server_info) {
00728             return $this->raiseError(MDB2_ERROR_UNSUPPORTED, null, null,
00729                 'Requires either "server_version" or "DBA_username"/"DBA_password" option', __FUNCTION__);
00730         }
00731         // cache server_info
00732         $this->connected_server_info = $server_info;
00733         if (!$native) {
00734             //WI-V1.5.3.4854 Firebird 1.5
00735             if (!preg_match('/-V([\d\.]*)/', $server_info, $matches)) {
00736                 return $this->raiseError(MDB2_ERROR_INVALID, null, null,
00737                     'Could not parse version information:'.$server_info, __FUNCTION__);
00738             }
00739             $tmp = explode('.', $matches[1], 4);
00740             $server_info = array(
00741                 'major' => isset($tmp[0]) ? $tmp[0] : null,
00742                 'minor' => isset($tmp[1]) ? $tmp[1] : null,
00743                 'patch' => isset($tmp[2]) ? $tmp[2] : null,
00744                 'extra' => isset($tmp[3]) ? $tmp[3] : null,
00745                 'native' => $server_info,
00746             );
00747         }
00748         return $server_info;
00749     }
00750
00751     // }}}
00752     // {{{ prepare()
00753
00774     function &prepare($query, $types = null, $result_types = null, $lobs = array())
00775     {
00776         if ($this->options['emulate_prepared']) {
00777             $obj =& parent::prepare($query, $types, $result_types, $lobs);
00778             return $obj;
00779         }
00780         $is_manip = ($result_types === MDB2_PREPARE_MANIP);
00781         $offset = $this->offset;
00782         $limit  = $this->limit;
00783         $this->offset = $this->limit = 0;
00784         $result = $this->debug($query, __FUNCTION__, array('is_manip' => $is_manip, 'when' => 'pre'));
00785         if ($result) {
00786             if (PEAR::isError($result)) {
00787                 return $result;
00788             }
00789             $query = $result;
00790         }
00791         $placeholder_type_guess = $placeholder_type = null;
00792         $question = '?';
00793         $colon = ':';
00794         $positions = array();
00795         $position = 0;
00796         while ($position < strlen($query)) {
00797             $q_position = strpos($query, $question, $position);
00798             $c_position = strpos($query, $colon, $position);
00799             if ($q_position && $c_position) {
00800                 $p_position = min($q_position, $c_position);
00801             } elseif ($q_position) {
00802                 $p_position = $q_position;
00803             } elseif ($c_position) {
00804                 $p_position = $c_position;
00805             } else {
00806                 break;
00807             }
00808             if (is_null($placeholder_type)) {
00809                 $placeholder_type_guess = $query[$p_position];
00810             }
00811             if (is_int($quote = strpos($query, "'", $position)) && $quote < $p_position) {
00812                 if (!is_int($end_quote = strpos($query, "'", $quote + 1))) {
00813                     $err =& $this->raiseError(MDB2_ERROR_SYNTAX, null, null,
00814                         'query with an unterminated text string specified', __FUNCTION__);
00815                     return $err;
00816                 }
00817                 switch ($this->escape_quotes) {
00818                 case '':
00819                 case "'":
00820                     $position = $end_quote + 1;
00821                     break;
00822                 default:
00823                     if ($end_quote == $quote + 1) {
00824                         $position = $end_quote + 1;
00825                     } else {
00826                         if ($query[$end_quote-1] == $this->escape_quotes) {
00827                             $position = $end_quote;
00828                         } else {
00829                             $position = $end_quote + 1;
00830                         }
00831                     }
00832                     break;
00833                 }
00834             } elseif ($query[$position] == $placeholder_type_guess) {
00835                 if (is_null($placeholder_type)) {
00836                     $placeholder_type = $query[$p_position];
00837                     $question = $colon = $placeholder_type;
00838                 }
00839                 if ($placeholder_type == ':') {
00840                     $parameter = preg_replace('/^.{'.($position+1).'}([a-z0-9_]+).*$/si', '\\1', $query);
00841                     if ($parameter === '') {
00842                         $err =& $this->raiseError(MDB2_ERROR_SYNTAX, null, null,
00843                             'named parameter with an empty name', __FUNCTION__);
00844                         return $err;
00845                     }
00846                     $positions[$parameter] = $p_position;
00847                     $query = substr_replace($query, '?', $position, strlen($parameter)+1);
00848                 } else {
00849                     $positions[] = $p_position;
00850                 }
00851                 $position = $p_position + 1;
00852             } else {
00853                 $position = $p_position;
00854             }
00855         }
00856         $connection = $this->getConnection();
00857         if (PEAR::isError($connection)) {
00858             return $connection;
00859         }
00860         $statement = @ibase_prepare($connection, $query);
00861         if (!$statement) {
00862             $err =& $this->raiseError(null, null, null,
00863                 'Could not create statement', __FUNCTION__);
00864             return $err;
00865         }
00866
00867         $class_name = 'MDB2_Statement_'.$this->phptype;
00868         $obj =& new $class_name($this, $statement, $positions, $query, $types, $result_types, $is_manip, $limit, $offset);
00869         $this->debug($query, __FUNCTION__, array('is_manip' => $is_manip, 'when' => 'post', 'result' => $obj));
00870         return $obj;
00871     }
00872
00873     // }}}
00874     // {{{ getSequenceName()
00875
00883     function getSequenceName($sqn)
00884     {
00885         return strtoupper(parent::getSequenceName($sqn));
00886     }
00887
00888     // }}}
00889     // {{{ nextID()
00890
00901     function nextID($seq_name, $ondemand = true)
00902     {
00903         $sequence_name = $this->getSequenceName($seq_name);
00904         $query = 'SELECT GEN_ID('.$sequence_name.', 1) as the_value FROM RDB$DATABASE';
00905         $this->expectError('*');
00906         $result = $this->queryOne($query, 'integer');
00907         $this->popExpect();
00908         if (PEAR::isError($result)) {
00909             if ($ondemand) {
00910                 $this->loadModule('Manager', null, true);
00911                 // Since we are creating the sequence on demand
00912                 // we know the first id = 1 so initialize the
00913                 // sequence at 2
00914                 $result = $this->manager->createSequence($seq_name, 2);
00915                 if (PEAR::isError($result)) {
00916                     return $this->raiseError($result, null, null,
00917                         'on demand sequence could not be created', __FUNCTION__);
00918                 } else {
00919                     // First ID of a newly created sequence is 1
00920                     // return 1;
00921                     // BUT generators are not always reset, so return the actual value
00922                     return $this->currID($seq_name);
00923                 }
00924             }
00925         }
00926         return $result;
00927     }
00928
00929     // }}}
00930     // {{{ currID()
00931
00939     function currID($seq_name)
00940     {
00941         $sequence_name = $this->getSequenceName($seq_name);
00942         $query = 'SELECT GEN_ID('.$sequence_name.', 0) as the_value FROM RDB$DATABASE';
00943         $value = $this->queryOne($query);
00944         if (PEAR::isError($value)) {
00945             return $this->raiseError($result, null, null,
00946                 'Unable to select from ' . $seq_name, __FUNCTION__);
00947         }
00948         if (!is_numeric($value)) {
00949             return $this->raiseError(MDB2_ERROR, null, null,
00950                 'could not find value in sequence table', __FUNCTION__);
00951         }
00952         return $value;
00953     }
00954
00955     // }}}
00956 }
00957
00965 class MDB2_Result_ibase extends MDB2_Result_Common
00966 {
00967     // {{{ _skipLimitOffset()
00968
00976     function _skipLimitOffset()
00977     {
00978         if ($this->db->supports('limit_queries') === true) {
00979             return true;
00980         }
00981         if ($this->limit) {
00982             if ($this->rownum > $this->limit) {
00983                 return false;
00984             }
00985         }
00986         if ($this->offset) {
00987             while ($this->offset_count < $this->offset) {
00988                 ++$this->offset_count;
00989                 if (!is_array(@ibase_fetch_row($this->result))) {
00990                     $this->offset_count = $this->offset;
00991                     return false;
00992                 }
00993             }
00994         }
00995         return true;
00996     }
00997
00998     // }}}
00999     // {{{ fetchRow()
01000
01009     function &fetchRow($fetchmode = MDB2_FETCHMODE_DEFAULT, $rownum = null)
01010     {
01011         if ($this->result === true) {
01012             //query successfully executed, but without results...
01013             $null = null;
01014             return $null;
01015         }
01016         if (!$this->_skipLimitOffset()) {
01017             $null = null;
01018             return $null;
01019         }
01020         if (!is_null($rownum)) {
01021             $seek = $this->seek($rownum);
01022             if (PEAR::isError($seek)) {
01023                 return $seek;
01024             }
01025         }
01026         if ($fetchmode == MDB2_FETCHMODE_DEFAULT) {
01027             $fetchmode = $this->db->fetchmode;
01028         }
01029         if ($fetchmode & MDB2_FETCHMODE_ASSOC) {
01030             $row = @ibase_fetch_assoc($this->result);
01031             if (is_array($row)
01032                 && $this->db->options['portability'] & MDB2_PORTABILITY_FIX_CASE
01033             ) {
01034                 $row = array_change_key_case($row, $this->db->options['field_case']);
01035             }
01036         } else {
01037             $row = @ibase_fetch_row($this->result);
01038         }
01039         if (!$row) {
01040             if ($this->result === false) {
01041                 $err =& $this->db->raiseError(MDB2_ERROR_NEED_MORE_DATA, null, null,
01042                     'resultset has already been freed', __FUNCTION__);
01043                 return $err;
01044             }
01045             $null = null;
01046             return $null;
01047         }
01048         if (($mode = ($this->db->options['portability'] & MDB2_PORTABILITY_RTRIM)
01049             + ($this->db->options['portability'] & MDB2_PORTABILITY_EMPTY_TO_NULL))
01050         ) {
01051             $this->db->_fixResultArrayValues($row, $mode);
01052         }
01053         if (!empty($this->values)) {
01054             $this->_assignBindColumns($row);
01055         }
01056         if (!empty($this->types)) {
01057             $row = $this->db->datatype->convertResultRow($this->types, $row);
01058         }
01059         if ($fetchmode === MDB2_FETCHMODE_OBJECT) {
01060             $object_class = $this->db->options['fetch_class'];
01061             if ($object_class == 'stdClass') {
01062                 $row = (object) $row;
01063             } else {
01064                 $row = &new $object_class($row);
01065             }
01066         }
01067         ++$this->rownum;
01068         return $row;
01069     }
01070
01071     // }}}
01072     // {{{ _getColumnNames()
01073
01083     function _getColumnNames()
01084     {
01085         $columns = array();
01086         $numcols = $this->numCols();
01087         if (PEAR::isError($numcols)) {
01088             return $numcols;
01089         }
01090         for ($column = 0; $column < $numcols; $column++) {
01091             $column_info = @ibase_field_info($this->result, $column);
01092             $columns[$column_info['alias']] = $column;
01093         }
01094         if ($this->db->options['portability'] & MDB2_PORTABILITY_FIX_CASE) {
01095             $columns = array_change_key_case($columns, $this->db->options['field_case']);
01096         }
01097         return $columns;
01098     }
01099
01100     // }}}
01101     // {{{ numCols()
01102
01110     function numCols()
01111     {
01112         if ($this->result === true) {
01113             //query successfully executed, but without results...
01114             return 0;
01115         }
01116
01117         if (!is_resource($this->result)) {
01118             return $this->db->raiseError(MDB2_ERROR_NOT_FOUND, null, null,
01119                 'numCols(): not a valid ibase resource', __FUNCTION__);
01120         }
01121         $cols = @ibase_num_fields($this->result);
01122         if (is_null($cols)) {
01123             if ($this->result === false) {
01124                 return $this->db->raiseError(MDB2_ERROR_NEED_MORE_DATA, null, null,
01125                     'resultset has already been freed', __FUNCTION__);
01126             } elseif (is_null($this->result)) {
01127                 return count($this->types);
01128             }
01129             return $this->db->raiseError(null, null, null,
01130                 'Could not get column count', __FUNCTION__);
01131         }
01132         return $cols;
01133     }
01134
01135     // }}}
01136     // {{{ free()
01137
01144     function free()
01145     {
01146         if (is_resource($this->result) && $this->db->connection) {
01147             $free = @ibase_free_result($this->result);
01148             if ($free === false) {
01149                 return $this->db->raiseError(null, null, null,
01150                     'Could not free result', __FUNCTION__);
01151             }
01152         }
01153         $this->result = false;
01154         return MDB2_OK;
01155     }
01156
01157     // }}}
01158 }
01159
01167 class MDB2_BufferedResult_ibase extends MDB2_Result_ibase
01168 {
01169     // {{{ class vars
01170
01171     var $buffer;
01172     var $buffer_rownum = - 1;
01173
01174     // }}}
01175     // {{{ _fillBuffer()
01176
01185     function _fillBuffer($rownum = null)
01186     {
01187         if (isset($this->buffer) && is_array($this->buffer)) {
01188             if (is_null($rownum)) {
01189                 if (!end($this->buffer)) {
01190                     return false;
01191                 }
01192             } elseif (isset($this->buffer[$rownum])) {
01193                 return (bool) $this->buffer[$rownum];
01194             }
01195         }
01196
01197         if (!$this->_skipLimitOffset()) {
01198             return false;
01199         }
01200
01201         $buffer = true;
01202         while ((is_null($rownum) || $this->buffer_rownum < $rownum)
01203             && (!$this->limit || $this->buffer_rownum < $this->limit)
01204             && ($buffer = @ibase_fetch_row($this->result))
01205         ) {
01206             ++$this->buffer_rownum;
01207             $this->buffer[$this->buffer_rownum] = $buffer;
01208         }
01209
01210         if (!$buffer) {
01211             ++$this->buffer_rownum;
01212             $this->buffer[$this->buffer_rownum] = false;
01213             return false;
01214         } elseif ($this->limit && $this->buffer_rownum >= $this->limit) {
01215             ++$this->buffer_rownum;
01216             $this->buffer[$this->buffer_rownum] = false;
01217         }
01218         return true;
01219     }
01220
01221     // }}}
01222     // {{{ fetchRow()
01223
01232     function &fetchRow($fetchmode = MDB2_FETCHMODE_DEFAULT, $rownum = null)
01233     {
01234         if ($this->result === true || is_null($this->result)) {
01235             //query successfully executed, but without results...
01236             $null = null;
01237             return $null;
01238         }
01239         if ($this->result === false) {
01240             $err =& $this->db->raiseError(MDB2_ERROR_NEED_MORE_DATA, null, null,
01241                 'resultset has already been freed', __FUNCTION__);
01242             return $err;
01243         }
01244         if (!is_null($rownum)) {
01245             $seek = $this->seek($rownum);
01246             if (PEAR::isError($seek)) {
01247                 return $seek;
01248             }
01249         }
01250         $target_rownum = $this->rownum + 1;
01251         if ($fetchmode == MDB2_FETCHMODE_DEFAULT) {
01252             $fetchmode = $this->db->fetchmode;
01253         }
01254         if (!$this->_fillBuffer($target_rownum)) {
01255             $null = null;
01256             return $null;
01257         }
01258         $row = $this->buffer[$target_rownum];
01259         if ($fetchmode & MDB2_FETCHMODE_ASSOC) {
01260             $column_names = $this->getColumnNames();
01261             foreach ($column_names as $name => $i) {
01262                 $column_names[$name] = $row[$i];
01263             }
01264             $row = $column_names;
01265         }
01266         if (($mode = ($this->db->options['portability'] & MDB2_PORTABILITY_RTRIM)
01267             + ($this->db->options['portability'] & MDB2_PORTABILITY_EMPTY_TO_NULL))
01268         ) {
01269             $this->db->_fixResultArrayValues($row, $mode);
01270         }
01271         if (!empty($this->values)) {
01272             $this->_assignBindColumns($row);
01273         }
01274         if (!empty($this->types)) {
01275             $row = $this->db->datatype->convertResultRow($this->types, $row);
01276         }
01277         if ($fetchmode === MDB2_FETCHMODE_OBJECT) {
01278             $object_class = $this->db->options['fetch_class'];
01279             if ($object_class == 'stdClass') {
01280                 $row = (object) $row;
01281             } else {
01282                 $row = &new $object_class($row);
01283             }
01284         }
01285         ++$this->rownum;
01286         return $row;
01287     }
01288
01289     // }}}
01290     // {{{ seek()
01291
01299     function seek($rownum = 0)
01300     {
01301         if ($this->result === false) {
01302             return $this->db->raiseError(MDB2_ERROR_NEED_MORE_DATA, null, null,
01303                 'resultset has already been freed', __FUNCTION__);
01304         }
01305         $this->rownum = $rownum - 1;
01306         return MDB2_OK;
01307     }
01308
01309     // }}}
01310     // {{{ valid()
01311
01318     function valid()
01319     {
01320         if ($this->result === false) {
01321             return $this->db->raiseError(MDB2_ERROR_NEED_MORE_DATA, null, null,
01322                 'resultset has already been freed', __FUNCTION__);
01323         } elseif (is_null($this->result)) {
01324             return true;
01325         }
01326         if ($this->_fillBuffer($this->rownum + 1)) {
01327             return true;
01328         }
01329         return false;
01330     }
01331
01332     // }}}
01333     // {{{ numRows()
01334
01341     function numRows()
01342     {
01343         if ($this->result === false) {
01344             return $this->db->raiseError(MDB2_ERROR_NEED_MORE_DATA, null, null,
01345                 'resultset has already been freed', __FUNCTION__);
01346         } elseif (is_null($this->result)) {
01347             return 0;
01348         }
01349         $this->_fillBuffer();
01350         return $this->buffer_rownum;
01351     }
01352
01353     // }}}
01354     // {{{ free()
01355
01362     function free()
01363     {
01364         $this->buffer = null;
01365         $this->buffer_rownum = null;
01366         return parent::free();
01367     }
01368
01369     // }}}
01370 }
01371
01379 class MDB2_Statement_ibase extends MDB2_Statement_Common
01380 {
01381     // {{{ _execute()
01382
01391     function &_execute($result_class = true, $result_wrap_class = false)
01392     {
01393         if (is_null($this->statement)) {
01394             $result =& parent::_execute($result_class, $result_wrap_class);
01395             return $result;
01396         }
01397         $this->db->last_query = $this->query;
01398         $this->db->debug($this->query, 'execute', array('is_manip' => $this->is_manip, 'when' => 'pre', 'parameters' => $this->values));
01399         if ($this->db->getOption('disable_query')) {
01400             if ($this->is_manip) {
01401                 $return = 0;
01402                 return $return;
01403             }
01404             $null = null;
01405             return $null;
01406         }
01407
01408         $connection = $this->db->getConnection();
01409         if (PEAR::isError($connection)) {
01410             return $connection;
01411         }
01412
01413         $parameters = array(0 => $this->statement);
01414         foreach ($this->positions as $parameter => $current_position) {
01415             if (!array_key_exists($parameter, $this->values)) {
01416                 return $this->db->raiseError(MDB2_ERROR_NOT_FOUND, null, null,
01417                     'Unable to bind to missing placeholder: '.$parameter, __FUNCTION__);
01418             }
01419             $value = $this->values[$parameter];
01420             $type = !empty($this->types[$parameter]) ? $this->types[$parameter] : null;
01421             $parameters[] = $this->db->quote($value, $type, false);
01422         }
01423
01424         $result = @call_user_func_array('ibase_execute', $parameters);
01425         if ($result === false) {
01426             $err =& $this->db->raiseError(null, null, null,
01427                 'Could not execute statement', __FUNCTION__);
01428             return $err;
01429         }
01430
01431         if ($this->is_manip) {
01432             $affected_rows = $this->db->_affectedRows($connection);
01433             return $affected_rows;
01434         }
01435
01436         $result =& $this->db->_wrapResult($result, $this->result_types,
01437             $result_class, $result_wrap_class, $this->limit, $this->offset);
01438         $this->db->debug($this->query, 'execute', array('is_manip' => $this->is_manip, 'when' => 'post', 'result' => $result));
01439         return $result;
01440     }
01441
01442     // }}}
01443
01444     // }}}
01445     // {{{ free()
01446
01453     function free()
01454     {
01455         if (is_null($this->positions)) {
01456             return $this->db->raiseError(MDB2_ERROR, null, null,
01457                 'Prepared statement has already been freed', __FUNCTION__);
01458         }
01459         $result = MDB2_OK;
01460
01461         if (!is_null($this->statement) && !@ibase_free_query($this->statement)) {
01462             $result = $this->db->raiseError(null, null, null,
01463                 'Could not free statement', __FUNCTION__);
01464         }
01465
01466         parent::free();
01467         return $result;
01468     }
01469 }
01470 ?>

