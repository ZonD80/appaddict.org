<?php

require_once 'init.php';
require_once('classes' . DS . 'recaptchalib.php');
$publickey = "6LfNawkAAAAAAIJgnPXSsA1uNHUwjDTFy2mxmFGS";
$privatekey = "6LfNawkAAAAAAJtUriMFU76AeYtytZgJVAJ3O6_1";

if ($API->account) {
    $API->TPL->assign('message', $API->LANG->_("You already signed up, did you not?"));
    $API->TPL->assign('warning', $API->LANG->_("You can use AppAddict account to login to forum"));
    $API->TPL->display('message.tpl');
    die();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

//if ($_POST["recaptcha_response_field"]) {
    $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

    if ($resp->is_valid) {
        //
    } else {
        $error = $API->LANG->_('Invalid CAPTCHA challenge. Please try again');
        $API->TPL->assign('error', $error);
        $API->TPL->assign('captcha', recaptcha_get_html($publickey, NULL));
        $API->TPL->display('signup.tpl');
        die();
    }
    //}

    $email = $API->getval('email');
    if (!$API->validemail($email) || preg_match('/(mailnator\.com|mailwithyou\.com)/', $email)) {
        $error = $API->LANG->_('Invalid email address');
        $API->TPL->assign('error', $error);
        $API->TPL->display('signup.tpl');
        die();
    }

    $password = $API->mkpassword();

    $name = $API->getval('name');

    if (!$name || !preg_match('#[a-zA-Z0-9-. ]+#si', $name) || (strlen($name) > 26)) {
        $error = $API->LANG->_('Name should be 26 symbols max and in following format: a-zA-Z0-9 and can contain space, dot and dash');
        $API->TPL->assign('error', $error);

        $API->TPL->assign('captcha', recaptcha_get_html($publickey, NULL));
        $API->TPL->display('signup.tpl');
    }

    if (!$API->create_account($email, $password, $name)) {
        $error = $API->LANG->_('This email address or nickname is already in use');
        $API->TPL->assign('error', $error);
        $API->TPL->assign('captcha', recaptcha_get_html($publickey, NULL));
        $API->TPL->display('signup.tpl');
        die();
    }

    $body = "{$API->LANG->_('Hello')}!<br/>
        {$API->LANG->_('You have just registered on')} {$CONFIG['sitename']}.<br/>
        {$API->LANG->_('Here are your login details')} ({$API->LANG->_('valid for site forum too')}):<br/>
        {$API->LANG->_('E-mail')}: <b>$email</b><br/>
        {$API->LANG->_('Password')}: <b>$password</b><br/>
            {$API->LANG->_('You can login by clicking this link')}: {$API->SEO->make_link('login')}
        <br/>
        {$API->LANG->_('You can change password later by clicking link in the bottom of a main page under My account section of menu.')}
        <br/>
        {$API->LANG->_('Please keep these credentials in a cool, dry place and away from children:)')}<br/><br/>
        --<br/>
        {$API->LANG->_('Best regards, team of')} {$CONFIG['sitename']}.";
    $API->send_mail($email, $CONFIG['sitename'], $CONFIG['siteemail'], "{$API->LANG->_('Registration on')} {$CONFIG['sitename']}", $body, true);

    $API->TPL->assign('message', $API->LANG->_('Your account has been created. Please check your email for login credentials'));
    $API->TPL->assign('warning', $API->LANG->_('EMAIL_ADD_TO_CONTACTS', $CONFIG['siteemail']));
    $API->TPL->display('message.tpl');
    die();
}
$API->TPL->assign('captcha', recaptcha_get_html($publickey, NULL));
$API->TPL->display('signup.tpl');
?>