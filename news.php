<?php

require_once 'init.php';

$id = $API->getval('id', 'int');

$API->TPL->assign('pagetitle', $API->LANG->_('Site News'));
$API->TPL->assign('footername', $API->LANG->_('Site News'));


$cache = $API->CACHE->get('news', 'all');

if ($cache === false) {
    $allnews = $API->DB->query_return("SELECT id,title FROM news ORDER BY added DESC");
    $API->CACHE->set('news', 'all', $allnews);
}
else
    $allnews = $cache;

if (!$allnews) {
    $API->TPL->assign('message', $API->LANG->_('There are no news yet. Come back later.'));
    $API->TPL->display('message.tpl');
    die();
}
$API->TPL->assign('allnews', $allnews);

if (!$id)
    $id = $allnews[0]['id'];

$news = $API->DB->query_row("SELECT * FROM news WHERE id=$id");

if (!$news)
    $API->error($API->LANG->_('No news with such ID'));
$API->TPL->assign('news', $news);


$API->TPL->display('news.tpl');
?>