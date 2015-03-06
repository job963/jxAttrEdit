<?php

/*$nPathEnd = strpos( $_SERVER['SCRIPT_FILENAME'], '/admin/index.php' );
$sShopPath = substr( $_SERVER['SCRIPT_FILENAME'], 0, $nPathEnd );
include $sShopPath . '/modules/jxattredit/application/views/admin/de/jxattredit_lang.php';*/
include oxRegistry::get( "oxConfigFile" )->getVar( "sShopDir" ) . '/modules/jxattredit/application/views/admin/de/jxattredit_lang.php';

?>