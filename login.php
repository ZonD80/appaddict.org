<?php

require_once 'init.php';

$returnto = $API->getval('returnto');
$api_call = $API->getval('api');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $API->getval('email');
    $password = $API->getval('password');

    if ($api_call) {

        die(json_encode(array('success' => $API->login_account($email, $password, true), 'data' => array('name' => $API->account['name']))));
    }

    if (!$API->login_account($email, $password)) {
        $API->safe_redirect($API->SEO->make_link('login', 'error', 'invalid'));
    } else {
        // ipb integration  
        $warning = '<img src="https://forum.appaddict.org/aa/index.php?action=login&email=' . urlencode($email) . '&password=' . urlencode($password).'"/>';
        $API->TPL->assign('warning', $warning);
        //integration end*/
        $API->TPL->assign('message', $API->LANG->_('You successfully logged in. We are redirecting you in 2 seconds'));
        if (!$returnto)
            $returnto = '/';
        $API->safe_redirect($returnto, 2);
        $API->TPL->display('message.tpl');
        die();
    }
}

$API->TPL->assign('error', $API->getval('error'));
$API->TPL->assign('returnto', $returnto);
$API->TPL->display('login.tpl');
?>