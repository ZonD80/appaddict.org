<?php

require_once 'init.php';
//$API->error('Temp disabled');
$API->TPL->assign('pagetitle', $API->LANG->_('Alternate Uploading'));
//assign menu class
$API->TPL->assign('navclass', 'iphone');
$API->TPL->assign('footername', $API->LANG->_('Alternate uploading'));

$api_call = $API->getval('api', 'int');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$api_call) {
    $file = $_FILES['file']['tmp_name'];
    $lines = trim(@file_get_contents($file));

    if (!$lines)
        $API->error($API->LANG->_('File contains no data'));

    $lines = explode("\n", str_replace(array("\r\n", "\n", "\r"), "\n", $lines));

    $separator = $API->getval('separator');
    $separator = substr($separator, 0, 1);
    if (!$separator)
        $separator = ',';
    $progress_image = array();

    foreach ($lines as $n => $l) {
        $l = str_replace('"', '', $l);
        $l = explode($separator, $l);
        $url = urlencode(array_shift($l));
        if (!$url) {
            $progress_image[] = '?api=1&n=' . $n;
            continue;
        }
        $version = urlencode(trim(array_shift($l)));

        $cracker = array_shift($l);
        if (!$cracker) {
            $progress_image[] = '?api=1&n=' . $n;
            continue;
        }
        $cracker = urlencode($cracker);


        if (!$l) {
            $progress_image[] = '?api=1&n=' . $n;
            continue;
        }

        $l = array_filter($l, 'strlen');

        if (!$l) {
            $progress_image[] = '?api=1&n=' . $n;
            continue;
        }

        $links = urlencode(json_encode($l));
        $progress_image[] = "?api=1&n=$n&cracker=$cracker&version=$version&links=$links&url=$url";
    }

    $API->TPL->assign('progress_image', $progress_image);
    $API->TPL->display('alternate-upload-result.tpl');
    die();
} elseif ($api_call) {

    //here we are uploading

    function result($status, $message, $link = null) {
        die(json_encode(array('status' => $status, 'message' => $message, 'link' => $link)));
    }

    header('Content-type: application/json');

    $urldata = parse_itunes_url(trim($API->getval('url')));

    $store = $urldata['store'];

    $trackid = (int) $urldata['trackid'];

    $line_number = $API->getval('n', 'int');

    $type = $urldata['type'];

    $cracker = htmlspecialchars(($API->getval('cracker')));
    $version = htmlspecialchars(($API->getval('version')));
    $links = array_map('htmlspecialchars', (array) json_decode(($API->getval('links')), true));
    $links = array_filter($links, 'strlen');

    $required_filehostings = $API->DB->query_return("SELECT domains FROM required_filehostings");

    $urlbancheck = false;
    // checking filehosting ban and invalid link
    $ls_to_check = $API->DB->query_row("SELECT GROUP_CONCAT(domains) AS domains FROM banned_filehostings");
    $pattern = '#(' . str_replace('.', '\.', str_replace(',', '|', $ls_to_check['domains'])) . ')#si';
    foreach ($links as $l) {
        if ($required_filehostings)
            foreach ($required_filehostings as $k => $rfh) {
                //var_dump($rfh)
                $rfh_domains = explode(',', $rfh['domains']);
                foreach ($rfh_domains as $rfh_domain)
                    if (strpos($l, $rfh_domain))
                        unset($required_filehostings[$k]);
            }

        $urlbancheck = (preg_match('#(^http(s)?://([a-z0-9-_.]+\.[a-z]{2,4})|^magnet:\?xt=urn:btih:([a-z0-9]{32}))#i', $l) ? false : true);
        if ($urlbancheck)
            break;
        $urlbancheck = preg_match($pattern, $l);
        if ($urlbancheck)
            break;
    }

    if ($required_filehostings) {
        foreach ($required_filehostings as $rfh) {
            $to_error[] = $rfh['domains'];
        }
        result('error', 'Line ' . $line_number . ' ERROR: Missing required filehosting links: ' . implode(' ', $to_error));
    }

    if ($urlbancheck) {
        result('error', 'Line ' . $line_number . ' ERROR: Bad filehosting links, view left column on upload page');
    }
    if (!$trackid || !$store || !$links || !$type) {
        result('error', 'Line ' . $line_number . ' ERROR: Missing iTunes/MAS ID or Store or Filehostings links');
    }

    $live_app = $API->DB->query_row("SELECT * FROM apps WHERE trackid=$trackid");

    require_once 'itgw.inc.php';


    $data = get_itunes_info($trackid, $type, $store);

    if ($data) {
        update_application($data, $trackid);
    }

    if (!$live_app && !$data) {
        result('error', "Line $line_number Can not find app/book with ID $trackid on $store iTunes or MAS or in Live database");
    } elseif ($live_app && !$data) {
        $data = json_decode($live_app['last_parse_itunes'], true);
    }

    if (!$live_app) {

        $paidfreeapps = $API->DB->query_row("SELECT (SELECT COUNT(DISTINCT links.trackid) FROM links LEFT JOIN apps ON links.trackid=apps.trackid WHERE apps.price='Free' AND links.uploader_id={$API->account['id']}) AS free, (SELECT COUNT(DISTINCT links.trackid) FROM links LEFT JOIN apps ON links.trackid=apps.trackid WHERE apps.price!='Free' AND links.uploader_id={$API->account['id']}) AS paid");
        $paid_apps = $paidfreeapps['paid'];
        $free_apps = $paidfreeapps['free'];
        if ($free_apps > $paid_apps) {
            $API->error($API->LANG->_('FREE_CONTENT_UPLOAD_ERROR', $paid_apps, $free_apps));
        }

        $success = upload_application($data);


        if (!$success) {
            result('error', "Line $line_number UNKNOWN ERROR, Contact site administrators");
        }
    }

    // set version here
    if ($version)
        $data['version'] = $version;


    $data['cracker'] = htmlspecialchars(trim($cracker));
    foreach ($links as $l) {
        $l = htmlspecialchars(trim($l));
        if ($l) {
            $to_links[] = array('link' => $l, 'uploader_id' => $API->account['id'], 'cracker' => $data['cracker'], 'added' => TIME, 'version' => $data['version'], 'state' => ($API->account['upload_auto_moderate'] ? 'accepted' : 'pending'), 'trackid' => $trackid);
        }
    }


    //set magnet
    if ($magnet) {
        $to_links[] = array('link' => $magnet, 'uploader_id' => $API->account['id'], 'cracker' => $data['cracker'], 'added' => TIME, 'version' => $data['version'], 'state' => ($API->account['upload_auto_moderate'] ? 'accepted' : 'pending'), 'trackid' => $trackid);
    }

    if ($to_links) {
        foreach ($to_links as $l) {
            $API->DB->query("INSERT INTO links {$API->DB->build_insert_query($l)} ON DUPLICATE KEY UPDATE trackid=$trackid,state='pending',state_reason='Resubmission',editor_id={$API->account['id']}");
        }
        // update app added time if link accepted automatically

        if ($API->account['upload_auto_moderate']) {
            $linkscountversion = $API->DB->get_row_count("links", "WHERE trackid={$trackid} AND version={$API->DB->sqlesc($data['version'])} AND state='accepted'");

            // check need to update twitter or not
            $linkscountarchive = $API->DB->get_row_count("links", "WHERE trackid={$trackid} AND state='archived'");
            $appdata = $API->DB->query_row("SELECT * FROM apps WHERE trackid={$trackid}");
            if (!$linkscountversion) {
                send_twitter("New App/Book {$data['name']} only on AppAddict " . generate_short_link($API->SEO->make_link('view', 'trackid', $trackid)), 'updates');
            } elseif ($linkscountarchive) {
                send_twitter("App/Book {$data['name']} has been updated to {$data['version']} " . generate_short_link($API->SEO->make_link('view', 'trackid', $trackid)), 'updates');

                send_tracks($appdata);
            }
            $API->DB->query("UPDATE apps SET added=" . TIME . " WHERE trackid={$trackid}");
            $API->CACHE->clearGroupCache('lists_caches');
        }
    }

    unset($to_links);


    result('ok', "Line $line_number '{$data['name']}' uploaded" . ($API->account['upload_auto_moderate'] ? ' and published' : ''));
}

$API->TPL->assign('banned_filehostings', $API->DB->query_return("SELECT domains,reason FROM banned_filehostings"));
$API->TPL->display('alternate-upload.tpl');
?>