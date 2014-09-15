<?php

// usage pusher.php message type [[json_encoded_custom_fields]]
// PUSH sender via CLI
// PUSH sender via CLI
if (PHP_SAPI != 'cli')
    die('this script must be run in cli mode');

require_once('init.php');

$title = $argv[1];
$body = $argv[2];
$url = $argv[3];
$notification_type = $argv[4];
$ids = $argv[5];

if (!$ids) {
    $where = 'WHERE token IS NOT NULL';
} else {
    $where = "WHERE push_safari.account_id IN ($ids) AND token IS NOT NULL";
}

$data = $API->DB->query_return("SELECT account_id,token,accounts.safari_push_notifications FROM push_safari LEFT JOIN accounts ON push_safari.account_id=accounts.id $where");

// Using Autoload all classes are loaded on-demand
require_once 'classes/ApnsPHP/Autoload.php';

// Instanciate a new ApnsPHP_Push object
$push = new ApnsPHP_Push(
        ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION, 'push_certs/push-safari.pem'
);
$logger = new ApnsPHP_Log_Null();
$push->setLogger($logger);
// Set the Provider Certificate passphrase
// $push->setProviderCertificatePassphrase('test');
// Set the Root Certificate Autority to verify the Apple remote peer
//$push->setRootCertificationAuthority('push_certs/entrust_root_sandbox.cer');
// Connect to the Apple Push Notification Service
$push->connect();

foreach ($data as $d) {


    $safari_push_notificaions = explode(',', $d['safari_push_notifications']);
    if ($safari_push_notificaions[0] != 'none' || !$safari_push_notificaions[0] || in_array($notification_type, $safari_push_notificaions)) {
        $message = new ApnsPHP_Message($d['token']);

// Set a custom identifier. To get back this identifier use the getCustomIdentifier() method
// over a ApnsPHP_Message object retrieved with the getErrors() message.
        $message->setCustomIdentifier(uniqid());

        $alert = array(
            "title" => $title,
            "body" => $body,
            "action" => $API->LANG->_to($d['account_id'], 'View'),
        );
        $url_args = array(
            str_replace('https://', '', $url),
        );

        $message->setApsCustomProperty('alert', $alert);
        $message->setApsCustomProperty('url-args', $url_args);

// Set the expiry value to 30 seconds
        $message->setExpiry(86400);

// Add the message to the message queue
        $push->add($message);
    }
}

// Send all messages in the message queue
$push->send();

// Disconnect from the Apple Push Notification Service
$push->disconnect();
