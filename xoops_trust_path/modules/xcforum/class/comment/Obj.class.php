<?php
/**
 * @file
 * @package xcforum
 * @version $Id$
 **/

// a class for XcComment Authorization
if( ! class_exists( 'Xcforum_Comment_Obj' ) ) {
	/**
	 * Xcforum_Comment_Obj
	 **/
class Xcforum_Comment_Obj {

var $xcComObj = null ;

public function __construct ($params )
//  $params['forum_dirname'] , $params['external_dirname'] , $params['classname'] , $params['external_trustdirname']
{
	$mytrustdirpath = dirname(dirname(__FILE__));
	if( empty( $params['classname'] ) ) {
		include_once $mytrustdirpath.'/class/comment/Abstract.class.php' ;
		$this->xcComObj = new Xcforum_Comment_Abstract( $forum_dirname , '' ) ;
		return ;
	}

	// search the class file
	$class_bases = array(
		XOOPS_ROOT_PATH.'/modules/'.$params['external_dirname'].'/class' ,
		XOOPS_TRUST_PATH.'/modules/'.$params['external_trustdirname'].'/class' ,
		XOOPS_TRUST_PATH.'/modules/xcforum/class' ,
	) ;

	foreach( $class_bases as $class_base ) {
		if( file_exists( $class_base.'/'.$params['classname'].'.class.php' ) ) {
			require_once $mytrustdirpath.'/comment/Abstract.class.php' ;
			require_once $class_base.'/'.$params['classname'].'.class.php' ;
			break ;
		}
	}

	// check the class
	if( ! $params['classname'] || ! class_exists( $params['classname'] ) ) {
		include_once $mytrustdirpath.'/comment/Abstract.class.php' ;
		$this->xcComObj = new Xcforum_Comment_Abstract( $params['forum_dirname'] , $params['external_dirname'] ) ;
		return ;
	}

	$this->xcComObj = new $params['classname']( $params['forum_dirname'] ,
			$params['external_dirname'] , $params['external_trustdirname'] ) ;
}

function & getInstance( $params )
{

	$external_dirname = $params['external_dirname'] ;

	static $instance ;
	if( ! isset( $instance[$external_dirname] ) ) {
		$instance[$external_dirname] = new Xcforum_Comment_Obj( $params ) ;
	}
	return $instance[$external_dirname] ;
}

} // end class Xcforum_Comment_Obj
}

?>
