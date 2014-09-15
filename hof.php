<?php

require_once 'init.php';

$API->TPL->assign('navclass', 'mac');
$API->TPL->assign('footername', $API->LANG->_('Hall Of Fame'));
$API->TPL->assign('headeradd', '<link rel="stylesheet" type="text/css" href="./css/pr.css"> ');

$API->TPL->assign('pagetitle', $API->LANG->_('Hall Of Fame'));
$API->TPL->display('hof.tpl');
?>