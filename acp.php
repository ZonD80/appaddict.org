<?php

require_once 'init.php';


// automatic moderation bydlocode
$auto_moderate = $API->getval('auto_moderate', 'int');


if (!$auto_moderate) {
    $API->auth(array('access_acp' => 1));

    $API->httpauth();

    $action = $API->getval('action');
    $trackid = $API->getval('trackid', 'int');
    $confirm = $API->getval('confirm', 'int');
    $from = $API->getval('from');
    $to = $API->getval('to');
    $id = $API->getval('id', 'int');
} else {
    $no_ajax = $API->getval('no_ajax', 'int');
    if (!$API->auth(array('upload_auto_moderate' => 1), true)) {
        if (!$no_ajax) {
            $API->error($API->LANG->_('Automatic action result') . ': ' . $API->LANG->_('Uploaded, but not published, no permissions'));
        }
        die('<span style="color:orange;">SUCCESS: Uploaded, but NOT PUBLISHED - NO PERMISSIONS</span>');
    }
    $action = 'auto_moderate';
    $id = $auto_moderate;
}


// dirty fix
if (!$trackid)
    $trackid = $id;

if ($action == 'edit') {
    $API->auth(array('is_moderator' => 1));
    $curlink = $API->DB->query_row("SELECT * FROM links WHERE id=$id");
    if (!$curlink) {
        $API->error("Error: Invalid link");
    }
    if ($curlink['state'] == 'pending') {
        $check = $API->DB->query_row("SELECT 1 FROM links WHERE added<{$curlink['added']} AND state='pending'");
        if ($check) {
            $API->error("Error: Please make a decision about all links before this one");
        }
    }
    $link = htmlspecialchars($API->getval('link'));
    $state = $API->getval('state');
    $reason = htmlspecialchars($API->getval('reason'));
    $version = htmlspecialchars($API->getval('version'));
    $cracker = htmlspecialchars($API->getval('cracker'));
    $protected = ($API->getval('protected','int')?1:0);
    if (!in_array($state, explode(',', 'pending,archived,accepted,rejected,reported,dead'))) {
        $API->error('Error: Invalid state defined');
    }
    $to_links['state'] = $state;
    $to_links['link'] = $link;
    $to_links['editor_id'] = $API->account['id'];
    $to_links['version'] = $version;
    $to_links['cracker'] = $cracker;
    $to_links['protected'] = $protected;
    if ($state != 'dead') {
        $to_links['state_reason'] = $reason;
    } else {
        $to_links['state_reason'] = 'Set as dead by moderator';
    }
    $API->write_log("{$API->account['name']} edited link $link", 'links', $id, var_export($curlink, true), var_export($to_links, true));



    if ($state == 'accepted') {
        $linkscountversion = $API->DB->get_row_count("links", "WHERE trackid={$curlink['trackid']} AND version={$API->DB->sqlesc($version)} AND state='accepted'");

        // check need to update twitter or not
        $linkscountarchive = $API->DB->get_row_count("links", "WHERE trackid={$curlink['trackid']} AND state='archived'");
        $appdata = $API->DB->query_row("SELECT * FROM apps WHERE trackid={$curlink['trackid']}");
        if (!$linkscountversion) {
            send_twitter("New App/Book {$appdata['name']} only on AppAddict " . generate_short_link($API->SEO->make_link('view', 'trackid', $curlink['trackid'])), 'updates');
        } elseif ($linkscountarchive) {
            send_twitter("App/Book {$appdata['name']} has been updated to {$version} " . generate_short_link($API->SEO->make_link('view', 'trackid', $curlink['trackid'])), 'updates');
            $appdata['version'] = $version;
            send_tracks($appdata);
        }

        $API->DB->query("UPDATE apps SET added=" . TIME . " WHERE trackid={$curlink['trackid']}");
    }

    $API->DB->query("UPDATE links SET {$API->DB->build_update_query($to_links)} WHERE id=$id");

    $API->CACHE->clearGroupCache('lists_caches');
    $API->message('Updated successfully');
} elseif ($action == 'auto_moderate') {
    $API->DB->query("UPDATE links SET state='accepted' WHERE trackid=$auto_moderate AND uploader_id={$API->account['id']}");
    $API->CACHE->clearGroupCache('lists_caches');
    $API->message('Auto moderated successfully');
} elseif ($action == 'loginas') {
    $API->auth(array('can_loginas' => 1));

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $API->getval('aname');

        $account = $API->DB->query_row("SELECT * FROM accounts WHERE name={$API->DB->sqlesc($name)} OR email={$API->DB->sqlesc($name)}");

        if (!$account)
            $API->error('No account with such id or email');

        $protected = $API->DB->get_row_count("accounts_configuration", "WHERE account_id={$account['id']} AND name='protected_account'");

        if ($protected)
            $API->error('You can not log in as protected account');


        setcookie('id', $account['id'], $CONFIG['TIME'] + 86400 * 365 * 10);
        setcookie('hash', $account['pass_hash'], $CONFIG['TIME'] + 86400 * 365 * 10);

        $API->safe_redirect('/', 3);
        $API->message("Logged in as ID {$account['id']}: {$account['name']}. Redirecting in 3 seconds");
        die();
    } else {
        $API->TPL->display('acp-loginas.tpl');
    }
} elseif ($action == 'filehostings') {
    $API->auth(array('manage_filehostings' => 1));
    $API->error('Under construction');
} elseif ($action == 'bitcoin') {
    $API->auth(array('bitcoin' => 1));
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_BTC_PURCHASE_SALT = 'fsfhi3o2hof_BTC_AA';

        $mode = $API->getval('mode');

        if ($mode == 'check') {

            if ($API->DB->get_row_count("premium_purchases", "WHERE uuid={$API->DB->sqlesc(md5($_BTC_PURCHASE_SALT . $API->getval('txid') . $_BTC_PURCHASE_SALT))}")) {
                $API->message('THERE IS SUCH TRANSACTION', 'DO NOT PROVIDE PREMIUM TO USER');
            } else {
                $API->message("There is no such transaction", "It's okay to add premium to user");
            }
        } elseif ($mode == 'register') {
            $name = $API->getval('aname');
            $id = $API->getval('id', 'int');

            if (!$name)
                $API->error('Account name or email required');
            $account = $API->DB->query_row("SELECT id,premium_expired FROM accounts WHERE " . ($id ? "id=$id" : "name={$API->DB->sqlesc($name)} OR email={$API->DB->sqlesc($name)}"));

            if (!$account)
                $API->error('No account with such username or email');

            $to_db['uuid'] = md5($_BTC_PURCHASE_SALT . $API->getval('txid') . $_BTC_PURCHASE_SALT);
            $to_db['account_id'] = $account['id'];
            $to_db['status'] = 'ok';
            $days = $API->getval('days', 'int');
            $time_to_add = $days * 86400;
            $to_db['time_purchased'] = $time_to_add;
            $to_db['added'] = TIME;

            $API->DB->query("INSERT INTO premium_purchases {$API->DB->build_insert_query($to_db)}");
            $result = $API->DB->mysql_insert_id();

            if ($result) {
                if ($account['premium_expired'] > TIME) {
                    $current_premium = $account['premium_expired'];
                } else {
                    $account['premium_expired'] = TIME;
                }
                $time_to_add = $account['premium_expired'] + $time_to_add;
                $API->DB->query("UPDATE accounts SET premium_expired={$time_to_add} WHERE id={$account['id']}");
                $API->message("Transaction registered successfully", "User has been granted premium access for $days days");
            } else {
                $API->error('Error: transaction not registered (maybe it was registered before?)');
            }
        }
    }
    $API->message('Check/register BTC transaction:<form method="post"><input type="text" name="txid" placeholder="TXID" size="40"/><br/><input type="text" name="aname" placeholder="user name or email (required only for registration)" size="40"/> or id <input name="id" type="text"/><br/>days paid (required only for registration):<input type="text" name="days" size="3"/><br/><input type="submit" name="mode" value="check"/> or <input type="submit" name="mode" value="register"/></form>');
} elseif ($action == 'bans') {

    $mode = $API->getval('mode');

    if (!$mode) {
        $API->auth(array('view_bans' => 1));
    } elseif ($mode == 'ban') {
        $API->auth(array('can_ban' => 1));
        $name = $API->getval('aname');
        $id = $API->getval('id', 'int');
        $reason = htmlspecialchars($API->getval('reason'));

        if (!$reason)
            $API->error('No reason provided');

        $account = $API->DB->query_row("SELECT id FROM accounts WHERE " . ($id ? "id=$id" : "name={$API->DB->sqlesc($name)} OR email={$API->DB->sqlesc($name)}"));

        if (!$account)
            $API->error('No account with such username or email');

        $protected = $API->DB->get_row_count("accounts_configuration", "WHERE account_id={$account['id']} AND name='protected_account'");

        if ($protected)
            $API->error('You can not ban protected account');
        $to_accounts['ban_reason'] = $reason;
        $API->DB->query("UPDATE accounts SET {$API->DB->build_update_query($to_accounts)} WHERE id={$account['id']}");
        $API->write_log("{$API->account['name']} banned $name for $reason", 'admincp_bans', $account['id']);
        $API->safe_redirect($API->SEO->make_link('acp', 'action', 'bans'), 1);
        $API->message('Account was successfully banned');
    } elseif ($mode == 'unban') {
        $API->auth(array('can_unban' => 1));
        $API->DB->query("UPDATE accounts SET ban_reason=NULL WHERE id=$id");
        $API->write_log("{$API->account['name']} unbanned user with id $id", 'admincp_bans', $id);

        $API->safe_redirect($API->SEO->make_link('acp', 'action', 'bans'), 1);
        $API->message('Account was successfully enabled');
    }
    $bans = $API->DB->query_return("SELECT id,name,email,ban_reason FROM accounts WHERE ban_reason!=''");
    $API->TPL->assign('bans', $bans);
    $API->TPL->display('acp-bans.tpl');
    die();
} elseif ($action == 'resetpassword') {
    $API->auth(array('manage_passwords' => 1));

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $API->getval('aname');

        $account = $API->DB->query_row("SELECT id,name FROM accounts WHERE name={$API->DB->sqlesc($name)} OR email={$API->DB->sqlesc($name)}");

        if (!$account)
            $API->error('No account with such username or email');

        $protected = $API->DB->get_row_count("accounts_configuration", "WHERE account_id={$account['id']} AND name='protected_account'");

        if ($protected)
            $API->error('You can not reset password for protected account');

        $password = $API->mkpassword();
        $to_db['pass_salt'] = $API->mksecret();
        $to_db['reset_hash'] = $API->mkpasshash($password, $to_db['pass_salt']);

        $API->DB->query("UPDATE accounts SET " . $API->DB->build_update_query($to_db) . " WHERE id={$account['id']}");

        $API->TPL->assign('reset_password', "Please email user this password <u>$password</u> and this activation link: <u>{$API->SEO->make_link('iforgot', 'activate', $to_db['reset_hash'])}</u>");

        $API->write_log("{$API->account['name']} generated new password for {$account['name']} ({$account['email']})", 'admincp_passwords', $account['id']);
    }
    $API->TPL->display('acp-resetpassword.tpl');
    die();
} elseif ($action == 'privs') {
    $API->auth(array('manage_privs' => 1));

    $mode = $API->getval('mode');

    if ($mode == 'delete') {
        $API->DB->query("DELETE FROM accounts_configuration WHERE account_id=$id");
        $API->TPL->assign('message', 'Privileges revoked');
        $API->write_log("{$API->account['name']} revoked all priveleges from account with id $id", 'admincp_privs', $id);
        $API->safe_redirect($API->SEO->make_link('acp', 'action', 'privs'), 1);
        $API->TPL->display('message.tpl');
        die();
    } elseif ($mode == 'add') {
        $name = $API->getval('aname');
        $check = $API->DB->query_row("SELECT id,name FROM accounts WHERE name=" . $API->DB->sqlesc($name));

        if (!$check)
            $API->error('No account with such username');

        $to_privs['name'] = htmlspecialchars(trim($API->getval('name')));
        $to_privs['value'] = $API->getval('value', 'int');

        if (!$to_privs['name'] || !$to_privs['value'])
            $API->error('Missing form data');
        $to_privs['account_id'] = $check['id'];

        $API->DB->query("INSERT INTO accounts_configuration {$API->DB->build_insert_query($to_privs)} ON DUPLICATE KEY UPDATE {$API->DB->build_update_query($to_privs)}");

        $API->write_log("{$API->account['name']} added privelege {$to_privs['name']}={$to_privs['value']} to account with id {$to_privs['account_id']}", 'admincp_privs', $to_privs['account_id']);
        $API->TPL->assign('message', 'Privilege added');
        $API->safe_redirect($API->SEO->make_link('acp', 'action', 'privs'), 1);
        $API->TPL->display('message.tpl');
        die();
    } elseif ($mode == 'save') {
        $data = json_decode($API->getval('data'), true);
        if (!$data)
            die('Failed to set priveleges');

        $API->DB->query("DELETE FROM accounts_configuration WHERE account_id=$id");
        foreach ($data as $p) {
            $to_privs['name'] = htmlspecialchars((string) $p['name']);
            $to_privs['value'] = intval($p['value']);
            $to_privs['account_id'] = $id;
            $API->DB->query("INSERT INTO accounts_configuration {$API->DB->build_insert_query($to_privs)} ON DUPLICATE KEY UPDATE {$API->DB->build_update_query($to_privs)}");
        }
        $before = $API->DB->query_return("SELECT * FROM accounts_configuration WHERE account_id=$id");
        $API->write_log("{$API->account['name']} edited privileges for account with id $id", 'admincp_privs', $id, $before, $data);
        die('priveleges saved');
    }
    $privs = $API->DB->query_return("SELECT accounts_configuration.*,accounts.name AS aname,accounts.email FROM accounts_configuration LEFT JOIN accounts ON accounts_configuration.account_id=accounts.id");
    if ($privs) {
        foreach ($privs as $p) {
            $pd[$p['account_id']]['name'] = $p['aname'];
            $pd[$p['account_id']]['email'] = $p['email'];
            $pd[$p['account_id']]['privs'][] = array('name' => $p['name'], 'value' => $p['value']);
        }

        $API->TPL->assign('privs', $pd);
    }

    $API->TPL->display('acp-privs.tpl');
    die();
} elseif ($action == 'lang') {
    $API->auth(array('manage_langs' => 1));
    $mode = htmlspecialchars($API->getval('mode'));
    $API->TPL->assign('mode', $mode);
    if ($mode == 'export') {
        $lang_export = substr(trim($API->getval('lang_export')), 0, 2);
        $API->TPL->assign('lang_export', $lang_export);
        if ($lang_export) {
            $check = $API->DB->get_row_count('languages', 'WHERE ltranslate=' . $API->DB->sqlesc($lang_export));
            if (!$check)
                $API->error("No language with this code");
            $API->LANG->export_langfile($lang_export, $API->getval('as_php'));
            die();
        } else {
            $langs = $API->DB->query_return('SELECT ltranslate FROM languages GROUP BY ltranslate');
            $API->TPL->assign('langs', $langs);
//$API->TPL->display('acp-language.tpl');
//die();
        }
    } elseif ($mode == 'import') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $lang = htmlspecialchars(substr(trim($API->getval('language')), 0, 2));
            $f = $_FILES["langfile"];
            if (!is_uploaded_file($f["tmp_name"]) || !filesize($f["tmp_name"])) {
                $API->error('File upload error or file size 0 bytes');
            }
            $result = $API->LANG->import_langfile($f["tmp_name"], $lang, $_POST['override']);
            if ($result['errors']) {
                $status['errors'] = implode('<br/>', $result['errors']);
            }
            if ($result['words']) {
                $status['ok'] = implode('<br/>', $result['words']);
            }
            $API->TPL->assign('result', $result);
            $API->CACHE->clearCache('languages', $lang);
        }
    } elseif ($mode == 'editor') {

        $langs = $API->DB->query_return("SELECT ltranslate FROM languages GROUP BY ltranslate");
        $API->TPL->assign('langs', $langs);
        $do = $API->getval('do');

        if (!$do) {
            $query = array();
            $searchkey = $API->getval('searchkey');
            $searchvalue = $API->getval('searchvalue');
            if ($searchkey)
                $query[] = "lkey LIKE " . $API->DB->sqlwildcardesc($searchkey);
            if ($searchvalue)
                $query[] = "lvalue LIKE " . $API->DB->sqlwildcardesc($searchvalue);

            $searchkey = htmlspecialchars($searchkey);
            $searchvalue = htmlspecialchars($searchvalue);
            $API->TPL->assign('searchkey', $searchkey);
            $API->TPL->assign('searchvalue', $searchvalue);
            if ($query)
                $query = ' WHERE ' . implode(' AND ', $query);
            else
                $query = NULL;
            //$count = $API->DB->get_row_count('languages', $query);

            list($limit, $pagercode) = $API->generate_pagination($count, array('acp', 'action', 'lang', 'mode', 'editor', 'searchkey', $searchkey, 'searchvalue', $searchvalue), 25);
            $API->TPL->assign('pagercode', $pagercode);

            $API->TPL->assign('data', $API->DB->query_return("SELECT * FROM languages$query ORDER BY lkey DESC $limit"));
        } elseif ($do == 'saveadd') {
            $lang = array_map("strval", $API->getval('word', 'array'));
            $key = trim($API->getval('key'));
            if (!$lang || !$key) {
                $API->error('Missing form data');
            }
            foreach ($lang as $l => $word) {
                $word = (trim((string) $word));
                if ($word) {
                    $l = substr($l, 0, 2);
                    $API->DB->query("INSERT INTO languages (lkey,ltranslate,lvalue) VALUES (" . $API->DB->sqlesc($key) . "," . $API->DB->sqlesc($l) . "," . $API->DB->sqlesc($word) . ")");
                    if ($API->DB->mysql_errno() == 1062) {
                        $errors .= 'REDECLARATED KEY:"' . $key . '"<br/>';
                    }
                    $okays .= "$l: $key : $word<br/>";
                }
            }
            $API->CACHE->clearGroupCache('languages');
//$API->safe_redirect($API->SEO->make_link('acp', 'action', 'lang', 'mode', 'editor'), );
            $API->TPL->assign('message', "Added: $okays");
            $API->TPL->assign('warning', "Errors: $errors");
            $API->TPL->display('message.tpl');
            die();
        } elseif ($do == 'gensave') {
            $keys = $API->getval('key', 'array');
            $vals = $API->getval('val', 'array');

            foreach ($keys as $key => $chkey) {
                if (is_array($chkey)) {
                    foreach ($chkey as $lang => $keyvalue) {
                        $lang = substr(trim($lang), 0, 2);
                        $query_result = $API->DB->query("UPDATE languages SET lkey=" . $API->DB->sqlesc($keyvalue) . ", lvalue=" . $API->DB->sqlesc($vals[$key][$lang]) . " WHERE lkey=" . $API->DB->sqlesc($key) . " AND ltranslate = " . $API->DB->sqlesc($lang));
                        $error_info = $query_result->errorInfo();
                        if ($error_info[1] == 1062)
                            $fail[] = ($key <> $keyvalue ? "{$key}-&gt;{$keyvalue}" : $key) . " : {$vals[$key][$lang]}";
                        else
                            $success[] = ($key <> $keyvalue ? "{$key}-&gt;{$keyvalue}" : $key) . " : {$vals[$key][$lang]}";
                    }
                }
            }
            if ($fail)
                $errors = implode("<br/>", $fail);
            if ($success)
                $okays = implode("<br/>", $success);
            $API->CACHE->clearGroupCache('languages');
            $API->TPL->assign('message', "Okays: $okays");
            $API->TPL->assign('warning', "Errors: $errors");
            $API->TPL->display('message.tpl');
            die();
        } elseif ($do == 'delete') {
            $lang = $API->DB->sqlesc(substr(trim($API->getval('language')), 0, 2));
            $key = $API->DB->sqlesc((trim($API->getval('key'))));
            $API->DB->query("DELETE FROM languages WHERE lkey=$key AND ltranslate=$lang");
            $API->CACHE->clearCache('languages', $lang);
            die('Successfully deleted');
        }
    } elseif ($mode == 'clearcache') {
        $API->CACHE->clearGroupCache('languages');
        $API->safe_redirect($API->SEO->make_link('acp', 'action', 'lang'), 1);
        $API->TPL->assign('message', "Language cache cleared. Redirecting you back in 1 second");
        $API->TPL->display('message.tpl');
        die();
    }
    $API->TPL->display('acp-language.tpl');
    die();
} elseif ($action == 'reparse_itunes_errors') {
    $API->auth(array('reparse_itunes_errors' => 1));
    if (!$trackid) {
        $API->TPL->assign('progress_image', $API->DB->query_return("SELECT trackid FROM apps WHERE itunes_parse_error=1"));

        $API->TPL->display('acp-reparse-itunes-errors.tpl');
        die();
    } else {
//header('Content-Type: image/png');

        $app = $API->DB->query_row("SELECT trackid,type,store FROM apps WHERE trackid=$trackid");

        if (!$app) {
            die('<span style="color:red;">ERROR: Missing app with trackid $trackid</span>');
        }

        require_once 'itgw.inc.php';
        $data = get_itunes_info($app['trackid'], $app['type'], $app['store']);
        if (!$data) {
            die("<span style=\"color:red;\">ERROR: Can not find app with trackid {$app['trackid']} on store {$app['store']}");
        }

        $to_app['last_parse_itunes'] = json_encode($data);
        $to_app['itunes_parse_error'] = '0';

        $API->DB->query("UPDATE apps SET " . $API->DB->build_update_query($to_app) . " WHERE trackid={$app['trackid']}");

        die("<span style=\"color:green;\">SUCCESS: '{$data['name']}' reparsed");
    }
} elseif ($action == 'crackers') {
    $API->auth(array('manage_crackers' => 1));
    $section = $API->getval('section');
    if (!in_array($section, explode(',', 'verified,proposed')))
        $section = 'verified';

    $mode = $API->getval('mode');

    if ($mode == 'move') {
        $to = $API->getval('to');
        $from = $API->getval('from');
        if (!in_array($to, explode(',', 'verified,proposed')) || !in_array($from, explode(',', 'verified,proposed')) || $to == $from)
            $API->error('Invalid "to/from" arguments');
        $check = $API->DB->query_row("SELECT {$from}_crackers.*,accounts.email FROM {$from}_crackers LEFT JOIN accounts ON {$from}_crackers.account_id=accounts.id WHERE account_id=$id");
        if (!$check)
            $API->error('No user with this ID');

        $email = $check['email'];
        unset($check['email']);

        $pushes = $API->DB->query_row("SELECT GROUP_CONCAT(udid) AS udids FROM push WHERE account_id=$id");

        if ($from == 'proposed') {
            send_push_safari($API->LANG->_to($id, "Notification"), $API->LANG->_to($id, "Your verification proposal has been accepted"), $CONFIG['defaultbaseurl'], $id);
            if ($pushes['udids']) {
                send_push($API->LANG->_to($id, "Your verification proposal has been accepted"), 0, '', $pushes['udids']);
            }
            $body = "{$API->LANG->_to($id, 'Hello')}!<br/>
        <strong>{$API->LANG->_to($id, 'Congratulations! Now you are verified cracker!')}</strong>
        <br/><br/>
        {$API->LANG->_to($id, 'You can view your public profile by visiting this link')}: {$API->SEO->make_link('crackers', 'id', $id)}<br/><br/>
        <br/>
        --<br/>
        {$API->LANG->_to($id, 'Best regards, team of')} {$CONFIG['sitename']}.";
            $API->send_mail($email, $CONFIG['sitename'], $CONFIG['siteemail'], $API->LANG->_to($id, "Your verification proposal has been accepted"), $body, true);
        } elseif ($from == 'verified') {
            $reason = htmlspecialchars($API->getval('reason'));
            if (!$reason) {
                $API->error('No reason - can not move');
            }

            send_push_safari($API->LANG->_to($id, "Notification"), $API->LANG->_to($id, "Your verified cracker status has been revoked"), $CONFIG['defaultbaseurl'], $id);

            if ($pushes['udids']) {
                send_push($API->LANG->_to($id, "Your verified cracker status has been revoked"), 0, '', $pushes['udids']);
            }
            $body = "{$API->LANG->_to($id, 'Hello')}!<br/>
        <strong>{$API->LANG->_to($id, 'Your verified cracker status has been revoked')}</strong><br/>
        {$API->LANG->_to($id, 'Moderator provided the following reason')}:<br/>
        <strong>{$reason}</strong>
        <br/><br/>
        {$API->LANG->_to($id, 'CONTACT_ADMIN_TO_RESOLVE', $CONFIG['adminemail'])}<br/><br/>
        --<br/>
        {$API->LANG->_to($id, 'Best regards, team of')} {$CONFIG['sitename']}.";
            $API->send_mail($email, $CONFIG['sitename'], $CONFIG['siteemail'], $API->LANG->_to($id, "Your verified cracker status has been revoked"), $body, true);
        }

        $API->DB->query("INSERT INTO {$to}_crackers " . $API->DB->build_insert_query($check) . " ON DUPLICATE KEY UPDATE " . $API->DB->build_update_query($check));
        $API->DB->query("DELETE FROM {$from}_crackers WHERE account_id=$id");

        $API->write_log("{$API->account['name']} moved cracker with account id $id from $from to $to", 'admincp_crackers', $id);
        $API->TPL->assign('message', "Cracker moved from $from to $to");
        $API->TPL->display('message.tpl');
        die();
    } elseif ($mode == 'check') {
        $name = htmlspecialchars($API->getval('name'));
        $check = $API->DB->query_row("SELECT id,name FROM accounts WHERE name=" . $API->DB->sqlesc($name));

        if (!$check)
            die('No account with such name');

        $data = $API->DB->query_row("SELECT (SELECT COUNT(DISTINCT trackid) FROM links WHERE uploader_id={$check['id']}) AS uploaded, (SELECT COUNT(DISTINCT trackid) FROM links WHERE uploader_id={$check['id']} AND cracker={$API->DB->sqlesc($check['name'])}) AS cracked");
        $uploaded = $data['uploaded'];
        $cracked = $data['cracked'];

        die("Uploaded $uploaded apps, cracked $cracked apps!");
    } elseif ($mode == 'delete') {


        $reason = htmlspecialchars($API->getval('reason'));
        if (!$reason)
            $API->error('No reason - can not delete');

        $check = $API->DB->query_row("SELECT {$section}_crackers.*,accounts.email FROM {$section}_crackers LEFT JOIN accounts ON {$section}_crackers.account_id=accounts.id WHERE account_id=$id");
        if (!$check)
            $API->error('No user with this ID');

        $email = $check['email'];
        unset($check['email']);

        $pushes = $API->DB->query_row("SELECT GROUP_CONCAT(udid) AS udids FROM push WHERE account_id=$id");
        send_push_safari($API->LANG->_to($id, "Notification"), $API->LANG->_to($id, "Your verification has been rejected"), $CONFIG['defaultbaseurl'], $id);

        if ($pushes['udids']) {
            send_push("{$API->LANG->_to($id, "Your verification has been rejected")}, {$API->LANG->_to($id, "check your email")}", 0, '', $pushes['udids']);
        }
        $body = "{$API->LANG->_to($id, 'Hello')}!<br/>
        <strong>{$API->LANG->_to($id, 'Your verification has been rejected')}</strong><br/>
        {$API->LANG->_to($id, 'Moderator provided the following reason')}:<br/>
        <strong>{$reason}</strong>
        <br/><br/>
        {$API->LANG->_to($id, 'Please fix the issue and submit your proposal again')}<br/><br/>
        --<br/>
        {$API->LANG->_to($id, 'Best regards, team of')} {$CONFIG['sitename']}.";
        $API->send_mail($email, $CONFIG['sitename'], $CONFIG['siteemail'], $API->LANG->_to($id, "Your verification has been rejected"), $body, true);

        $API->DB->query("DELETE FROM {$section}_crackers WHERE account_id=$id");

        $API->write_log("{$API->account['name']} deleted cracker with account id $id from $section (rejected proposal if from proposed)", 'admincp_crackers', $id);
        $API->TPL->assign('message', 'Cracker deleted from ' . $section);
        $API->TPL->display('message.tpl');
        die();
    }
    $API->TPL->assign('section', $section);

    $crackers = $API->DB->query_return("SELECT {$section}_crackers.*,accounts.name, COUNT(DISTINCT trackid) AS numapps FROM {$section}_crackers LEFT JOIN accounts ON {$section}_crackers.account_id=accounts.id LEFT JOIN links ON {$section}_crackers.account_id=links.uploader_id AND accounts.name=links.cracker GROUP BY {$section}_crackers.account_id ORDER BY numapps DESC");

    $API->TPL->assign('crackers', $crackers);


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = htmlspecialchars($API->getval('name'));
        $check = $API->DB->query_row("SELECT id FROM accounts WHERE name=" . $API->DB->sqlesc($name));

        if (!$check)
            $API->error('No account with such name');

        $id = $check['id'];

        $to_db = array('account_id' => $id,
            'avatar' => htmlspecialchars(trim($API->getval('avatar'))),
            'background' => htmlspecialchars(trim($API->getval('background'))),
            'slogan' => htmlspecialchars(trim($API->getval('slogan'))),
            'story' => trim($API->getval('story')));

        $before = $API->DB->query_row("SELECT account_id,avatar,background,slogan,story FROM {$section}_crackers WHERE account_id=$id");

        $API->DB->query("INSERT INTO {$section}_crackers " . $API->DB->build_insert_query($to_db) . " ON DUPLICATE KEY UPDATE " . $API->DB->build_update_query($to_db));

        if ($before != $to_db)
            $API->write_log("{$API->account['name']} edited cracker with account id $id", 'admincp_crackers', $id, $before, $to_db);
        $API->TPL->assign('message', 'Changes saved');
        $API->TPL->display('message.tpl');
        die();
    }


    if ($id) {
        $API->TPL->assign('cracker', $API->DB->query_row("SELECT verified_crackers.*, accounts.name FROM verified_crackers LEFT JOIN accounts ON verified_crackers.account_id=accounts.id WHERE account_id=$id"));
    }

    $API->TPL->display('acp-crackers.tpl');

    die();
} elseif ($action == 'clearcache') {
    $API->auth(array('clear_cache' => 1));
    $API->CACHE->clearAllCache();
    $API->TPL->assign('message', 'Caches cleared. You wil be redirected in 1 second.');
    $API->safe_redirect($API->SEO->make_link('acp'), 1);
    $API->TPL->display('message.tpl');
    die();
} elseif ($action == 'news') {
    $API->auth(array('manage_news' => 1));
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $to_news['title'] = htmlspecialchars($API->getval('title'));
        $to_news['text'] = $API->cleanhtml($API->getval('text'));
        if (!$id) {
            $to_news['added'] = $CONFIG['TIME'];
            $API->DB->query("INSERT INTO news " . $API->DB->build_insert_query($to_news));
            $newid = $API->DB->mysql_insert_id();
            $API->write_log("{$API->account['name']} created news with title {$to_news['title']}", 'admincp_news', $newid);

            $link = $API->SEO->make_link('news', 'id', $newid);
            $link = generate_short_link($link);

            send_push_safari("Hot news!", $to_news['title'], $link, '', 'news');

            send_push("Hot news: {$to_news['title']}", 1, json_encode(array('id' => $newid)), '', 0, 'news');

            send_twitter("RT Hot News: {$to_news['title']} " . $link);
        } else {
            $before = $API->DB->query_row("SELECT title,text FROM news WHERE id=$id");
            if ($before != $to_news)
                $API->write_log("{$API->account['name']} editen news with title {$to_news['title']}", 'admincp_news', $id);
            $API->DB->query("UPDATE news SET " . $API->DB->build_update_query($to_news) . " WHERE id=$id");
        }
        $API->CACHE->clearGroupCache('news');
        $API->TPL->assign('message', 'News successfully edited/added. You wil be redirected in 1 second.');
        $API->safe_redirect($API->SEO->make_link('acp', 'action', 'news'), 1);
        $API->TPL->display('message.tpl');
        die();
    }
    if ($id) {
        $news = $API->DB->query_row("SELECT * FROM news WHERE id=$id");
        $API->TPL->assign('news', $news);
    }
    $newsdata = $API->DB->query_return("SELECT * FROM news ORDER BY added DESC");
    $API->TPL->assign('newsdata', $newsdata);
    $API->TPL->display('acp-news.tpl');

    die();
} elseif ($action == 'delete-news') {
    $API->auth(array('manage_news' => 1));
    $API->DB->query("DELETE FROM news WHERE id=$id");

    $API->write_log("{$API->account['name']} deleted news with id=$id", 'admincp_news', $id);
    $API->CACHE->clearGroupCache('news');
    $API->TPL->assign('message', 'News deleted.');
    $API->safe_redirect($API->SEO->make_link('acp', 'action', 'news'), 1);
    $API->TPL->display('message.tpl');
    die();
} elseif ($action == 'push-safari') {
    $API->auth(array('send_pushes' => 1));
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $names = array_map('strval', $API->getval('anames', 'array'));

        if ($names) {
            $ids = $API->DB->query_return("SELECT GROUP_CONCAT(accounts.id) AS ids FROM accounts WHERE accounts.name IN (" . implode(',', array_map(array($API->DB, 'sqlesc'), $names)) . ") OR accounts.email IN (" . implode(',', array_map(array($API->DB, 'sqlesc'), $names)) . ")");

            if ($ids) {
                $ids = $ids['ids'];
            } else {
                $API->error('No one found for your input');
            }
        } else {
            $ids = '';
        }
        $title = $API->getval('title');
        $body = $API->getval('body');
        $url = $API->getval('url');
        if (!$title | !$body || !$url) {
            $API->error('Missing body or/and title, url');
        }
        
        $notification_type = htmlspecialchars($API->getval('notification_type'));
        send_push_safari($title, $body, $url, $ids,$notification_type);
        $API->TPL->assign('message', 'SAFARI Push messages scheduled to be sent.');
        $API->TPL->display('message.tpl');
        die();
    } else {
        $API->TPL->display('acp-push-safari.tpl');
        die();
    }
} elseif ($action == 'push') {
    $API->auth(array('send_pushes' => 1));
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $udids = $API->getval('udids', 'array');
        if ($udids) {
            $udids = array_map('htmlspecialchars', $udids);
            $udids = array_map('trim', $udids);
            $udids = array_map('strtoupper', $udids);
            $udids = implode(',', $udids);
        } else
            $udids = null;

//die($udids);

        $custom_prop_names = $API->getval('cusom_prop_names', 'array');
        $custom_prop_vals = $API->getval('custom_prop_vals', 'array');

        if ($custom_prop_names) {
            foreach ($custom_prop_names as $k => $v) {
                if ($v != 'type')
                    $custom_props[$v] = $custom_prop_vals[$k];
            }
            $custom_props = json_encode($custom_props);
        } else
            $custom_props = '';

        $content_available = $API->getval('content_available', 'int');
        $silent = $API->getval('is_silent', 'int');
        
        $notification_type = htmlspecialchars($API->getval('notification_type'));
        send_push($API->getval('message'), $API->getval('type', 'int'), $custom_props, $udids, $content_available, $silent,$notification_type);

        $API->TPL->assign('message', 'Push messages scheduled to be sent.');
        $API->TPL->display('message.tpl');
        die();
    } else {
        $API->TPL->display('acp-push.tpl');
        die();
    }
} elseif ($action == 'message') {
    $API->auth(array('is_moderator' => 1));
    $msg = htmlspecialchars($API->getval('msg'));
    $a = $API->DB->query_row("SELECT accounts.email, accounts.id,accounts.safari_push_notifications,accounts.email_notifications,accounts.push_notifications FROM accounts WHERE accounts.id={$id}");
    if ($a) {

        $email_notifications = explode(',', $a['email_notifications']);
        $safari_push_notificaions = explode(',', $a['safari_push_notifications']);
        $push_notificaions = explode(',', $a['push_notifications']);

        if ($push_notificaions[0] != 'none' || !$push_notificaions[0] || in_array('moderator', $push_notificaions)) {

            $pushes = $API->DB->query_row("SELECT GROUP_CONCAT(udid) AS udids FROM push RIGHT JOIN accounts ON push.account_id=accounts.id WHERE account_id={$id}");

            if ($pushes['udids']) {
                send_push($API->LANG->_to($a['id'], "You received message from moderator, check your email"), 0, '', $pushes['udids']);
            }
        }

        if ($email_notifications[0] != 'none' || !$email_notifications[0] || in_array('moderator', $email_notifications)) {

            $body = "{$API->LANG->_to($a['id'], 'Hello')}!<br/>
{$API->LANG->_to($a['id'], 'Moderator has useful short message for you, that can speed up approving your apps in future')}:<br/>
<strong>{$msg}</strong><br/>
{$API->LANG->_to($a['id'], 'Please review this message and take appropriate action')}.
<br/><br/>
--<br/>
{$API->LANG->_to($a['id'], 'Best regards, team of')} {$CONFIG['sitename']}";
            $API->send_mail($a['email'], $CONFIG['sitename'], $CONFIG['siteemail'], $API->LANG->_to($a['id'], "Important message from moderator"), $body);
        }


        if ($safari_push_notificaions[0] != 'none' || !$safari_push_notificaions[0] || in_array('moderator', $safari_push_notificaions)) {
            send_push_safari($API->LANG->_to($id, "Notification"), "You received message from moderator, check your email", $CONFIG['defaultbaseurl'], $id);
        }

        die('Message sent');
    } else
        die('Error: no such account');
} elseif ($action == 'logs') {

    $API->auth(array('view_logs' => 1));
    $log_types = $API->DB->query_return("SELECT type FROM logs GROUP BY type");
//$log_types = explode(',',$log_types['lt']);
    $API->TPL->assign('log_types', $log_types);
    $type = htmlspecialchars(trim($API->getval('type')));

    if ($type) {
        $q[] = "type = " . $API->DB->sqlesc($type);
    }

    $API->TPL->assign('type', $type);
    $object_id = $API->getval('object_id', 'int');

    if ($object_id) {
        $q[] = "object_id = $object_id";
    }
    $API->TPL->assign('object_id', $object_id);

    if ($q)
        $query = "WHERE " . implode(' AND ', $q);
    else
        $query = '';
    //$count = $API->DB->get_row_count("logs", $query);
    list($limit, $pagercode) = $API->generate_pagination($count, array('acp', 'action', 'logs', 'type', $type), 100);

    $API->TPL->assign('pagercode', $pagercode);
    $logs = $API->DB->query_return("SELECT logs.*, accounts.name,accounts.email FROM logs LEFT JOIN accounts ON accounts.id=logs.account_id $query ORDER BY added DESC $limit");

    $API->TPL->assign('logs', $logs);

    $API->TPL->display('acp-logs.tpl');
    die();
}


$s = $API->getval('q', 'array');

$s_to_tpl = array_map('htmlspecialchars', $s);
$API->TPL->assign('s', $s_to_tpl);
if ($s['cracker']) {
    if (strlen($s['cracker']) < 3) {
        $API->error('search by cracker only from 3 sybmols');
    }
    $q[] = "links.cracker LIKE {$API->DB->sqlwildcardesc($s['cracker'])}";
}
if ($s['name']) {
    if (strlen($s['name']) < 3) {
        $API->error('search by name only from 3 sybmols');
    }
    $q[] = "apps.name LIKE {$API->DB->sqlwildcardesc($s['name'])}";
}
if ($s['bid']) {
    if (strlen($s['bid']) < 3) {
        $API->error('search by bundle_id only from 3 sybmols');
    }
    $q[] = "apps.bundle_id LIKE {$API->DB->sqlwildcardesc($s['bid'])}";
}
if ($s['link']) {
    if (strlen($s['link']) < 3) {
        $API->error('search by link only from 3 sybmols');
    }
    $q[] = "links.link LIKE {$API->DB->sqlwildcardesc($s['link'])}";
}


if ($s['uploader']) {
    if (strlen($s['uploader']) < 3) {
        $API->error('search by uploader only from 3 sybmols');
    }

    $uploader_ids = $API->DB->query_row("SELECT GROUP_CONCAT(id) AS ids FROM accounts WHERE (name LIKE {$API->DB->sqlwildcardesc($s['uploader'])} OR email LIKE {$API->DB->sqlwildcardesc($s['uploader'])})");
    if ($uploader_ids['ids']) {
        $q[] = "uploader_id IN ({$uploader_ids['ids']})";
    }
}
if ($s['editor']) {
    if (strlen($s['editor']) < 3) {
        $API->error('search by editor only from 3 sybmols');
    }
    $editor_ids = $API->DB->query_row("SELECT GROUP_CONCAT(id) AS ids FROM accounts WHERE (name LIKE {$API->DB->sqlwildcardesc($s['editor'])} OR email LIKE {$API->DB->sqlwildcardesc($s['editor'])})");
    if ($editor_ids['ids']) {
        $q[] = "editor_id IN ({$editor_ids['ids']})";
    }
}
$state = $API->getval('state');
if (!in_array($state, explode(',', 'pending,archived,accepted,rejected,reported,dead'))) {
    $state = 'pending';
}

if ($state == 'pending') {
    $order = 'ASC';
} else {
    $order = 'DESC';
}


$q[] = "state={$API->DB->sqlesc($state)}";


if ($q) {
    $query = " WHERE " . implode(" AND ", $q);
}

//$linkscount = $API->DB->query_row("SELECT COUNT(*) AS total FROM links LEFT JOIN apps ON links.trackid=apps.trackid $where");
//$linkscount = $linkscount['total'];
list($limit, $pagercode) = $API->generate_pagination($linkscount, array('acp', 'state', $state, 'q[bid]', $s_to_tpl['bid'], 'q[cracker]', $s_to_tpl['cracker'], 'q[uploader]', $s_to_tpl['uploader'], 'q[link]', $s_to_tpl['link'], 'q[editor]', $s_to_tpl['editor'], 'q[name]', $s_to_tpl['name']));

$links = $API->DB->query("SELECT links.*, apps.name, apps.version AS itversion, apps.store, apps.image, artists.name AS aname, accounts.name AS uname,accounts.email AS uemail, e.name AS ename,e.email AS eemail, e.id AS eid, genres.name AS gname, IF(verified_crackers.account_id=accounts.id AND accounts.name=links.cracker,1,0) AS cverified FROM links LEFT JOIN accounts AS e ON links.editor_id=e.id LEFT JOIN verified_crackers ON uploader_id=verified_crackers.account_id LEFT JOIN apps ON links.trackid=apps.trackid LEFT JOIN artists ON apps.artist_id=artists.id LEFT JOIN genres ON apps.genre_id=genres.id LEFT JOIN accounts ON links.uploader_id=accounts.id $query ORDER BY links.added $order $limit");


$API->TPL->assign('reports', $API->DB->get_row_count("links", "WHERE state='reported'"));
$API->TPL->assign('cracker_proposals', $API->DB->get_row_count("proposed_crackers"));
$API->TPL->assign('itunes_parse_errors', $API->DB->get_row_count("apps", "WHERE itunes_parse_error=1"));
$API->TPL->assign('state', $state);
$API->TPL->assign('links', $links);
$API->TPL->assign('pagercode', $pagercode);
$API->TPL->display('acp.tpl');
die();
