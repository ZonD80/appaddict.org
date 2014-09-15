<?php

// download script really
require_once 'init.php';

$API->auth();

if (!is_premium()) {
    $API->TPL->assign('message', $API->LANG->_('Your account type is not premium'));
    $API->TPL->assign('warning', "<a href=\"{$API->SEO->make_link('premium')}\">{$API->LANG->_("Extend account")}</a>");
    $API->TPL->display('message.tpl');
    die();
}

$id = $API->getval('id', 'int');

$udid = $API->getval('udid');

$app = $API->DB->query_row("SELECT links.*,apps.* FROM links LEFT JOIN apps ON links.trackid=apps.trackid WHERE links.id=$id");

if (!$app) {
    app_error_message($trackid);
}

//$app['last_parse_itunes'] = json_decode($app['last_parse_itunes'], true);

if ($udid) {

    $check = $API->DB->get_row_count("push", "WHERE udid={$API->DB->sqlesc($udid)} AND account_id={$API->account['id']}");

    if (!$check)
        die($API->LANG->_('This device does not belong to you'));


    $link = $app['link'];

    //var_dump($link);
    if (!$link) {
        die($API->LANG->_('Error'));
    }

    /*
     * LINK GET CODE, used in APPGW too
     */
    //in appgw: get_signservice_link,get_directinsaller_link; signservice, directinstaller
    $link = get_directinstaller_link($link);
    //die($link);

    if (!$link) {
        $API->error($API->LANG->_('Something went wrong. Sorry for inconvenience.') . " <a href=\"{$API->SEO->make_link('report', 'id', $id, 'reason', 'Directinstaller failture')}\">{$API->LANG->_('Report broken link')}</a>");
    }


    $link = $API->SEO->make_link('plist', 'b', urlencode($app['bundle_id']), 'i', urlencode($app['image']), 'n', urlencode($app['name']), 'l', urlencode($link));
    $link = generate_short_link($link);

    //die($link);

    send_push("{$API->LANG->_('Install')} {$app['name']}", 7, json_encode(array('url' => $link)), $udid);
//send_push('test',7,'','4297c9148fcd0894fe6447e31d296c1fc01f55c3');
    die($API->LANG->_('Install request has been sent. Please wait a little.'));
}
$API->TPL->assign('appdata', $app);

$API->TPL->assign('headeradd', '
    <link rel="stylesheet" type="text/css" href="./css/tracks.css">
<script>
function report(id) {
var report = prompt("' . str_replace('"', '\"', $API->LANG->_('REPORT_WARNING')) . '");
	
	if (!report) {
		alert("' . str_replace('"', '\"', $API->LANG->_('You must provide reason of report')) . '");
		return false;
	}
	
	window.location="report.php?id="+id+"&reason="+report;
	
	return true;
}

function install_on_device(udid) {
    var id="' . $id . '";
        var confirmed = confirm("' . str_replace('"', '\"', $API->LANG->_('OTA_INSTALL_NOTICE')) . '");
            if (!confirmed) {
            alert("' . str_replace('"', '\"', $API->LANG->_('You must to be authorized in iOS App to use over-the-air installer')) . '");
return false;}

$("#device-"+udid).html("<td colspan=\"4\">' . str_replace('"', '\"', $API->LANG->_('Sending request...')) . '</td>");
$.post("' . $API->SEO->make_link('directinstaller') . '",{udid:udid,id:id},function(data) {
$("#device-"+udid).html("<td colspan=\"4\">"+data+"</td>");   
});
}
</script>

');

$API->TPL->assign('pagetitle', $API->LANG->_('DirectInstaller'));
$API->TPL->assign('footername', $API->LANG->_('Downloading Content...'));

$devices = $API->DB->query_return("SELECT * FROM push WHERE account_id={$API->account['id']}");

$API->TPL->assign('devices', $devices);
// check that app is uloaded:


$API->TPL->display('directinstaller.tpl');
?>