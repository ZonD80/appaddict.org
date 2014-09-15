<?php

require_once 'init.php';

$API->auth();

$API->TPL->assign('pagetitle', $API->LANG->_('Notification settings'));
//assign menu class
$API->TPL->assign('footername', $API->LANG->_('Notification settings'));

$action = $API->getval('action');





$allowed_notifs['email_notifications'] = array(
    'moderator' => $API->LANG->_('From moderator'),
    'tracks' => $API->LANG->_('Tracked content update'),
);

$allowed_notifs['push_notifications'] = array(
    'news' => $API->LANG->_('New news'),
    'moderator' => $API->LANG->_('From moderator'),
    'tracks' => $API->LANG->_('Tracked content update'),
);

$allowed_notifs['safari_push_notifications'] = array(
    'news' => $API->LANG->_('New news'),
    'moderator' => $API->LANG->_('From moderator'),
    'tracks' => $API->LANG->_('Tracked content update'),
);

if ($action == 'enable') {
    $where = $API->getval('where');
    if ($where == 'email') {
        $API->DB->query("UPDATE accounts SET email_notifications = NULL WHERE id={$API->account['id']}");
        $API->safe_redirect($API->SEO->make_link('notification-settings'), 2);
        $API->message($API->LANG->_('Email notifications have been enabled for you'));
    }
} elseif ($action == 'configure') {
    $notifs = $API->getval('notifs', 'array');

    foreach ($allowed_notifs as $group=>$types) {
        foreach (array_keys($types) as $type) {
            if ($notifs[$group][$type]) {
                $to_notifs[$group][] = $type;
            }
        }
    }
    
    foreach (array_keys($allowed_notifs) as $type) {
        if ($to_notifs[$type]) {
            $to_db[$type] = implode(',',$to_notifs[$type]);
        } else {
            $to_db[$type] = 'none';
        }
    }
    $API->DB->query("UPDATE accounts SET {$API->DB->build_update_query($to_db)} WHERE id={$API->account['id']}");
    
    $API->safe_redirect($API->SEO->make_link('notification-settings'),2);
    $API->message($API->LANG->_('Notification settings have been updated'));
}

$API->TPL->assign('allowed_notifs', $allowed_notifs);

$API->TPL->assign('account_notifications', array('email' => explode(',', $API->account['email_notifications']), 'push' => explode(',', $API->account['push_notifications']), 'safari_push' => explode(',', $API->account['safari_push_notifications'])));
$API->TPL->display('notification-settings.tpl');
?>