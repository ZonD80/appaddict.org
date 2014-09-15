<?php

require_once 'init.php';

$trackid = $API->getval('trackid', 'int');


// check that app is uloaded:

$appdata = $API->DB->query_row("SELECT apps.* FROM apps WHERE trackid={$trackid}");

if (!$appdata) {
    $store = substr($API->getval('store', 'string'), 0, 2);
    $type = htmlspecialchars($API->getval('type'));
    app_error_message($trackid, $type, $store);
}

//$API->DB->debug();
$archived_count = $API->DB->query_row("SELECT COUNT(DISTINCT version) AS archived_count FROM links WHERE trackid={$trackid} AND state='archived'");
$API->TPL->assign('archived_count', $archived_count['archived_count']);

if ($API->account) {
    $trackjs = '
    
    if (!untrack)
    var ready = confirm("' . $API->LANG->_('Do you want to track this app? You will receive emails and push notifications once app will be updated.') . '");
    else var ready = confirm("' . $API->LANG->_('Do you want to STOP track this app? You WILL NOT receive emails and push notifications once app will be updated.') . '");

    if (!ready)
    return;
    else {
    var http = new XMLHttpRequest();
    var url = "trackapp.php?trackid="+trackid+"&untrack="+untrack;
    http.open("HEAD", url);
    http.onreadystatechange = function() {
        if (this.readyState == this.DONE) {
            var trackappa = document.getElementById("trackapp-a");
            var trackappimg = document.getElementById("trackapp-img");
            
            trackappimg.setAttribute("width", "180");
            trackappimg.setAttribute("height", "30");

            var trackstate = 1-untrack; 

            var root = (typeof exports === "undefined" ? window : exports);

            var mediaQuery = "(-webkit-min-device-pixel-ratio: 1.5), (min--moz-device-pixel-ratio: 1.5), (-o-min-device-pixel-ratio: 3/2), (min-resolution: 1.5dppx)";

            var suff = ".png";

            if ((root.devicePixelRatio > 1)||(root.matchMedia && root.matchMedia(mediaQuery).matches))
            {
              suff = "@2x.png"
            }
            
              if (trackstate)
             {
              trackappimg.setAttribute("src", "/images/untrack-new"+suff);
              trackappimg.setAttribute("title", "' . $API->LANG->_('Untrack this app') . '");
             }
              else
             {
              trackappimg.setAttribute("src", "/images/track-new"+suff);
              trackappimg.setAttribute("title", "' . $API->LANG->_('Track this app') . '");
             }

             trackappa.setAttribute("onclick","return trackapp("+trackstate+");");

        }
    };
    http.send();
   }';
    $API->TPL->assign('app_tracked', $API->DB->get_row_count('tracks', "WHERE trackid={$trackid} AND account_id={$API->account['id']}"));
} else {
    $trackjs = 'var confirmed = confirm("' . $API->LANG->_('You must be registered to manage your tracking apps.') . ' ' . $API->LANG->_('Do you want to sign up now?') . '");
if (confirmed) { window.location="' . $API->SEO->make_link('signup') . '"; return true; }
    else return false;';
}

$API->TPL->assign('headeradd', '<script>
function trackapp(untrack) {
var trackid=' . $trackid . ';
 ' . $trackjs . '
}
</script>

');

$API->TPL->assign('pagetitle', $appdata['name']);

require_once 'itgw.inc.php';

$store = $appdata['store'];
$type = $appdata['type'];

// here are we parsing apps only for 24h
if ($appdata['last_parse_timestamp'] < (TIME - 86400)) {

    $itdata = get_itunes_info($trackid, $type, $store);

    if (!$itdata) {
        $API->DB->query("UPDATE apps SET itunes_parse_error=1 WHERE trackid=$trackid");
        $itdata = (array) json_decode($appdata['last_parse_itunes'], true);
    } else {
        update_application($itdata, $trackid);
    }
} else {
    $itdata = json_decode($appdata['last_parse_itunes'], true);
}

$API->TPL->assign('appdata', $appdata);

$API->TPL->assign('iphone_screenshots_width', count($itdata['screenshots']['iphone']) * 448);
$API->TPL->assign('ipad_screenshots_width', count($itdata['screenshots']['ipad']) * 600);
$API->TPL->assign('itdata', $itdata);
$API->TPL->display('view.tpl');
?>