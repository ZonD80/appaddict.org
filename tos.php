<?php

require_once 'init.php';

$API->TPL->assign('pagetitle',$API->LANG->_('Terms Of Service'));
$API->TPL->assign('footername',$API->LANG->_('Terms Of Service'));
$API->TPL->display('tos.tpl');

?>