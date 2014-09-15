<?php

require_once 'init.php';

$API->TPL->assign('pagetitle',$API->LANG->_('Donate'));
$API->TPL->assign('footername',$API->LANG->_('Donate'));
$API->TPL->display('donate.tpl');

?>