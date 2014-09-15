<?php

// download script really
require_once 'init.php';

$API->auth();

$check = $API->DB->get_row_count('verified_crackers', "WHERE account_id={$API->account['id']}");

if ($check)
    $API->error($API->LANG->_("You are verified cracker already, aren't you?"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = $API->DB->query_row("SELECT (SELECT COUNT(DISTINCT trackid) FROM links WHERE uploader_id={$API->account['id']}) AS uploaded, (SELECT COUNT(DISTINCT trackid) FROM links WHERE uploader_id={$API->account['id']} AND cracker={$API->DB->sqlesc($API->account['name'])}) AS cracked");
    $uploaded = $data['uploaded'];
    $cracked = $data['cracked'];
    if ($cracked < 50) {
        $API->error("{$API->LANG->_('We can not accept your proposal')}<br/><br/>{$API->LANG->_('You uploaded %s apps, cracked %s apps', $uploaded, $cracked)}<br/><br/>{$API->LANG->_('Please upload/crack more and try again. Thanks.')}");
        die();
    }
    $to_db = array('account_id' => $API->account['id'],
        'avatar' => htmlspecialchars(trim($API->getval('avatar'))),
        'background' => htmlspecialchars(trim($API->getval('background'))),
        'slogan' => htmlspecialchars(trim($API->getval('slogan'))),
        'story' => trim(htmlspecialchars(strip_tags($API->getval('story')))));

    $API->DB->query("INSERT INTO proposed_crackers " . $API->DB->build_insert_query($to_db) . " ON DUPLICATE KEY UPDATE " . $API->DB->build_update_query($to_db));

    $API->TPL->assign('message', $API->LANG->_('Your proposal will be verified. You will get email about verification status.'));
    $API->TPL->display('message.tpl');
    die();
}
$API->TPL->assign('pagetitle', $API->LANG->_('Become a verified cracker'));
$API->TPL->assign('footername', $API->LANG->_('Become a verified cracker'));

$API->TPL->display('become-verified.tpl');
?>