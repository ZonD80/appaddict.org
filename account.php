<?php

require_once 'init.php';

$API->auth();

$delete_device = $API->getval('delete_device');

if ($delete_device) {
    $API->DB->query("DELETE FROM push WHERE udid=".$API->DB->sqlesc($delete_device));
    die($API->LANG->_('Device has been deleted from your account'));
}
$API->TPL->assign('headeradd', '<link rel="stylesheet" type="text/css" href="./css/tracks.css">
<script>
function delete_device(udid) {
    var confirmed = confirm("' . $API->LANG->_('IOS_LOGOUT_CONFIRMATION') . '");
        if (!confirmed) {
        alert("' . $API->LANG->_('You must to log out on iOS device to unlink it') . '");
            return false;
            }
            
    $.post("' . $API->SEO->make_link('account') . '",{delete_device:udid},function(data){
    $("#device-"+udid).html("<td colspan=\"4\">"+data+"</td>");
    $("#device-"+udid).fadeOut("slow");
}
);
return false;
}
</script>
');

$API->TPL->assign('pagetitle', $API->LANG->_('My account'));
//assign menu class
$API->TPL->assign('footername', $API->LANG->_('Account management'));

$devices = $API->DB->query_return("SELECT * FROM push WHERE account_id={$API->account['id']}");

$API->TPL->assign('devices', $devices);

$API->TPL->display('account.tpl');
?>