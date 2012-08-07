<?php

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'set XOOPS_TRUST_PATH into mainfile.php' ) ;

$mydirname = $myDirName = basename(dirname(__FILE__));
$mydirpath = dirname( __FILE__ ) ;

$mytrustdirname = 'xcforum';

require XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/notification.php' ;

?>