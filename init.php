<?php

/**
 * Script that initializes all stuff
 *
 */
define('INIT', true);
define('DS', DIRECTORY_SEPARATOR);
define('TIME', time());
define('MICROTIME',  microtime(true));
//ini_set('session.cache_limiter', ''); // prevent no-cache


require_once 'classes' . DS . 'api.class.php';
require_once 'functions.inc.php';

$db = array(
    'host' => 'localhost',
    'user' => 'aa',
    'pass' => 'fsj318hf8FWFih2380h',
    'db' => 'aa',
    'charset' => 'utf8'
);

$CONFIG = array(
    'defaultbaseurl' => 'https://www.appaddict.org',
    'sitename' => 'AppAddict',
    'siteemail' => 'noreply@appaddict.org',
    'adminemail' => 'freddy@appaddict.cc',
    'ROOT_PATH' => dirname(__FILE__) . DS,
    'debug_language' => 1,
    'static_language_dir' => '',//languages', // set to empty if you do not use static languages
    'languages' => 'en,de,fr,ru,pt,it,ar,sr,ro,es,tr,hg',
    'TIME' => time(),
    'START' => microtime(true),
    'CACHEDRIVER' => 'native', // or native for file cache
    'cache_dir' => dirname(__FILE__) . DS . 'cache',
    'TEMPLATE_PATH' => ($_COOKIE['test_tpl']?dirname(__FILE__) . DS . 'test_tpl':dirname(__FILE__) . DS . 'tpl'),
    'use_compression' => true, // use zlib compression
    'GOOGLE_API_KEY' => 'AIzaSyARyGHgnp7ClUUWZeG4cHcy3FIJri2vejk',
    'CRONTAB' => 'cli', // or cli for crontab usage
    'REDIRECTOR_SECRET'=>'AA_d_engine', // passhprase for redirector link encryption
    'redirection_wait'=>30, // how many seconds wait for redirect
    'report_status'=>true, // use status pinging feature
);
$API = new API($CONFIG, $db);

$API->TPL->template_dir = $CONFIG['TEMPLATE_PATH'];

if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
    define("AJAX", true);
else
    define("AJAX", false);


$API->LANG = new LANG($API);


$API->TPL->assign('COUNTRY_FLAGS', array(
    'ru' => 'russia',
    'en' => 'usa',
    'de' => 'germany',
    'pt' => 'portugal',
    'it' => 'italy',
    'ar' => 'saudi_arabia',
    'sr' => 'serbia',
    'fr' => 'france',
    'es' => 'spain',
    'ro' => 'romania',
    'tr' => 'turkey',
    'hg' => 'hungary',
));


if (PHP_SAPI!='cli'&&file_get_contents($CONFIG['ROOT_PATH'].'/status')) {
    $API->TPL->display('beback.tpl');
    die();
}

$API->session();
?>