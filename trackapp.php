<?php

require_once 'init.php';

if (!$API->account) {
    $API->TPL->assign('message', $API->LANG->_('You must be registered to track apps'));
    $API->TPL->assign('warning', '<a href="' . $API->SEO->make_link('login') . '"><input type="submit" value="'.$API->LANG->_('Login').'"/></a><a href="' . $API->SEO->make_link('signup') . '"><input type="submit" value="'.$API->LANG->_('Create free account').'"/></a>');
    $API->TPL->display('message.tpl');
    die();
}

$trackid = $API->getval('trackid', 'int');

if (!$trackid)
    $API->error($API->LANG->_('No application ID provided'));

$data = $API->DB->query_row("SELECT name FROM apps WHERE trackid=$trackid");

if (!$data) {
    app_error_message($trackid);
}

$untrack = $API->getval('untrack', 'int');

$to_tracks = array('account_id' => $API->account['id'], 'trackid' => $trackid);


if (!$untrack) {
    $API->DB->query("INSERT INTO tracks " . $API->DB->build_insert_query($to_tracks));

    if ($API->DB->mysql_errno()) {
        $API->error($API->LANG->_('You already tracking this app. Please be patient. Once this app will be updated, you will get email and push notification.'));
    }
    $API->TPL->assign('message', "{$API->LANG->_('You successfully begin to track')} \"{$data['name']}\"! {$API->LANG->_('You will receive an email and push notification once this app will be updated.')}");
    $API->TPL->assign('warning', $API->LANG->_('We redirecting you back in 1 second.'));
    $API->safe_redirect($API->SEO->make_link('view', 'trackid', $trackid), 1);
} else {
    $API->DB->query("DELETE FROM tracks WHERE account_id={$API->account['id']} AND trackid=$trackid");
    $API->TPL->assign('message', "{$API->LANG->_('You successfully stopped to track')} \"{$data['name']}\"!");
    $API->TPL->assign('warning', $API->LANG->_('We redirecting you back in 1 second.'));
    $returnto = $API->getval('returnto');
    if ($returnto) {

        $API->safe_redirect($returnto, 1);
    } else
        $API->safe_redirect($API->SEO->make_link('view', 'trackid', $trackid), 1);
}


$API->TPL->display('message.tpl');
?>