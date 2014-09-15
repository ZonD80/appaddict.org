<?php

// PUSH sender via CLI
if (PHP_SAPI != 'cli')
    die('this script must be run in cli mode');
require_once 'init.php';


require_once 'classes/ApnsPHP/Autoload.php';

// Instanciate a new ApnsPHP_Push object
$push = new ApnsPHP_Push(
        ApnsPHP_Abstract::ENVIRONMENT_SANDBOX, 'push_certs/sandbox.pem'
);
$logger = new ApnsPHP_Log_Null();
$push->setLogger($logger);
// Set the Provider Certificate passphrase
// $push->setProviderCertificatePassphrase('test');
// Set the Root Certificate Autority to verify the Apple remote peer
//$push->setRootCertificationAuthority('push_certs/entrust_root_sandbox.cer');
// Connect to the Apple Push Notification Service
$push->connect();

$notification_type = $argv[5];
$devices = $API->DB->query_return("SELECT push.*, accounts.push_notifications FROM push LEFT JOIN accounts ON push.account_id=accounts.id WHERE udid IN(" . implode(',', array_map(array($API->DB, 'sqlesc'), explode(',', $argv[6]))) . ")");

foreach ($devices as $d) {

    $push_notificaions = explode(',', $d['push_notifications']);

    if ($push_notificaions[0] != 'none' || !$push_notificaions[0] || in_array($notification_type, $push_notificaions)) {
// Instantiate a new Message with a single recipient
        $message = new ApnsPHP_Message($d['token']);

// Set a custom identifier. To get back this identifier use the getCustomIdentifier() method
// over a ApnsPHP_Message object retrieved with the getErrors() message.
        $message->setCustomIdentifier(uniqid());

// Set badge icon to "3"
        $newbadge = $d['badge'] + 1;
        
        $badges[$newbadge][] = $d['udid'];
        $message->setBadge($newbadge);

// Set a simple welcome text
        if ($argv[1]) {
            $message->setText($argv[1]);
        }

// Play the default sound
        $message->setSound();

// Set a custom property
        if ($argv[3]) {
            foreach ((array) json_decode($argv[3], true) as $k => $v) {
                $message->setCustomProperty($k, $v);
            }
        }
        $message->setCustomProperty('type', $argv[2]);
        if ($argv[4]) {
            $message->setContentAvailable(true);
        }
// Set t
// Set the expiry value to 30 seconds
        $message->setExpiry(86400);

// Add the message to the message queue
        $push->add($message);
// Send all messages in the message queue
    }
}
$push->send();

// Disconnect from the Apple Push Notification Service
$push->disconnect();

if ($badges) {
    foreach ($badges as $badge=>$udids) {
        $API->DB->query("UPDATE push SET badge=$badge WHERE udid IN (".implode(',', array_map(array($API->DB, 'sqlesc'),$udids)).")");
    }
}
?>