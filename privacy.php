<?php

require_once 'init.php';

$API->TPL->assign('pagetitle',$API->LANG->_('Privacy Policy'));
$API->TPL->assign('footername',$API->LANG->_('Privacy Policy'));
$API->TPL->display('privacy.tpl');

?>