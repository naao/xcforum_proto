<?php
require '../../mainfile.php' ;
if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'set XOOPS_TRUST_PATH in mainfile.php' ) ;

$mydirname = $myDirName = basename( dirname( __FILE__ ) ) ;
$mydirpath = dirname( __FILE__ ) ;
$mydirurl = XOOPS_URL.'/modules/'.$mydirname;

$mytrustdirname = 'xcforum';

$_GET['page'] = basename( __FILE__ , '.php');

require XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/sub/notification_update.php' ;

?>