<?php

require_once 'init.php';
$API->TPL->assign('pagetitle', 'API');
$API->TPL->assign('footername', $API->LANG->_('Application Interfaces'));

$action = $API->getval('action');

if ($action == 'di_test') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $link = htmlspecialchars($API->getval('link'));
        $key = substr($API->getval('key'),0,24);
        if (!$key || !$link) {
            $API->error('Data is missing');
        }
        $data['time'] = $API->CONFIG['TIME'];
        $dt = get_download_ticket($data, $key);
        if (strpos($link, '?')) {
            $link = $link . "&amp;dt=" . urlencode($dt);
        } else {
            $link = $link . "?dt=" . urlencode($dt);
        }
        $API->message("DI link is: <input type=\"text\" value=\"$link\"><br/>Try to download file from this link directly without any waits.");
    }
    $API->message('<form method="POST">Test your DI integration:<br/><input name="link" placeholder="Link to file"/><input name="key" placeholder="Encryption key" maxlength="24"/><br/><input type="submit" value="Generate link"/></form>');
}
$API->TPL->display('apis.tpl');
?>