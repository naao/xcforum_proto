<?php
/**
 * @file
 * @brief The sub page controller in the directory for d3forum legacy pages
 * @package xcforum
 * @version $Id$
 **/

/** @var string $mytrustdirname **/
$mytrustdirname = basename( dirname( __FILE__ ) ) ;

/** @var string $mytrustdirpath **/
$mytrustdirpath = dirname( __FILE__ ) ;

// fork each pages
$sub = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_GET['sub'] ) ;
if( file_exists( "$mytrustdirpath/sub/$sub.php" ) ) {
	include "$mytrustdirpath/sub/$sub.php" ;
}

?>