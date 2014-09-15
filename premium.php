<?php

require_once 'init.php';
require_once 'classes/money2btc.class.php';


$_RECEIVER_ADDRESS = '16LkqztAL2HVyRmYZ5TrcSJX6LtcBRSXsZ';
$_BTC_PURCHASE_KEY = 'AA_BTC_PT_3r22fhsdk';
$_BTC_PURCHASE_SALT = 'fsfhi3o2hof_BTC_AA';
$_EXPIRES_TIME = 3600; // offer expiration time for btc payment

$allowed_days = array(1 => '1.00', 7 => '3.99', 30 => '7.99', 90 => '14.99', 180 => '26.99', 365 => '45.99', 3650 => '149.99');

function get_satoshi_secret() {
    // min  0.00005460 <> 0.00009990
    return ('0.0000' . rand(5460, 9999));
}

function get_received_by_address($address, $txid, $after_unixtime) {
    if (!preg_match('/[0-9a-zA-Z]{64}/', $txid))
        return false;
    $data = json_decode(curl_request('https://blockchain.info/rawtx/' . $txid), true);

    if (!$data['out'])
        return false;

    if ($data['time'] < $after_unixtime)
        return false;
    foreach ($data['out'] as $out) {
        if ($out['addr'] == $address)
            return number_format($out['value'] / 100000000, 8, '.', '');
    }
    return false;
}

function mtgox_query($path, $data = '', $key = '', $secret = '') {
    global $API;
    $url = 'https://data.mtgox.com/api/2/';
    $url = $url . $path;


    //$headers[] = 'User-Agent: money2btc.com';
    //$headers[] = 'Accept-encoding: gzip, deflate';

    static $ch = null;
    if (is_null($ch)) {
        $ch = curl_init();
        //curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'money2btc.com');
    }
    curl_setopt($ch, CURLOPT_URL, $url);

    if ($key && $secret) {

        $data['nonce'] = intval(microtime(true) * 4);
        $data = http_build_query($data);
        $hash_data = $path . chr(0) . $data;
        $secret = base64_decode($secret);
        $hmac = base64_encode(hash_hmac('sha512', $hash_data, $secret, true));

        $headers[] = "Rest-Key: $key";
        $headers[] = "Rest-Sign: $hmac";
    }
    if ($data)
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    if ($headers)
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    // run the query
    $res = curl_exec($ch);

    //curl_close($ch);
    //var_dump($res);
    if ($res === false)
        return false;
    $dec = json_decode(($res), true);
    if (!$dec)
        return false;
    return $dec;
}

function get_bitcoin_price($q_d) {
    global $API;
    $data = json_decode(@file_get_contents('https://www.bitstamp.net/api/ticker/'), true);
    $return = $data['low'];
    return $return;
}

$from = $API->getval('from');



$voucher = $API->getval('voucher');

$API->TPL->assign('voucher', htmlspecialchars($voucher));

if ($from == 'voucher') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $API->auth();

        $vd = explode(':', $voucher);
        $partner = false;
        $partners = $API->DB->query_return("SELECT * FROM voucher_partners");

        foreach ($partners as $p) {
            if ((int) $vd[0] == $p['id']) {
                $partner = $p;
                break;
            }
        }

        if (!$partner) {
            $API->error($API->LANG->_('Provided voucher is from unknown partner'));
        }
        $days = (int) $vd[1];
        $random = (string) $vd[2];
        $digest = (string) $vd[3];
        if (md5("{$p['salt']}:{$p['id']}:$days:$random:{$p['salt']}") != $digest) {
            $API->error($API->LANG->_('Invalid voucher'));
        }

        $days_remain = $p['days_left'] - $days;
        if ($days_remain < 0) {
            $API->error($API->LANG->_('Voucher limit for this partner exceeded. Please ask partner to obtain more vouchers'));
        }

        $current_premium = $API->account['premium_expired'];
        if ($current_premium <= $CONFIG['TIME']) {
            $current_premium = $CONFIG['TIME'];
        }
        $to_account = array('premium_expired' => $current_premium + $to_db['time_purchased']);

        $API->DB->query("INSERT INTO used_vouchers (voucher) VALUES ({$API->DB->sqlesc("{$vd[0]}:{$vd[1]}:{$vd[2]}:{$vd[3]}")})");
        if ($API->DB->mysql_errno()) {
            $API->error($API->LANG->_('This voucher was already used before'));
        }
        $API->DB->query("UPDATE accounts SET {$API->DB->build_update_query($to_account)} WHERE id={$API->account['id']}");
        $API->DB->query("UPDATE voucher_partners SET days_left=$days_remain WHERE id={$p['id']}");

        $API->safe_redirect($API->SEO->make_link('premium'), 2);
        $API->message($API->LANG->_('Voucher applied. We have added %s days of premium membership to your account', $days));
    }
} elseif ($from == 'rmu') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_SERVER['HTTP_USER_AGENT'] != 'RMU; hash:14832907ufdfhlqhr12lrhfdkjf') {
            $API->error('Hello, hacker!');
        }
        $days = $API->getval('days', 'int');
        $email = $API->getval('email');
        $txid = $API->getval('transaction_id');

        $check = $API->DB->query_row("SELECT * FROM premium_purchases WHERE uuid={$API->DB->sqlesc($txid)}");

        if ($check) {
            die($API->LANG->_('This transaction was already processed'));
        }
        $to_db['uuid'] = $txid;

        $account = $API->DB->query_row("SELECT id FROM accounts WHERE email={$API->DB->sqlesc($email)}");
        if (!$account) {
            die('Can not find account');
        }
        $to_db['account_id'] = $account['id'];
        $to_db['time_purchased'] = $days * 86400;
        $to_db['status'] = 'ok';
        $to_db['added'] = TIME;
        $API->DB->query("INSERT INTO premium_purchases " . $API->DB->build_insert_query($to_db));

        $current_premium = $API->DB->query_row("SELECT premium_expired FROM accounts WHERE id={$to_db['account_id']}");
        $current_premium = $current_premium['premium_expired'];
        if ($current_premium <= $CONFIG['TIME'])
            $current_premium = $CONFIG['TIME'];
        $to_account = array('premium_expired' => $current_premium + $to_db['time_purchased']);
        $API->DB->query("UPDATE accounts SET {$API->DB->build_update_query($to_account)} WHERE id={$to_db['account_id']}");
        die('ok;');
    }
} elseif ($from == 'bitcoin') {
    $API->auth();

    $days = $API->getval('days', 'int');
    $API->TPL->assign('pagetitle', $API->LANG->_('Extend account to premium'));
    $API->TPL->assign('headeradd', '<link rel="stylesheet" type="text/css" href="./css/tracks.css"> ');
//assign menu class
    $API->TPL->assign('footername', $API->LANG->_('Premium'));

    if (!in_array($days, array_keys($allowed_days)))
        $API->error($API->LANG->_('Invalid package was selected for purchase'));

    if ($API->getval('payment_check')) {

        $purchase_ticket = $API->getval('purchase_ticket');
        //var_dump($purchase_ticket);
        $purchase_ticket = json_decode(decrypt($purchase_ticket, $_BTC_PURCHASE_KEY), true);

        if (!$purchase_ticket)
            $API->error($API->LANG->_('Invalid purchase data'));

        if ($purchase_ticket['expires'] < TIME)
            $API->error($API->LANG->_('Expired purchase ticket'));

        $txid = $API->getval('txid');

        if (!$txid)
            $API->error($API->LANG->_('Invalid purchase data'));

        $received = (get_received_by_address($_RECEIVER_ADDRESS, $txid, $purchase_ticket['added']));

        if ($received != $purchase_ticket['amount']) {
            $API->error($API->LANG->_('You have sent invalid amount of bitcoins to specified address'));
        } else {
            $hash = md5($_BTC_PURCHASE_SALT . $txid . $_BTC_PURCHASE_SALT);
            $check = $API->DB->query_row("SELECT * FROM premium_purchases WHERE uuid={$API->DB->sqlesc($hash)}");

            if ($check)
                $API->error($API->LANG->_('This transaction was already processed'));
            $to_db['uuid'] = $hash;
            $to_db['account_id'] = $purchase_ticket['account_id'];
            $to_db['time_purchased'] = $purchase_ticket['time_purchased'];
            $to_db['status'] = 'ok';
            $to_db['added'] = TIME;
            $API->DB->query("INSERT INTO premium_purchases " . $API->DB->build_insert_query($to_db));

            $current_premium = $API->DB->query_row("SELECT premium_expired FROM accounts WHERE id={$to_db['account_id']}");
            $current_premium = $current_premium['premium_expired'];
            if ($current_premium <= $CONFIG['TIME'])
                $current_premium = $CONFIG['TIME'];
            $to_account = array('premium_expired' => $current_premium + $to_db['time_purchased']);
            $API->DB->query("UPDATE accounts SET {$API->DB->build_update_query($to_account)} WHERE id={$to_db['account_id']}");

            $API->TPL->assign('message', $API->LANG->_('Your transaction has been completed'));

            $API->TPL->assign('warning', $API->LANG->_('We already extended your account to premium') . ':)');
            $API->TPL->display('message.tpl');
            die();
        }

        die();
    } else {
        $purchase_ticket['amount'] = round($allowed_days[$days] / get_bitcoin_price(0), 4); //number_format(($allowed_days[$days] / get_bitcoin_price(0)) + get_satoshi_secret(), 8, '.', '');
        $purchase_ticket['account_id'] = $API->account['id'];
        $purchase_ticket['time_purchased'] = $days * 86400;
        $purchase_ticket['added'] = TIME;
        $purchase_ticket['expires'] = TIME + $_EXPIRES_TIME;

        $API->TPL->assign('purchase_ticket', (encrypt(json_encode($purchase_ticket), $_BTC_PURCHASE_KEY)));

        $API->TPL->assign('amount', $purchase_ticket['amount']);
        $API->TPL->assign('days', $days);
        $API->TPL->assign('address', $_RECEIVER_ADDRESS);
        $API->TPL->assign('expires_time', $_EXPIRES_TIME);
        $API->TPL->display('premium-bitcoin-purchase.tpl');
        die();
    }
} elseif ($from == 'money2btc') {

    // IPN handler
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $transaction = (money2btc::check_transaction($_POST['data'], $_POST['request_key']));
        if (!$transaction->success)
            die();
        //var_dump($transaction->receipt['info']['uuid']);
        $to_db['uuid'] = $transaction->receipt['info']['uuid'];
        //check that OK transaction exists
        $current_transaction = $API->DB->query_row("SELECT * FROM premium_purchases WHERE uuid={$API->DB->sqlesc($to_db['uuid'])} AND status IN ('ok','pending_bc')");
        /* if ($current_transaction)
          die('duplicate transaction'); */
        $data = json_decode($transaction->decrypted_data, true);
        $to_db['account_id'] = $data['account_id'];
        $to_db['time_purchased'] = $data['time'];
        $to_db['status'] = $transaction->receipt['info']['status'];
        $to_db['added'] = TIME;
        $API->DB->query("INSERT INTO premium_purchases {$API->DB->build_insert_query($to_db)} ON DUPLICATE KEY UPDATE {$API->DB->build_update_query($to_db)}");
        if (!$current_transaction && in_array($to_db['status'], explode(',', 'pending_bc,ok'))) {

            $current_premium = $API->DB->query_row("SELECT premium_expired FROM accounts WHERE id={$to_db['account_id']}");
            $current_premium = $current_premium['premium_expired'];
            if ($current_premium <= $CONFIG['TIME'])
                $current_premium = $CONFIG['TIME'];
            $to_account = array('premium_expired' => $current_premium + $to_db['time_purchased']);
            $API->DB->query("UPDATE accounts SET {$API->DB->build_update_query($to_account)} WHERE id={$to_db['account_id']}");
        }
        die('OK');
    }
}
$API->auth();

$days = $API->getval('days', 'int');
$API->TPL->assign('pagetitle', $API->LANG->_('Extend account to premium'));
$API->TPL->assign('headeradd', '<link rel="stylesheet" type="text/css" href="./css/tracks.css"> ');
//assign menu class
$API->TPL->assign('footername', $API->LANG->_('Premium'));

if ($days) {

    $uuid = $API->getval('uuid');

    if ($uuid) {
        $transaction = $API->DB->query_row("SELECT * FROM premium_purchases WHERE account_id={$API->account['id']} AND uuid={$API->DB->sqlesc($uuid)}");
        if (!$transaction)
            $API->error('Payment gateway does not replied yet about your transaction. Please wait.');

        if ($transaction['status'] == 'ok' || $transaction['status'] == 'pending_bc')
            $API->TPL->assign('payment_status', $API->LANG->_('Completed'));
        elseif ($transaction['status'] == 'failed_gw' || $transaction['status'] == 'failed_bc')
            $API->TPL->assign('payment_status', $API->LANG->_('Failed'));
        elseif ($transaction['status'] == 'verified_sys')
            $API->TPL->assign('payment_status', $API->LANG->_('Verified'));

        $API->TPL->assign('transaction', $transaction);
    } else {

        if (!in_array($days, array_keys($allowed_days)))
            $API->error($API->LANG->_('Invalid package was selected for purchase'));

        $data = json_encode(array('account_id' => $API->account['id'], 'time' => $days * 86400));
        $m2btc = new money2btc($API->SEO->make_link('premium', 'days', $days, 'from', 'money2btc'), $data, "Buy AppAddict Premium", $allowed_days[$days], $_RECEIVER_ADDRESS);


        $API->TPL->assign('payment_status', $API->LANG->_('Acceptance of terms'));
        $API->TPL->assign('package', "Premium for {$days} days");
        $API->TPL->assign('checkout_button', $m2btc->get_form());

        $API->TPL->assign('bitcoin_button', '<h1>[<a href="' . $API->SEO->make_link('premium', 'days', $days, 'from', 'bitcoin') . '">Buy with bitcoins</a>]</h1>');
    }
    $API->TPL->display('premium-purchase.tpl');
    /* if ($_SERVER['REQUEST_METHOD']=='POST')
      var_dump($m2btc->check_transaction($_POST['data'],$_POST['request_key']));
      print $m2btc->get_form(); */
} else {
    $API->TPL->display('premium-list.tpl');
}
?>