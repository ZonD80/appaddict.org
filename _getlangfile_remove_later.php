<?php

require_once 'init.php';

//var_dump($API->LANG->import_langfile('ar.txt','ar'));

$lang = $API->getval('lang');

if (!$lang) {
 print "Select language for export:<br/>";
 foreach (explode(',',$CONFIG['languages']) as $l) {
    print "<a href=\"?lang=$l\">$l</a><br/>";
}
} else {
$API->LANG->export_langfile($lang);
}

?>