<?php

require_once 'init.php';

$trackid = $API->getval('trackid', 'int');

$API->TPL->assign('pagetitle', $API->LANG->_('Upload new Content'));
//assign menu class
$API->TPL->assign('navclass', 'iphone');
$API->TPL->assign('footername', $API->LANG->_('Upload new Content'));

$API->auth();


$url = ($API->getval('url'));


$required_filehostings = $API->DB->query_return("SELECT domains FROM required_filehostings");

if ($url) {
    $urldata = parse_itunes_url($url);

    $store = $urldata['store'];

    $trackid = (int) $urldata['trackid'];

    $type = $urldata['type'];


    if (!$trackid) {

        $API->TPL->display('no-app-error-itunes.tpl');
        die();
    } elseif ($trackid) {
        $appdata = $API->DB->query_row("SELECT apps.* FROM apps WHERE trackid={$trackid}");
        if ($appdata) {
            $API->TPL->assign('archive_warning', true);
            $API->TPL->assign('app', $appdata);
        }
    }

    // check required file hostings
    if ($appdata['links']) {
        if ($required_filehostings)
            foreach ($required_filehostings as $k => $rfh) {
                //var_dump($rfh)
                $rfh_domains = explode(',', $rfh['domains']);
                foreach ($rfh_domains as $rfh_domain)
                    if (strpos($appdata['links'], $rfh_domain))
                        unset($required_filehostings[$k]);
            }
    }
    $API->TPL->assign('url', htmlspecialchars($url));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cracker = $API->getval('cracker');
    $version = $API->getval('version');
    $links = $API->getval('links', 'array');
    $links = array_filter($links, 'strlen');


    $file = $_FILES['torrent'];
    $NO_LINKS = (!$links[0] ? true : false);

    if ($NO_LINKS && !$file['tmp_name'])
        $API->error($API->LANG->_('No links provided and no .torrent file uploaded.'));

    if (!$NO_LINKS) {

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


        if ($urlbancheck) {
            $API->error($API->LANG->_('FILEHOSTING_BAN_NOTICE'));
        }

        if ($required_filehostings) {
            foreach ($required_filehostings as $rfh) {
                $to_error[] = $rfh['domains'];
            }
            $API->error($API->LANG->_('FILE_HOSTING_MISSING_NOTICE', implode('<br/>', $to_error)));
        }
    }
    $magnet = false;
    if ($file['tmp_name']) {
        preg_match('/.*\.torrent$/', $file['name'], $matches);

        if (!$file['size'] || $file['error'] || !$matches)
            $API->error($API->LANG->_('You uploaded invalid .torrent file (zero size, not .torrent extension or upload error)'));
        require_once 'classes' . DS . 'Torrent.php';
        $torrent = new Torrent($file['tmp_name']);
        if ($torrent->errors()) {
            $API->error($API->LANG->_('There is something wrong with your torrent file'));
        }
        // do not preform cheks on torrents
        //$torrent->announce(false);
        //$torrent->announce(array('http://pixi.appaddict.org:2710/announce'));
        $magnet = $torrent->magnet();
    }

    require_once 'itgw.inc.php';

    $data = get_itunes_info($trackid, $type, $store);
    if (!$appdata && !$data) {

        $API->TPL->assign('trackid', $trackid);

        $API->TPL->display('no-app-error-itunes.tpl');
        die();
    } elseif ($data) {

        $data['last_parse_itunes'] = $data;
        update_application($data, $trackid);
    }

    if (!$appdata && $data) {

        if ($data['price'] == 'Free') {
            $paidfreeapps = $API->DB->query_row("SELECT (SELECT COUNT(DISTINCT links.trackid) FROM links LEFT JOIN apps ON links.trackid=apps.trackid WHERE apps.price='Free' AND links.uploader_id={$API->account['id']}) AS free, (SELECT COUNT(DISTINCT links.trackid) FROM links LEFT JOIN apps ON links.trackid=apps.trackid WHERE apps.price!='Free' AND links.uploader_id={$API->account['id']}) AS paid");
            $paid_apps = $paidfreeapps['paid'];
            $free_apps = $paidfreeapps['free'];
            if ($free_apps > $paid_apps) {
                $API->error($API->LANG->_('FREE_CONTENT_UPLOAD_ERROR', $paid_apps, $free_apps));
            }
        }

        $success = upload_application($data);

        if (!$success)
            $API->error($API->LANG->_('There is an error with your upload.'));
    } elseif (!$data) {
        $live_used = true;
        $data = json_decode($appdata['last_parse_itunes'], true);
    }

    $data['cracker'] = htmlspecialchars(trim($cracker));

    if (!$data['cracker']) {
        $API->error($API->LANG->_('You forget to add cracker name to your upload'));
    }

    if ($version) {
        $data['version'] = htmlspecialchars($version);
    }

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



    $API->TPL->assign('message', $API->LANG->_('APP_UPLOADED_NOTICE'));
    if ($magnet) {
        //$torrent->save($torrentfname);
        $warning = $API->LANG->_('TORRENT_UPLOADED_NOTICE', $magnet);
    } else
        $warning = '';

    if ($live_used) {
        $warning .= $API->LANG->_('CONTENT_FROM_DB_NOTICE') . "<br/>";
    }

    $warning .= "<a href=\"{$API->SEO->make_link('upload')}\"><input type=\"button\" value=\"{$API->LANG->_('upload one more app')}\"></a>";
    $API->TPL->assign('warning', $warning);
    $API->TPL->display('message.tpl');
    die();
}

$API->TPL->assign('banned_filehostings', $API->DB->query_return("SELECT domains,reason FROM banned_filehostings"));
$API->TPL->assign('required_filehostings', $required_filehostings);
$API->TPL->display('upload.tpl');
?>