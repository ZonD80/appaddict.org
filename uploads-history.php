<?php

require_once 'init.php';

$API->auth();


$API->TPL->assign('headeradd', '<link rel="stylesheet" type="text/css" href="./css/tracks.css"> ');

$API->TPL->assign('pagetitle', $API->LANG->_('Uploads History'));
//assign menu class
$API->TPL->assign('footername', $API->LANG->_('Uploads History'));

/* $linkscount = $API->DB->get_row_count("links", "WHERE uploader_id={$API->account['id']}");

  if (!$linkscount) {
  $API->TPL->assign('message', $API->LANG->_('You uploaded nothing'));
  $API->TPL->assign('warning', "<a href=\"{$API->SEO->make_link('upload')}\"><input type=\"submit\" value=\"{$API->LANG->_('Upload new Content')}\"/></a>");
  $API->TPL->display('message.tpl');
  die();
  } */


list($limit, $pagercode) = $API->generate_pagination($linkscount, array('uploads-history'));

$API->TPL->assign('pagercode', $pagercode);

$apps = $API->DB->query_return("SELECT links.*, apps.store, apps.name, apps.image FROM links LEFT JOIN apps ON links.trackid=apps.trackid WHERE uploader_id={$API->account['id']} ORDER BY added DESC $limit");

if (!$apps) {
    $API->TPL->assign('message', $API->LANG->_('You uploaded nothing'));
    $API->TPL->assign('warning', "<a href=\"{$API->SEO->make_link('upload')}\"><input type=\"submit\" value=\"{$API->LANG->_('Upload new Content')}\"/></a>");
    $API->TPL->display('message.tpl');
    die();
}
$API->TPL->assign('apps', $apps);
$API->TPL->display('uploads-history.tpl');
?>