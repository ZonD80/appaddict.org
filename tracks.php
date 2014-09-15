<?php

require_once 'init.php';

$API->auth();


$API->TPL->assign('headeradd','<link rel="stylesheet" type="text/css" href="./css/tracks.css"> ');

$API->TPL->assign('pagetitle', $API->LANG->_('My tracking apps'));
//assign menu class
$API->TPL->assign('footername', $API->LANG->_('MY TRACKS'));

/*$appscount = $API->DB->get_row_count("tracks","WHERE account_id={$API->account['id']}");

if (!$appscount) {
    $API->TPL->assign('message', $API->LANG->_('You are tracking no apps'));
    $API->TPL->assign('warning', $API->LANG->_('START_TRACKING_APP'));  
    $API->TPL->display('message.tpl');
    die();
}*/


list($limit,$pagercode) = $API->generate_pagination($appscount,array('tracks'));

$API->TPL->assign('pagercode',$pagercode);

$apps = $API->DB->query_return("SELECT apps.name,apps.trackid,apps.image FROM tracks LEFT JOIN apps ON tracks.trackid=apps.trackid WHERE tracks.account_id={$API->account['id']} ORDER BY added DESC $limit");

if (!$apps) {
    $API->TPL->assign('message', $API->LANG->_('You are tracking no apps'));
    $API->TPL->assign('warning', $API->LANG->_('START_TRACKING_APP'));  
    $API->TPL->display('message.tpl');
    die();
}
$API->TPL->assign('apps',$apps);

$API->TPL->assign('returnto',htmlspecialchars($_SERVER['REQUEST_URI']));
$API->TPL->display('tracks.tpl');

?>