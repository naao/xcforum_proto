<?php

// main_functions and common_functions

if(!defined('XOOPS_ROOT_PATH'))
{
    exit;
}

if( ! class_exists( 'Xcforum_Func' ) ) {

	require_once XCFORUM_TRUST_PATH . '/class/Root.class.php' ;
//	require_once XCFORUM_TRUST_PATH . '/class/XcforumUtils.class.php' ;

class Xcforum_Func extends Xcforum_Root{
//class Xcforum_Func extends Xcforum_Utils{

	/* xcforum module variables */

	public function __construct()
	{
		parent::__construct() ;
	}

	function &getInstance()
	{
		static $instance;
		if (!isset($instance))
			$instance = new Xcforum_Func();
		return $instance;
	}

		/*
	   *
	   */
		public function processThispost( /*** XcforumPostsObject ***/ &$postsObj , /*** XcforumTopicsObject ***/ &$topicObj, /*** array ***/ &$params)
		{
			// TODO: d3forum では　かなり複雑
			/*
		   $can_reply = ( $topic_row['topic_locked'] || $post_row['invisible'] || ! $post_row['approval'] ) ? false : $can_post ;
		   if( $isadminormod ) {
			   $can_edit = true ;
			   $can_delete = true ;
		   } else if( $post_row['uid_hidden'] && $post_row['uid_hidden'] == $uid  ) {
			   $can_edit = $forum_permissions[ $forum_id ]['can_edit'] && time() < $post_row['post_time'] + $xoopsModuleConfig['selfeditlimit'] ? true : false ;
			   $can_delete = $forum_permissions[ $forum_id ]['can_delete'] && time() < $post_row['post_time'] + $xoopsModuleConfig['selfdellimit'] ? true : false ;
		   } else if( $uid > 0 ) {
			   // normal user cannot touch guest's post
			   $can_edit = false ;
			   $can_delete = false ;
		   } else {
			   // guest can delete posts by password
			   $can_edit = false ;
			   $can_delete = $post_row['guest_pass_md5'] && $forum_permissions[ $forum_id ]['can_delete'] && time() < $post_row['post_time'] + $xoopsModuleConfig['selfdellimit'] ? true : false ;
		   }
		   */

			$rtn = $params;
			//user's info
			$uid = $postsObj->get('uid');
			if ($uid >0){
				// hold each uid's user object
				$params['uObj'][$uid] = new XoopsUser($uid);
				$uname = $params['uObj'][$uid]->getUnameFromId($uid, $this->mod_config['use_name']);
				$rtn['poster_uname'] = !empty($uname) ? $uname : $params['uObj'][$uid]->getUnameFromId($uid, 0);
			} else {
				$rtn['poster_uname'] =  $postsObj->get('gname');
			}

			$rtn['can_reply'] = ( $topicObj->get('topic_locked') || $postsObj->get('invisible') || !$postsObj->get('approval') ) ? false : $params['can_post'];
			if( $params['isadminormod'] ) {
				$rtn['can_edit'] = true ;
				$rtn['can_delete'] = true ;
			} else if( $postsObj['uid_hidden'] && $postsObj['uid_hidden'] == $uid  ) {
				$rtn['can_edit'] = $params['can_edit'] && time() < $postsObj['post_time'] + $params['mod_config']['selfeditlimit'] ? true : false ;
				$rtn['can_delete'] = $params['can_delete'] && time() < $postsObj['post_time'] + $params['mod_config']['selfdellimit'] ? true : false ;
			} else if( $uid > 0 ) {
				// normal user cannot touch guest's post
				$rtn['can_edit'] = false ;
				$rtn['can_delete'] = false ;
			} else {
				// guest can delete posts by password
				$rtn['can_edit'] = false ;
				$rtn['can_delete'] = $postsObj->get['guest_pass_md5'] && $params['can_delete'] && time() < $postsObj->get['post_time'] + $params['mod_config']['selfdellimit'] ? true : false ;
			}

			return $rtn;

		}

// copy from d3forum/include/main_functions.php
// this file can be included only from main or admin (not from blocks)
// add fields for tree structure into $posts or $categories
		public function make_treeinformations( $data )
		{
			$previous_depth = -1 ;
			$path_to_i = array() ;

			for( $i = 0 ; $i < sizeof( $data ) ; $i ++ ) {
				$unique_path = $data[$i]['unique_path'] ;
				$path_to_i[ $unique_path ] = $i ;
				$parent_path = substr( $unique_path , 0 , strrpos( $unique_path , '.' ) ) ;
				if( $parent_path && isset( $path_to_i[ $parent_path ] ) ) {
					$data[ $path_to_i[ $parent_path ] ]['f1s'][ $data[$i]['id'] ] = strrchr( $data[$i]['unique_path'] , '.' ) ;
				}

				$depth_diff = $data[$i]['depth_in_tree'] - @$previous_depth ;
				$previous_depth = $data[$i]['depth_in_tree'] ;
				$data[$i]['ul_in'] = '' ;
				$data[$i]['ul_out'] = '' ;
				if( $depth_diff > 0 ) {
					if( $i > 0 ) {
						$data[$i-1]['first_child_id'] = $data[$i]['id'] ;
					}
					for( $j = 0 ; $j < $depth_diff ; $j ++ ) {
						$data[$i]['ul_in'] .= '<ul><li>' ;
					}
				} else if( $depth_diff < 0 ) {
					for( $j = 0 ; $j < - $depth_diff ; $j ++ ) {
						$data[$i-1]['ul_out'] .= '</li></ul>' ;
					}
					$data[$i-1]['ul_out'] .= '</li>' ;
					$data[$i]['ul_in'] = '<li>' ;
				} else {
					$data[$i-1]['ul_out'] .= '</li>' ;
					$data[$i]['ul_in'] = '<li>' ;
				}
				if( $i > 0 ) {
					$data[$i-1]['next_id'] = $data[$i]['id'] ;
					$data[$i]['prev_id'] = $data[$i-1]['id'] ;
				}
			}
			$data[ sizeof( $data ) - 1 ]['ul_out'] = str_repeat( '</li></ul>' , $previous_depth + 1 ) ;

			return $data ;
		}


// check done
		function get_forum_permissions_of_current_user()
		{
			$mydirname =& $this->mModule->get('dirname');
			$xoopsUser =& $this->mXoopsUser ;
			//$this->mid = $this->mModule->get('mid');
			//$this->mname = $this->mModule->get('name');

			$db =& $this->db ;

			if( is_object( $xoopsUser ) ) {
				$uid = intval( $xoopsUser->getVar('uid') ) ;
				$groups = $xoopsUser->getGroups() ;
				if( ! empty( $groups ) ) $whr = "`uid`=$uid || `groupid` IN (".implode(",",$groups).")" ;
				else $whr = "`uid`=$uid" ;
			} else {
				$whr = "`groupid`=".intval(XOOPS_GROUP_ANONYMOUS) ;
			}

			$sql = "SELECT forum_id,SUM(can_post) AS can_post,SUM(can_edit) AS can_edit,SUM(can_delete) AS can_delete,SUM(post_auto_approved) AS post_auto_approved,SUM(is_moderator) AS is_moderator FROM ".$db->prefix($mydirname."_forumaccess")." WHERE ($whr) GROUP BY forum_id" ;
			$result = $db->query( $sql ) ;
			if( $result ) while( $row = $db->fetchArray( $result ) ) {
				$ret[ $row['forum_id'] ] = $row ;
			}

			if( empty( $ret ) ) return array( 0 => array() ) ;
			else return $ret ;
		}


// check done
		function get_category_permissions_of_current_user()
		{
			$mydirname =& $this->mModule->get('dirname');
			$xoopsUser =& $this->mXoopsUser ;
			$db =& $this->db ;

			if( is_object( $xoopsUser ) ) {
				$uid = intval( $xoopsUser->getVar('uid') ) ;
				$groups = $xoopsUser->getGroups() ;
				if( ! empty( $groups ) ) $whr = "`uid`=$uid || `groupid` IN (".implode(",",$groups).")" ;
				else $whr = "`uid`=$uid" ;
			} else {
				$whr = "`groupid`=".intval(XOOPS_GROUP_ANONYMOUS) ;
			}

			$sql = "SELECT cat_id,SUM(can_makeforum) AS can_makeforum,SUM(is_moderator) AS is_moderator FROM ".$db->prefix($mydirname."_categoryaccess")." WHERE ($whr) GROUP BY cat_id" ;
			$result = $db->query( $sql ) ;
			if( $result ) while( $row = $db->fetchArray( $result ) ) {
				$ret[ $row['cat_id'] ] = $row ;
			}

			if( empty( $ret ) ) return array( 0 => array() ) ;
			else return $ret ;
		}


// check done
		function get_users_can_read_forum( $forum_id , $cat_id = null )
		{
			$mydirname =& $this->mModule->get('dirname');
			//$xoopsUser =& $this->mXoopsUser ;
			$db =& $this->db ;

			$forum_id = intval( $forum_id ) ;
			$forum_uids = array() ;
			$cat_uids = array() ;

			if( is_null( $cat_id ) ) {
				// get $cat_id from $forum_id
				list( $cat_id ) = $db->fetchRow( $db->query( "SELECT `cat_id` FROM ".$db->prefix($mydirname."_forums")." WHERE `forum_id`=$forum_id" ) ) ;
			}

			$sql = "SELECT `uid` FROM ".$db->prefix($mydirname."_categoryaccess")." WHERE `cat_id`=$cat_id AND `uid` IS NOT NULL" ;
			$result = $db->query( $sql ) ;
			while( list( $uid ) = $db->fetchRow( $result ) ) {
				$cat_uids[] = $uid ;
			}
			$sql = "SELECT distinct g.uid FROM ".$db->prefix($mydirname."_categoryaccess")." x , ".$db->prefix("groups_users_link")." g WHERE x.groupid=g.groupid AND x.`cat_id`=$cat_id AND x.`groupid` IS NOT NULL" ;
			$result = $db->query( $sql ) ;
			while( list( $uid ) = $db->fetchRow( $result ) ) {
				$cat_uids[] = $uid ;
			}
			$cat_uids = array_unique( $cat_uids ) ;

			$sql = "SELECT `uid` FROM ".$db->prefix($mydirname."_forum_access")." WHERE `forum_id`=$forum_id AND `uid` IS NOT NULL" ;
			$result = $db->query( $sql ) ;
			while( list( $uid ) = $db->fetchRow( $result ) ) {
				$forum_uids[] = $uid ;
			}
			$sql = "SELECT distinct g.uid FROM ".$db->prefix($mydirname."_forumaccess")." x , ".$db->prefix("groups_users_link")." g WHERE x.groupid=g.groupid AND x.`forum_id`=$forum_id AND x.`groupid` IS NOT NULL" ;
			$result = $db->query( $sql ) ;
			while( list( $uid ) = $db->fetchRow( $result ) ) {
				$forum_uids[] = $uid ;
			}
			$forum_uids = array_unique( $forum_uids ) ;

			return array_intersect( $forum_uids , $cat_uids ) ;
		}


// check done
		function get_forum_moderate_groups4show( $forum_id )
		{
			$mydirname =& $this->mModule->get('dirname');
			//$xoopsUser =& $this->mXoopsUser ;
			$db =& $this->db ;

			$forum_id = intval( $forum_id ) ;

			$ret = array() ;
			$sql = 'SELECT g.groupid, g.name FROM '.$db->prefix($mydirname.'_forumaccess').' fa LEFT JOIN '.$db->prefix('groups').' g ON fa.groupid=g.groupid WHERE fa.groupid IS NOT NULL AND fa.is_moderator AND forum_id='.$forum_id ;
			$mrs = $db->query( $sql ) ;
			while( list( $mod_gid , $mod_gname ) = $db->fetchRow( $mrs ) ) {
				$ret[] = array(
					'gid' => $mod_gid ,
					'gname' => htmlspecialchars( $mod_gname , ENT_QUOTES ) ,
				) ;
			}

			return $ret ;
		}


// check done
		function get_forum_moderate_users4show( $forum_id )
		{

			$mydirname =& $this->mModule->get('dirname');
			//$xoopsUser =& $this->mXoopsUser ;
			$db =& $this->db ;
			$mod_config = $this->mod_config ;

			$forum_id = intval( $forum_id ) ;

			$ret = array() ;
			$sql = 'SELECT u.uid, u.uname, u.name FROM '.$db->prefix($mydirname.'_forumaccess').' fa LEFT JOIN '.$db->prefix('users').' u ON fa.uid=u.uid WHERE fa.uid IS NOT NULL AND fa.is_moderator AND forum_id='.$forum_id ;
			$mrs = $db->query( $sql ) ;
			// naao from
			while( list( $mod_uid , $mod_uname , $mod_name) = $db->fetchRow( $mrs ) ) {
				if ($mod_config['use_name'] == 1 && $mod_name ) {
					$mod_uname = $mod_name ;
				}
				// naao to
				$ret[] = array(
					'uid' => $mod_uid ,
					'uname' => htmlspecialchars( $mod_uname , ENT_QUOTES ) ,
				) ;
			}

			return $ret ;
		}


// check done
		function get_category_moderate_groups4show( $mydirname , $cat_id )
		{
			$mydirname =& $this->mModule->get('dirname');
			//$xoopsUser =& $this->mXoopsUser ;
			$db =& $this->db ;

			$cat_id = intval( $cat_id ) ;

			$ret = array() ;
			$sql = 'SELECT g.groupid, g.name FROM '.$db->prefix($mydirname.'_categoryaccess').' ca LEFT JOIN '.$db->prefix('groups').' g ON ca.groupid=g.groupid WHERE ca.groupid IS NOT NULL AND ca.is_moderator AND cat_id='.$cat_id ;
			$mrs = $db->query( $sql ) ;
			while( list( $mod_gid , $mod_gname ) = $db->fetchRow( $mrs ) ) {
				$ret[] = array(
					'gid' => $mod_gid ,
					'gname' => htmlspecialchars( $mod_gname , ENT_QUOTES ) ,
				) ;
			}

			return $ret ;
		}


// check done
		function get_category_moderate_users4show( $cat_id )
		{
			$mydirname =& $this->mModule->get('dirname');
			//$xoopsUser =& $this->mXoopsUser ;
			$db =& $this->db ;
			$mod_config = $this->mod_config ;

			$cat_id = intval( $cat_id ) ;

			$ret = array() ;
			$sql = 'SELECT u.uid, u.uname, u.name FROM '.$db->prefix($mydirname.'_categoryaccess').' ca LEFT JOIN '.$db->prefix('users').' u ON ca.uid=u.uid WHERE ca.uid IS NOT NULL AND ca.is_moderator AND cat_id='.$cat_id ;
			$mrs = $db->query( $sql ) ;
			// naao from
			while( list( $mod_uid , $mod_uname , $mod_name) = $db->fetchRow( $mrs ) ) {
				if ($mod_config['use_name'] == 1 && $mod_name ) {
					$mod_uname = $mod_name ;
				}
				// naao to
				$ret[] = array(
					'uid' => $mod_uid ,
					'uname' => htmlspecialchars( $mod_uname , ENT_QUOTES ) ,
				) ;
			}

			return $ret ;
		}


// select box for jumping into a specified forum
		function make_jumpbox_options( $whr4cat , $whr4forum , $forum_selected = 0 )
		{

			$mydirname =& $this->mModule->get('dirname');
			//$xoopsUser =& $this->mXoopsUser ;
			$db =& $this->db ;
			//$mod_config = $this->mod_config ;
			$myts = $this->myts ;

			$ret = "" ;
			$sql = "SELECT c.cat_id, c.cat_title, c.cat_depth_in_tree, f.forum_id, f.forum_title FROM ".$db->prefix($mydirname."_categories")." c LEFT JOIN ".$db->prefix($mydirname."_forums")." f ON f.cat_id=c.cat_id WHERE ($whr4cat) AND ($whr4forum) ORDER BY c.cat_order_in_tree, f.forum_weight" ;
			if( $result = $db->query( $sql ) ) {
				while( list( $cat_id , $cat_title , $cat_depth , $forum_id , $forum_title ) = $db->fetchRow( $result ) ) {
					$selected = $forum_id == $forum_selected ? 'selected="selected"' : '' ;
					$ret .= "<option value='$forum_id' $selected>".str_repeat('--',$cat_depth).$myts->makeTboxData4Show($cat_title)." - ".$myts->makeTboxData4Show($forum_title)."</option>\n" ;
				}
			} else {
				$ret = "<option value=\"-1\">ERROR</option>\n";
			}

			return $ret ;
		}


// select box for jumping into a specified category
		function make_cat_jumpbox_options( $whr4cat , $cat_selected = 0 )
		{
			$mydirname =& $this->mModule->get('dirname');
			//$xoopsUser =& $this->mXoopsUser ;
			$db =& $this->db ;
			//$mod_config = $this->mod_config ;
			$myts = $this->myts ;

			$ret = "" ;
			$sql = "SELECT c.cat_id, c.cat_title, c.cat_depth_in_tree FROM ".$db->prefix($mydirname."_categories")." c WHERE ($whr4cat) ORDER BY c.cat_order_in_tree" ;
			if( $result = $db->query( $sql ) ) {
				while( list( $cat_id , $cat_title , $cat_depth ) = $db->fetchRow( $result ) ) {
					$selected = $cat_id == $cat_selected ? 'selected="selected"' : '' ;
					$ret .= "<option value='$cat_id' $selected>".str_repeat('--',$cat_depth).$myts->makeTboxData4Show($cat_title)."</option>\n" ;
				}
			} else {
				$ret = "<option value=\"-1\">ERROR</option>\n";
			}

			return $ret ;
		}


		function trigger_event( $category , $item_id , $event , $extra_tags=array() , $user_list=array() , $omit_user_id=null )
		{
			$mydirname =& $this->mModule->get('dirname');

			require_once XOOPS_TRUST_PATH.'/libs/altsys/class/D3NotificationHandler.class.php' ;

			$not_handler =& D3NotificationHandler::getInstance() ;
			$not_handler->triggerEvent( $mydirname , 'xcforum' , $category , $item_id , $event , $extra_tags , $user_list , $omit_user_id ) ;
		}


// started from {XOOPS_URL} for conventional modules
		function get_comment_link( $external_link_format , $external_link_id )
		{
			if( substr( $external_link_format , 0 , 11 ) != '{XOOPS_URL}' ) return '' ;

			$format = str_replace( '{XOOPS_URL}' , XOOPS_URL , $external_link_format ) ;
			return sprintf( $format , urlencode( $external_link_id ) ) ;
		}


// started from class:: for native d3comment modules
		function get_comment_description( $external_link_format , $external_link_id )
		{
			$mydirname =& $this->mModule->get('dirname');
			$d3com =& main_get_comment_object( $mydirname , $external_link_format ) ;
			if( ! is_object( $d3com ) ) return '' ;

			$description = $d3com->fetchDescription( $external_link_id ) ;

			if( $description ) return $description ;
			else return $d3com->fetchSummary( $external_link_id ) ;
		}

// get object for comment integration  // naao modified
		function & main_get_comment_object( $forum_dirname, $external_link_format )
		{
			require_once dirname(dirname(__FILE__)).'/class/D3commentObj.class.php' ;

			$params['forum_dirname'] = $forum_dirname ;

			@list( $params['external_dirname'] , $params['classname'] , $params['external_trustdirname'] )
				= explode( '::' , $external_link_format ) ;

			$obj =& D3commentObj::getInstance ( $params ) ;
			return $obj->d3comObj;
		}

// get samples of category options
		function main_get_categoryoptions4edit( $configs_can_be_override )
		{
			$mod_config = $this->mod_config ;

			$lines = array() ;
			foreach( $configs_can_be_override as $key => $type ) {
				if( isset( $mod_config[ $key ] ) ) {
					$val = $mod_config[ $key ] ;
					if( $type == 'int' || $type == 'bool' ) {
						$val = intval( $val ) ;
					}
					$lines[] = htmlspecialchars( $key . ':' . $val , ENT_QUOTES ) ;
				}
			}
			return implode( '<br />' , $lines ) ;
		}


// hook topic_id/external_link_id into $_POST['mode'] = 'reply' , $_POST['post_id']
		function main_posthook_sametopic()
		{
			$mydirname =& $this->mModule->get('dirname');
			$db =& $this->db ;

			if( ! empty( $_POST['external_link_id'] ) ) {
				// search the first post of the latest topic with the external_link_id
				$external_link_id4sql = addslashes( @$_POST['external_link_id'] ) ;
				$forum_id = intval( @$_POST['forum_id'] ) ;
				$result = $db->query( "SELECT topic_first_post_id,topic_locked FROM ".$db->prefix($mydirname."_topics")." WHERE topic_external_link_id='$external_link_id4sql' AND forum_id=$forum_id AND ! topic_invisible ORDER BY topic_last_post_time DESC LIMIT 1" ) ;
			} else if( ! empty( $_POST['topic_id'] ) ) {
				// search the first post of the topic with the topic_id
				$topic_id = intval( @$_POST['topic_id'] ) ;
				$result = $db->query( "SELECT topic_first_post_id,topic_locked FROM ".$db->prefix($mydirname."_topics")." WHERE topic_id=$topic_id AND ! topic_invisible" ) ;
			}

			if( empty( $result ) ) return ;

			list( $pid , $topic_locked ) = $db->fetchRow( $result ) ;
			if( $pid > 0 && ! $topic_locked ) {
				// hook to reply
				$_POST['mode'] = 'reply' ;
				$_POST['pid'] = $pid ;
			}
		}

	// copy from d3forum/include/common_functions.php
	// this file can be included from d3forum's blocks.

		function get_forums_can_read()
		{
			$mydirname =& $this->mModule->get('dirname');
			$xoopsUser =& $this->mXoopsUser ;
			$db =& $this->db ;
			//$mod_config = $this->mod_config ;
			//$myts = $this->myts ;

			if( is_object( $xoopsUser ) ) {
				$uid = intval( $xoopsUser->getVar('uid') ) ;
				$groups = $xoopsUser->getGroups() ;
				if( ! empty( $groups ) ) {
					$whr4forum = "fa.`uid`=$uid || fa.`groupid` IN (".implode(",",$groups).")" ;
					$whr4cat = "`uid`=$uid || `groupid` IN (".implode(",",$groups).")" ;
				} else {
					$whr4forum = "fa.`uid`=$uid" ;
					$whr4cat = "`uid`=$uid" ;
				}
			} else {
				$whr4forum = "fa.`groupid`=".intval(XOOPS_GROUP_ANONYMOUS) ;
				$whr4cat = "`groupid`=".intval(XOOPS_GROUP_ANONYMOUS) ;
			}

			// get categories
			$sql = "SELECT distinct cat_id FROM ".$db->prefix($mydirname."_category_access")." WHERE ($whr4cat)" ;
			$result = $db->query( $sql ) ;
			if( $result ) while( list( $cat_id ) = $db->fetchRow( $result ) ) {
				$cat_ids[] = intval( $cat_id ) ;
			}
			if( empty( $cat_ids ) ) return array(0) ;

			// get forums
			$sql = "SELECT distinct f.forum_id FROM ".$db->prefix($mydirname."_forums")." f LEFT JOIN ".$db->prefix($mydirname."_forumaccess")." fa ON fa.forum_id=f.forum_id WHERE ($whr4forum) AND f.cat_id IN (".implode(',',$cat_ids).')' ;
			$result = $db->query( $sql ) ;
			if( $result ) while( list( $forum_id ) = $db->fetchRow( $result ) ) {
				$forums[] = intval( $forum_id ) ;
			}

			if( empty( $forums ) ) return array(0) ;
			else return $forums ;
		}


		function get_categories_can_read()
		{
			$mydirname =& $this->mModule->get('dirname');
			$xoopsUser =& $this->mXoopsUser ;
			$db =& $this->db ;
			//$mod_config = $this->mod_config ;
			//$myts = $this->myts ;

			if( is_object( $xoopsUser ) ) {
				$uid = intval( $xoopsUser->getVar('uid') ) ;
				$groups = $xoopsUser->getGroups() ;
				if( ! empty( $groups ) ) {
					$whr4cat = "`uid`=$uid || `groupid` IN (".implode(",",$groups).")" ;
				} else {
					$whr4cat = "`uid`=$uid" ;
				}
			} else {
				$whr4cat = "`groupid`=".intval(XOOPS_GROUP_ANONYMOUS) ;
			}

			// get categories
			$sql = "SELECT distinct cat_id FROM ".$db->prefix($mydirname."_categoryaccess")." WHERE ($whr4cat)" ;
			$result = $db->query( $sql ) ;
			if( $result ) while( list( $cat_id ) = $db->fetchRow( $result ) ) {
				$cat_ids[] = intval( $cat_id ) ;
			}

			if( empty( $cat_ids ) ) return array(0) ;
			else return $cat_ids ;
		}


		function get_submenu()
		{
			$mydirname =& $this->mModule->get('dirname');
			//$xoopsUser =& $this->mXoopsUser ;
			$db =& $this->db ;
			//$mod_config = $this->mod_config ;
			$myts = $this->myts ;

			static $submenus_cache ;

			if( ! empty( $submenus_cache[$mydirname] ) ) return $submenus_cache[$mydirname] ;

/*			$module_handler =& xoops_gethandler('module') ;
			$module =& $module_handler->getByDirname( $mydirname ) ;
			if( ! is_object( $module ) ) return array() ;
			$config_handler =& xoops_gethandler('config') ;
			$mod_config =& $config_handler->getConfigsByCat( 0 , $module->getVar('mid') ) ;
*/
			$whr_read4cat = '`cat_id` IN (' . implode( "," , $this->get_categories_can_read( $mydirname ) ) . ')' ;
			$whr_read4forum = '`forum_id` IN (' . implode( "," , $this->get_forums_can_read( $mydirname ) ) . ')' ;
			$categories = array( 0 => array( 'pid' => -1 , 'name' => '' , 'url' => '' ) ) ;

			// categories query
			$sql = "SELECT cat_id,pid,cat_title FROM ".$db->prefix($mydirname."_categories")." WHERE ($whr_read4cat) ORDER BY cat_order_in_tree" ;
			$crs = $db->query( $sql ) ;
			if( $crs ) while( $cat_row = $db->fetchArray( $crs ) ) {
				$cat_id = intval( $cat_row['cat_id'] ) ;
				$categories[ $cat_id ] = array(
					'name' => $myts->makeTboxData4Show( $cat_row['cat_title'] ) ,
					'url' => 'index.php?cat_id='.$cat_id ,
					'pid' => $cat_row['pid'] ,
				) ;
			}

			// forums query
			$frs = $db->query( "SELECT cat_id,forum_id,forum_title FROM ".$db->prefix($mydirname."_forums" )." WHERE ($whr_read4forum) ORDER BY forum_weight" ) ;
			if( $frs ) while( $forum_row = $db->fetchArray( $frs ) ) {
				$cat_id = intval( $forum_row['cat_id'] ) ;
				$categories[ $cat_id ]['sub'][] = array(
					'name' => $myts->makeTboxData4Show( $forum_row['forum_title'] ) ,
					'url' => '?forum_id='.intval( $forum_row['forum_id'] ) ,
				) ;
			}

			// restruct categories
			$submenus_cache[$mydirname] = array_merge( $this->restruct_categories( $categories , 0 ) ) ;
			return $submenus_cache[$mydirname] ;
		}


		function restruct_categories( $categories , $parent )
		{
			$ret = array() ;
			foreach( $categories as $cat_id => $category ) {
				if( $category['pid'] == $parent ) {
					if( empty( $category['sub'] ) ) $category['sub'] = array() ;
					$ret[] = array(
						'name' => $category['name'] ,
						'url' => $category['url'] ,
						'sub' => array_merge( $category['sub'] , $this->restruct_categories( $categories , $cat_id ) ) ,
					) ;
				}
			}

			return $ret ;
		}


		function common_is_necessary_antispam()
		{
			$user =& $this->mXoopsUser ;
			$belong_groups = is_object( $user ) ? $user->getGroups() : array( XOOPS_GROUP_ANONYMOUS ) ;
			$mod_config = $this->mod_config ;

			if( trim( $mod_config['antispam_class'] ) == '' ) return false ;
			if( ! is_object( $user ) ) return true ;
			if( count( array_intersect( $mod_config['antispam_groups'] , $belong_groups ) ) == count( $belong_groups ) ) return true ;
			return false ;
		}


		function &common_get_antispam_object()
		{
			$mod_config = $this->mod_config ;

			require_once XCFORUM_TRUST_PATH. '/class/D3forumAntispamDefault.class.php' ;
			$class_name = 'D3forumAntispam'.ucfirst(trim($mod_config['antispam_class'])) ;
			if( file_exists( XCFORUM_TRUST_PATH. '/class/'.$class_name.'.class.php' ) ) {
				require_once XCFORUM_TRUST_PATH. '/class/'.$class_name.'.class.php' ;
				if( class_exists( $class_name ) ) {
					$antispam_obj = new $class_name() ;
				}
			}
			if( ! is_object( $antispam_obj ) ) {
				$antispam_obj = new D3forumAntispamDefault() ;
			}

			return $antispam_obj ;
		}


		function common_unhtmlspecialchars( $text )
		{
			return strtr( $text , array_flip( get_html_translation_table( HTML_SPECIALCHARS , ENT_QUOTES ) ) + array( '&#039;' => "'" ) ) ;
		}


		function common_simple_request( $params )
		{
			//$mydirname =& $this->mModule->get('dirname');
			//$xoopsUser =& $this->mXoopsUser ;
			//$db =& $this->db ;
			//$mod_config = $this->mod_config ;
			$myts = $this->myts ;

			$requests = array() ;
			$whrs = array() ;
			$queries = array() ;
			foreach( $params as $key => $type ) {
				$key_by_dot = explode( '.' , $key , 2 ) ;
				if( sizeof( $key_by_dot ) == 1 ) {
					$whr_prefix = '' ;
				} else {
					$whr_prefix = $key_by_dot[0].'.' ;
					$key = $key_by_dot[1] ;
				}

				switch( $type ) {
					case 'int' :
						// 0 means null
						$val = intval( @$_GET[ $key ] ) ;
						if( empty( $val ) ) $val = '' ;
						$requests[ $key ] = $val ;
						$whrs[] = $val ? "($whr_prefix$key='$val')" : '1' ;
						$queries[] = "$key=".urlencode($val) ;
						break ;
					case 'like' :
						$val = $myts->stripSlashesGPC( @$_GET[ $key ] ) ;
						$requests[ $key ] = $val ;
						$whrs[] = $val ? "($whr_prefix$key LIKE '%".addslashes($val)."%')" : '1' ;
						$queries[] = "$key=".urlencode($val) ;
						break ;
				}
			}

			return array(
				'requests' => $requests ,
				'whr' => implode( ' AND ' , $whrs ) ,
				'query' => implode( '&' , $queries ) ,
			) ;
		}


		function common_utf8_encode_recursive( &$data )
		{
			if( is_array( $data ) ) {
				foreach( array_keys( $data ) as $key ) {
					$this->common_utf8_encode_recursive( $data[ $key ] ) ;
				}
			} else if( ! is_numeric( $data ) ) {
				if( XOOPS_USE_MULTIBYTES == 1 ) {
					if( function_exists( 'mb_convert_encoding' ) ) {
						$data = mb_convert_encoding( $data , 'UTF-8' , _CHARSET ) ;
					} else if( function_exists( 'iconv' ) ) {
						$data = iconv( _CHARSET , 'UTF-8' , $data ) ;
					}
				} else {
					$data = utf8_encode( $data ) ;
				}
			}
		}

		public function getGet($key, $default = null) {
			return $this->mRoot->mContext->mRequest->getRequest($key);
			//$request = ( isset($_GET[$key]) ) ? $_GET[$key] : $default;
			//return $request;
		}

		public function getPost($key, $default = null) {
			return $this->mRoot->mContext->mRequest->getRequest($key);
			//$request = ( isset($_POST[$key]) ) ? $_POST[$key] : $default;
			//return $request;
		}


} // end class
} // end if

?>