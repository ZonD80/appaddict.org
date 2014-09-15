<?php

require '../init.php';

$action = $API->getval('action');


if ($action == 'remove' && $API->account) {
    
} elseif ($action == 'update' && $API->account) {

    $token = $API->getval('token');
    if ($token) {
        $API->DB->query("UPDATE push_safari SET account_id={$API->account['id']} WHERE token={$API->DB->sqlesc($token)}");
    }
}
if (preg_match('#pushPackages#si', $_SERVER['QUERY_STRING'])) {
    require_once 'createPushPackage.php';
} elseif (preg_match('#log#si', $_SERVER['QUERY_STRING'])) {
    
} elseif (preg_match('#devices/([a-f0-9]{64})/registrations#si', $_SERVER['QUERY_STRING'], $matches)) {

    $auth_token = str_replace('ApplePushNotifications ', '', (string) $_SERVER["HTTP_AUTHORIZATION"]);
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        //file_put_contents('log.txt', $_SERVER['QUERY_STRING']."\n\n",FILE_APPEND);
        $API->DB->query("DELETE FROM push_safari WHERE token={$API->DB->sqlesc($matches[1])} AND auth_token={$API->DB->sqlesc($auth_token)}");
    } else {
        $API->DB->query("UPDATE push_safari SET token={$API->DB->sqlesc($matches[1])} WHERE auth_token={$API->DB->sqlesc($auth_token)}");
    }
}