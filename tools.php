<?php

require_once 'init.php';

$API->TPL->assign('pagetitle',$API->LANG->_('Tools'));
$API->TPL->assign('footername',$API->LANG->_('Tools'));
$API->TPL->display('tools.tpl');

?>