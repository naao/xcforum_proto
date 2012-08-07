<?php
/**
 * @file
 * @brief The page controller in the directory
 * @package xcforum
 * @version $Id$
**/

require_once '../../mainfile.php';
if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'set XOOPS_TRUST_PATH in mainfile.php' ) ;

$mydirname = basename( dirname( __FILE__ ) ) ;
$mydirpath = dirname( __FILE__ ) ;

$mytrustdirname = 'xcforum';

//$sub = @$_GET['sub'];
if ( !empty($_GET['sub']) ) {
	require_once XOOPS_TRUST_PATH . '/modules/'.$mytrustdirname.'/sub.php';
} else {
	require_once XOOPS_TRUST_PATH . '/modules/'.$mytrustdirname.'/index.php';
}

?>
