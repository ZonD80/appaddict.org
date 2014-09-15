<?php

require_once 'init.php';

$API->TPL->assign('navclass', 'mac');
$API->TPL->assign('footername', $API->LANG->_('Hall Of Fame'));
$API->TPL->assign('headeradd', '<link rel="stylesheet" type="text/css" href="./css/pr.css"> ');

$id = $API->getval('id', 'int');
$team = $API->getval('team', 'int');

if ($team) {
    $teamdata = $API->DB->query_row("SELECT * FROM cracking_teams WHERE id=$team");
    if (!$teamdata)
        $API->error($API->LANG->_('No team with such ID'));
    $API->TPL->assign('team', $team);
    $API->TPL->assign('teamdata', $teamdata);
    $query['teamdata'] = "verified_crackers.account_id IN ({$teamdata['account_ids']})";
}

$price = $API->getval('price', 'int');
$API->TPL->assign('price', $price);
if ($price == 1) {
    $apps_join = "price<>'Free'";
} elseif ($price == 2) {
    $apps_join = "price='Free'";
}

if ($id) {
    $cracker = $API->DB->query_row("SELECT verified_crackers.slogan,verified_crackers.background,verified_crackers.story,accounts.id,accounts.name FROM verified_crackers LEFT JOIN accounts ON verified_crackers.account_id=accounts.id WHERE verified_crackers.account_id=$id");

    if (!$cracker)
        $API->error($API->LANG->_('No cracker with such ID or cracker is not verified'));


    $query['cracker'] = "cracker=" . $API->DB->sqlesc($cracker['name']);
    $query['uploader'] = "uploader_id={$cracker['id']}";
    $where = "WHERE " . implode(' AND ', $query);
//$API->DB->Debug();
    $cracked_apps = $API->DB->query_row("SELECT GROUP_CONCAT(DISTINCT trackid) AS trackids, COUNT(trackid) AS numapps FROM links $where");
    //var_dump($cracked_apps);
    $cracker['numapps'] = $cracked_apps['numapps'];
    if ($cracked_apps['trackids']) {
        $downloads_sql = $API->DB->query_row("SELECT SUM(clicks) AS clicks FROM download_stats_archive WHERE trackid IN ({$cracked_apps['trackids']})");
        $cracker['clicks'] = $downloads_sql['clicks'];
    } else {
        $cracker['clicks'] = 0;
    }
    $API->TPL->assign('cracker', $cracker);

    $API->TPL->assign('pagetitle', $cracker['name']);
    $API->TPL->display('crackers-profile.tpl');

    die();
}

if ($query)
    $where = "WHERE " . implode(' AND ', $query);

function crackers_sort($a, $b) {
    return $a['numapps'] < $b['numapps'];
}

$crackers = $API->CACHE->get('lists_caches', 'crackers-' . md5($where));

if ($crackers === false) {
    $crackers = $API->DB->query_return("SELECT verified_crackers.slogan, verified_crackers.avatar, accounts.id, accounts.name FROM verified_crackers LEFT JOIN accounts ON verified_crackers.account_id=accounts.id LEFT JOIN links ON links.cracker=accounts.name AND links.uploader_id=accounts.id $where GROUP BY verified_crackers.account_id DESC");

    if ($crackers) {

        unset($query['teamdata']); // we do not need this here

        foreach ($crackers as $k => $c) {

            $cracked_apps = array();

            $query['cracker'] = "cracker=" . $API->DB->sqlesc($c['name']);
            $query['uploader'] = "uploader_id={$c['id']}";

            if ($query) {
                $where = "WHERE " . implode(' AND ', $query);
            }

            $cracked_apps = $API->DB->query_row("SELECT GROUP_CONCAT(DISTINCT trackid) AS trackids, COUNT(trackid) AS numapps FROM links $where");

            $crackers[$k]['numapps'] = $cracked_apps['numapps'];

            if ($cracked_apps['trackids']) {
                $downloads_sql = $API->DB->query_row("SELECT SUM(clicks) AS clicks FROM download_stats_archive WHERE trackid IN ({$cracked_apps['trackids']})");
                $crackers[$k]['clicks'] = (int) $downloads_sql['clicks'];
            }
        }
    }

    usort($crackers, 'crackers_sort');
    $API->CACHE->set('lists_caches', 'crackers-' . md5($where), $crackers);
}

$API->TPL->assign('crackers', $crackers);

$API->TPL->assign('pagetitle', $API->LANG->_('Hall Of Fame'));
$API->TPL->display('crackers.tpl');
?>