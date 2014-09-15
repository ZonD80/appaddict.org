<?php

// archive download script
require_once 'init.php';

$trackid = $API->getval('trackid', 'int');

$API->TPL->assign('pagetitle', $API->LANG->_('View Archived Versions'));
$API->TPL->assign('footername', $API->LANG->_('View Archived Versions'));
// check that app is uloaded:

$appdata = $API->DB->query_row("SELECT apps.* FROM apps WHERE trackid={$trackid}");

$decoded_links = $API->DB->query_return("SELECT links.*, IF(verified_crackers.account_id=accounts.id AND accounts.name=links.cracker,1,0) AS verified FROM links LEFT JOIN verified_crackers ON links.uploader_id=verified_crackers.account_id LEFT JOIN accounts ON accounts.id=links.uploader_id WHERE trackid={$trackid} AND state='archived' ORDER BY  verified DESC, links.added ASC, id DESC");
if (!$appdata || !$decoded_links) {
    $API->error($API->LANG->_('There are no archived apps with this ID'));
}

$wait = ($API->account?0:$API->CONFIG['redirection_wait']);//(is_premium()?0:$API->CONFIG['redirection_wait']);

foreach ($decoded_links as $ldetails) {

    $ldata = parse_url($ldetails['link']);

    if ($ldata['scheme'] == 'magnet') {
        $links[$ldetails['version']][] = array('id' => $ldetails['id'], 'no_redirection' => true, 'host' => $API->LANG->_('.torrent magnet link'), 'link_ticket' => $ldetails['link'], 'cracker' => $ldetails['cracker'], 'verified' => ($ldetails['verified'] ? true : false), 'di_compatible' => (($appdata['compatibility'] != 4) && $appdata['type'] != 'book' && is_directinstaller_compatible($ldetails['link'])),'protected'=>$ldetails['protected']);
    } else {
        $link_ticket = urlencode(encrypt(json_encode(array('link'=>$ldetails['link'],'wait'=>$wait, 'ua' => $_SERVER['HTTP_USER_AGENT'], 'ip' => $API->getip())), $API->CONFIG['REDIRECTOR_SECRET']));

        $links[$ldetails['version']][] = array('id' => $ldetails['id'], 'no_redirection' => false, 'host' => $API->LANG->_('Download from %s', $ldata['host']), 'link_ticket' => $link_ticket, 'cracker' => $ldetails['cracker'], 'verified' => ($ldetails['verified'] ? true : false), 'di_compatible' => (($appdata['compatibility'] != 4) && $appdata['type'] != 'book' && is_directinstaller_compatible($ldetails['link'])), 'ss_compatible' => (($appdata['compatibility'] != 4) && $appdata['type'] != 'book' && is_signservice_compatible($ldetails['link'])),'protected'=>$ldetails['protected']);
    }
}

$API->TPL->assign('appdata', $appdata);
$API->TPL->assign('links', $links);

$API->TPL->display('archive.tpl');
?>