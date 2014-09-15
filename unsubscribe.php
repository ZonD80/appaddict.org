<?php

require_once('init.php');

$email = $API->getval('email');

if (!$API->validemail($email)) {
    $API->error($API->LANG->_('Invalid email address'));
}

$confirm = $API->getval('confirm');

if (!$confirm) {
$API->message($API->LANG->_('You will no longer receive emails from this website').'<br/>'.$API->LANG->_('Are you sure?').'<br/><form method="post"><input type="hidden" name="confirm" value="1"/><input type="submit" value="'.$API->LANG->_('Yes').'"/></form><br/>[ <a href="/">'.$API->LANG->_('No').', '.$API->LANG->_('Go back').'</a> ]');
} else {
    $API->DB->query("UPDATE accounts SET email_notifications='none' WHERE email={$API->DB->sqlesc($email)}");
    $API->message($API->LANG->_('Unsubscribed successfully').'<br/>'.$API->LANG->_('You will no longer receive emails from this website'));
}