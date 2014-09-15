<?php

// safe redirects

require_once 'init.php';

//$API->auth();
//$API->error('Maintenance mode');

$API->TPL->assign('pagetitle', 'iSignCloud');
$API->TPL->assign('footername', 'iSignCloud');

if ($API->getval('rmuss')) {



    if (!preg_match('/(iPod|iPhone|iPad)/', $_SERVER['HTTP_USER_AGENT']))
        $API->error($API->LANG->_('Please visit this page from iDevice'));

    if (!$API->account)
        $ticket = encrypt(json_encode(array('name' => 'AppAddict', 'link' => 'https://app.appaddict.org/builds/darkseid.ipa', 'image' => 'https://app.appaddict.org/icns/icon_76x76@2x.png', 'bundle_id' => 'yolo.zoro.swag', 'force_sign' => true)), 'RMUSS_super_secret');
    else
        $ticket = encrypt(json_encode(array('name' => 'AppAddict', 'link' => 'https://app.appaddict.org/builds/darkseid.ipa', 'image' => 'https://app.appaddict.org/icns/icon_76x76@2x.png', 'bundle_id' => 'yolo.zoro.swag', 'email' => $API->account['email'], 'account' => $API->account['name'], 'force_sign' => true)), 'RMUSS_super_secret');

    header("Location: https://regmyudid.com/isigncloud/mcb.php?t=" . urlencode($ticket));
    die();
}

$id = $API->getval('id', 'int');
$appdata = $API->DB->query_row("SELECT links.*,apps.* FROM links LEFT JOIN apps ON links.trackid=apps.trackid WHERE links.id=$id");


if (!$appdata) {
    $API->TPL->display('signservice.tpl');
    die();
} else {

    if (!preg_match('/(iPod|iPhone|iPad)/', $_SERVER['HTTP_USER_AGENT'])) {
        $API->error($API->LANG->_('Please visit this page from iDevice'));
    }

    $link = $appdata['link'];
    /*
     * LINK GET CODE, used in APPGW too
     */
    //in appgw: get_signservice_link,get_directinsaller_link; signservice, directinstaller
    $link = get_directinstaller_link($link);

    if (!$link) {
        $API->error($API->LANG->_('Something went wrong. Sorry for inconvenience.') . " <a href=\"{$API->SEO->make_link('report', 'id', $id, 'reason', urlencode("iSignCloud Integration Failure"))}\">{$API->LANG->_('Report broken link')}</a>");
    }

    if (!$API->account)
        $ticket = encrypt(json_encode(array('name' => $appdata['name'], 'link' => $link, 'image' => $appdata['image'], 'bundle_id' => $appdata['bundle_id'])), 'RMUSS_super_secret');
    else
        $ticket = encrypt(json_encode(array('name' => $appdata['name'], 'link' => $link, 'image' => $appdata['image'], 'bundle_id' => $appdata['bundle_id'], 'email' => $API->account['email'], 'account' => $API->account['name'])), 'RMUSS_super_secret');

    header("Location: https://regmyudid.com/isigncloud/mcb.php?t=" . urlencode($ticket));
}
?>