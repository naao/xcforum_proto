<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     xcforum_comment
 * Version:  1.0
 * Date:     
 * Author:   naao
 * Purpose:  
 * Input:    
 * 
 * Examples: {xcforum_comment class=(class_name) mydirname=(dirname)}
 * -------------------------------------------------------------
 */
function smarty_function_xcforum_comment($params, &$smarty)
{
	$forum_dirpath = XOOPS_TRUST_PATH.'/modules/xcforum' ;
	require_once $forum_dirpath.'/class/comment/Obj.class.php' ;

	$mydirname = @$params['mydirname'] ;
	$classname = @$params['class'] ;

	$mytrustdirname = '' ;
	if( $mydirname != '' ) {
		@include XOOPS_ROOT_PATH.'/modules/'.$mydirname.'/mytrustdirname.php' ;
	}
	$params['mytrustdirname'] = $mytrustdirname ;

	$class_bases = array(
		XOOPS_ROOT_PATH.'/modules/'.$mydirname.'/class' ,
		XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class' ,
		XOOPS_TRUST_PATH.'/modules/xcforum/class' ,
	) ;

	foreach( $class_bases as $class_base ) {
		if( file_exists( $class_base.'/'.$classname.'.class.php' ) ) {
			require_once $class_base.'/'.$classname.'.class.php' ;
			break ;
		}
	}

	$m_params['forum_dirname'] = $forum_dirname ;

	$m_params['external_dirname'] = $mydirname  ; 
	$m_params['classname'] = $classname ;
	$m_params['external_trustdirname'] = $mytrustdirname ;

	adump($params,$m_params);

	if( class_exists( $classname ) ) {
		$obj =& Xcforum_Comment_Obj::getInstance ( $m_params ) ;
		$obj->xcComObj->setSmarty( $smarty ) ;
		switch( $params['mode'] ) {
			case 'count' :
				$obj->xcComObj->displayCommentsCount( $params ) ;
				break ;
			case 'display_inline' :
			default :
				$obj->xcComObj->displayCommentsInline( $params ) ;
				break ;
		}
	} else {
		echo "class parameter is invalid in <{xcforum_comment}>" ;
	}
}

?>