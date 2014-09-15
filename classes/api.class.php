<?php

/**
 * Main api class
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Doodstrap
 */
class API {

    /**
     * Processes form data using callback functions
     * @param array $input Name of POST/GET variable with array of data
     * @param array $callbacks Associative array of lamda-functions, keys are keys of POST/GET variable array
     * @return array Processed array
     */
    function process_form_data($input, $callbacks) {
        $input = $this->getval($input, 'array');
        foreach ($callbacks as $k => $c) {
            $return[$k] = $c($input[$k]);
        }
        return $return;
    }

    /**
     * Load class into api
     * @param string $classname /classes/$classname.class.php Class to be loaded
     * Dies on false or continues execution 
     */
    function load_class($classname) {
        $file = $this->CONFIG['ROOT_PATH'] . 'classes' . DS . strtolower($classname) . '.class.php';

        if (!file_exists($file))
            die('Class file not found');
        require_once $file;
        $class = strtoupper($classname);
        $this->$class = new $classname($this);
    }

    /**
     * Converts unix timestamp to mysql date field
     * @param int $timestamp Unix timestamp
     * @return string Mysql timestamp
     */
    function mysql_date($timestamp) {
        return date('Y-m-d', $timestamp);
    }

    /**
     * Validates password length
     * @param string $pass Plaintext password
     * @return boolean True or false
     */
    function validate_password($pass) {
        return (strlen((string) $pass) >= 5 ? true : false);
    }

    /**
     * Generates pagination html code and SQL query LIMIT subquery
     * @param int $count How many records in DB
     * @param array $link Array to be used in pages link creation
     * @param int $perpage Elements limit per page
     * @return array Array of paginator, first is LIMIT subquery, second is html code of paginator
     */
    function generate_pagination($count, $link = array(), $perpage = 25) {

        $return = '<div class="boot" style="display: block;">
					<div id="moresearchtools-www" class="paginator">';

        $page = $this->getval('page', 'int');
        if (!$page || $page < 1)
            $page = 1;

        $page_computed = $page - 1;

        $pages = ceil($count / $perpage);
        $limit = "LIMIT " . ($page_computed * $perpage) . "," . ($perpage);
        //if ($_COOKIE['test']) var_dump($limit);
        /* if ($page_computed >= $pages)
          return array($limit, '');
          //var_dump($pages);
          if ($pages == 1) {
          $curlink = $link;
          $curlink[] = 'page';
          $curlink[] = $page;
          $return.= "<a class=\"previous inactive\" href=\"javascript://\">{$this->LANG->_('Prev')}</a><ul class=\"paginator\"><li class=\"inactive\"><a href=\"{$this->SEO->make_link($curlink)}\">1</a></li></ul><a class=\"next inactive\" href=\"javascript://\">{$this->LANG->_('Next')}</a>";
          } else {

          if ($page_computed != 0) {
          $prevlink = $link;
          $prevlink[] = 'page';
          $prevlink[] = $page - 1;
          $return.= "<a class=\"previous\" href=\"{$this->SEO->make_link($prevlink)}\">{$this->LANG->_('Prev')}</a><ul class=\"paginator\">";
          } else {
          $return.="<a class=\"previous inactive\" href=\"javascript://\">{$this->LANG->_('Prev')}</a><ul class=\"paginator\">";
          }

          $nextpages = $pages - $page_computed;
          if ($nextpages > 6)
          $nextpages = 6;
          $prevpages = ($page - 1);
          if ($prevpages > 5)
          $prevpages = 5;

          //die (var_dump($prevpages));
          for ($i = $prevpages; $i >= 1; $i-=1) {
          $curlink = $link;
          $curlink[] = 'page';
          $curlink[] = $page - $i;
          $return.="<li class=\"active\"><a href=\"{$this->SEO->make_link($curlink)}\">" . ($page - $i) . "</a></li>";
          }
          $curlink = $link;
          $curlink[] = 'page';
          $curlink[] = $page;
          $return.="<li class=\"inactive\"><a href=\"{$this->SEO->make_link($curlink)}\">{$page}</a></li>";

          for ($i = 1; $i < $nextpages; $i++) {
          $curlink = $link;
          $curlink[] = 'page';
          $curlink[] = $page + $i;
          $return.="<li class=\"active\"><a href=\"{$this->SEO->make_link($curlink)}\">" . ($page + $i) . "</a></li>";
          }

          if ($page + 1 < $pages) {
          $nextlink = $link;
          $nextlink[] = 'page';
          $nextlink[] = $page + 1;
          $return.= "</ul><a class=\"next\" href=\"{$this->SEO->make_link($nextlink)}\">{$this->LANG->_('Next')}</a>";
          } else
          $return.= "</ul><a class=\"next inactive\" href=\"javascript://\">{$this->LANG->_('Next')}</a>";
          }
         */


        $curlink = $link;
        $curlink[] = 'page';
        $curlink[] = $page;
        $nextlink = $link;
        $nextlink[] = 'page';
        $nextlink[] = $page + 1;
        $prevlink = $link;
        $prevlink[] = 'page';
        $prevlink[] = $page - 1;
        if ($page == 1) {
            $return.= "<a class=\"previous inactive\" href=\"javascript://\">{$this->LANG->_('Prev')}</a><ul class=\"paginator\"><li class=\"inactive\"><a href=\"{$this->SEO->make_link($curlink)}\">1</a></li></ul><a class=\"next\" href=\"{$this->SEO->make_link($nextlink)}\">{$this->LANG->_('Next')}</a>";
        } else {
            $return.= "<a class=\"previous\" href=\"{$this->SEO->make_link($prevlink)}\">{$this->LANG->_('Prev')}</a><ul class=\"paginator\"><li class=\"inactive\"><a href=\"{$this->SEO->make_link($curlink)}\">{$page}</a></li></ul><a class=\"next\" href=\"{$this->SEO->make_link($nextlink)}\">{$this->LANG->_('Next')}</a>";
        }
        $return .='</div></div>';
        return array($limit, $return);
    }

    /**
     * Sends email $this->message(s)
     * @param string $to receiver email
     * @param string $fromname sender name
     * @param string $fromemail sender email
     * @param string $subject subject of $this->message
     * @param string $body body of $this->message, excluding <html> and <body> tags
     * @param boolean $force_send Force send of a message
     * @todo Normal SMTP functionality
     * @return boolean True or false while sending email
     */
    function send_mail($to, $fromname, $fromemail, $subject, $body, $force_send = false) {

        if (!$force_send) {
            $do_not_send_mail = $this->DB->query_row("SELECT 1 FROM accounts WHERE email={$this->DB->sqlesc($to)} AND email_notifications!='none'");
            if ($do_not_send_mail) {
                return true;
            }
        }


        require_once $this->CONFIG['ROOT_PATH'] . "classes/class.phpmailer.php";
        $m = new PHPMailer();
        $m->SetFrom($fromemail, $fromname);
        $m->AddCustomHeader("Reply-to:" . $fromemail);
        $m->AddCustomHeader('Precedence: bulk');

        $m->CharSet = 'utf-8';
        $m->Subject = $subject;
        $this->TPL->assign('body', $body);
        $this->TPL->assign('email', $to);
        $body = $this->TPL->fetch($this->CONFIG['TEMPLATE_PATH'] . DS . 'email.tpl');
        $m->MsgHTML($body);
        $m->AddAddress($to);
        return $m->Send();
    }

    /**
     * Outputs $this->error with dieing
     */
    function error($text = 'Error') {
        if (AJAX)
            die($text);
        $this->TPL->assign('error', $text);
        $this->TPL->display('error.tpl');
        die();
    }

    /**
     * Outputs $this->message with dieing
     */
    function message($text = 'Message', $warning = '') {

        if (AJAX)
            die($text);

        $this->TPL->assign('message', $text);
        $this->TPL->assign('warning', $warning);
        $this->TPL->display('message.tpl');
        die();
    }

    /**
     * Gets user ip
     * @return string user ip
     */
    function getip() {
        $ip = false;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = false;
            }
            for ($i = 0; $i < count($ips); $i++) {
                if (!preg_match("/^(10|172\.16|192\.168)\./i", $ips[$i])) {
                    if (version_compare(phpversion(), "5.0.0", ">=")) {
                        if (ip2long($ips[$i]) != false) {
                            $ip = $ips[$i];
                            break;
                        }
                    } else {
                        if (ip2long($ips[$i]) != - 1) {
                            $ip = $ips[$i];
                            break;
                        }
                    }
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }

    /**
     * Validates email
     * @param string $email
     * @return boolean
     */
    function validemail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false;
    }

    /**
     * Makes user salt/secret
     * @param int $length Length of secret. Default 5
     * @return string
     */
    function mksecret($length = 5) {
        $set = array("a", "A", "b", "B", "c", "C", "d", "D", "e", "E", "f", "F", "g", "G", "h", "H", "i", "I", "j", "J", "k", "K", "l", "L", "m", "M", "n", "N", "o", "O", "p", "P", "q", "Q", "r", "R", "s", "S", "t", "T", "u", "U", "v", "V", "w", "W", "x", "X", "y", "Y", "z", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        $str = '';
        for ($i = 1; $i <= $length; $i++) {
            $ch = rand(0, count($set) - 1);
            $str .= $set[$ch];
        }
        return $str;
    }

    /**
     * Generate random password
     * @return string Paintext Password
     */
    function mkpassword() {
        // generate new password;
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $newpassword = "";
        for ($i = 0; $i < 10; $i++)
            $newpassword .= $chars[mt_rand(0, mb_strlen($chars) - 1)];

        return $newpassword;
    }

    /**
     * Make password hash
     * @param string $pass Plaintext password
     * @param string $secret Password salt
     * @return string MD5 hash
     */
    function mkpasshash($pass, $secret) {
        return md5($secret . $pass . $secret);
    }

    /**
     * Default redirect function
     * @param string|array $url URL of redirection.
     * <code>
     * safe_redirect('index.php?id=300&view=deleted');
     * </code>
     * @param int|float $timeout timeout in seconds before redirection
     * @return void
     */
    function safe_redirect($url, $timeout = 0) {
        $url = trim($url);
        if (AJAX)
            return;
        /* if (REL_AJAX || ob_get_length()) */ print('
			<script type="text/javascript" language="javascript">
			function Redirect() {
			location.href = "' . addslashes($url) . '";
}
			setTimeout(\'Redirect()\',' . ($timeout * 1000) . ');
			</script>
			');
        //else header("Refresh: $timeout; url=$url");
        return;
    }

    /**
     * HTTP auth in admincp, etc
     * @return void
     */
    function httpauth() {
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth_params = explode(":", base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
            $_SERVER['PHP_AUTH_USER'] = strip_tags($auth_params[0]);
            unset($auth_params[0]);
            $_SERVER['PHP_AUTH_PW'] = implode('', $auth_params);
        }

        if (!$this->login_account($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'], true)) {
            header("WWW-Authenticate: Basic realm=\"Enter your email and password. Case sensitive.\"");
            header("HTTP/1.0 401 Unauthorized");
            $this->error('not authorized');
        }
        return;
    }

    /**
     * Gets value from GET or POST converting in to required type
     * @param string $name Key of GET or POST array
     * @param string $type Type of variable to be returned
     * @return mixed Variable from request
     */
    function getval($name, $type = 'string') {
        if ($_GET[$name]) {
            $t = $_GET[$name];
        } else
            $t = $_POST[$name];
        eval('$t = (' . $type . ')$t;');
        return $t;
    }

    /**
     * Creates associative array of categories
     * @param string $table Table to use
     * @param string $where WHERE subquery
     * @return array Associative array of categories
     */
    function assoc_cats($table, $where = '') {
        $retdata = $this->API->DB->query_return("SELECT * FROM $table $where");
        if ($retdata) {
            foreach ($retdata as $r) {
                $return[$r['id']] = $r;
            }
            return $return;
        } else
            return array();
    }

// TREE FUNCTIONS

    /**
     * Gets array of all childen of a branch
     * @param int $id Parent id to get children for
     * @param string $table Table to select
     * @param bool $recursion Used in recursion, do not use in function call
     * @return array Array of children IDs
     */
    function get_children_ids($id, $table = 'geo', $recursion = false) {
        if ($recursion)
            $return = false;
        else
            $return = $this->API->CACHE->get('trees', "$table-childs-$id");
        if ($return === false) {
            $return = array();
            $children = $this->API->DB->query_return("SELECT id FROM $table WHERE parent_id=$id");
            //var_dump($children);
            if (!$children) {
                if (!$recursion)
                    $this->API->CACHE->set('trees', "$table-childs-$id", $return);
                return array($id);
            }

            foreach ($children as $child) {
                //var_dump(get_children_ids($child['id'], $table,true));
                $return = array_merge($return, get_children_ids($child['id'], $table, true));
            }

            // remove parent-childred
            foreach ($return as $k => $r) {
                $c = $this->API->DB->get_row_count($table, " WHERE parent_id=$r");
                if ($c)
                    unset($return[$k]);
            }
            $this->API->CACHE->set('trees', "$table-childs-$id", $return);
            return $return;
        } else
            return $return;
    }

    /**
     * Outputs array of IDs of way to branch
     * @param int $tid ID of branch
     * @param string $table Table to select
     * @param bool $recursion Used in recursion, do not use in function call
     * @return array Array of braches from top parent to nearest child, sort is ASC
     */
    function build_way($tid, $table = 'geo', $recursion = false) {

        if ($recursion)
            $return = false;
        else
            $return = $this->API->CACHE->get('trees', "$table-way-$tid");
        if ($return === false) {
            $return = $this->API->DB->query_return("SELECT id,name,parent_id FROM $table WHERE id=$tid");

            if (!$return[0]['parent_id']) {
                if (!$recursion)
                    $this->API->CACHE->set('trees', "$table-way-$tid", $return);
                return $return;
            } else {
                $return = array_merge(build_way($return[0]['parent_id'], $table, true), $return);
            }

            $this->API->CACHE->set('trees', "$table-way-$tid", $return);
            return $return;
        } else
            return $return;
    }

    /**
     * Builds a way to branch in string
     * @param int $tid ID of branch
     * @param string $table Table to select
     * @return string Way
     */
    function build_str_way($tid, $table = 'geo') {
        $return = array();
        $ar = build_way($tid, $table);
        foreach ($ar AS $r) {
            $return[] = "{$r['name']}";
        }
        return implode(' / ', $return);
    }

    /**
     * Cleans html code using HTMLawed
     * @param string $code Text to be processed
     * @return string The cleaned html code
     */
    function cleanhtml($code) {
        require_once($this->CONFIG['ROOT_PATH'] . 'htmLawed.php');
        $config = array(
            //'safe'=>1, // Dangerous elements and attributes thus not allowed
            'comments' => 1,
            'cdata' => 1,
            'valid_xhtml' => 1,
            'deny_attribute' => 'on*',
            'elements' => '*-applet' . /* -embed */'-iframe' . /* -object */'-script', //object, embed allowed for youtube video
            'scheme' => 'href: aim, feed, file, ftp, gopher, http, https, irc, mailto, news, nntp, sftp, ssh, telnet; style: nil; *:file, http, https'
        );
        $spec = 'a = title, href;'; // The 'a' element can have only these attributes
        /* $images = get_images($code);

          if ($images)
          {
          $host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
          $host = str_replace('.','\.',$host);
          foreach ($images as $key => $image) {

          if (preg_match('/"?(http:\/\/(?!(www\.|)'.$host.')([^">\s]*))/ie',$image)) {
          $img = @fopen($image, "r");
          if (!$img) {$bb[] = $images[$key]; $html[] = 'pic/disabled.gif'; } else fclose($img);
          }
          }
          }
          if ($bb)
          $code = str_replace($bb,$html,$code); */

        return htmLawed($code, $config, $spec);
    }

    /**
     * API class counstructor
     * @param array $CONFIG Configuration array
     * @param array $db Database connection array
     */
    function __construct($CONFIG, $db) {
        date_default_timezone_set('GMT');
        $this->CONFIG = &$CONFIG;

        if ($this->CONFIG['use_compression'])
            ob_start('ob_gzhandler');

        /* @var database object */
        require_once($this->CONFIG['ROOT_PATH'] . 'classes' . DS . 'database.class.php');

        $this->DB = new DB($db);
        unset($db);


        /* @var object general cache object */
        require_once($this->CONFIG['ROOT_PATH'] . 'classes' . DS . 'cache.class.php');
        $this->CACHE = new Cache($this);
        if ($this->CONFIG['CACHEDRIVER'] == 'native') {
            require_once($this->CONFIG['ROOT_PATH'] . 'classes' . DS . 'fileCacheDriver.class.php');
            $this->CACHE->addDriver(NULL, new FileCacheDriver($this->CONFIG['cache_dir']));
        } elseif ($this->CONFIG['CACHEDRIVER'] == 'memcached') {
            require_once($this->CONFIG['ROOT_PATH'] . 'classes' . DS . 'MemCacheDriver.class.php');
            $this->CACHE->addDriver(NULL, new MemCacheDriver());
        }

        require_once($this->CONFIG['ROOT_PATH'] . 'classes' . DS . 'lang.class.php');

        $this->CONFIG = $CONFIG;

        $this->LANG = new LANG($this);

        require_once($this->CONFIG['ROOT_PATH'] . 'classes' . DS . 'seo.class.php');

        $this->SEO = new SEO($this);

        define('SMARTY_RESOURCE_CHAR_SET', 'utf-8');

        require_once($this->CONFIG['ROOT_PATH'] . 'classes' . DS . 'Smarty' . DS . 'Smarty.class.php');
        require_once($this->CONFIG['ROOT_PATH'] . 'classes' . DS . 'template.class.php');

        $this->TPL = new TEMPLATE($this);

        $this->TPL->template_dir = $this->CONFIG['ROOT_PATH'] . 'modes';
        $compile_dir = $this->CONFIG['cache_dir'] . '/compiled_template';
        if (!is_dir($compile_dir))
            mkdir($compile_dir);
        $this->TPL->compile_dir = $compile_dir;

        $cachedir = $this->CONFIG['cache_dir'] . '/cached_template';
        if (!is_dir($cachedir))
            mkdir($cachedir);
        $this->TPL->cache_dir = $cachedir;

        $this->TPL->assignByRef('API', $this);

        //$TPL->$this->error_reporting = 0;

        $this->TPL->assign('CONFIG', $this->CONFIG);
    }

    /**
     * Loads module by specified mode and action
     * @param string $mode Mode
     * @param string $action Action
     */
    function load_module($mode, $action = 'index') {

        if (!$action)
            $action = 'index';
        if (!$mode)
            $mode = 'index';
        $this->MODE = $mode;
        $this->ACTION = $action;
        $path = $this->CONFIG['MODULE_PATH'] . DS;
        $this->MODULE_PATH = $path;
        $file = $path . $mode . DS . $action . '.php';
        if (!file_exists($file))
            $this->error('Unknown mode of operation');
        require_once($file);
    }

    /**
     * Writes log to database
     * @param string $action Text to be logged
     * @param string $type Type of log
     * @param string $object_id Related object id
     * @param mixed $before Data before logging
     * @param mixed $after Data after logging
     * @return boolean
     */
    function write_log($action, $type, $object_id, $before = null, $after = null) {
        $to_db['action'] = $action;
        $to_db['type'] = $type;
        $to_db['object_id'] = $object_id;
        if ($before)
            $to_db['data_before'] = var_export($before, true);
        if ($after)
            $to_db['data_after'] = var_export($after, true);
        $to_db['added'] = $this->CONFIG['TIME'];
        if ($this->account)
            $to_db['account_id'] = $this->account['id'];
        $to_db['ip'] = $this->getip();
        $to_db['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $to_db['url'] = $_SERVER['REQUEST_URI'];
        if ($_POST)
            $to_db['post'] = var_export($_POST, true);
        if ($_GET)
            $to_db['get'] = var_export($_GET, true);
        $this->DB->query("INSERT INTO logs {$this->DB->build_insert_query($to_db)}");
        return true;
    }

    /**
     * Creates new account
     * @param string $email Email address of account
     * @param string $password Plaintext password for account
     * @param string $name Account username
     * @param array $data Extended data array
     * @return boolean|integer New user ID on success or false on fail
     */
    function create_account($email, $password, $name = '', $data = null) {
        $to_db['name'] = $name;
        $to_db['pass_salt'] = $this->mksecret();
        $to_db['pass_hash'] = $this->mkpasshash($password, $to_db['pass_salt']);
        $to_db['email'] = $email;
        $to_db['added'] = $this->CONFIG['TIME'];
        if ($data) {
            foreach ($data as $k => $d) {
                $to_db[$k] = $d;
            }
        }
        $this->DB->query("INSERT INTO accounts " . $this->DB->build_insert_query($to_db));
        if ($this->DB->mysql_errno())
            return false;
        else
            return $this->DB->mysql_insert_id();
    }

    /**
     * Logins/check login of account
     * @param string $email Email
     * @param string $password Password
     * @param boolean $nosession Do not start session (e.g. check only)
     * @return boolean True on success, false on fail
     */
    function login_account($email, $password, $nosession = false) {
        $account = $this->DB->query_row("SELECT * FROM accounts WHERE email=" . $this->DB->sqlesc($email));

        $pass_hash = $this->mkpasshash($password, $account['pass_salt']);
        if ($pass_hash == $account['pass_hash']) {
            $configuration = $this->DB->query_return("SELECT name,value FROM accounts_configuration WHERE account_id={$account['id']}");
            if ($configuration) //$this->error('No configuration found for this account');
                foreach ($configuration as $c) {
                    $account[$c['name']] = $c['value'];
                }
            $this->account = $account;
            if (!$nosession)
                $this->session();
            return $this->account['id'];
        } else
            return false;
    }

    /**
     * Session handler
     * @global type $CONFIG
     * @param type $option
     * @return boolean
     */
    function session($option = NULL) {
        global $CONFIG;
        if (PHP_SAPI == 'cli') {
            return;
        }
        session_start();

        $sid = session_id();
//ends session
        if ($option == 'end') {
            $this->DB->query("DELETE FROM sessions WHERE phpsessid=" . $this->DB->sqlesc($sid));
            return true;
        }
        //var_dump($sid);
        $ip = $this->getip();
        if (!$this->account) {
            $id = (int) $_COOKIE['id'];
            $pass_hash = $_COOKIE['hash'];

            if ($id && $pass_hash) {
                // is account with that data?
                $check2 = $this->DB->query_row("SELECT * FROM accounts WHERE id={$id} AND pass_hash=" . $this->DB->sqlesc($pass_hash));
                if ($check2) {
                    $ar = array('phpsessid' => $sid, 'user_id' => $id, 'user_agent' => $_SERVER['HTTP_USER_AGENT'], 'ip' => $ip, 'started' => $this->CONFIG['TIME']);
//create new session
                    $this->DB->query("INSERT INTO sessions " . $this->DB->build_insert_query($ar) . " ON DUPLICATE KEY UPDATE " . $this->DB->build_update_query($ar));
                    $this->account = $check2;
                    $extra = $this->get_account($id, true);
                    if ($extra) {
                        foreach ($extra as $k => $v) {
                            $this->account[$k] = $v;
                        }
                    }
// update cookie
                    setcookie('id', $this->account['id'], $this->CONFIG['TIME'] + 86400 * 365 * 10,'/');
                    setcookie('hash', $this->account['pass_hash'], $this->CONFIG['TIME'] + 86400 * 365 * 10,'/');
//ban here
                    if ($this->account['ban_reason']) {
                        $this->TPL->display('banned.tpl');
                        die();
                    }
                    return true;
                }
            }
            $ar = array('phpsessid' => $sid, 'user_agent' => $_SERVER['HTTP_USER_AGENT'], 'ip' => $ip, 'started' => $this->CONFIG['TIME']);
            $this->DB->query("INSERT INTO sessions " . $this->DB->build_insert_query($ar) . " ON DUPLICATE KEY UPDATE " . $this->DB->build_update_query($ar));
            return true;
        } else {
            //account exists
            $ar = array('phpsessid' => $sid, 'user_id' => $this->account['id'], 'user_agent' => $_SERVER['HTTP_USER_AGENT'], 'ip' => $ip, 'started' => TIME);
            $this->DB->query("INSERT INTO sessions " . $this->DB->build_insert_query($ar) . " ON DUPLICATE KEY UPDATE " . $this->DB->build_update_query($ar));
            setcookie('id', $this->account['id'], $this->CONFIG['TIME'] + 86400 * 365 * 10,'/');
            setcookie('hash', $this->account['pass_hash'], $this->CONFIG['TIME'] + 86400 * 365 * 10,'/');
//ban here
            if ($this->account['ban_reason']) {
                $this->TPL->display('banned.tpl');
                die();
            }

            return true;
        }
    }

    /**
     * Gets account data
     * @param int $id ID of account
     * @param boolean $only_extra Return only extra account configuration
     * @return boolean|array account data or false if no such account
     */
    function get_account($id, $only_extra = false) {
        $configuration = $this->DB->query_return("SELECT name,value FROM accounts_configuration WHERE account_id={$id}");
        //if (!$configuration) $this->error('No configuration found for account');
        if ($only_extra && !$configuration)
            return false;
        $extra = array();
        if ($configuration)
            foreach ($configuration as $c) {
                $extra[$c['name']] = $c['value'];
            }

        if ($only_extra)
            return $extra;
        $account = $this->DB->query_row("SELECT * FROM accounts WHERE id=$id");
        if (!$account)
            return false;


        return array_merge($account, $extra);
    }

    /**
     * Logs out user account
     */
    function logout_account() {
        global $API;
        setcookie('id', NULL,NULL,'/');
        setcookie('hash', NULL,NULL,'/');
        unset($API->account);
        $this->session('end');
    }

    /**
     * Checks that user logged in
     * @param array $option Additional extended fields to check
     * @param boolean $return Return false or redirect?
     */
    function auth($option = array(), $return = false) {
        //if (!isset($this->SESSION_STARTED))
        //$this->session();
        if (!$this->account) {
            if ($return)
                return false;
            $this->safe_redirect($this->SEO->make_link('login', 'error', 'auth', 'returnto', urlencode($_SERVER['REQUEST_URI'])));
            die();
        }

        foreach ($option as $k => $v) {
            if ($this->account[$k] != $v) {
                if ($return)
                    return false;
                $this->safe_redirect($this->SEO->make_link('login', 'error', 'access', 'returnto', urlencode($_SERVER['REQUEST_URI'])));
                $this->write_log('Auth failed - no permissions', 'auth', $this->account['id'], "$k != $v");
                die();
            }
        }
        return true;
    }

}

?>