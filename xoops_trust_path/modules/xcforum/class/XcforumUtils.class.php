<?php
/**
 * @file
 * @package xcforum
 * @version $Id$
**/

if(!defined('XOOPS_ROOT_PATH'))
{
    exit;
}

/**
 * Xcforum_Utils
**/
class Xcforum_Utils
{
	/**
	 * getTransactFuncObject
	 **/
	public static function  getTransactFuncObject(){
		$trsFunc = new Xcforum_Transact_Functions ;
		return $trsFunc;
	}
	/**
     * getModuleConfig
     * 
     * @param   string  $name
     * @param   bool  $optional
     * 
     * @return  XoopsObjectHandler
    **/
    public static function getModuleConfig(/*** string ***/ $dirname, /*** mixed ***/ $key)
    {
    	$handler = self::getXoopsHandler('config');
    	$conf = $handler->getConfigsByDirname($dirname);
    	return (isset($conf[$key])) ? $conf[$key] : null;
    }

    /**
     * &getXoopsHandler
     * 
     * @param   string  $name
     * @param   bool  $optional
     * 
     * @return  XoopsObjectHandler
    **/
    public static function &getXoopsHandler(/*** string ***/ $name,/*** bool ***/ $optional = false)
    {
        // TODO will be emulated xoops_gethandler
        return xoops_gethandler($name,$optional);
    }

    /**
     * getPermittedIdList
     * 
     * @param   string  $dirname
     * @param   string  $action
     * @param   int     $categeoryId
     * 
     * @return  XoopsObjectHandler
    **/
    public static function getPermittedIdList(/*** string ***/ $dirname, /*** string ***/ $action=null, /*** int ***/ $categoryId=0)
    {
        $action = isset($action) ? $action : Xcforum_AuthType::VIEW;
    
        $accessController = self::getAccessControllerModule($dirname);
    
        if(! is_object($accessController)) return;
    
        $role = $accessController->get('role');
        $idList = array();
        if($role=='cat'){
            $delegateName = 'Legacy_Category.'.$accessController->dirname().'.GetPermittedIdList';
            XCube_DelegateUtils::call($delegateName, new XCube_Ref($idList), $accessController->dirname(), self::getActor($dirname, Xcforum_AuthType::VIEW), Legacy_Utils::getUid(), $categoryId);
        }
        elseif($role=='group'){
            $delegateName = 'Legacy_Group.'.$accessController->dirname().'.GetGroupIdListByAction';
            XCube_DelegateUtils::call($delegateName, new XCube_Ref($idList), $accessController->dirname(), $dirname, 'page', Xcforum_AuthType::VIEW);
        }
        else{   //has user group permission ?
            ///TODO
        }
        return $idList;
    }

    /**
     * getAccessControllerModule
     * 
     * @param   string  $dirname
     * 
     * @return  XoopsModule
    **/
    public static function getAccessControllerModule(/*** string ***/ $dirname)
    {
        $handler = self::getXoopsHandler('module');
        return $handler->getByDirname(self::getModuleConfig($dirname, 'access_controller'));
    }

    /**
     * getAccessControllerObject
     * 
     * @param   string  $dirname
     * @param   string  $dataname
     * 
     * @return  Xcforum_AbstractAccessController
    **/
	public static function getAccessControllerObject(/*** string ***/ $dirname, /*** string ***/ $dataname)
	{
		$server = self::getModuleConfig($dirname, 'access_controller');

		//get server's role
		$handler = self::getXoopsHandler('module');
		$module = $handler->getByDirname($server);
		if(! $module){
			require_once XCFORUM_TRUST_PATH . '/class/NoneAccessController.class.php';
			$accessController = new Xcforum_NoneAccessController($server, $dirname, $dataname);
			return $accessController;
		}
		$role = $module->get('role');

		switch($role){
		case 'cat':
			require_once XCFORUM_TRUST_PATH . '/class/CatAccessController.class.php';
			$accessController = new Xcforum_CatAccessController($server, $dirname, $dataname);
			break;
		case 'group':
			require_once XCFORUM_TRUST_PATH . '/class/GroupAccessController.class.php';
			$accessController = new Xcforum_GroupAccessController($server, $dirname, $dataname);
			break;
		case 'none':
		default:
			require_once XCFORUM_TRUST_PATH . '/class/NoneAccessController.class.php';
			$accessController = new Xcforum_NoneAccessController($server, $dirname, $dataname);
			break;
		}
		return $accessController;
	}

    /**
     * getActor
     * 
     * @param   string  $dirname
     * @param   string  $action
     * 
     * @return  string
    **/
    public static function getActor(/*** string ***/ $dirname, /*** string ***/ $action)
    {
        $authSetting = self::getModuleConfig($dirname, 'auth_type');
        $authType = isset($authSetting) ? explode('|', $authSetting) : array('viewer', 'poster', 'manager', 'manager');
        switch($action){
            case Xcforum_AuthType::VIEW:
                return trim($authType[0]);
                break;
            case Xcforum_AuthType::POST:
                return trim($authType[1]);
                break;
            case Xcforum_AuthType::MANAGE:
                return trim($authType[3]);
                break;
        }
    }

    /**
     * getListCriteria
     * 
     * @param   string  $dirname
     * @param   int     $categoryId
     * @param   int     $order
     * @param   Lenum_Status    $status
     * 
     * @return  XoopsObjectHandler
    **/
    public static function getListCriteria(/*** string ***/ $dirname, /*** int ***/ $categoryId=null, /*** int ***/ $order=null, /*** int ***/ $status=Lenum_Status::PUBLISHED)
    {
        $accessController = self::getAccessControllerModule($dirname);
    
        $cri = new CriteriaCompo();
    
        //category
        if(isset($categoryId)){
            $cri->add(new Criteria('category_id', $categoryId));
        }
        else{
            //get permitted categories to show
            if($accessController instanceof XoopsModule && ($accessController->get('role')=='cat' || $accessController->get('role')=='group')){
                $idList = self::getPermittedIdList($dirname);
                if(count($idList)>0){
                    $cri->add(new Criteria('category_id', $idList, 'IN'));
                }
            }
        }
    
        return $cri;
    }

	/**
	 * getMytextSanitizer
	 *
	 * @return  Xcforum_TextSanitizer
	 **/
	public static function getMytextSanitizer(){
		// mytextsanitizer
		require_once XCFORUM_TRUST_PATH . '/class/Textsanitizer.php' ;
		$myts =& Xcforum_TextSanitizer::getInstance() ;
		return $myts;
	}

	/**
	 * getPosterData
	 *
	 * @param  array  $params
	 * @return array
	 */
	public function getPosterData( /*** int ***/ $uid, /*** array ***/ &$params )
	{
		$myts =& self::getMytextSanitizer();
		$rtn = array();

		if ($uid >0){
			// hold each uid's user object
			$uObj = $params['uObj'][$uid] = new XoopsUser($uid);
			$rtn['poster_uname'] =  $uObj->getUnameFromId($uid, 0);
			$rtn['poster_name'] =  $uObj->getUnameFromId($uid, 1);
			$rtn['poster_avatar'] =  $uObj->getAvatarUrl($uid);
			$rtn['poster_regdate'] = $uObj->getVar( 'user_regdate' ) ;
			$rtn['poster_from4disp'] = $myts->makeTboxData4Show( $uObj->getVar( 'user_from' ) , 1 ) ;
			$rtn['poster_signature'] = $myts->makeTboxData4Show( $uObj->getVar( 'user_sig' ) , 1 ) ;
			//$rtn['poster_rank'] = $uObj->rank() ;
			//$rtn['poster_rank_title4disp'] = htmlspecialchars( @$rtn['poster_rank']['title'] , ENT_QUOTES , _CHARSET ) ;
			//$rtn['poster_rank_image4disp'] = htmlspecialchars( @$rtn['poster_rank']['image'] , ENT_QUOTES , _CHARSET ) ;
			//$rtn['poster_is_online'] = $uObj->isOnline() ;
			$rtn['poster_posts_count'] = intval( $uObj->getVar( 'posts' ) ) ;
			$rtn['poster_dispname'] = ( $this->mod_config['use_name'] && !empty($rtn['poster_name'])) ? $rtn['poster_name'] : $rtn['poster_uname'];
			$rtn['poster_gname'] = "";
		} else {
			$rtn['poster_dispname'] = $params['guest_name'];
			$rtn['poster_name'] = $rtn['poster_uname'] = $rtn['poster_avatar'] =  $rtn['poster_from4disp'] = $rtn['poster_signature'] = "";
			$rtn['poster_regdate'] = $rtn['poster_posts_count'] = 0;
			//$rtn['poster_rank'] = $rtn['poster_is_online'] = 0;
		}

		return $rtn ;
	}

	/**
	 * processThispost
	 *
	 * @param   XcforumPostsObject  $postObj
	 * @param   XcforumTopicsObject  $topicObj
	 * @param   array  $params
	 * @return array
	**/
	public function processThispost( /*** XcforumPostsObject ***/ &$postObj , /*** XcforumTopicsObject ***/ &$topicObj, /*** array ***/ &$params)
	{
		$myts =& self::getMytextSanitizer();

		$rtn = array();
		$rtn['poster_uname'] =  "";
		$rtn['poster_name'] =  "";
		//user's info
		$uid = $postObj->get('uid');
		$rtn['poster_uid'] = $uid;
		if ($uid >0){
			// hold each uid's user object
			$uObj = $params['uObj'][$uid] = new XoopsUser($uid);
			$rtn['poster_uname'] =  $uObj->getUnameFromId($uid, 0);
			$rtn['poster_name'] =  $uObj->getUnameFromId($uid, 1);
			$rtn['poster_avatar'] =  $uObj->getAvatarUrl($uid);
			$rtn['poster_regdate'] = $uObj->getVar( 'user_regdate' ) ;
			$rtn['poster_from4disp'] = $myts->makeTboxData4Show( $uObj->getVar( 'user_from' ) , 1 ) ;
			$rtn['poster_signature'] = $myts->makeTboxData4Show( $uObj->getVar( 'user_sig' ) , 1 ) ;
			$rtn['poster_rank'] = $uObj->rank() ;
			$rtn['poster_rank_title4disp'] = htmlspecialchars( @$rtn['poster_rank']['title'] , ENT_QUOTES , _CHARSET ) ;
			$rtn['poster_rank_image4disp'] = htmlspecialchars( @$rtn['poster_rank']['image'] , ENT_QUOTES , _CHARSET ) ;
			$rtn['poster_is_online'] = $uObj->isOnline() ;
			$rtn['poster_posts_count'] = intval( $uObj->getVar( 'posts' ) ) ;
			$rtn['poster_dispname'] = ( $this->mod_config['use_name'] && !empty($rtn['poster_name'])) ? $rtn['poster_name'] : $rtn['poster_uname'];
		} else {
			$rtn['poster_dispname'] =  $postObj->get('guest_name');
			$rtn['poster_avatar'] =  $rtn['poster_from4disp'] = $rtn['poster_signature'] = "";
			$rtn['poster_regdate'] = $rtn['poster_rank'] = $rtn['poster_is_online'] = $rtn['poster_posts_count'] = 0;
		}
		$rtn['poster_gname'] =  $postObj->get('guest_name');

		$rtn['can_post'] =  $params['can_post'];
		$rtn['can_reply'] = ( $topicObj->get('topic_locked') || $postObj->get('invisible') || !$postObj->get('approval') ) ? false : $params['can_post'];
		if( $params['isadminormod'] ) {
			$rtn['can_edit'] = true ;
			$rtn['can_delete'] = true ;
		//} else if( $postObj->get('uid_hidden') && $postObj->get('uid_hidden') == $uid  ) {
		} else if( $uid > 0 && $uid == $params['uid'] ) {
			$rtn['can_edit'] = $params['can_edit'] && time() < $postObj->get('post_time') + $params['mod_config']['selfeditlimit'] ? true : false ;
			$rtn['can_delete'] = $params['can_delete'] && time() < $postObj->get('post_time')  + $params['mod_config']['selfdellimit'] ? true : false ;
		} else if( $uid > 0 ) {
			// normal user cannot touch guest's post
			$rtn['can_edit'] = false ;
			$rtn['can_delete'] = false ;
		} else {
			// guest can delete posts by password
			$rtn['can_edit'] = false ;
			$rtn['can_delete'] = $postObj->get('guest_pass_md5') && $params['can_delete'] && time() < $postObj->get('post_time') + $params['mod_config']['selfdellimit'] ? true : false ;
		}

		return $rtn;

	}

	// get samples of category options
	public function main_get_categoryoptions4edit( $configs_can_be_override )
	{
		$mod_config =& XCube_Root::getSingleton()->mContext->mModuleConfig;

		$lines = array() ;
		foreach( $configs_can_be_override as $key => $type ) {
			if( isset( $mod_config[ $key ] ) ) {
				$val = $mod_config[ $key ] ;
				if( $type == 'int' || $type == 'bool' ) {
					$val = intval( $val ) ;
				}
				$lines[] = htmlspecialchars( $key . ':' . $val , ENT_QUOTES , _CHARSET ) ;
			}
		}
		return implode( '<br />' , $lines ) ;
	}

	// get requests for category's sql (parse options)
	public function set_category_options( $configs_can_be_override )
	{
		$root =& XCube_Root::getSingleton();
		$mod_config =& $root->mContext->mModuleConfig;
		global $myts ;

		$options = array() ;
		foreach( $mod_config as $key => $val ) {
			if( empty( $configs_can_be_override[ $key ] ) ) continue ;
			foreach( explode( "\n" , @$root->mContext->mRequest->getRequest('forum_options') ) as $line ) {
				if( preg_match( '/^'.$key.'\:(.{1,100})$/' , $line , $regs ) ) {
					switch( $configs_can_be_override[ $key ] ) {
						case 'text' :
							$options[ $key ] = trim( $regs[1] ) ;
							break ;
						case 'int' :
							$options[ $key ] = intval( $regs[1] ) ;
							break ;
						case 'bool' :
							$options[ $key ] = intval( $regs[1] ) > 0 ? 1 : 0 ;
							break ;
					}
				}
			}
		}

		//return addslashes( serialize( $options ) ) ;
		return @serialize( $options ) ;
	}

}

class Xcforum_Transact_Functions {

// this class can be included from transaction procedures

	protected $mRoot ;
	protected $db ;
	protected $mModule ;
	protected $mAsset ;

	/**
	 * __construct
	 * @return Xcforum_Transact_Functions
	 **/
	public function __construct(){
		$this->mRoot =& XCube_Root::getSingleton();
		$this->db =& $this->mRoot->mController->mDB;
		$this->mModule =& $this->mRoot->mContext->mModule;
		$this->mAsset =& $this->mModule->mAssetManager;
	}

// call back for comment integration
	public function main_comment_callback( $dirname , $topic_id , $mode = 'update' , $post_id = 0 )
	{
		$db =& $this->db ;

		$topic_id = intval( $topic_id ) ;

		list( $external_link_format , $external_link_id , $forum_id ) = $db->fetchRow( $db->query( "SELECT f.forum_external_link_format,t.topic_external_link_id,t.forum_id FROM ".$db->prefix($dirname."_topics")." t LEFT JOIN ".$db->prefix($dirname."_forums")." f ON f.forum_id=t.forum_id WHERE topic_id=$topic_id" ) ) ;

		if( ! empty( $external_link_format ) && ! empty( $external_link_id ) ) {
			$d3com =& $this->main_get_comment_object( $dirname , $external_link_format ) ;
			if( is_object( @$d3com ) ) {
				$d3com->onUpdate( $mode , $external_link_id , $forum_id , $topic_id , $post_id ) ;
			}
		}
	}


// delete posts recursively
	public function delete_post_recursive( $dirname , $post_id )
	{
		$db =& $this->db ;

		$post_id = intval( $post_id ) ;

		list( $topic_id ) = $db->fetchRow( $db->query( "SELECT topic_id FROM ".$db->prefix($dirname."_posts")." WHERE post_id=$post_id" ) ) ;

		$sql = "SELECT post_id FROM ".$db->prefix($dirname."_posts")." WHERE pid=$post_id" ;
		if( ! $result = $db->query( $sql ) ) die( "DB ERROR in delete posts" ) ;
		while( list( $child_post_id ) = $db->fetchRow( $result ) ) {
			$this->delete_post_recursive( $dirname , $child_post_id ) ;
		}

		/* list( $uid ) = $db->fetchRow( $db->query( "SELECT uid FROM ".$db->prefix($dirname."_posts")." WHERE post_id=$post_id" ) ) ;
	  if( ! empty( $uid ) ) {
		  // decrement user's posts
		  $db->query( "UPDATE ".$db->prefix("users")." SET posts=posts-1 WHERE uid=$uid" ) ;
	  } */

		$this->transact_make_post_history( $dirname , $post_id , true ) ;
		$db->query( "DELETE FROM ".$db->prefix($dirname."_posts")." WHERE post_id=$post_id" ) ;
		$db->query( "DELETE FROM ".$db->prefix($dirname."_post_votes")." WHERE post_id=$post_id" ) ;

		// call back to the target of comment
		$this->main_d3comment_callback( $dirname , $topic_id , 'delete' , $post_id ) ;
	}


// delete a topic
	public function delete_topic( $dirname , $topic_id , $delete_also_posts = true )
	{
		global $xoopsModule ;

		$db =& $this->db ;

		$topic_id = intval( $topic_id ) ;

		// delete posts
		if( $delete_also_posts ) {
			$sql = "SELECT post_id FROM ".$db->prefix($dirname."_posts")." WHERE topic_id=$topic_id" ;
			if( ! $result = $db->query( $sql ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;
			while( list( $post_id ) = $db->fetchRow( $result ) ) {
				$this->delete_post_recursive( $dirname , $post_id ) ;
			}
		}

		// delete notifications about this topic
		$notification_handler =& xoops_gethandler( 'notification' ) ;
		$notification_handler->unsubscribeByItem( $xoopsModule->getVar( 'mid' ) , 'topic' , $topic_id ) ;

		// delete topic
		if( ! $db->query( "DELETE FROM ".$db->prefix($dirname."_topics")." WHERE topic_id=$topic_id" ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;

		// delete u2t
		if( ! $db->query( "DELETE FROM ".$db->prefix($dirname."_users2topics")." WHERE topic_id=$topic_id" ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;
	}


// delete a forum
	public function delete_forum( $dirname , $forum_id , $delete_also_topics = true )
	{
		global $xoopsModule ;

		$db =& $this->db ;

		$forum_id = intval( $forum_id ) ;

		// delete topics
		if( $delete_also_topics ) {
			$sql = "SELECT topic_id FROM ".$db->prefix($dirname."_topics")." WHERE forum_id=$forum_id" ;
			if( ! $result = $db->query( $sql ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;
			while( list( $topic_id ) = $db->fetchRow( $result ) ) {
				$this->delete_topic( $dirname , $topic_id ) ;
			}
		}

		// delete notifications about this forum
		$notification_handler =& xoops_gethandler( 'notification' ) ;
		$notification_handler->unsubscribeByItem( $xoopsModule->getVar( 'mid' ) , 'forum' , $forum_id ) ;

		// delete forum
		if( ! $db->query( "DELETE FROM ".$db->prefix($dirname."_forums")." WHERE forum_id=$forum_id" ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;

		// delete forum_access
		if( ! $db->query( "DELETE FROM ".$db->prefix($dirname."_forum_access")." WHERE forum_id=$forum_id" ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;
	}


// delete a category
	function delete_category( $dirname , $cat_id , $delete_also_forums = true )
	{
		global $xoopsModule ;

		$db =& $this->db ;

		$cat_id = intval( $cat_id ) ;

		// delete forums
		if( $delete_also_forums ) {
			$sql = "SELECT forum_id FROM ".$db->prefix($dirname."_forums")." WHERE cat_id=$cat_id" ;
			if( ! $result = $db->query( $sql ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;
			while( list( $forum_id ) = $db->fetchRow( $result ) ) {
				$this->delete_forum( $dirname , $forum_id ) ;
			}
		}

		// delete notifications about this category
		$notification_handler =& xoops_gethandler( 'notification' ) ;
		$notification_handler->unsubscribeByItem( $xoopsModule->getVar( 'mid' ) , 'category' , $cat_id ) ;

		// delete category
		if( ! $db->query( "DELETE FROM ".$db->prefix($dirname."_categories")." WHERE cat_id=$cat_id" ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;

		// delete category_access
		if( ! $db->query( "DELETE FROM ".$db->prefix($dirname."_category_access")." WHERE cat_id=$cat_id" ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;
	}


// store redundant informations to a category from its forums
	public function sync_category( $dirname , $cat_id )
	{
		$db =& $this->db ;

		$cat_id = intval( $cat_id ) ;

		// get children
		include_once XOOPS_ROOT_PATH."/class/xoopstree.php" ;
		$mytree = new XoopsTree( $db->prefix($dirname."_categories") , "cat_id" , "pid" ) ;
		$children = $mytree->getAllChildId( $cat_id ) ;
		$children[] = $cat_id ;
		$children = array_map( 'intval' , $children ) ;

		// topics/posts information belonging this category directly
		$sql = "SELECT MAX(forum_last_post_id),MAX(forum_last_post_time),SUM(forum_topics_count),SUM(forum_posts_count) FROM ".$db->prefix($dirname."_forums")." WHERE cat_id=$cat_id" ;
		if( ! $result = $db->query( $sql ) ) die( "ERROR SELECT forum in sync category" ) ;
		list( $last_post_id , $last_post_time , $topics_count , $posts_count ) = $db->fetchRow( $result ) ;

		// topics/posts information belonging this category and/or subcategories
		$sql = "SELECT MAX(forum_last_post_id),MAX(forum_last_post_time),SUM(forum_topics_count),SUM(forum_posts_count) FROM ".$db->prefix($dirname."_forums")." WHERE cat_id IN (".implode(",",$children).")" ;
		if( ! $result = $db->query( $sql ) ) die( "ERROR SELECT forum in sync category" ) ;
		list( $last_post_id_in_tree , $last_post_time_in_tree , $topics_count_in_tree , $posts_count_in_tree ) = $db->fetchRow( $result ) ;

		// update query
		if( ! $result = $db->queryF( "UPDATE ".$db->prefix($dirname."_categories")." SET cat_topics_count=".intval($topics_count).",cat_posts_count=".intval($posts_count).", cat_last_post_id=".intval($last_post_id).", cat_last_post_time=".intval($last_post_time).",cat_topics_count_in_tree=".intval($topics_count_in_tree).",cat_posts_count_in_tree=".intval($posts_count_in_tree).", cat_last_post_id_in_tree=".intval($last_post_id_in_tree).", cat_last_post_time_in_tree=".intval($last_post_time_in_tree)." WHERE cat_id=$cat_id" ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;

		// do sync parents
		list( $pid ) = $db->fetchRow( $db->query( "SELECT pid FROM ".$db->prefix($dirname."_categories")." WHERE cat_id=$cat_id" ) ) ;
		if( $pid != $cat_id && $pid > 0 ) {
			$this->sync_category( $dirname , $pid ) ;
		}

		return true ;
	}


	public function sync_cattree( $dirname )
	{
		$db =& $this->db ;

		// rebuild tree informations
		$tree_array = $this->makecattree_recursive( $db->prefix($dirname."_categories") , 0 ) ;
		array_shift( $tree_array ) ;
		$paths = array() ;
		$previous_depth = 0 ;
		if( ! empty( $tree_array ) ) foreach( $tree_array as $key => $val ) {
			$depth_diff = $val['depth'] - $previous_depth ;
			$previous_depth = $val['depth'] ;
			if( $depth_diff > 0 ) {
				for( $i = 0 ; $i < $depth_diff ; $i ++ ) {
					$paths[ $val['cat_id'] ] = $val['cat_title'] ;
				}
			} else {
				for( $i = 0 ; $i < - $depth_diff + 1 ; $i ++ ) {
					array_pop( $paths ) ;
				}
				$paths[ $val['cat_id'] ] = $val['cat_title'] ;
			}

			$db->queryF( "UPDATE ".$db->prefix($dirname."_categories")." SET cat_depth_in_tree=".($val['depth']-1).", cat_order_in_tree=".($key).", cat_path_in_tree='".addslashes(serialize($paths))."' WHERE cat_id=".$val['cat_id'] ) ;
		}
	}


	public function makecattree_recursive( $tablename , $cat_id , $order = 'cat_weight' , $parray = array() , $depth = 0 , $cat_title = '' )
	{
		$db =& $this->db ;

		$parray[] = array( 'cat_id' => $cat_id , 'depth' => $depth , 'cat_title' => $cat_title ) ;

		$sql = "SELECT cat_id,cat_title FROM $tablename WHERE pid=$cat_id ORDER BY $order" ;
		$result = $db->query( $sql ) ;
		if( $db->getRowsNum( $result ) == 0 ) {
			return $parray ;
		}
		while( list( $new_cat_id , $new_cat_title ) = $db->fetchRow( $result ) ) {
			$parray = $this->makecattree_recursive( $tablename , $new_cat_id , $order , $parray , $depth + 1 , $new_cat_title ) ;
		}
		return $parray ;
	}


// store redundant informations to a forum from its topics
	public function sync_forum( $dirname , $forum_id , $sync_also_category = true )
	{
		$db =& $this->db ;

		$forum_id = intval( $forum_id ) ;

		$sql = "SELECT cat_id FROM ".$db->prefix($dirname."_forums")." WHERE forum_id=$forum_id" ;
		if( ! $result = $db->query( $sql ) ) die( "ERROR SELECT forum in sync forum" ) ;
		list( $cat_id ) = $db->fetchRow( $result ) ;

		$sql = "SELECT MAX(topic_last_post_id),MAX(topic_last_post_time),COUNT(topic_id),SUM(topic_posts_count) FROM ".$db->prefix($dirname."_topics")." WHERE forum_id=$forum_id" ;
		if( ! $result = $db->query( $sql ) ) die( "ERROR SELECT topics in sync forum" ) ;
		list( $last_post_id , $last_post_time , $topics_count , $posts_count ) = $db->fetchRow( $result ) ;

		if( ! $result = $db->queryF( "UPDATE ".$db->prefix($dirname."_forums")." SET forum_topics_count=".intval($topics_count).",forum_posts_count=".intval($posts_count).", forum_last_post_id=".intval($last_post_id).", forum_last_post_time=".intval($last_post_time)." WHERE forum_id=$forum_id" ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;

		if( $sync_also_category ) return $this->sync_category( $dirname , $cat_id ) ;
		else return true ;
	}


// store redundant informations to a topic from its posts
// and rebuild tree informations (depth, order_in_tree)
	public function sync_topic( $dirname , $topic_id , $sync_also_forum = true , $sync_topic_title = false )
	{
		$db =& $this->db ;

		$topic_id = intval( $topic_id ) ;

		$sql = "SELECT forum_id FROM ".$db->prefix($dirname."_topics")." WHERE topic_id=$topic_id" ;
		if( ! $result = $db->query( $sql ) ) die( "ERROR SELECT topic in sync topic" ) ;
		list( $forum_id ) = $db->fetchRow( $result ) ;

		// get first_post_id
		$sql = "SELECT post_id FROM ".$db->prefix($dirname."_posts")." WHERE topic_id=$topic_id AND pid=0" ;
		if( ! $result = $db->query( $sql ) ) die( "ERROR SELECT first_post in sync topic" ) ;
		list( $first_post_id ) = $db->fetchRow( $result ) ;

		// get last_post_id and total_posts
		$sql = "SELECT MAX(post_id),COUNT(post_id) FROM ".$db->prefix($dirname."_posts")." WHERE topic_id=$topic_id" ;
		if( ! $result = $db->query( $sql ) ) die( "ERROR SELECT last_post in sync topic" ) ;
		list( $last_post_id , $total_posts ) = $db->fetchRow( $result ) ;

		if( empty( $total_posts ) ) {
			// this is empty topic should be removed
			$this->delete_topic( $dirname , $topic_id ) ;

		} else {

			// update redundant columns in topics table
			list( $first_post_time , $first_uid , $first_subject , $unique_path ) = $db->fetchRow( $db->query( "SELECT post_time,uid,subject,unique_path FROM ".$db->prefix($dirname."_posts")." WHERE post_id=$first_post_id" ) ) ;
			list( $last_post_time , $last_uid ) = $db->fetchRow( $db->query( "SELECT post_time,uid FROM ".$db->prefix($dirname."_posts")." WHERE post_id=$last_post_id" ) ) ;

			// sync topic_title same as first post's subject if specified
			$topictitle4set = $sync_topic_title ? "topic_title='".addslashes($first_subject)."'," : "" ;

			if( ! $db->queryF( "UPDATE ".$db->prefix($dirname."_topics")." SET {$topictitle4set} topic_posts_count=$total_posts, topic_first_uid=$first_uid, topic_first_post_id=$first_post_id, topic_first_post_time=$first_post_time, topic_last_uid=$last_uid, topic_last_post_id=$last_post_id, topic_last_post_time=$last_post_time WHERE topic_id=$topic_id" ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;

			// rebuild tree informations
			$tree_array = $this->maketree_recursive(intval( $first_post_id ) , 'post_id' , array() , 0 , empty( $unique_path ) ? '.1' : $unique_path ) ;
			if( ! empty( $tree_array ) ) foreach( $tree_array as $key => $val ) {
				$db->queryF( "UPDATE ".$db->prefix($dirname."_posts")." SET depth_in_tree=".$val['depth'].", order_in_tree=".($key+1).", unique_path='".addslashes($val['unique_path'])."' WHERE post_id=".$val['post_id'] ) ;
			}
		}

		if( $sync_also_forum ) return $this->sync_forum( $dirname , $forum_id ) ;
		else return true ;
	}


// store redundant informations to a topic from its posts
	public function sync_topic_votes( $dirname , $topic_id )
	{
		$db =& $this->db ;

		$topic_id = intval( $topic_id ) ;

		/* $sql = "SELECT forum_id FROM ".$db->prefix($dirname."_topics")." WHERE topic_id=$topic_id" ;
	  if( ! $result = $db->query( $sql ) ) die( "ERROR SELECT topic in sync topic_votes" ) ;
	  list( $forum_id ) = $db->fetchRow( $result ) ;*/

		$sql = "SELECT SUM(votes_count),SUM(votes_sum) FROM ".$db->prefix($dirname."_posts")." WHERE topic_id=$topic_id" ;
		if( ! $result = $db->query( $sql ) ) die( "ERROR SELECT topic_votes in sync topic_votes" ) ;
		list( $votes_count , $votes_sum ) = $db->fetchRow( $result ) ;

		if( ! $db->queryF( "UPDATE ".$db->prefix($dirname."_topics")." SET topic_votes_count=".intval($votes_count).",topic_votes_sum=".intval($votes_sum)." WHERE topic_id=$topic_id" ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;

		//if( $sync_also_topic_votes ) return $this->sync_forum_votes( $dirname , $forum_id ) ;
		return true ;
	}


// store redundant informations to a post from its post_votes
	public function sync_post_votes( $dirname , $post_id , $sync_also_topic_votes = true )
	{
		$db =& $this->db ;

		$post_id = intval( $post_id ) ;

		$sql = "SELECT topic_id FROM ".$db->prefix($dirname."_posts")." WHERE post_id=$post_id" ;
		if( ! $result = $db->query( $sql ) ) die( "ERROR SELECT post in sync post_votes" ) ;
		list( $topic_id ) = $db->fetchRow( $result ) ;

		$sql = "SELECT COUNT(*),SUM(vote_point) FROM ".$db->prefix($dirname."_post_votes")." WHERE post_id=$post_id" ;
		if( ! $result = $db->query( $sql ) ) die( "ERROR SELECT post_votes in sync post_votes" ) ;
		list( $votes_count , $votes_sum ) = $db->fetchRow( $result ) ;

		if( ! $db->queryF( "UPDATE ".$db->prefix($dirname."_posts")." SET votes_count=".intval($votes_count).",votes_sum=".intval($votes_sum)." WHERE post_id=$post_id" ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;

		if( $sync_also_topic_votes ) return $this->sync_topic_votes( $dirname , $topic_id ) ;
		else return true ;
	}


	public function maketree_recursive( $post_id , $order = 'post_id' , $parray = array() , $depth = 0 , $unique_path = '.1' )
	{
		// this function is called by
		//  - class/handler/Posts.class.php

		// TODO: ポストの度に、トピック内の全ポストをなめてアップデートかけてるけど、やりすぎじゅやないかな？
		// 投稿の時は,pid基準だけでいいと思うし。全UPDATEするとしても、少なくとも読み込みはtopic_idで１回で済むと思う。
		// 全なめするのは、投稿削除があった時と、管理画面の同期操作の時だけで。

		$db =& $this->db ;
		$tablename = $db->prefix($this->mAsset->mDirname."_posts");
		$parray[] = array( 'post_id' => $post_id , 'depth' => $depth , 'unique_path' => $unique_path ) ;

		$sql = "SELECT post_id,unique_path FROM $tablename WHERE pid=$post_id ORDER BY $order" ;
		$result = $db->query( $sql ) ;
		if( $db->getRowsNum( $result ) == 0 ) {
			return $parray ;
		}
		$new_post_ids = array() ;
		$max_count_of_last_level = 0 ;
		while( list( $new_post_id , $new_unique_path ) = $db->fetchRow( $result ) ) {
			$new_post_ids[ intval( $new_post_id ) ] = $new_unique_path ;
			if( ! empty( $new_unique_path ) ) {
				$count_of_last_level = intval( substr( strrchr( $new_unique_path , '.' ) , 1 ) ) ;
				if( $max_count_of_last_level < $count_of_last_level ) {
					$max_count_of_last_level = $count_of_last_level ;
				}
			}
		}
		foreach( $new_post_ids as $new_post_id => $new_unique_path ) {
			if( empty( $new_unique_path ) ) {
				$new_unique_path = $unique_path . '.' . ++ $max_count_of_last_level ;
			}
			$parray = $this->maketree_recursive( $new_post_id , $order , $parray , $depth + 1 , $new_unique_path ) ;
		}
		return $parray ;
	}


	public function cutpasteposts( $dirname , $post_id , $pid , $new_forum_id , $forum_permissions , $isadmin )
	{
		$db =& $this->db ;

		$post_id = intval( $post_id ) ;
		$pid = intval( $pid ) ;
		$new_forum_id = intval( $new_forum_id ) ;

		// get children
		include_once XOOPS_ROOT_PATH."/class/xoopstree.php" ;
		$mytree = new XoopsTree( $db->prefix($dirname."_posts") , "post_id" , "pid" ) ;
		$children = $mytree->getAllChildId( $post_id ) ;
		$children[] = $post_id ;

		if( $pid == 0 ) {
			// check validation to $new_forum_id
			list( $new_forum_id , $new_forum_external_link_format ) = $db->fetchRow( $db->query( "SELECT forum_id,forum_external_link_format FROM ".$db->prefix($dirname."_forums")." WHERE forum_id=$new_forum_id" ) ) ;
			if( empty( $new_forum_id ) ) die( _MD_D3FORUM_ERR_READFORUM ) ;

			// check the user is distinated forum's admin or mod
			if( ! $isadmin && ! $forum_permissions[ $new_forum_id ]['is_moderator'] ) die( _MD_D3FORUM_ERR_CUTPASTENOTADMINOFDESTINATION ) ;

			// check the post is the top or not
			list( $pid , $topic_id , $subject ) = $db->fetchRow( $db->query( "SELECT pid,topic_id,subject FROM ".$db->prefix($dirname."_posts")." WHERE post_id=$post_id" ) ) ;

			if( $pid ) {
				// get external_link_id of the current topic
				list( $external_link_id ) = $db->fetchRow( $db->query( "SELECT topic_external_link_id FROM ".$db->prefix($dirname."_topics")." WHERE topic_id=$topic_id" ) ) ;
				// create a new topic and copy subject to topic_title in sync
				if( ! $db->query( "INSERT INTO ".$db->prefix($dirname."_topics")." SET forum_id=$new_forum_id, topic_title='".addslashes($subject)."', topic_external_link_id='".addslashes($external_link_id)."'" ) ) die( "DB ERROR in INSERT topic" ) ;
				$new_topic_id = $db->getInsertId() ;
				$new_topic_created = true ;
				if( ! $db->query( "UPDATE ".$db->prefix($dirname."_posts")." SET pid=0 WHERE post_id=$post_id" ) ) die( "DB ERROR in UPDATE post" ) ;
			} else if( $topic_id ) {
				// change forum_id of the topic
				$new_topic_id = $topic_id ;
				if( ! $db->query( "UPDATE ".$db->prefix($dirname."_topics")." SET forum_id=$new_forum_id WHERE topic_id=$topic_id" ) ) die( "DB ERROR in UPDATE topic" ) ;
			} else {
				die( "DB ERROR in SELECT topic" ) ;
			}

			// clear topic_external_link_id if the new forum has no external_link_fmt
			if( $new_forum_external_link_format == '' ) {
				if( ! $db->query( "UPDATE ".$db->prefix($dirname."_topics")." SET topic_external_link_id='' WHERE topic_id=$new_topic_id" ) ) die( "DB ERROR in UPDATE topic".__LINE__ ) ;
			}
		} else {
			// get topic_id from post_id
			list( $pid , $new_topic_id , $new_forum_id ) = $db->fetchRow( $db->query( "SELECT p.post_id,t.topic_id,t.forum_id FROM ".$db->prefix($dirname."_posts")." p LEFT JOIN ".$db->prefix($dirname."_topics")." t ON p.topic_id=t.topic_id LEFT JOIN ".$db->prefix($dirname."_forums")." f ON t.forum_id=f.forum_id WHERE p.post_id=$pid" ) ) ;
			if( empty( $pid ) ) die( _MD_D3FORUM_ERR_PIDNOTEXIST ) ;

			// check the user is distinated forum's admin or mod
			if( ! $isadmin && ! $forum_permissions[ $new_forum_id ]['is_moderator'] ) die( _MD_D3FORUM_ERR_CUTPASTENOTADMINOFDESTINATION ) ;

			// loop check
			if( in_array( $pid , $children ) ) die( _MD_D3FORUM_ERR_PIDLOOP ) ;
			if( ! $db->query( "UPDATE ".$db->prefix($dirname."_posts")." SET pid=$pid WHERE post_id=$post_id" ) ) die( "DB ERROR IN UPDATE post" ) ;
		}
		foreach( $children as $child_post_id ) {
			$child_post_id = intval( $child_post_id ) ;
			$sql = "UPDATE ".$db->prefix($dirname."_posts")." SET topic_id=$new_topic_id WHERE post_id=$child_post_id" ;
			$db->query( $sql ) ;
		}

		return array( $new_topic_id , $new_forum_id ) ;
	}


// done
	public function update_topic_from_post( $dirname , $topic_id , $forum_id , $forum_permissions , $isadmin )
	{
		global $myts ;

		$db =& $this->db ;

		$sql4set = '' ;

		$topic_id = intval( $topic_id ) ;
		$new_forum_id = intval( @$_POST['forum_id'] ) ;

		// prefetch for forum
		list( $new_forum_external_link_format ) = $db->fetchRow( $db->query( "SELECT forum_external_link_format FROM ".$db->prefix($dirname."_forums")." WHERE forum_id=$new_forum_id" ) ) ;

		// check the user is destined forum's admin or mod
		if( ! $isadmin && ! $forum_permissions[ $new_forum_id ]['is_moderator'] ) die( _MD_D3FORUM_ERR_CUTPASTENOTADMINOFDESTINATION ) ;

		$topic_title4sql = addslashes( $myts->stripSlashesGPC( @$_POST['topic_title'] ) ) ;
		$topic_sticky = intval( @$_POST['topic_sticky'] ) ;
		$topic_locked = intval( @$_POST['topic_locked'] ) ;
		$topic_invisible = intval( @$_POST['topic_invisible'] ) ;
		$topic_solved = intval( @$_POST['topic_solved'] ) ;
		$external_link_id = $myts->stripSlashesGPC( @$_POST['topic_external_link_id'] ) ;

		// do update
		if( ! $db->query( "UPDATE ".$db->prefix($dirname."_topics")." SET $sql4set topic_title='$topic_title4sql', forum_id='$new_forum_id', topic_sticky='$topic_sticky', topic_locked='$topic_locked', topic_invisible='$topic_invisible', topic_solved='$topic_solved', topic_external_link_id='".addslashes($external_link_id)."' WHERE topic_id=$topic_id" ) ) die( "DB ERROR IN UPDATE topic".__LINE__ ) ;

		// clear topic_external_link_id if the new forum has no external_link_fmt
		if( $new_forum_external_link_format == '' ) {
			if( ! $db->query( "UPDATE ".$db->prefix($dirname."_topics")." SET topic_external_link_id='' WHERE topic_id=$topic_id" ) ) die( "DB ERROR in UPDATE topic".__LINE__ ) ;
		}

		// call back to the target of comment
		if( ! empty( $external_link_format ) && ! empty( $external_link_id ) ) {
			$d3com =& $this->main_get_comment_object( $dirname , $external_link_format ) ;
			if( is_object( @$d3com ) ) {
				$d3com->onUpdate( 'update' , $external_link_id , $forum_id , $topic_id ) ;
			}
		}

		$this->sync_forum( $dirname , $forum_id ) ;
		$this->sync_forum( $dirname , $new_forum_id ) ;
	}


// get requests for forum's sql (parse options)
	public function get_requests4sql_forum( $dirname )
	{
		global $myts , $xoopsModuleConfig ;

		$db =& $this->db ;

		include dirname(dirname(__FILE__)).'/include/constant_can_override.inc.php' ;
		$options = array() ;
		foreach( $xoopsModuleConfig as $key => $val ) {
			if( empty( $d3forum_configs_can_be_override[ $key ] ) ) continue ;
			foreach( explode( "\n" , @$_POST['options'] ) as $line ) {
				if( preg_match( '/^'.$key.'\:(.{1,100})$/' , $line , $regs ) ) {
					switch( $d3forum_configs_can_be_override[ $key ] ) {
						case 'text' :
							$options[ $key ] = trim( $regs[1] ) ;
							break ;
						case 'int' :
							$options[ $key ] = intval( $regs[1] ) ;
							break ;
						case 'bool' :
							$options[ $key ] = intval( $regs[1] ) > 0 ? 1 : 0 ;
							break ;
					}
				}
			}
		}

		// check $cat_id
		$cat_id = empty( $_POST['cat_id'] ) ? intval( @$_GET['cat_id'] ) : intval( @$_POST['cat_id'] ) ;
		$sql = "SELECT * FROM ".$db->prefix($dirname."_categories")." c WHERE c.cat_id=$cat_id" ;
		if( ! $crs = $db->query( $sql ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;
		if( $db->getRowsNum( $crs ) <= 0 ) die( _MD_D3FORUM_ERR_READCATEGORY ) ;

		return array(
			'title' => addslashes( $myts->stripSlashesGPC( @$_POST['title'] ) ) ,
			'desc' => addslashes( $myts->stripSlashesGPC( @$_POST['desc'] ) ) ,
			'weight' => intval( @$_POST['weight'] ) ,
			'external_link_format' => addslashes( $myts->stripSlashesGPC( @$_POST['external_link_format'] ) ) ,
			'cat_id' => $cat_id ,
			'options' => addslashes( serialize( $options ) ) ,
		) ;
	}


// create a forum
	public function makeforum( $dirname , $cat_id , $isadmin )
	{
		$db =& $this->db ;

		$requests = $this->get_requests4sql_forum( $dirname ) ;

		$set4admin = $isadmin ? ", forum_weight='{$requests['weight']}', forum_options='{$requests['options']}', forum_external_link_format='{$requests['external_link_format']}'" : '' ;
		if( ! $db->query( "INSERT INTO ".$db->prefix($dirname."_forums")." SET forum_title='{$requests['title']}', forum_desc='{$requests['desc']}', cat_id=$cat_id $set4admin" ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;
		$new_forum_id = $db->getInsertId() ;

		// permissions are set same as the parent category. (also moderator)
		$sql = "INSERT INTO ".$db->prefix($dirname."_forum_access")." (forum_id,uid,groupid,can_post,can_edit,can_delete,post_auto_approved,is_moderator) SELECT $new_forum_id,uid,groupid,can_post,can_edit,can_delete,post_auto_approved,is_moderator FROM ".$db->prefix($dirname."_category_access")." WHERE cat_id=$cat_id" ;
		if( ! $db->query( $sql ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;

		return array( $new_forum_id , stripslashes( $requests['title'] ) ) ;
	}


// update a forum
	public function updateforum( $dirname , $forum_id , $isadmin )
	{
		$db =& $this->db ;

		$requests = $this->get_requests4sql_forum( $dirname ) ;

		$set4admin = $isadmin ? ", forum_weight='{$requests['weight']}', forum_options='{$requests['options']}', forum_external_link_format='{$requests['external_link_format']}', cat_id='{$requests['cat_id']}'" : '' ;
		if( ! $db->query( "UPDATE ".$db->prefix($dirname."_forums")." SET forum_title='{$requests['title']}', forum_desc='{$requests['desc']}' $set4admin WHERE forum_id=$forum_id" ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;

		return $forum_id ;
	}


// get requests for category's sql (parse options)
	public function get_requests4sql_category( $dirname )
	{
		global $myts , $xoopsModuleConfig ;

		$db =& $this->db ;

		include dirname(dirname(__FILE__)).'/include/constant_can_override.inc.php' ;
		$options = array() ;
		foreach( $xoopsModuleConfig as $key => $val ) {
			if( empty( $d3forum_configs_can_be_override[ $key ] ) ) continue ;
			foreach( explode( "\n" , @$_POST['options'] ) as $line ) {
				if( preg_match( '/^'.$key.'\:(.{1,100})$/' , $line , $regs ) ) {
					switch( $d3forum_configs_can_be_override[ $key ] ) {
						case 'text' :
							$options[ $key ] = trim( $regs[1] ) ;
							break ;
						case 'int' :
							$options[ $key ] = intval( $regs[1] ) ;
							break ;
						case 'bool' :
							$options[ $key ] = intval( $regs[1] ) > 0 ? 1 : 0 ;
							break ;
					}
				}
			}
		}

		// check $pid
		$pid = intval( @$_POST['pid'] ) ;
		if( $pid ) {
			$sql = "SELECT * FROM ".$db->prefix($dirname."_categories")." c WHERE c.cat_id=$pid" ;
			if( ! $crs = $db->query( $sql ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;
			if( $db->getRowsNum( $crs ) <= 0 ) die( _MD_D3FORUM_ERR_READCATEGORY ) ;
		}

		return array(
			'title' => addslashes( $myts->stripSlashesGPC( @$_POST['title'] ) ) ,
			'desc' => addslashes( $myts->stripSlashesGPC( @$_POST['desc'] ) ) ,
			'weight' => intval( @$_POST['weight'] ) ,
			'pid' => $pid ,
			'options' => addslashes( serialize( $options ) ) ,
		) ;
	}


// create a category
	public function makecategory( $dirname )
	{
		global $xoopsUser ;

		$db =& $this->db ;

		$requests = $this->get_requests4sql_category( $dirname ) ;

		if( ! $db->query( "INSERT INTO ".$db->prefix($dirname."_categories")." SET cat_title='{$requests['title']}', cat_desc='{$requests['desc']}', cat_weight='{$requests['weight']}', cat_options='{$requests['options']}', pid={$requests['pid']}" ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;
		$new_cat_id = $db->getInsertId() ;

		if( $requests['pid'] ) {
			// permissions are set same as the parent category. (also moderator)
			$sql = "SELECT uid,groupid,can_post,can_edit,can_delete,post_auto_approved,can_makeforum,is_moderator FROM ".$db->prefix($dirname."_category_access")." WHERE cat_id={$requests['pid']}" ;
			if( ! $result = $db->query( $sql ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;
			while( $row = $db->fetchArray( $result ) ) {
				$uid4sql = empty( $row['uid'] ) ? 'null' : intval( $row['uid'] ) ;
				$groupid4sql = empty( $row['groupid'] ) ? 'null' : intval( $row['groupid'] ) ;
				$sql = "INSERT INTO ".$db->prefix($dirname."_category_access")." (cat_id,uid,groupid,can_post,can_edit,can_delete,post_auto_approved,can_makeforum,is_moderator) VALUES ($new_cat_id,$uid4sql,$groupid4sql,{$row['can_post']},{$row['can_edit']},{$row['can_delete']},{$row['post_auto_approved']},{$row['can_makeforum']},{$row['is_moderator']})" ;
				if( ! $db->query( $sql ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;
			}
		} else {
			// default permissioning for top category
			$groups = $xoopsUser->getGroups() ;
			foreach( $groups as $groupid ) {
				$adminflag = $groupid == 1 ? 1 : 0 ;
				$sql = "INSERT INTO ".$db->prefix($dirname."_category_access")." (cat_id,uid,groupid,can_post,can_edit,can_delete,post_auto_approved,can_makeforum,is_moderator) VALUES ($new_cat_id,null,$groupid,1,1,1,1,$adminflag,$adminflag)" ;
				if( ! $db->query( $sql ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;
			}
		}

		// rebuild category tree
		$this->sync_cattree( $dirname ) ;

		return $new_cat_id ;
	}


// update a category
	public function updatecategory( $dirname , $cat_id )
	{
		$db =& $this->db ;

		$requests = $this->get_requests4sql_category( $dirname ) ;

		// get children
		include_once XOOPS_ROOT_PATH."/class/xoopstree.php" ;
		$mytree = new XoopsTree( $db->prefix($dirname."_categories") , "cat_id" , "pid" ) ;
		$children = $mytree->getAllChildId( $cat_id ) ;
		$children[] = $cat_id ;

		// loop check
		if( in_array( $requests['pid'] , $children ) ) die( _MD_D3FORUM_ERR_PIDLOOP ) ;

		if( ! $db->query( "UPDATE ".$db->prefix($dirname."_categories")." SET cat_title='{$requests['title']}', cat_desc='{$requests['desc']}', cat_weight='{$requests['weight']}', cat_options='{$requests['options']}', pid='{$requests['pid']}' WHERE cat_id=$cat_id" ) ) die( _MD_D3FORUM_ERR_SQL.__LINE__ ) ;

		// rebuild category tree
		$this->sync_cattree( $dirname ) ;

		return $cat_id ;
	}


// make a new history entry for a post
	public function transact_make_post_history( $dirname , $post_id , $full_backup = false )
	{
		$db =& $this->db ;
		$post_id = intval( $post_id ) ;

		$result = $db->query( "SELECT * FROM ".$db->prefix($dirname."_posts")." WHERE post_id=$post_id" ) ;
		if( ! $result || $db->getRowsNum( $result ) == 0 ) return ;
		$post_row = $db->fetchArray( $result ) ;
		$data = array() ;
		$indexes = $full_backup ? array_keys( $post_row ) : array( 'subject' , 'post_text' ) ;
		foreach( $indexes as $index ) {
			$data[ $index ] = $post_row[ $index ] ;
		}

		// check the latest data in history
		$result = $db->query( "SELECT data FROM ".$db->prefix($dirname."_post_histories")." WHERE post_id=$post_id ORDER BY history_time DESC" ) ;
		if( $db->getRowsNum( $result ) > 0 ) {
			list( $old_data_serialized ) = $db->fetchRow( $result ) ;
			$old_data = unserialize( $old_data_serialized ) ;
			if( $old_data == $data ) return ;
		}

		if( ! $db->queryF( "INSERT INTO ".$db->prefix($dirname."_post_histories")." SET post_id=$post_id, history_time=UNIX_TIMESTAMP(), data='".mysql_real_escape_string( serialize( $data ) )."'" ) ) die( "DB ERROR ON making post_history".__LINE__ ) ;
	}


// turning topic_solved of all topics in the category on (batch action)
	public function transact_turnsolvedon_in_category( $dirname , $cat_id )
	{
		$db =& $this->db ;
		$cat_id = intval( $cat_id ) ;

		$sql = "SELECT forum_id FROM ".$db->prefix($dirname."_forums")." WHERE cat_id=$cat_id" ;
		$result = $db->query( $sql ) ;
		while( list( $forum_id ) = $db->fetchRow( $result ) ) {
			$this->transact_turnsolvedon_in_forum( $dirname , $forum_id ) ;
		}
	}


// turning topic_solved of all topics in the forum on (batch action)
	public function transact_turnsolvedon_in_forum( $dirname , $forum_id )
	{
		$db =& $this->db ;
		$forum_id = intval( $forum_id ) ;

		$sql = "UPDATE ".$db->prefix($dirname."_topics")." SET topic_solved=1 WHERE forum_id=$forum_id" ;
		if( ! $db->query( $sql ) ) die( 'ERROR IN TURNSOLVEDON '.__LINE__ ) ;
	}


// return purified HTML
	public function transact_htmlpurify( $text )
	{
		if( substr( PHP_VERSION , 0 , 1 ) != 4 && file_exists( XOOPS_TRUST_PATH.'/modules/protector/library/HTMLPurifier.auto.php' ) ) {
			require_once XOOPS_TRUST_PATH.'/modules/protector/library/HTMLPurifier.auto.php' ;
			$config = HTMLPurifier_Config::createDefault();
			$config->set('Cache', 'SerializerPath', XOOPS_TRUST_PATH.'/modules/protector/configs');
			$config->set('Core', 'Encoding', _CHARSET);
			//$config->set('HTML', 'Doctype', 'HTML 4.01 Transitional');
			$purifier = new HTMLPurifier($config);
			$text = $purifier->purify( $text ) ;
		}
		return $text ;
	}

}
?>
