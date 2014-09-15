<?php

require_once 'init.php';


$password = $API->getval('password');

if ($API->account && !$password) {
    $API->TPL->display('set-password.tpl');
    die();
}

$activate = $API->getval('activate');

require_once('classes' . DS . 'recaptchalib.php');
$publickey = "6LfNawkAAAAAAIJgnPXSsA1uNHUwjDTFy2mxmFGS";
$privatekey = "6LfNawkAAAAAAJtUriMFU76AeYtytZgJVAJ3O6_1";

if ($activate) {
    $API->DB->query("UPDATE accounts SET pass_hash=reset_hash, reset_hash=NULL WHERE reset_hash=" . $API->DB->sqlesc($activate));
    if (!$API->DB->mysql_affected_rows()) {

        $API->TPL->assign('captcha', recaptcha_get_html($publickey, $error));
        $error = $API->LANG->_('Invalid activation code');
        $API->TPL->assign('error', $error);
        $API->TPL->display('iforgot.tpl');
        die();
    } else {
        $API->safe_redirect($API->SEO->make_link('login'), 2);
        $API->TPL->assign('message', $API->LANG->_('Your new password has been activated'));
        $API->TPL->assign('warning', $API->LANG->_("You will be redirected to login page in 2 seconds"));
        $API->TPL->display('message.tpl');
        die();
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $account_used = true;
    if (!$API->account) {
        $account_used = false;
        $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

        if ($resp->is_valid) {
            //
        } else {
            $error = $API->LANG->_('Invalid CAPTCHA challenge. Please try again');
            $API->TPL->assign('error', $error);
            $API->TPL->assign('captcha', recaptcha_get_html($publickey, $error));
            $API->TPL->display('iforgot.tpl');
            die();
        }
        //}

        $email = $API->getval('email');
        if (!$API->validemail($email)) {
            $error = $API->LANG->_('Invalid email address');
            $API->TPL->assign('error', $error);
            $API->TPL->display('iforgot.tpl');
            die();
        }

        $account = $API->DB->query_row("SELECT * FROM accounts WHERE email=" . $API->DB->sqlesc($email));

        if (!$account) {
            $error = $API->LANG->_('No account with such email');
            $API->TPL->assign('error', $error);
            $API->TPL->display('iforgot.tpl');
            die();
        }

        $password = $API->mkpassword();
    } else {
        $account = $API->account;
        $old_password = $API->getval('old_password');
        $check = $API->login_account($account['email'], $old_password, true);
        if (!$check) {
            $API->error($API->LANG->_('Invalid current password'));
        }
        $password2 = $API->getval('password2');
        if ($password != $password2) {
            $API->error($API->LANG->_('New password and confirmation does not match'));
        }
        $email = $account['email'];
        $API->logout_account();
    }
    $to_db['pass_salt'] = $API->mksecret();
    $to_db['reset_hash'] = $API->mkpasshash($password, $to_db['pass_salt']);
    if ($old_password) {
        $to_db['pass_hash'] = $API->mkpasshash($old_password, $to_db['pass_salt']);
    }

    $API->DB->query("UPDATE accounts SET " . $API->DB->build_update_query($to_db) . " WHERE id={$account['id']}");


    $body = "{$API->LANG->_('Hello')}!<br/>
        {$API->LANG->_('You have just requested a password reset on')} {$CONFIG['sitename']}.<br/>
        {$API->LANG->_('Here are your new login details')} ({$API->LANG->_('valid for site forum too')}):<br/>
        {$API->LANG->_('E-mail')}: <b>$email</b><br/>
        {$API->LANG->_('Password')}: <b>" . ($account_used ? '*******' : $password) . "</b><br/>
        <b>{$API->LANG->_('To activate your new login credentials please visit this link')}: <a href=\"{$API->SEO->make_link('iforgot', 'activate', $to_db['reset_hash'])}\">{$API->SEO->make_link('iforgot', 'activate', $to_db['reset_hash'])}</a></b>
        <br/>
        {$API->LANG->_('You can change password later by clicking link in the bottom of a main page under My account section of menu.')}
        <br/>
        {$API->LANG->_('Please keep these credentials in a cool, dry place and away from children:)')}<br/>
        <b>{$API->LANG->_('If you have not requested to reset password, please ignore this email.')}</b><br/>
        --<br/>
        {$API->LANG->_('Best regards, team of')} {$CONFIG['sitename']}.";
    //print $body;
    $API->send_mail($email, $CONFIG['sitename'], $CONFIG['siteemail'], "{$API->LANG->_('Password reset on')} {$CONFIG['sitename']}", $body, true);

    $API->TPL->assign('message', $API->LANG->_('Further instructions were sent to your email'));
    $API->TPL->assign('warning', $API->LANG->_('EMAIL_ADD_TO_CONTACTS', $CONFIG['siteemail']));
    $API->TPL->display('message.tpl');
    die();
}

$API->TPL->assign('captcha', recaptcha_get_html($publickey, $error));
$API->TPL->display('iforgot.tpl');
?>