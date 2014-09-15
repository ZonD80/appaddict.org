<?php

require_once 'init.php';

$API->logout_account();
// ipb integration  
$warning = '<img src="https://forum.appaddict.org/aa/index.php?action=logout"/>';
$API->TPL->assign('warning', $warning);
// ipb integration end*/
$API->TPL->assign('message', $API->LANG->_('You successfully logged out'));
//$API->safe_redirect($returnto,2);
$API->TPL->display('message.tpl');
?>