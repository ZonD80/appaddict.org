<?php

// app gateway v2
require_once 'init.php';
header('Content-type: application/json; charset=utf-8');

define('INTERNAL_API_KEY', 'AFH2qkuhfks');

function result($status = array(), $data = array()) {
    die(json_encode(array('error' => $status, 'response' => $data)));
}

function generate_nonce() {

    $nonce = md5(uniqid() + rand(0, 65536));
    $_SESSION['nonce'] = $nonce;
    return $nonce;
}

function check_nonce($nonce) {
    if ($_SESSION['nonce'] != $nonce) {
        return false;
    }
    unset($_SESSION['nonce']);
    return true;
}

$allowed_service_requests = array();
if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'AppAddict Legacy') {
    define('API_KEY', 'heyvqkddjq');
    $allowed_service_requests = explode(',', "login,list_genres,list_devs,list_news,register_device,list_tracks,get_directinstaller_link,get_signservice_link,list_account,trackid_lookup,track_lookup,report,get_links,get_archived_links,get_updates");
} elseif ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'AppAddict Official') {
    $allowed_service_requests = explode(',', "login,list_genres,list_devs,list_news,register_device,list_tracks,get_directinstaller_link,get_signservice_link,list_account,trackid_lookup,track_lookup,report,get_links,get_archived_links,get_updates");

    define('API_KEY', 'NINJASUCKS');
} elseif ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'CrackAddict Official') {
    $allowed_service_requests = explode(',', "login,list_genres,list_devs,list_account,trackid_lookup");

    define('API_KEY', 'CAISTHEBEST');
} elseif ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'appfigures.com') {
    $allowed_service_requests = explode(',', "list_genres,list_devs,trackid_lookup");

    define('API_KEY', 'fsh21hfGFHo1g');
} elseif ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'IPASpy Client') {
    $allowed_service_requests = explode(',', "login,list_genres,list_devs,trackid_lookup");

    define('API_KEY', '12fhsfh1oDH');
} elseif ($_SERVER['HTTP_X_REQUESTED_WITH'] == '$%yOl0sweg') { // tjglass shit
    $allowed_service_requests = explode(',', "login,list_genres,list_devs,trackid_lookup,get_directinstaller_link,get_links,get_archived_links");

    define('API_KEY', 'rejg2FHo2hf');
} elseif ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'ASTweak Official') { // Addixion Tweak
    $allowed_service_requests = explode(',', "trackid_lookup");

    define('API_KEY', 'apptrackrsucks');
} else {
    //die(decrypt('sQYnBylgX3kNqxNhhDFZ6lqE9U8oQWyCFkpQxFeDH/NS3mxi6/LAjgzvVBWOPTCP', 'API_KEY'));
    die('unknown app');
}


if (isset($_GET['get_nonce'])) {
    result(array(), encrypt(json_encode(array('ts' => TIME, 'nonce' => uniqid())), INTERNAL_API_KEY));
}

//result('Maintenance mode');
//die(var_dump($_SERVER));
$id = $API->getval('id', 'int');
$q = trim($API->getval('q'));
$genre = $API->getval('genre', 'int');
$dev = $API->getval('dev', 'int');
$sort = $API->getval('sort');
$order = $API->getval('order');
$trackid = array_map('intval', $API->getval('trackid', 'array'));
$type = htmlspecialchars($API->getval('type'));

if (!in_array($type, array('app', 'book', '')))
    $type = '';

if (!$trackid) {
    $trackid = array($API->getval('trackid', 'int'));
    if ($trackid[0] == 0)
        unset($trackid);
}
$perpage = $API->getval('perpage', 'int');

if (!$perpage || $perpage < 0 || $perpage > 100) {
    $perpage = 50;
}



$service_request = $API->getval('service_request');

if ($service_request && !in_array($service_request, $allowed_service_requests))
    result('Unknown or forbidden service request');

if ($service_request == 'get_updates') {
    $bundle_ids = $API->getval('bundle_ids', 'array');
    $versions = $API->getval('versions', 'array');
    $compatibilities = $API->getval('compatibilities', 'array');

    foreach ($bundle_ids as $k => $bundle_id) {
        $bundle_id = htmlspecialchars((string) $bundle_id);
        $compatibility = (string) $compatibilities[$k];
        $version = (string) $versions[$k];
        //$API->DB->debug();
        $appdata = $API->DB->query_row("SELECT trackid,version,store FROM apps WHERE bundle_id={$API->DB->sqlesc($bundle_id)} AND compatibility={$API->DB->sqlesc($compatibility)}");
        if ($appdata) {

            $links = array();

            $result[$bundle_id]['trackid'] = $appdata['trackid'];
            $result[$bundle_id]['store'] = $appdata['store'];
            $version_no_dots = (int) str_replace('.', '', $version);
            $version_length = strlen($version_no_dots);


            if (preg_match('/[0-9]+/', $version_no_dots) && ($version != $appdata['version'])) {
                $links = $API->DB->query_return("SELECT links.*, verified_crackers.account_id AS verified FROM links LEFT JOIN verified_crackers ON links.uploader_id=verified_crackers.account_id WHERE trackid={$appdata['trackid']} AND state IN ('accepted','archived') AND version<>{$API->DB->sqlesc($version)} ORDER BY links.added ASC");

                // derermine longest version
                foreach ($links as $k => $ldetails) {
                    $link_version_no_dots = (int) str_replace('.', '', $ldetails['version']);
                    $link_version_length = strlen($link_version_no_dots);
                    $max_length = ($version_length > $link_version_length ? $version_length : $link_version_length);
                }

                $version_no_dots = str_pad($version_no_dots, $max_length, '0');
                // filtering links

                foreach ($links as $k => $ldetails) {
                    $link_version_no_dots = (int) str_replace('.', '', $ldetails['version']);
                    $link_version_no_dots = str_pad($link_version_no_dots, $max_length, '0');
                    if ($link_version_no_dots <= $version_no_dots) {
                        unset($links[$k]);
                    }
                }
            } elseif (($version != $appdata['version'])) {
                $links = $API->DB->query_return("SELECT links.*, verified_crackers.account_id AS verified FROM links LEFT JOIN verified_crackers ON links.uploader_id=verified_crackers.account_id WHERE trackid={$appdata['trackid']} AND state IN ('accepted','archived') ORDER BY links.added ASC");
            } else {
                unset($result[$bundle_id]);
                //$result[$bundle_id]['links'] = array();
            }

            if ($links) {

                $wait = ($API->account ? 0 : $API->CONFIG['redirection_wait']); //(is_premium() ? 0 : $API->CONFIG['redirection_wait']);
                foreach ($links as $ldetails) {
                    $ldata = parse_url($ldetails['link']);
                    $link_ticket = urlencode(encrypt(json_encode(array('link' => $ldetails['link'], 'wait' => $wait, 'ua' => $_SERVER['HTTP_USER_AGENT'], 'ip' => $API->getip())), $API->CONFIG['REDIRECTOR_SECRET']));

                    if ($ldata['scheme'] == 'magnet') {
                        $result[$bundle_id]['links'][$ldetails['version']][] = array('id' => $ldetails['id'], 'no_redirection' => true, 'domain' => $ldata['host'], 'host' => $API->LANG->_('.torrent magnet link'), 'link' => $ldetails['link'], 'cracker' => $ldetails['cracker'], 'verified' => ($ldetails['verified'] ? true : false), 'di_compatible' => false, 'ss_compatible' => false, 'protected' => $ldetails['protected']);
                    } else {
                        $result[$bundle_id]['links'][$ldetails['version']][] = array('id' => $ldetails['id'], 'no_redirection' => false, 'domain' => $ldata['host'], 'host' => $API->LANG->_('Download from %s', $ldata['host']), 'link' => $API->SEO->make_link('redirector', 'lt', $link_ticket), 'cracker' => $ldetails['cracker'], 'verified' => ($ldetails['verified'] ? true : false), 'di_compatible' => (($appdata['compatibility'] != 4) && $appdata['type'] != 'book' && is_directinstaller_compatible($ldetails['link'])), 'ss_compatible' => (($appdata['compatibility'] != 4) && $appdata['type'] != 'book' && is_signservice_compatible($ldetails['link'])), 'protected' => $ldetails['protected']);
                    }
                }
            }
        }
    }

    result(array(), $result);
} elseif ($service_request == 'get_links') {
    $links = array();
    $decoded_links = $API->DB->query_return("SELECT links.*, verified_crackers.account_id AS verified FROM links LEFT JOIN verified_crackers ON links.uploader_id=verified_crackers.account_id WHERE trackid={$trackid[0]} AND state='accepted' ORDER BY links.added ASC");

    /* if (!$decoded_links) {
      // AppCake time, it's user id 351249
      $cake_links = json_decode(curl_request('http://apiv2.iphonecake.com/appcake/appcake_api/s4/links.php?app=appcake&id=' . $trackid[0], 'iPhoneCake/18 (iPad; iOS 7.1.2; Scale/2.00)'), true);
      if ($cake_links['links']) {
      foreach ($cake_links['links'] as $l) {
      $to_links['version'] = htmlspecialchars($l['version']);
      $to_links['cracker'] = htmlspecialchars($l['cracker']);
      $to_links['link'] = $l['url'];
      $to_links['uploader_id'] = 351249;
      $to_links['added'] = TIME;
      $to_links['trackid'] = $trackid[0];
      $to_links['state'] = 'accepted';
      $API->DB->query("INSERT INTO links {$API->DB->build_insert_query($to_links)}");
      $link_id = $API->DB->mysql_insert_id();
      if ($link_id) {
      $to_links['id'] = $link_id;
      $to_links['verified'] = 0;
      $decoded_links[] = $to_links;
      }
      }
      }
      } */

    if ($decoded_links) {

        $wait = ($API->account ? 0 : $API->CONFIG['redirection_wait']); //(is_premium() ? 0 : $API->CONFIG['redirection_wait']);
        foreach ($decoded_links as $ldetails) {

            $ldata = parse_url($ldetails['link']);
            $link_ticket = urlencode(encrypt(json_encode(array('link' => $ldetails['link'], 'wait' => $wait, 'ua' => $_SERVER['HTTP_USER_AGENT'], 'ip' => $API->getip())), $API->CONFIG['REDIRECTOR_SECRET']));

            if ($ldata['scheme'] == 'magnet') {
                $links[] = array('id' => $ldetails['id'], 'no_redirection' => true, 'domain' => $ldata['host'], 'host' => $API->LANG->_('.torrent magnet link'), 'link' => $ldetails['link'], 'cracker' => $ldetails['cracker'], 'verified' => ($ldetails['verified'] ? true : false), 'di_compatible' => false, 'ss_compatible' => false, 'protected' => $ldetails['protected']);
            } else {

                $links[] = array('id' => $ldetails['id'], 'no_redirection' => false, 'domain' => $ldata['host'], 'host' => $API->LANG->_('Download from %s', $ldata['host']), 'link' => $API->SEO->make_link('redirector', 'lt', $link_ticket), 'cracker' => $ldetails['cracker'], 'verified' => ($ldetails['verified'] ? true : false), 'di_compatible' => (($appdata['compatibility'] != 4) && $appdata['type'] != 'book' && is_directinstaller_compatible($ldetails['link'])), 'ss_compatible' => (($appdata['compatibility'] != 4) && $appdata['type'] != 'book' && is_signservice_compatible($ldetails['link'])), 'protected' => $ldetails['protected']);
            }
        }
    }
    result(array(), $links);
} elseif ($service_request == 'get_archived_links') {
    $links = array();
    $decoded_links = $API->DB->query_return("SELECT links.*, verified_crackers.account_id AS verified FROM links LEFT JOIN verified_crackers ON links.uploader_id=verified_crackers.account_id WHERE trackid={$trackid[0]} AND state='archived' ORDER BY links.added ASC");

    if ($decoded_links) {

        $wait = ($API->account ? 0 : $API->CONFIG['redirection_wait']); //(is_premium() ? 0 : $API->CONFIG['redirection_wait']);
        foreach ($decoded_links as $ldetails) {

            $ldata = parse_url($ldetails['link']);

            if ($ldata['scheme'] == 'magnet') {
                $links[$ldetails['version']][] = array('id' => $ldetails['id'], 'domain' => $ldata['host'], 'no_redirection' => true, 'host' => $API->LANG->_('.torrent magnet link'), 'link' => $ldetails['link'], 'cracker' => $ldetails['cracker'], 'verified' => ($ldetails['verified'] ? true : false), 'di_compatible' => (($appdata['compatibility'] != 4) && $appdata['type'] != 'book' && is_directinstaller_compatible($ldetails['link'])), 'protected' => $ldetails['protected']);
            } else {
                $link_ticket = urlencode(encrypt(json_encode(array('link' => $ldetails['link'], 'wait' => $wait, 'ua' => $_SERVER['HTTP_USER_AGENT'], 'ip' => $API->getip())), $API->CONFIG['REDIRECTOR_SECRET']));

                $links[$ldetails['version']][] = array('id' => $ldetails['id'], 'domain' => $ldata['host'], 'no_redirection' => false, 'host' => $API->LANG->_('Download from %s', $ldata['host']), 'link' => $API->SEO->make_link('redirector', 'lt', $link_ticket), 'cracker' => $ldetails['cracker'], 'verified' => ($ldetails['verified'] ? true : false), 'di_compatible' => (($appdata['compatibility'] != 4) && $appdata['type'] != 'book' && is_directinstaller_compatible($ldetails['link'])), 'ss_compatible' => (($appdata['compatibility'] != 4) && $appdata['type'] != 'book' && is_signservice_compatible($ldetails['link'])), 'protected' => $ldetails['protected']);
            }
        }
    }
    result(array(), $links);
} elseif ($service_request == 'report') {
    if (!$API->account['id']) {
        result('Not logged in');
    }
    $reason = htmlspecialchars($API->getval('reason'));

    if (!$reason || !$id) {
        result('No reason, Link or ID provided');
    }

    $url = $API->DB->query_row("SELECT links.*, accounts.name, accounts.email FROM links LEFT JOIN accounts ON links.uploader_id=accounts.id WHERE links.id=$id AND state<>'reported' AND protected=0");

    if (!$url) {
        $API->error("{$API->LANG->_('This link was already reported by you or another user.')}<br/>{$API->LANG->_('or')}<br/>{$API->LANG->_('There is no link you are reporting to.')}<br/>{$API->LANG->_('or')}<br/>{$API->LANG->_('This link is protected from reports.')}");
    }

    if (check_filehosting_link($url['link']) && !$_SESSION['confirm_report_' . $id]) {
        $_SESSION['confirm_report_' . $id] = true;
        result('Your report was rejected - link is live. Please request again to report live link');
    }

    unset($_SESSION['confirm_report_' . $id]);

    $to_links['state'] = 'reported';
    $to_links['state_reason'] = $reason;
    $to_links['editor_id'] = $API->account['id'];

    $API->DB->query("UPDATE links SET {$API->DB->build_update_query($to_links)} WHERE id=$id");

    send_report_email($url);


    result(array(), 'Reported Successfully');
} elseif ($service_request == 'track_lookup') {
    if (!$API->account['id'])
        result('Not logged in');
    $check = $API->DB->query_row("SELECT 1 FROM tracks WHERE trackid={$API->DB->sqlesc($trackid[0])} AND account_id={$API->account['id']}");
    if (!$check) {
        $check = false;
    } else {
        $check = true;
    }
    result(array(), $check);
} elseif ($service_request == 'trackid_lookup') {
    $bundle_ids = array_map('htmlspecialchars', $API->getval('bundle_id', 'array'));
    $compatibility = $API->getval('compatibility', 'int');

    if (!$compatibility || $compatibility > 4 || $compatibility < 0) {
        $compatibility_sql = "1=1";
    } elseif ($compatibility == 1) {

        $compatibility_sql = "compatibility<>4";
    } elseif ($compatibility == 2) {

        $compatibility_sql = "compatibility NOT IN(3,4)";
    } elseif ($compatibility == 3) {

        $compatibility_sql = "compatibility NOT IN(2,4)";
    } elseif ($compatibility == 4) {
        $compatibility_sql = "(apps.type='book' OR compatibility=4)";
    }

    if (!$bundle_ids)
        result('Missing bundle IDs');
    foreach ($bundle_ids as $bid) {
        $bids_orig[strtolower($bid)] = $bid;
    }
    $bundle_ids = array_map(array($API->DB, 'sqlesc'), $bundle_ids);
    //var_dump($bundle_ids);
    $trackids = $API->DB->query_return("SELECT bundle_id,trackid FROM apps WHERE bundle_id IN (" . implode(',', $bundle_ids) . ") AND $compatibility_sql");
    //var_dump($trackids);
    if ($trackids) {
        foreach ($trackids as $tdata) {
            // fuck apple here for case-sensitive bids
            $result[$bids_orig[strtolower($tdata['bundle_id'])]][] = (int) $tdata['trackid'];
            //var_dump($result);
        }
        result(array(), $result);
    } else
        result('TrackID for this bundle ID was not found');
}
elseif ($service_request == 'login') {
    $email = $API->getval('email');
    $password = $API->getval('password');
    result(array(), $API->login_account($email, $password));
} elseif ($service_request == 'list_account') {
    if (!$API->account['id'])
        result('Not logged in');
    $result['name'] = $API->account['name'];
    $result['email'] = $API->account['email'];
    $result['premium_expired'] = $API->account['premium_expired'];
    $result['devices'] = $API->DB->query_return("SELECT name, udid FROM push WHERE account_id={$API->account['id']}");
    $result['is_premium'] = is_premium();
    result(array(), $result);
}
elseif ($service_request == 'get_signservice_link') {

    $id = $API->getval('id', 'int');

    if ($id) {
        $appdata = $API->DB->query_row("SELECT links.*, apps.name, apps.trackid, apps.image, apps.bundle_id FROM links LEFT JOIN apps ON links.trackid=apps.trackid WHERE links.id=$id");

        if (!$appdata) {
            result('Invalid link ID');
        }

        $link = $appdata['link'];
    } else {

        $link = trim($API->getval('link'));
        $trackid = $trackid[0];
        $appdata = $API->DB->query_row("SELECT apps.name, apps.trackid, apps.image, apps.bundle_id FROM apps  WHERE trackid=$trackid");

        if (!$appdata) {
            result('Invalid trackid');
        }
    }

    //in appgw: get_signservice_link,get_directinsaller_link; signservice, directinstaller
    $link = get_directinstaller_link($link);

    if (!$link) {
        result('Unable to make directinstaller link');
    }

    $force_sign = $API->getval('force_sign', 'int');

    if (!$API->account) {
        $ticket = encrypt(json_encode(array('name' => $appdata['name'], 'link' => $link, 'image' => $appdata['image'], 'bundle_id' => $appdata['bundle_id'], 'force_sign' => $force_sign)), 'RMUSS_super_secret');
    } else {
        $ticket = encrypt(json_encode(array('name' => $appdata['name'], 'link' => $link, 'image' => $appdata['image'], 'bundle_id' => $appdata['bundle_id'], 'email' => $API->account['email'], 'account' => $API->account['name'], 'force_sign' => $force_sign)), 'RMUSS_super_secret');
    }
    result(array(), "https://regmyudid.com/isigncloud/mcb.php?t=" . urlencode($ticket));
} elseif ($service_request == 'get_directinstaller_link') {

    $id = $API->getval('id', 'int');

    /*
     * LINK GET CODE, used in APPGW too
     */

    if ($id) {
        $appdata = $API->DB->query_row("SELECT links.* FROM links WHERE links.id=$id");

        if (!$appdata) {
            result('Invalid link ID');
        }

        $link = $appdata['link'];
    } else {
        $link = trim($API->getval('link'));
    }

    if (!preg_match('#mega\.co\.nz#si', $link) && !is_premium()) {
        result('Account type is not premium');
    }
    $link = get_directinstaller_link($link);

    if (!$link) {
        result('Unable to make directinstaller link');
    }
    result(array(), $link);
} elseif ($service_request == 'register_device') {

    if ($API->account) {
        $to_db['account_id'] = $API->account['id'];

        $count = $API->DB->get_row_count('push', "WHERE account_id={$to_db['account_id']}");

        if ($count > 5)
            result('Can not register device. Limit in 5 devices reached.');
    }
    $to_db['udid'] = $API->getval('udid');
    $to_db['token'] = $API->getval('token');
    if (strlen($to_db['token']) != 64)
        result('Invalid push token');
    $to_db['name'] = htmlspecialchars($API->getval('name'));
    $to_db['model'] = htmlspecialchars($API->getval('model'));
    $to_db['ios_version'] = htmlspecialchars($API->getval('ios_version'));
    $to_db['badge'] = 0;
    if (!$to_db['udid'] || !$to_db['token'] || ($to_db['token'] == '(null)') || (strlen($to_db['udid']) != 40)) {
        result('No udid or Token or invalid udid length. Must be 40.');
    }
    $to_db['added'] = $CONFIG['TIME'];

    $API->DB->query("INSERT INTO push " . $API->DB->build_insert_query($to_db) . " ON DUPLICATE KEY UPDATE " . $API->DB->build_update_query($to_db));
    result(array(), array(true));
}

$NO_APPS_CACHE = false;
// @todo rewrite clicks
if (!in_array($sort, explode(',', "added,clicks,name,rand")))
    $sort = 'added';

if ($sort != 'rand') {
    if (!in_array($order, explode(',', "ASC,DESC")))
        $order = 'DESC';
} else {
    $sort = 'RAND()';
    $NO_APPS_CACHE = true;
    $order = '';
}

// download_stats_archive table query and sort by clicks

$_CACHE_GROUP = 'lists_caches';

if ($sort == 'clicks') {
    $_CACHE_GROUP = 'top100';
    $period = $API->getval('period');

    if (!in_array($period, explode(',', 'day,week,month,year,all'))) {
        $period = 'day';
    }


    if ($period == 'day') {
        $clicks_sql = "clicks_day AS clicks";
    } elseif ($period == 'week') {
        $clicks_sql = "clicks_week AS clicks";
    } elseif ($period == 'month') {
        $clicks_sql = "clicks_month AS clicks";
    } elseif ($period == 'year') {
        $clicks_sql = "clicks_year AS clicks";
    } else {
        $clicks_sql = "clicks_all AS clicks";
    }
    //$NO_APPS_CACHE = true;
} else {
    $clicks_sql = '0 AS clicks';
}

$compatibility = $API->getval('compatibility', 'int');

if ($type) {
    $query[] = "apps.type = {$API->DB->sqlesc($type)}";
}

// fucking selector, first check by compatibility, second by type of content. every turn of compatiblity we must check type
if (!$compatibility || $compatibility > 4 || $compatibility < 0) {
    $compatibility_sql = false;
    if ($type == 'book') {
        $compatibility_sql = "apps.type='book'";
        $genres_for_search_addition = "genres.type='books'";
    } elseif ($type == 'app') {
        $compatibility_sql = "apps.type='app'";
        $genres_for_search_addition = "genres.type !='books'";
    }
} elseif ($compatibility == 1) {

    if (!$type) {
        $compatibility_sql = "compatibility<>4";
        $genres_for_search_addition = "genres.type!='mas'";
    } elseif ($type == 'book') {
        $compatibility_sql = "(apps.type='book' AND compatibility<>4)";
        $genres_for_search_addition = "genres.type='books'";
    } elseif ($type == 'app') {
        $genres_for_search_addition = "genres.type NOT IN('mas','books')";
        $compatibility_sql = "(apps.type='app' AND compatibility<>4)";
    }
} elseif ($compatibility == 2) {

    if (!$type) {
        $genres_for_search_addition = "genres.type!='mas'";
        $compatibility_sql = "compatibility NOT IN(3,4)";
    } elseif ($type == 'book') {
        $compatibility_sql = "(apps.type='book' AND compatibility NOT IN(3,4))";
        $genres_for_search_addition = "genres.type='books'";
    } elseif ($type == 'app') {
        $genres_for_search_addition = "genres.type NOT IN('mas','books')";
        $compatibility_sql = "(apps.type='app' AND compatibility NOT IN(3,4))";
    }
} elseif ($compatibility == 3) {

    if (!$type) {
        $genres_for_search_addition = "genres.type!='mas'";
        $compatibility_sql = "compatibility NOT IN(2,4)";
    } elseif ($type == 'book') {
        $compatibility_sql = "(apps.type='book' AND compatibility NOT IN(2,4))";
        $genres_for_search_addition = "genres.type='books'";
    } elseif ($type == 'app') {
        $genres_for_search_addition = "genres.type NOT IN('mas','books')";
        $compatibility_sql = "(apps.type='app' AND compatibility NOT IN(2,4))";
    }
} elseif ($compatibility == 4) {
    $genres_for_search_addition = "genres.type IN('mas','books')";
    if ($type == 'book')
        $compatibility_sql = "apps.type='book'";
    elseif ($type == 'app') {
        $compatibility_sql = "(compatibility=4 AND apps.type='app')";
    } else
        $compatibility_sql = "(apps.type='book' OR compatibility=4)";
}
// selector end

if ($compatibility_sql)
    $query[] = $compatibility_sql;

$price = $API->getval('price', 'int');


if ($price == 1) {
    $query['price'] = "price<>'Free'";
} elseif ($price == 2) {
    $query['price'] = "price='Free'";
}

$NO_APPS_CACHE = false;


$apps = $genres = array();


$genres_cache_query = "SELECT id,name, type, (SELECT COUNT(*) FROM apps WHERE genre_id=genres.id" . ($compatibility_sql ? " AND $compatibility_sql" : '') . ") AS numapps FROM genres" . ($genres_for_search_addition ? " WHERE $genres_for_search_addition" : '') . " ORDER BY numapps DESC";
$genres_cache_name = "genres-" . md5($genres_cache_query);

$genres_cache = $API->CACHE->get('lists_caches', $genres_cache_name);

if ($genres_cache === false) {

    $genres = $API->DB->query_return($genres_cache_query);
    $API->CACHE->set('lists_caches', $genres_cache_name, $genres);
} else
    $genres = $genres_cache;
// translating genres

$to_genres = array('mas' => 'Mac', 'ios' => 'iOS', 'books' => $API->LANG->_('Books'));
if ($genres) {
    foreach ($genres as $k => $g) {
        $genres[$k]['name'] = "{$API->LANG->_($g['name'])} ({$to_genres[$g['type']]})";
    }
}

if ($service_request == 'list_genres') {
    result(array(), $genres);
} elseif ($service_request == 'list_devs') {
    result(array(), $API->DB->query_return("SELECT * FROM artists"));
} elseif ($service_request == 'list_news') {
    result(array(), $API->DB->query_return("SELECT * FROM news ORDER BY added DESC"));
} elseif ($service_request == 'list_tracks') {
    if (!$API->account['id'])
        result('Not logged in');
    $tracks = $API->DB->query_return("SELECT trackid FROM tracks WHERE account_id={$API->account['id']}");
    if (!$tracks)
        result('You are tracking no apps');

    foreach ($tracks as $t) {
        $result[] = $t['trackid'];
    }
    result(array(), $result);
}


if ($genre) {
    $query['genre'] = "genre_id = $genre";
}
if ($dev) {
    $query['artist'] = "artist_id = $dev";
}

$NO_APPS_CACHE = false;
if ($q) {

    if (strlen($q) < 2)
        result('You can only search from %s symbols', 2);

    $query[] = "(apps.name LIKE " . $API->DB->sqlwildcardesc($q) . ")";
    // searching by dev name

    if (!$query['artist']) {
        $devs_for_search = $API->DB->query_return("SELECT id FROM artists WHERE name LIKE " . $API->DB->sqlwildcardesc($q));

        if ($devs_for_search) {
            foreach ($devs_for_search as $d) {
                $devs_OR_query[] = "artist_id={$d['id']}";
            }
            $OR_query[] = "((" . implode(' OR ', $devs_OR_query) . ")" . ($compatibility_sql ? " AND $compatibility_sql" : '') . ($query['genre'] ? " AND {$query['genre']}" : '') . ($query['price'] ? " AND {$query['price']}" : '') . ")";
        }
    }

    if (!$query['genre']) {
        // searching by genre name
        $genres_for_search = $API->DB->query_return("SELECT id FROM genres WHERE name LIKE " . $API->DB->sqlwildcardesc($q) . ($genres_for_search_addition ? " AND $genres_for_search_addition" : ''));

        if ($genres_for_search) {
            foreach ($genres_for_search as $g) {
                $genres_OR_query[] = "genre_id={$g['id']}";
            }
            $OR_query[] = "((" . implode(' OR ', $genres_OR_query) . ")" . ($query['price'] ? " AND {$query['price']}" : '') . ")";
        }
    }
    $NO_APPS_CACHE = true;
}

$q = htmlspecialchars($q);

if ($trackid) {
    $query[] = "trackid IN (" . implode(',', $trackid) . ")";
}

if ($query)
    $OR_query[] = "(" . implode(' AND ', $query) . ")";

$where = ($OR_query ? ' WHERE ' . implode(' OR ', $OR_query) : '');


// do not use count cache if searching
/* if (!$q) {
  $appscount_cache_name = 'count-api-apps-' . md5($where);

  $appscount_cache = $API->CACHE->get('lists_caches', $appscount_cache_name);

  if ($appscount_cache === false) {
  $appscount = $API->DB->get_row_count(apps, $where);
  $API->CACHE->set($_CACHE_GROUP, $appscount_cache_name, $appscount);
  } else
  $appscount = $appscount_cache;
  } else
  $appscount = $API->DB->get_row_count(apps, $where);

  if (!$appscount || !$genres)
  result($API->LANG->_('No applications found'));
 */
list($limit, $pagercode) = $API->generate_pagination($appscount, array('search', 'dev', $dev, 'genre', $genre, 'q', $q, 'type', $type), $perpage);


$cache_query = "SELECT apps.*, $clicks_sql, genres.name AS gname, artists.name AS pname FROM apps LEFT JOIN genres ON apps.genre_id=genres.id LEFT JOIN artists ON apps.artist_id=artists.id " . ($OR_query ? ' WHERE ' . implode(' OR ', $OR_query) : '') . " ORDER BY $sort $order $limit";
$cache_name = 'data-api-' . md5($cache_query);

if ($NO_APPS_CACHE) {
    $cache = false;
} else {
    $cache = $API->CACHE->get($_CACHE_GROUP, $cache_name);
}

if ($cache === false) {
    $apps = $API->DB->query_return($cache_query);
    if (!$NO_APPS_CACHE) {
        $API->CACHE->set($_CACHE_GROUP, $cache_name, $apps);
    }
} else {
    $apps = $cache;
}

if (!$apps) {
    result($API->LANG->_('No applications found'));
}
//set tracked status
if ($apps && $API->account) {
    foreach ($apps as $k => $a) {
        $tracking_status = $API->DB->query_row("SELECT 1 FROM tracks WHERE trackid={$a['trackid']} AND account_id={$API->account['id']}");
        if ($tracking_status)
            $apps[$k]['is_tracked'] = true;
    }
}

result(array(), ($apps ? $apps : array()));
?>