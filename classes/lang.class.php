<?php

/**
 * Language class
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Doodstrap
 */
if (!defined("INIT"))
    die('Direct access to this file not allowed');

class LANG extends API {

    private $API = NULL;

    /**
     * Array of languages
     * @var array
     */
    private $lang;

    /**
     * Current language setting
     * @var string
     */
    private $language;

    /**
     * Debug mode
     * @var boolean
     */
    private $DEBUG;

    /**
     * Already parsed languages for file mode. Used to prevent double parse of languages
     * @var array
     */
    private $parsed_langs;
    // for runtime caching of user languages
    private $runtime_user_langs;

    /**
     * Class constructor, loads main language file
     * @param string $CONFIG Configuration to use
     * @return boolean True
     */
    function __construct($API) {
        $this->API = $API;
        if ($this->API->CONFIG['debug_language'])
            $this->DEBUG = true;
        else
            $this->DEBUG = false;
        $this->language = $this->getlang();
        $this->lang[$this->language] = array();

        if (!$this->API->CONFIG['static_language_dir']) {
            $this->load($this->language);
        } else {
            $this->load($this->language, $this->API->CONFIG['ROOT_PATH'] . $this->API->CONFIG['static_language_dir'] . DS . $this->language . '.lang.php');
        }
        return true;
    }

    /**
     * Loads selected language file
     * @param string $option What file to load
     * @param string $file Load language from this file, if empty, loads from database
     */
    public function load($language = 'en', $php_file = '') {
        if (!$file)
            $this->parse_db($language);
        else
            $this->parse_raw($language, $php_file);
    }

    function parse_raw($lang_code, $php_file) {
        @include $php_file;
        $this->lang[$lang_code] = $language;
    }

    /**
     * Translate string by given key to default language
     * @param string $value Key to use
     * @params MORE works like sprintf
     * @return string String of a language file
     */
    public function _() {
        $args = func_get_args();
        array_unshift($args, $this->getlang());

        return call_user_func_array(array(&$this, "_translate"), $args);
    }

    /**
     * Translate string to user by id and by given key to default language
     * @param int $id user id
     * @param string $value Key to use
     * @params MORE works like sprintf
     * @return string String of a language file
     */
    public function _to() {
        $args = func_get_args();
        $id = $args[0];
        unset($args[0]);
        array_unshift($args, $this->getlang($id));

        return call_user_func_array(array(&$this, "_translate"), $args);
    }

    /**
     * Translate string by given key in selected language
     * @param language code to use
     * @param string $value Key to use
     * @params MORE works like sprintf
     * @return string String of a language file
     */
    public function _translate() {


        $args = func_get_args();

        $language = $args[0];

        if (!$language)
            return "*NO_LANGUAGE_FOR_TRANSLATION*";
        $value = strtolower($args[1]);
        $return = '';

        if (!$this->lang[$language]) {
            if (!$this->API->CONFIG['static_language_dir']) {
                $this->load($language);
            } else {
                $this->load($language, $this->API->CONFIG['ROOT_PATH'] . $this->API->CONFIG['static_language_dir'] . DS . $this->language . '.lang.php');
            }
        }

        // remove later
        /* if (!array_key_exists($value, $this->lang[$language]))
          foreach (explode(',', $this->API->CONFIG['languages']) as $ll) {
          $to_lang = array('lkey' => $value, 'ltranslate' => $ll, 'lvalue' => $value);
          $this->API->DB->query("INSERT INTO languages " . $this->API->DB->build_insert_query($to_lang));
          } */
        // end
        if (!array_key_exists($value, $this->lang[$language])) {
            $return .= ($this->DEBUG ? "*NO_KEY:{$language}* " : '');
        }
        if (!$this->lang[$language][$value]) {
            $return .= ($this->DEBUG ? "*NO_VALUE:$value* " : '');
            $return .= $this->lang['en'][$value];
        }
        $return .= $this->lang[$language][$value];

        array_shift($args);

        //$return = ucfirst($return);

        if (count($args) > 1) {
            $return = str_replace('% ', '\% ', $return);
            $args[0] = $return;
            return call_user_func_array("sprintf", $args);
        } else {
            return $return;
        }
    }

    /**
     * Parse language from database (cache) into associative array
     * @param string $language Language to parse
     */
    private function parse_db($language = 'en') {
        if ($this->lang[$language])
            return;
        //var_dump($this->API->CACHE);
        $this->lang[$language] = $this->API->CACHE->get('languages', $language);
        if ($this->lang[$language] === false) {
            $res = $this->API->DB->query_return("SELECT lkey,lvalue FROM languages WHERE ltranslate='$language'");
            foreach ($res as $row)
                $this->lang[$language][strtolower($row['lkey'])] = $row['lvalue'];
            if (!$this->lang[$language]) {
                print ("ERROR: no language ($language)");
                $this->lang[$language] = array();
            }
            $this->API->CACHE->set('languages', $language, $this->lang[$language]);
        }
    }

    /**
     * Parses language file into associative array (used only in installer)
     * @param string $file File to be used (full path to file)
     * @param string $language Language to be used
     * @return boolean False on error & prints error message
     */
    private function parse_langfile($file, $language = 'en') {
        if (@in_array($file, $this->parsed_langs[$language]))
            return;
        $parse = @file($file, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
        if (!$parse) {
            print ("FATAL ERROR: no language ($language) for file $file.");
            return false;
        }

        foreach ($parse as $string) {
            $cut = strpos($string, '=');
            if (!$cut)
                continue;
            $key = substr($string, 0, $cut);
            $value = substr($string, $cut + 1, mb_strlen($string));
            if ($this->lang[$language][$key])
                $value = "*REDECLARATED_KEY:$key* $value";
            $this->lang[$language][$key] = $value;
        }
        $this->parsed_langs[$language][] = $file;
    }

    /**
     * Imports language file to database
     * @param string $file File location
     * @param string $language Language to import to
     * @param boolean $override Override current values
     * @return boolean
     */
    public function import_langfile($file, $language = 'en', $override = false) {

        $to_database = array();
        $return['errors'] = array();
        $return['words'] = array();


        $parse = @file($file, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
        if (!$parse)
            return false;
        $res = $this->API->DB->query_return("SELECT lkey,lvalue FROM languages WHERE ltranslate='$language'");
        foreach ($res as $row)
            $check[$row['lkey']] = $row['lvalue'];
        foreach ($parse as $string) {
            //$string = iconv('cp1251','utf-8',$string);
            $cut = strpos($string, '=');
            if (!$cut)
                continue;
            $key = strtolower(trim(substr($string, 0, $cut)));
            $value = trim(substr($string, $cut + 1, strlen($string)));
            if ($to_database[$key] || $check[$key] && !$override) {
                $return['errors'][] = 'REDECLARATED KEY:"' . $key . '"';
            }
            $to_database[$key] = $value;
        }
        //if ($return['errors']) return $return;

        foreach ($to_database as $key => $value) {
            $query_result = $this->API->DB->query("INSERT INTO languages (lkey,ltranslate,lvalue) VALUES (" . $this->API->DB->sqlesc(($key)) . ",'$language'," . $this->API->DB->sqlesc(($value)) . ")" . ($override ? " ON DUPLICATE KEY UPDATE lvalue=" . $this->API->DB->sqlesc(($value)) : ''));
            if ($this->API->DB->mysql_errno() != 1062)
                $return['words'][] = "$key : $value";
        }
        return $return;
    }

    /**
     * Exports language to file with downloading
     * @param string $lang Language file
     */
    public function export_langfile($lang, $as_php = false) {
        header("Content-type: text/plain");
        header("Content-Disposition: attachment;filename=$lang.lang" . ($as_php ? ".php" : ''));
        header("Content-Transfer-Encoding: binary");
        header('Pragma: no-cache');
        header('Expires: 0');
        if (!$this->lang[$lang]) {
            $this->load($lang);
            if (!$this->lang[$lang])
                die("ERROR: No lang to export ($lang)");
        }
        if ($as_php) {
            print '<?php $language = ' . var_export($this->lang[$lang], true) . ";";
        } else {

            print "// language tools\n";
            foreach ($this->lang[$lang] as $key => $value) {
                print "$key=" . str_replace(array("\n", "\r", "\r\n"), '', $value) . "\n";
            }
        }
        die("// langfile ($lang) from " . $this->API->CONFIG['defaultbaseurl'] . " created at " . date('d/m/Y H:i:s'));
    }

    /**
     * Gets current language setting
     * @return string 2-char language code
     */
    function getlang($id = 0) {
        if ($id) {
            if ($this->runtime_user_langs[$id])
                return $this->runtime_user_langs[$id];
            $lang = $this->API->DB->query_row("SELECT lang FROM accounts WHERE id=$id");
            if ($lang) {
                $lang = $lang['lang'];
                if (!in_array($lang, explode(',', $this->API->CONFIG['languages'])))
                    $return = 'en';
                else
                    $return = $lang;
            } else
                $return = 'en';

            $this->runtime_user_langs[$id] = $return;
            return $return;
        }
        $setlang = false;
        if ($this->language)
            return $this->language;

        if ($this->API->account) {
            $return = $this->API->account['lang'];
            if (!$return)
                $setlang = true;
        }
        if (!$return)
            $return = substr(trim((string) $_COOKIE['lang']), 0, 2);
        if (!$return) {
            $return = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            $setlang = true;
        }
        if (!in_array($return, explode(',', $this->API->CONFIG['languages']))) {
            $return = 'en';
            $setlang = true;
        }
        if ($setlang) {
            setcookie('lang', $return, $this->API->CONFIG['TIME'] + 86400 * 365 * 10);
            if ($this->API->account) {
                $this->API->DB->query("UPDATE accounts SET lang='$return' WHERE id={$this->API->account['id']}");
            }
        }
        return $return;
    }

    /**
     * Sets language to current user
     * @param string $l Language code
     * @return boolean True
     */
    function setlang($l) {
        $l = substr(trim((string) $l), 0, 2);
        if (!in_array($l, explode(',', $this->API->CONFIG['languages'])))
            $l = 'en';
        $this->language = $l;
        if ($this->API->account) {
            $this->API->DB->query("UPDATE accounts SET lang='$l' WHERE id={$this->API->account['id']}");
        }
        setcookie('lang', $l, $this->API->CONFIG['TIME'] + 86400 * 365 * 10);
        return true;
    }

}

?>