<?php

if (!defined('INIT'))
    die('Direct access not allowed');

//define some other functions

function get_directinstaller_link($link) {
    global $API;

    if (preg_match('#mega\.co\.nz#si', $link)) {
        preg_match('/#(.*)/', $link, $matches);
        $data['hash'] = $matches[1];
        $data['time'] = $API->CONFIG['TIME'];
        if (!$data['hash']) {
            return false;
        }
        $dt = get_download_ticket($data);
        $link = mega_get_proxy_link($dt);
    }
    //fileaddict
    elseif (preg_match('#joycloud\.me#si', $link)) {
        //$link = preg_replace('/([^dl2\.]|dl1\.)thefilebay/', "\1thefilebay", $link); // dirty fucking fix
        //$link = str_replace('http://', 'https://', $link);
        //$data['ip'] = $API->getip();
        $data['time'] = $API->CONFIG['TIME'];
        $dt = get_download_ticket($data, 'jfsl2ygf978ga');
        $link = $link . (preg_match('#\?#si', $link) ? '&' : '?') . "aadt=" . urlencode($dt);
    }
    //thefilebay
    elseif (preg_match('#thefilebay\.(com|net)#si', $link)) {
        //$link = preg_replace('/([^dl2\.]|dl1\.)thefilebay/', "\1thefilebay", $link); // dirty fucking fix
        $link = str_replace('http://', 'https://', $link);
        //$data['ip'] = $API->getip();
        $data['time'] = $API->CONFIG['TIME'];
        $dt = get_download_ticket($data, 'filebay_341h13ldd');
        $link = $link . (preg_match('#\?#si', $link) ? '&' : '?') . "provider=rmuss&aadt=" . urlencode($dt);
    } elseif (preg_match("#(ul\.to|uploaded\.net|sendspace\.com|mediafire\.com|thefilebay|datafilehost\.com|oboom\.com|filepup\.net)#si", $link)) {
        $data['link'] = $link;
        $data['time'] = $API->CONFIG['TIME'];

        $dt = get_download_ticket($data);
        $link = debrid_get_proxy_link($dt);
    }/* elseif (preg_match('#isharecloud\.com#si', $link)) {
      //$data['ip'] = $API->getip();
      $data['time'] = $API->CONFIG['TIME'];
      $data['link'] = $link;

      $dt = get_download_ticket($data);
      $link = isharecloud_get_proxy_link($dt);
      } */ else {
        //$data['ip'] = $API->getip();
        $data['time'] = $API->CONFIG['TIME'];

        $dt = get_download_ticket($data);
        $link = $link . (preg_match('#\?#si', $link) ? '&' : '?') . "provider=rmuss&dt=" . urlencode($dt);
    }

    return $link;
}

function send_report_email($linkdata) {
    return true;
}

function mksize($bytes) {
    if ($bytes < 1000 * 1024)
        return number_format($bytes / 1024, 2) . " kB";
    elseif ($bytes < 1000 * 1048576)
        return number_format($bytes / 1048576, 2) . " MB";
    elseif ($bytes < 1000 * 1073741824)
        return number_format($bytes / 1073741824, 2) . " GB";
    else
        return number_format($bytes / 1099511627776, 2) . " TB";
}

function generate_short_link($link) {
    global $API;
    $return = curl_request("https://s.appaddict.org/index.php?k=aa_shorten&l=" . urlencode($link));
    if (!$return) {
        return false;
    }
    return $return;
    /*
      require_once('classes/GoogleUrlApi.class.php');
      $gapi = new GoogleUrlApi($API->CONFIG['GOOGLE_API_KEY']);
      $link = $gapi->shorten($link);
      return $link; */
}

function curl_request($url, $user_agent = '') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    if ($user_agent) {
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    }
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    return curl_exec($ch);
}

function check_filehosting_link($link) {

    if (preg_match('/^magnet/', $link))
        return false;
    $response_format = "json";
    $url = 'http://urlchecker.net/api.php';
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "response_format=$response_format&link=$link");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 0);

    $response = curl_exec($ch);
    $response = json_decode($response, true);

    if ($response['status'] == 'working') {
        return true;
    } else {
        return false;
    }
}

function update_app_downloads($trackid) {
    global $API;
    $to_db['trackid'] = $trackid;
    $to_db['added'] = strtotime('today');
    $to_db['clicks'] = 1;
    $API->DB->query("INSERT LOW_PRIORITY INTO download_stats_archive {$API->DB->build_insert_query($to_db)} ON DUPLICATE KEY UPDATE clicks=clicks+1");
    $API->DB->query("UPDATE apps SET clicks_day=clicks_day+1 WHERE trackid=$trackid");
}

function is_device_compatible($compatibility_string, $model, $ios_version) {

    if (!$model || !$ios_version)
        return true;

    preg_match('/(.*?)[0-9],[0-9]/', $model, $matches);
    $device = $matches[1];
    if (!preg_match("/$device/", $compatibility_string))
        return false;


    preg_match("/Requires iOS (.*?) or/", $compatibility_string, $matches);
    $ios = $matches[1];
    $ios = (int) str_pad(str_replace('.', '', $ios), 3, '0');
    $ios_version = (int) str_pad(str_replace('.', '', $ios_version), 3, '0');
    if ($ios_version < $ios)
        return false;

    return true;
}

function nice_idevice_model($model) {
    $models = array("iPhone1,1" => "iPhone 1G",
        "iPhone1,2" => "iPhone 3G",
        "iPhone2,1" => "iPhone 3GS",
        "iPhone3,1" => "iPhone 4 (GSM)",
        "iPhone3,2" => "iPhone 4 (GSM Rev A)",
        "iPhone3,3" => "iPhone 4 (CDMA)",
        "iPhone4,1" => "iPhone 4S",
        "iPhone5,1" => "iPhone 5 (GSM)",
        "iPhone5,2" => "iPhone 5 (GSM+CDMA)",
        "iPhone5,3" => "iPhone 5C (GSM)",
        "iPhone5,4" => "iPhone 5C (GSM+CDMA)",
        "iPhone6,1" => "iPhone 5S (GSM)",
        "iPhone6,2" => "iPhone 5S (GSM+CDMA)",
        "iPod1,1" => "iPod Touch 1G",
        "iPod2,1" => "iPod Touch 2G",
        "iPod3,1" => "iPod Touch 3G",
        "iPod4,1" => "iPod Touch 4G",
        "iPod5,1" => "iPod Touch 5G",
        "iPad1,1" => "iPad",
        "iPad2,1" => "iPad 2 (WiFi)",
        "iPad2,2" => "iPad 2 (GSM)",
        "iPad2,3" => "iPad 2 (CDMA)",
        "iPad2,4" => "iPad 2 (WiFi)",
        "iPad2,5" => "iPad Mini (WiFi)",
        "iPad2,6" => "iPad Mini (GSM)",
        "iPad2,7" => "iPad Mini (GSM+CDMA)",
        "iPad4,4" => "iPad Mini 2G (WiFi)",
        "iPad4,5" => "iPad Mini 2G (Cellular)",
        "iPad3,1" => "iPad 3 (WiFi)",
        "iPad3,2" => "iPad 3 (GSM+CDMA)",
        "iPad3,3" => "iPad 3 (GSM)",
        "iPad3,4" => "iPad 4 (WiFi)",
        "iPad3,5" => "iPad 4 (GSM)",
        "iPad3,6" => "iPad 4 (GSM+CDMA)",
        "iPad4,1" => "iPad Air (WiFi)",
        "iPad4,2" => "iPad Air (Cellular)");

    return ($models[$model] ? $models[$model] : 'Uknown model');
}

function encrypt($text, $salt) {
    if (!$text)
        return false;
    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

function decrypt($text, $salt) {
    if (!$text)
        return false;
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}

function upload_application($data) {
    global $API, $CONFIG;

    $to_artists = $data['artist'];
    $API->DB->query("INSERT INTO artists " . $API->DB->build_insert_query($to_artists));

    $to_genres = $data['genre'];
    // fix for MAS genres
    if (($data['genre']['id'] > 9000) & ($data['genre']['id'] < 12000)) {
        $to_genres['type'] = 'books';
    }
    if ($data['genre']['id'] > 12000) {
        $to_genres['type'] = 'mas';
    }
    $API->DB->query("INSERT INTO genres " . $API->DB->build_insert_query($to_genres));

    $to_apps = array(
        'trackid' => $data['trackid'],
        'name' => $data['name'],
        'genre_id' => $data['genre']['id'],
        'image' => $data['image'],
        'version' => $data['version'],
        'artist_id' => $data['artist']['id'],
        'added' => TIME,
        'compatibility' => $data['compatibility'],
        'price' => $data['price'],
        'rating_count' => extract_rating_count($data['ratings']['current']),
        'rating' => extract_rating_stars($data['ratings']['current']),
        'last_parse_itunes' => json_encode($data['last_parse_itunes']),
        'store' => $data['store'],
        'type' => $data['type'],
        'bundle_id' => $data['bundleid'],
        'last_parse_timestamp' => TIME,
        'compatibility_string' => $data['requirements'],
    );

    $API->DB->query("INSERT INTO apps " . $API->DB->build_insert_query($to_apps));

    if ($API->DB->mysql_errno()) {
        //var_dump($API->DB->mysql_error());
        return false;
    }
    return true;
}

function update_application($data, $trackid) {
    global $API, $CONFIG;

    $to_apps = array(
        'trackid' => $data['trackid'],
        'name' => $data['name'],
        'genre_id' => $data['genre']['id'],
        'image' => $data['image'],
        'version' => $data['version'],
        'artist_id' => $data['artist']['id'],
        'compatibility' => $data['compatibility'],
        'price' => $data['price'],
        'rating_count' => extract_rating_count($data['ratings']['current']),
        'rating' => extract_rating_stars($data['ratings']['current']),
        'last_parse_itunes' => json_encode($data['last_parse_itunes']),
        'store' => $data['store'],
        'type' => $data['type'],
        'bundle_id' => $data['bundleid'],
        'last_parse_timestamp' => TIME,
        'compatibility_string' => $data['requirements'],
    );
    $API->DB->query("UPDATE links SET state='archived' WHERE trackid=$trackid AND version!={$API->DB->sqlesc($data['version'])}");


    $API->DB->query("UPDATE apps SET " . $API->DB->build_update_query($to_apps) . " WHERE trackid=$trackid");

    if ($API->DB->mysql_errno()) {
        return false;
    }
    return true;
}

function db_compatibility($s) {
    if (preg_match('#OS X#si', $s)) {
        return 4;
    }
    if (preg_match('#iphone#si', $s)) {
        $iphone = true;
    }
    if (preg_match('#ipad#si', $s)) {
        $ipad = true;
    }
    if ($iphone & $ipad)
        return 1;
    elseif ($iphone)
        return 2;
    elseif ($ipad)
        return 3;
    else
        return 0;
}

function extract_rating_count($s) {
    preg_match('#([0-9]+) Rating#si', $s, $matches);
    return $matches[1];
}

function extract_rating_stars($s) {
    preg_match('#([0-9]+) (.*?),#si', $s, $matches);
    return $matches[1] . (preg_match('/half/', $matches[2]) ? '.5' : '');
}

function send_tracks($data) {
    global $API, $CONFIG;
    $accounts = $API->DB->query_return("SELECT tracks.*,accounts.email,accounts.email_notifications,accounts.safari_push_notifications,accounts.push_notifications FROM tracks LEFT JOIN accounts ON tracks.account_id=accounts.id WHERE tracks.trackid={$data['trackid']}");

    $link = $API->SEO->make_link('view', 'trackid', $data['trackid']);
    if ($accounts) {
        foreach ($accounts as $a) {

            $email_notifications = explode(',', $a['email_notifications']);
            $safari_push_notificaions = explode(',', $a['safari_push_notifications']);
            $push_notificaions = explode(',', $a['push_notifications']);

            if ($push_notifications[0] != 'none' || !$push_notifications[0] || in_array('tracks', $push_notifications)) {

                $push_accounts[] = $a['account_id'];
            }
            if ($email_notifications[0] != 'none' || !$email_notifications[0] || in_array('tracks', $email_notifications)) {

                $body = "{$API->LANG->_to($a['account_id'], 'Hello')}!<br/>
        {$API->LANG->_to($a['account_id'], 'App "%s", that you are tracking has been updated to version %s', $data['name'], $data['version'])}.<br/>
        {$API->LANG->_to($a['account_id'], 'You can download app here')}:<br/>
        {$link}
        <br/><br/>
        --<br/>
        {$API->LANG->_to($a['account_id'], 'Best regards, team of')} {$CONFIG['sitename']}.";
                //$API->send_mail($a['email'], $CONFIG['sitename'], $CONFIG['siteemail'], $API->LANG->_to($a['account_id'], "App that you are tracking has been updated to v.%s!", $data['version']), $body);
            }
            if ($safari_push_notificaions[0] != 'none' || !$safari_push_notificaions[0] || in_array('tracks', $safari_push_notificaions)) {
                send_push_safari($API->LANG->_to($a['account_id'], 'Notification'), $API->LANG->_to($a['account_id'], "App that you are tracking has been updated to v.%s!", $data['version']), $link, $a['account_id']);
            }
        }

        if ($push_accounts) {
            $devices = $API->DB->query_return("SELECT push.udid,accounts.id FROM push LEFT JOIN accounts ON push.account_id=accounts.id WHERE account_id IN(" . $API->DB->sqlesc(implode(',', $push_accounts)) . ")");
            if ($devices) {
                foreach ($devices as $d) {
                    $udidslang[$API->LANG->getlang($d['id'])][] = $d['udid'];
                }
                $json = json_encode(array('id' => $data['trackid']));
                foreach ($udidslang as $lang => $udids) {
                    send_push($API->LANG->_translate($lang, "App %s has been updated", $data['name']), 3, $json, implode(',', $udids));
                }
            }
        }
    }
}

/*
 * 0 - message
 * 1 - news
 * 2 - wishes // unneeded
 * 3 - tracks
 * 4 - report reviewed
 * 5 - updates
 * 6 - dead links
 * 7 - ota install
 */

function send_push($message, $type = 0, $data_str = '', $udids_str = '', $content_available_flag = 0, $notification_type = 'all') {
    global $CONFIG, $API;
    if (!$udids_str) {
        $udids_str = $API->DB->query_return("SELECT udid FROM push");
        // workaround, sending with packs of 1000 udids
        $count = 0;
        $udids_temp = array();
        foreach ($udids_str as $udid) {
            $count++;
            $udids_temp[] = $udid['udid'];
            if ($count % 1000 == 0) {
                $udids_to_script = implode(',', $udids_temp);
                exec('php ' . $CONFIG['ROOT_PATH'] . 'pusher-single.php ' . escapeshellarg($message) . ' ' . escapeshellarg($type) . ' ' . escapeshellarg($data_str) . ' ' . escapeshellarg($content_available_flag) . ' ' . escapeshellarg($notification_type) . ' ' . escapeshellarg($udids_to_script) . ' > /dev/null 2>&1 &');
                $udids_temp = array();
            }
        }

        // last <1000 udids
        if ($udids_temp) {
            $udids_to_script = implode(',', $udids_temp);
            exec('php ' . $CONFIG['ROOT_PATH'] . 'pusher-single.php ' . escapeshellarg($message) . ' ' . escapeshellarg($type) . ' ' . escapeshellarg($data_str) . ' ' . escapeshellarg($content_available_flag) . ' ' . escapeshellarg($notification_type) . ' ' . escapeshellarg($udids_to_script) . ' > /dev/null 2>&1 &');
        }
    } else {
        exec('php ' . $CONFIG['ROOT_PATH'] . 'pusher-single.php ' . escapeshellarg($message) . ' ' . escapeshellarg($type) . ' ' . escapeshellarg($data_str) . ' ' . escapeshellarg($content_available_flag) . ' ' . escapeshellarg($notification_type) . ' ' . escapeshellarg($udids_str) . ' > /dev/null 2>&1 &');
    }
}

function send_push_safari($title, $body, $url, $account_ids = '', $notification_type = 'all') {
    global $CONFIG, $API;
    if ($account_ids) {
        $account_ids = explode(',', $account_ids);
        if (count($account_ids) > 1000) {
            $account_ids = array_chunk($account_ids, 1000);
            foreach ($account_ids as $aids_part) {
                exec('php ' . $CONFIG['ROOT_PATH'] . 'pusher-safari.php ' . escapeshellarg($title) . ' ' . escapeshellarg($body) . ' ' . escapeshellarg($url) . ' ' . escapeshellarg($notification_type) . ' ' . escapeshellarg(implode(',', $aids_part)) . ' > /dev/null 2>&1 &');
            }
        }
    } else {
        exec('php ' . $CONFIG['ROOT_PATH'] . 'pusher-safari.php ' . escapeshellarg($title) . ' ' . escapeshellarg($body) . ' ' . escapeshellarg($url) . ' ' . escapeshellarg($notification_type) . ' ' . escapeshellarg($account_ids) . ' > /dev/null 2>&1 &');
    }
}

function send_twitter($message, $account = 'main') {

    if ($account == 'main') {
        $consumer_key = 'fhQHlyfAtbSz0XK8Js3XA';
        $consumer_secret = '1tXMngr97AOUKmbemGmm709rHDSwPZxM54W364btL4';
        $access_token = '1078024868-aOT7v9d5Z51o53R0OsEVOF9YgqiXnjv5yMy0mC4';
        $access_token_secret = 'iNl5CXKUbynzYrwTnAih9kei8ESmZqb9OQEBV673oXZYl';
    } elseif ($account == 'updates') {
        $consumer_key = 'V9FZhK26VIzL6jPDsiRXxA';
        $consumer_secret = 'hrc3wo2daSoPcm6mR4bR6yv60eYQOWjt7omE3iWK6I';
        $access_token = '1305474655-8OcQug9Ewf2LLI9K1dMl7xZMXtRaMwU22fCKWjO';
        $access_token_secret = 'yjTZakzqrbJfgydHyeenRQj7jnm6aZPVWgqfNjc0';
    }

    require_once('classes' . DS . 'twitteroauth' . DS . 'twitteroauth.php');

    $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);

    //$content = $connection->get('account/verify_credentials');
    $connection->post('statuses/update', array('status' => $message));

    return true;
}

function app_error_message($trackid, $type = 'app', $store = 'us') {
    global $API;
    if (!$store)
        $store = 'us';
    if (!$type)
        $type = 'app';
    $API->TPL->assign('trackid', $trackid);
    require_once 'itgw-native.inc.php';
    $data = get_itunes_info_native($trackid, $store);
    if (!$data['resultCount'])
        $API->TPL->display('no-app-error-itunes.tpl');
    else {
        $API->TPL->assign('data', $data['results'][0]);
        $API->TPL->display('no-app-error-db.tpl');
    }
    die();
}

function get_download_ticket($data, $secret = '3hlsdFEf') {
    return encrypt(json_encode($data), $secret);
}

function is_directinstaller_compatible($host) {
    if (preg_match('#(ifilez\.co|mega\.co\.nz|ul\.to|uploaded\.net|sendspace\.com|mediafire\.com|thefilebay|datafilehost\.com|oboom\.com|filepup\.net|joycloud\.me)#si', $host))
        return true;
    else
        return false;
}

function is_signservice_compatible($host) {
    if (preg_match('#(ifilez\.co|mega\.co\.nz|ul\.to|uploaded\.net|sendspace\.com|mediafire\.com|thefilebay|datafilehost\.com|oboom\.com|filepup\.net|joycloud\.me)#si', $host))
        return true;
    else
        return false;
}

function parse_itunes_url($url) {
    preg_match('/\/([a-z]{0,2})\/?(app|book)\/.*id([0-9]+)/', $url, $matches);
    if (!$matches)
        return false;
    else {
        if (!$matches[1])
            $matches[1] = 'us';
        return array('store' => $matches[1], 'type' => $matches[2], 'trackid' => $matches[3]);
    }
}

function is_premium() {
    global $API;

    if (defined('_is_premium'))
        return _is_premium;
    if (!$API->account) {
        define('_is_premium', false);
        return false;
    }
    if ($API->account['premium_expired'] < $API->CONFIG['TIME']) {
        define('_is_premium', false);
        return false;
    } else {
        define('_is_premium', true);
        return true;
    }
}

// mega integration functions
function a32_to_str($hex) {
    return call_user_func_array('pack', array_merge(array('N*'), $hex));
}

function aes_ctr_decrypt($data, $key, $iv) {
    return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, 'ctr', $iv);
}

function base64_to_a32($s) {
    return str_to_a32(base64urldecode($s));
}

function base64urldecode($data) {
    $data .= substr('==', (2 - strlen($data) * 3) % 4);
    $data = str_replace(array('-', '_', ','), array('+', '/', ''), $data);
    return base64_decode($data);
}

function str_to_a32($b) {
    // Add padding, we need a string with a length multiple of 4
    $b = str_pad($b, 4 * ceil(strlen($b) / 4), "\0");
    return array_values(unpack('N*', $b));
}

function mega_api_req($req) {
    global $seqno, $sid;

    $ch = curl_init('https://g.api.mega.co.nz/cs?id=' . ($seqno++) . ($sid ? '&sid=' . $sid : ''));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array($req)));
    $resp = curl_exec($ch);
    curl_close($ch);
    $resp = json_decode($resp);
    return $resp[0];
}

function mega_get_file_url($id) {
    $dl_url = mega_api_req(array('a' => 'g', 'g' => 1, 'p' => $id));
    return $dl_url->g;
}

function mega_dec_attr($attr, $key) {
    $attr = trim(aes_cbc_decrypt($attr, a32_to_str($key)));
    if (substr($attr, 0, 6) != 'MEGA{"') {
        return false;
    }
    return json_decode(substr($attr, 4));
}

function mega_download_file($id, $k, $iv, $meta_mac) {
    $dl_url = mega_api_req(array('a' => 'g', 'g' => 1, 'p' => $id));
    $ch = curl_init($dl_url->g);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    $data_enc = curl_exec($ch);
    curl_close($ch);
    $data = aes_ctr_decrypt($data_enc, a32_to_str($k), a32_to_str($iv));
    file_put_contents('../torrents/test.ipa', $data);
    /* $file_mac = cbc_mac($data, $k, $iv);
      print "\nchecking mac\n";
      if (array($file_mac[0] ^ $file_mac[1], $file_mac[2] ^ $file_mac[3]) != $meta_mac) {
      echo "MAC mismatch";
      } */
}

function mega_get_file_keys($hash) {
    preg_match('/\!(.*?)\!(.*)/', $hash, $matches);

    $id = $matches[1];
    $key = $matches[2];
    $key = base64_to_a32($key);
    $k = array($key[0] ^ $key[4], $key[1] ^ $key[5], $key[2] ^ $key[6], $key[3] ^ $key[7]);
    $iv = array_merge(array_slice($key, 4, 2), array(0, 0));
    $meta_mac = array_slice($key, 6, 2);
    return array('id' => $id, 'key' => $key, 'k' => $k, 'iv' => $iv, 'meta_mac' => $meta_mac);
}

function mega_get_proxy_link($dt) {
    $proxies = array('http://pixi.appaddict.org/mega/?dt=%s', 'http://fairy.appaddict.org/mega/?dt=%s');
    return sprintf($proxies[array_rand($proxies)], urlencode($dt));
}

function debrid_get_proxy_link($dt) {
    $proxies = array('http://pixi.appaddict.org/d/?dt=%s', 'http://fairy.appaddict.org/d/?dt=%s');
    return sprintf($proxies[array_rand($proxies)], urlencode($dt));
}

/* function isharecloud_get_proxy_link($dt) {
  $proxies = array('http://pixi.appaddict.org/isc/?dt=%s', 'http://fairy.appaddict.org/isc/?dt=%s');
  return sprintf($proxies[array_rand($proxies)], urlencode($dt));
  } */
?>