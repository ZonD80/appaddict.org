<?php

require_once 'init.php';

if (!$API->account) {
    $API->TPL->assign('message', $API->LANG->_('You must be registered to submit link report'));
    $API->TPL->assign('warning', '<a href="' . $API->SEO->make_link('login') . '"><input type="submit" value="' . $API->LANG->_('Login') . '"/></a><a href="' . $API->SEO->make_link('signup') . '"><input type="submit" value="' . $API->LANG->_('Create free account') . '"/></a>');
    $API->TPL->display('message.tpl');
    die();
}

$id = $API->getval('id', 'int');

$reason = htmlspecialchars($API->getval('reason'));


if (!$reason || !$id) {
    $API->error($API->LANG->_('No reason, Link or ID provided'));
}

$url = $API->DB->query_row("SELECT links.*, accounts.name, accounts.email FROM links LEFT JOIN accounts ON links.uploader_id=accounts.id WHERE links.id=$id AND state<>'reported' AND protected=0");

if (!$url) {
    $API->error("{$API->LANG->_('This link was already reported by you or another user.')}<br/>{$API->LANG->_('or')}<br/>{$API->LANG->_('There is no link you are reporting to.')}<br/>{$API->LANG->_('or')}<br/>{$API->LANG->_('This link is protected from reports.')}");
}

if (check_filehosting_link($url['link']) && !$_SESSION['confirm_report_' . $id]) {
    $_SESSION['confirm_report_' . $id] = true;
    $API->error($API->LANG->_('Your report was rejected - link is live') . ".<br/><a href=\"javascript:window.location=window.location;\">{$API->LANG->_('Click here if you still want to submit report for this link')}</a>");
}

unset($_SESSION['confirm_report_' . $id]);

$to_links['state'] = 'reported';
$to_links['state_reason'] = $reason;
$to_links['editor_id'] = $API->account['id'];

$API->DB->query("UPDATE links SET {$API->DB->build_update_query($to_links)} WHERE id=$id");

send_report_email($url);


$API->TPL->assign('message', $API->LANG->_("Your report has been sent to moderators"));
if (!$returnto)
    $returnto = '/';
$API->safe_redirect($returnto, 3);
$API->TPL->display('message.tpl');
?>