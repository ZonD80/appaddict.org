<?php

require_once 'init.php';
$API->TPL->assign('pagetitle',$API->LANG->_('About us'));
$API->TPL->assign('footername',$API->LANG->_('About us'));
$API->TPL->display('about.tpl');

?>