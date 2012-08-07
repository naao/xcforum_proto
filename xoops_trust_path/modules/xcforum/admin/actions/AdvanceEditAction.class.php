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

require_once XCFORUM_TRUST_PATH . '/class/AbstractEditAction.class.php';

/**
 * Xcforum_Admin_AdvanceEditAction
 **/
class Xcforum_Admin_AdvanceEditAction extends Xcforum_AbstractEditAction
{
	const DATANAME = 'advance';

	private $mImportFunc ;
	private $mImportableModules ;
	private $mComImportableModules ;
	private $mDirname ;

	/**
	 * _setupActionForm
	 *
	 * @param   void
	 *
	 * @return  void
	 **/

	protected function _setupActionForm()
	{
		$this->mActionForm =& $this->mAsset->getObject('form', $this->_getConst('DATANAME'), $this->mod_isadmin, 'edit');
		$this->mActionForm->prepare();
	}

	/**
	 * _getHandler
	 *
	 * @return	Xcforum_ForumaccessHandler
	 */
	protected function &_getHandler()
	{
		$handler =& $this->mAsset->getObject('handler', 'Forumaccess',false);
		return $handler;
	}

	/**
	 * hasPermission
	 *
	 * @param	void
	 *
	 * @return	bool
	 **/
	public function hasPermission()
	{
		return $this->mod_isadmin;
	}

	/**
	 * _setupObject
	 *
	 * @param   void
	 *
	 * @return  void
	 **/
	protected function _setupObject()
	{
		$this->mObjectHandler =& $this->_getHandler();
		$this->mObject =& $this->mObjectHandler->create();
	}

	/**
	 * getDefaultView
	 *
	 * @param   void
	 *
	 * @return  Enum
	 **/
	public function getDefaultView()
	{
		//$this->mActionForm->load($this->mObject);
		return XCFORUM_FRAME_VIEW_INPUT;
	}

	/**
	 * prepare
	 *
	 * @param   void
	 *
	 * @return  bool
	 **/
	public function prepare()
	{
		parent::prepare();
		//$this->_setupObject();
		//$this->_setupActionForm();
		//adump('prepare');

		$mydirname = $this->mDirname =  $this->mAsset->mDirname;
		//adump('prepare',$mydirname);
		$Func = $this->mImportFunc = new Xcforum_Import_Functions($mydirname);
		$this->mImportableModules = $Func->import_getimportablemodules() ;

		$module_handler =& xoops_gethandler( 'module' ) ;
		$modules = $module_handler->getObjects( new Criteria('hascomments',1) ) ;
		$comment_handler =& xoops_gethandler( 'comment' ) ;
		$comimportable_modules = array() ;
		foreach( $modules as $module ) {
			$mid = $module->getVar('mid') ;
			$comment_sum = $comment_handler->getCount( new Criteria('com_modid',$mid) ) ;
			$this->mComImportableModules[ $mid ] = $module->getVar('name')." ($comment_sum)" ;
		}
		//adump($this->mImportableModules);

		return true;
	}

	/**
	 * execute
	 *
	 * @param   void
	 *
	 * @return  Enum
	 **/
	public function execute()
	{
		//adump($comimportable_modules);

		if( ! empty( $_POST['do_import'] ) && ! empty( $_POST['import_mid'] ) ) {
			set_time_limit( 0 ) ;

			$Func = $this->mImportFunc ;

			$import_mid = intval( @$_POST['import_mid'] ) ;
			if( empty( $this->mImportableModules[ $import_mid ] ) ) die( _MD_A_D3FORUM_ERR_INVALIDMID ) ;
			list( $fromtype , ) = explode( ':' , $this->mImportableModules[ $import_mid ] ) ;
			switch( $fromtype ) {
				case 'cbb3' :
					$Func->import_from_cbb3( $import_mid ) ;
					break ;
				case 'newbb1' :
					$Func->import_from_newbb1( $import_mid ) ;
					break ;
				case 'xhnewbb' :
					$Func->import_from_xhnewbb( $import_mid ) ;
					break ;
				case 'd3forum' :
					$Func->import_from_d3forum( $import_mid ) ;
					break ;
			}

		//	redirect_header( XOOPS_URL."/modules/$mydirname/admin/index.php?action=Advance" , 3 , _MD_A_D3FORUM_MSG_IMPORTDONE ) ;
		//	exit ;
		}

		parent::execute();

	}

	/**
	 * executeViewInput
	 *
	 * @param   XCube_RenderTarget  &$render
	 *
	 * @return  void
	 **/
	public function executeViewInput(/*** XCube_RenderTarget ***/ &$render)
	{
		$render->setTemplateName('xcforum_advance.html');
		$render->setAttribute('actionForm', $this->mActionForm);
		$render->setAttribute('object', $this->mObject);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('dataname', self::DATANAME);
		$render->setAttribute('import_from_options', $this->mImportableModules);
		$render->setAttribute('comimport_from_options', $this->mComImportableModules);
		//set tag usage
		//$render->setAttribute('tag_dirname', $this->mRoot->mContext->mModuleConfig['tag_dirname']);

		parent:: executeViewInput($render);

	}

	/**
	 * executeViewSuccess
	 *
	 * @param   XCube_RenderTarget  &$render
	 *
	 * @return  void
	 **/
	public function executeViewSuccess(/*** XCube_RenderTarget ***/ &$render)
	{
		//$param = ($this->forum_id > 0) ? '&forum_id='.$this->forum_id : '';
		//$this->mRoot->mController->executeRedirect(XOOPS_MODULE_URL.'/'.$this->mAsset->mDirname.'/admin/index.php?action=ForumaccessEdit'.$param, 1, XCFORUM_FRAME_VIEW_SUCCESS);
		//$this->mRoot->mController->executeRedirect('./index.php?action=ForumaccesEdit'.$param, 1, XCFORUM_FRAME_VIEW_SUCCESS);
		//$this->mRoot->mController->executeRedirect($this->_getNextUri($this->_getConst('DATANAME')), 1, XCFORUM_FRAME_VIEW_SUCCESS);
	}

	/**
	 * executeViewError
	 *
	 * @param   XCube_RenderTarget  &$render
	 *
	 * @return  void
	 **/
	public function executeViewError(/*** XCube_RenderTarget ***/ &$render)
	{
		//$param = ($this->forum_id > 0) ? '&forum_id='.$this->forum_id : '';
		//$this->mRoot->mController->executeRedirect(XOOPS_MODULE_URL.'/'.$this->mAsset->mDirname.'/admin/index.php?action=ForumaccessEdit'.$param, 1, _MD_XUPDATE_ERROR_DBUPDATE_FAILED);
		//$this->mRoot->mController->executeRedirect('./index.php?action=ForumaccesEdit'.$param, 1, _MD_XUPDATE_ERROR_DBUPDATE_FAILED);
		//$this->mRoot->mController->executeRedirect($this->_getNextUri($this->_getConst('DATANAME')), 1, _MD_XUPDATE_ERROR_DBUPDATE_FAILED);
	}

}

/**
 * Xcforum_Import_Functions
 **/
class Xcforum_Import_Functions
{

	public $mRoot ;
	private $mDirname ;

	protected $xcforum_tables = array(
/*
	'forumaccess' => array(
		'forum_id' ,
		'uid' ,
		'groupid' ,
		'can_post' ,
		'can_edit' ,
		'can_delete' ,
		'post_auto_approved' ,
		'is_moderator' ,
	) ,
*/
		'forumaccess' => array(
		) ,
/*
	'categories' => array(
		'cat_id' ,
		'pid' ,
		'cat_title' ,
		'cat_desc' ,
		'cat_topics_count' ,
		'cat_posts_count' ,
		'cat_last_post_id' ,
		'cat_last_post_time' ,
		'cat_topics_count_in_tree' ,
		'cat_posts_count_in_tree' ,
		'cat_last_post_id_in_tree' ,
		'cat_last_post_time_in_tree' ,
		'cat_depth_in_tree' ,
		'cat_order_in_tree' ,
		'cat_path_in_tree' ,
		'cat_unique_path' ,
		'cat_weight' ,
		'cat_options' ,
	) ,
*/
	'forums' => array(
		'forum_id' ,
		'cat_id' ,
		'forum_external_link_format' ,
		'forum_title' ,
		'forum_desc' ,
		'forum_topics_count' ,
		'forum_posts_count' ,
		'forum_last_post_id' ,
		'forum_last_post_time' ,
		'forum_weight' ,
		'forum_options' ,
	) ,
	'topics' => array(
		'topic_id' ,
		'forum_id' ,
		'topic_external_link_id' ,
		'topic_title' ,
		'topic_first_uid' ,
		'topic_first_post_id' ,
		'topic_first_post_time' ,
		'topic_last_uid' ,
		'topic_last_post_id' ,
		'topic_last_post_time' ,
		'topic_views' ,
		'topic_posts_count' ,
		'topic_locked' ,
		'topic_sticky' ,
		'topic_solved' ,
		'topic_invisible' ,
		'topic_votes_sum' ,
		'topic_votes_count' ,
	) ,
	'posts' => array(
		'post_id' ,
		'pid' ,
		'topic_id' ,
		'post_time' ,
		'modified_time' ,
		'uid' ,
		'uid_hidden' ,
		'poster_ip' ,
		'modifier_ip' ,
		'subject' ,
		'subject_waiting' ,
		'html' ,
		'smiley' ,
		'xcode' ,
		'br' ,
		'number_entity' ,
		'special_entity' ,
		'icon' ,
		'attachsig' ,
		'invisible' ,
		'approval' ,
		'votes_sum' ,
		'votes_count' ,
		'depth_in_tree' ,
		'order_in_tree' ,
		'path_in_tree' ,
		'unique_path' ,
		'guest_name' ,
		'guest_email' ,
		'guest_url' ,
		'guest_pass_md5' ,
		'guest_trip' ,
		'post_text' ,
		'post_text_waiting' ,
	) ,
	'users2topics' => array(
		'uid' ,
		'topic_id' ,
		'u2t_time' ,
		'u2t_marked' ,
		'u2t_rsv' ,
	) ,
	'postvotes' => array(
		'vote_id' ,
		'post_id' ,
		'uid' ,
		'vote_point' ,
		'vote_time' ,
		'vote_ip' ,
	) ,
	'posthistories' => array(
		'history_id' ,
		'post_id' ,
		'history_time' ,
		'data' ,
	) ,
	) ;

	public function __construct(/*** string ***/ $mDirname)
	{
		$this->mRoot =& XCube_Root::getSingleton();
		$this->mDirname = $mDirname;
	}

	public function import_getimportablemodules()
	{
		$db =& $this->mRoot->mController->mDB;
		$mReq = $this->mRoot->mContext->mRequest;
		$module_handler =& xoops_gethandler( 'module' ) ;
		$modules = $module_handler->getObjects() ;
		$mydirname = $this->mDirname ;

		$ret = array() ;

		foreach( $modules as $module ) {
			$mid = $module->getVar('mid') ;
			$dirname = $module->getVar('dirname') ;
			$dirpath = XOOPS_ROOT_PATH.'/modules/'.$dirname ;
			$mytrustdirname = '' ;
			if( file_exists( $dirpath.'/mytrustdirname.php' ) ) {
				include $dirpath.'/mytrustdirname.php' ;
			}
			if( $mytrustdirname == 'd3forum' && $dirname != $mydirname ) {
				// d3forum
				$ret[$mid] = 'd3forum:'.$module->getVar('name')."($dirname)" ;
			} else if( $dirname == 'xhnewbb' ) {
				// xhnewbb
				$ret[$mid] = 'xhnewbb:'.$module->getVar('name')."($dirname)" ;
			} else if( $dirname == 'newbb' ) {
				$judge_sql = "SELECT COUNT(*) FROM ".$db->prefix("bb_votedata") ;
				$judge_result = $db->query( $judge_sql ) ;
				if( $judge_result ) {
					// CBB3?
					$ret[$mid] = 'cbb3:'.$module->getVar('name')."($dirname)" ;
				} else {
					// newbb1
					$ret[$mid] = 'newbb1:'.$module->getVar('name')."($dirname)" ;
				}
			}
		}

		return $ret ;
	}



	protected function import_errordie()
	{
		$db =& $this->mRoot->mController->mDB;
		$mReq = $this->mRoot->mContext->mRequest;

		echo _MD_A_XCFORUM_ERR_SQLONIMPORT ;
		echo $db->logger->dumpQueries() ;
		exit ;
	}



	public function import_from_cbb3( $import_mid )
	{
		$db =& $this->mRoot->mController->mDB;
		$mReq = $this->mRoot->mContext->mRequest;
		$mydirname = $this->mDirname ;
		$from_prefix = 'bb' ;

		// get group_ids
		$group_handler =& xoops_gethandler( 'group' ) ;
		$group_objects = $group_handler->getObjects() ;
		$group_ids = array() ;
		foreach( $group_objects as $group_object ) {
			$group_ids[] = $group_object->getVar('groupid') ;
		}

		// categories
		$table_name = 'categories' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		$irs = $db->query( "INSERT INTO `$to_table` (cat_id,cat_title,cat_desc,cat_weight) SELECT cat_id,cat_title,cat_description,cat_order FROM `$from_table`" ) ;
		if( ! $irs ) $this->import_errordie() ;

		/*
		// category_access (TODO: get permissions from group_permission table 'category_access')
		$crs = $db->query( "SELECT cat_id FROM `$from_table`" ) ;
		$table_name = 'category_access' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		while( list( $cat_id ) = $db->fetchRow( $crs ) ) {
			foreach( $group_ids as $groupid ) {
				$irs = $db->query( "INSERT INTO `$to_table` VALUES ($cat_id,null,$groupid,1,1,1,1,0,0)" ) ;
				if( ! $irs ) $this->import_errordie() ;
			}
		}
		*/

		// forums
		$table_name = 'forums' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		$irs = $db->query( "INSERT INTO `$to_table` (forum_id,forum_title,forum_desc,forum_weight,cat_id) SELECT forum_id,forum_name,forum_desc,forum_order,cat_id FROM `$from_table`" ) ;
		if( ! $irs ) import_errordie() ;

		// forum_access (TODO: get permissions from group_permission table 'forum_access')
		$frs = $db->query( "SELECT forum_id FROM `$from_table`" ) ;
		$table_name = 'forumaccess' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		while( list( $forum_id ) = $db->fetchRow( $frs ) ) {
			foreach( $group_ids as $groupid ) {
				$irs = $db->query( "INSERT INTO `$to_table` VALUES ($forum_id,null,$groupid,1,1,1,1,0)" ) ;
				if( ! $irs ) import_errordie() ;
			}
		}

		// topics
		$table_name = 'topics' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		$irs = $db->query( "INSERT INTO `$to_table` (topic_id,topic_title,topic_views,forum_id,topic_locked,topic_sticky,topic_solved,topic_invisible) SELECT topic_id,topic_title,topic_views,forum_id,topic_status,topic_sticky,1,!approved FROM `$from_table`" ) ;
		if( ! $irs ) $this->import_errordie() ;

		// posts
		$table_name = 'posts' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$from_text_table = $db->prefix( $from_prefix.'_'.'posts_text') ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		$irs = $db->query( "INSERT INTO `$to_table` (post_id,pid,topic_id,post_time,modified_time,uid,poster_ip,modifier_ip,subject,html,smiley,xcode,br,number_entity,special_entity,icon,attachsig,invisible,approval,post_text) SELECT p.post_id,pid,topic_id,post_time,post_time,uid,poster_ip,poster_ip,subject,dohtml,dosmiley,doxcode,dobr,1,1,IF(SUBSTRING(icon,5,1),SUBSTRING(icon,5,1),1),attachsig,0,approved,pt.post_text FROM `$from_table` p LEFT JOIN `$from_text_table` pt ON p.post_id=pt.post_id" ) ;
		if( ! $irs ) $this->import_errordie() ;

		// vote (TODO)
	}

	public function import_from_newbb1( $import_mid )
	{
		$db =& $this->mRoot->mController->mDB;
		$mReq = $this->mRoot->mContext->mRequest;
		$mydirname = $this->mDirname ;
		$from_prefix = 'bb' ;

		// get group_ids
		$group_handler =& xoops_gethandler( 'group' ) ;
		$group_objects = $group_handler->getObjects() ;
		$group_ids = array() ;
		foreach( $group_objects as $group_object ) {
			$group_ids[] = $group_object->getVar('groupid') ;
		}

		// categories
		$table_name = 'categories' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		$irs = $db->query( "INSERT INTO `$to_table` (cat_id,cat_title,cat_weight) SELECT cat_id,cat_title,cat_order FROM `$from_table`" ) ;
		if( ! $irs ) $this->import_errordie() ;

		// category_access
		$crs = $db->query( "SELECT cat_id FROM `$from_table`" ) ;
		$table_name = 'category_access' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		while( list( $cat_id ) = $db->fetchRow( $crs ) ) {
			foreach( $group_ids as $groupid ) {
				$irs = $db->query( "INSERT INTO `$to_table` VALUES ($cat_id,null,$groupid,1,1,1,1,0,0)" ) ;
				if( ! $irs ) $this->import_errordie() ;
			}
		}

		// forums
		$table_name = 'forums' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		$irs = $db->query( "INSERT INTO `$to_table` (forum_id,forum_title,forum_desc,forum_weight,cat_id) SELECT forum_id,forum_name,forum_desc,0,cat_id FROM `$from_table`" ) ;
		if( ! $irs ) $this->import_errordie() ;

		// forum_access
		$frs = $db->query( "SELECT forum_id,forum_access,forum_type FROM `$from_table`" ) ;
		$table_name = 'forum_access' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$from_mods_table = $db->prefix( $from_prefix.'_'.'forum_mods' ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		while( list( $forum_id , $forum_access , $forum_type ) = $db->fetchRow( $frs ) ) {
			// moderator by uid
			$mrs = $db->query( "SELECT user_id FROM `$from_mods_table` WHERE forum_id=$forum_id" ) ;
			while( list( $uid ) = $db->fetchRow( $mrs ) ) {
				$irs = $db->query( "INSERT INTO `$to_table` VALUES ($forum_id,$uid,null,1,1,1,1,1)" ) ;
				if( ! $irs ) $this->import_errordie() ;
			}
			// users on forum_access (ignore duplicate id error)
			$irs = $db->query( "INSERT INTO `$to_table` (forum_id,uid,can_post) SELECT forum_id,user_id,can_post FROM `$from_table` WHERE forum_id=$forum_id" ) ;
			// groups on forum_access
			foreach( $group_ids as $groupid ) {
				if( $forum_type ) {
					/* @list( $can_read , $can_post ) = $db->fetchRow( $db->query( "SELECT groupid,can_post FROM `$from_table` WHERE user_id IS NULL AND forum_id=$forum_id AND groupid=$groupid" ) ) ;
					if( ! empty( $can_read ) ) {
					$irs = $db->query( "INSERT INTO `$to_table` VALUES ($forum_id,null,$groupid,$can_post,1,1,1,0)" ) ;
					if( ! $irs ) $this->import_errordie() ;
					} */
				} else {
					$can_post = 1 ;
					if( ( $groupid == 3 && $forum_access == 1 ) || $forum_access == 3 ) {
						$can_post = 0 ;
					}
					$irs = $db->query( "INSERT INTO `$to_table` VALUES ($forum_id,null,$groupid,$can_post,$can_post,$can_post,1,0)" ) ;
					if( ! $irs ) $this->import_errordie() ;
				}
			}
		}

		// topics
		$table_name = 'topics' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		$irs = $db->query( "INSERT INTO `$to_table` (topic_id,topic_title,topic_views,forum_id,topic_locked,topic_sticky,topic_solved) SELECT topic_id,topic_title,topic_views,forum_id,topic_status,topic_sticky,1 FROM `$from_table`" ) ;
		if( ! $irs ) $this->import_errordie() ;

		// posts
		$table_name = 'posts' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$from_text_table = $db->prefix( $from_prefix.'_'.'posts_text') ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		$irs = $db->query( "INSERT INTO `$to_table` (post_id,pid,topic_id,post_time,modified_time,uid,poster_ip,modifier_ip,subject,html,smiley,xcode,br,number_entity,special_entity,icon,attachsig,invisible,approval,post_text) SELECT p.post_id,pid,topic_id,post_time,post_time,uid,poster_ip,poster_ip,subject,!nohtml,!nosmiley,1,1,1,1,IF(SUBSTRING(icon,5,1),SUBSTRING(icon,5,1),1),attachsig,0,1,pt.post_text FROM `$from_table` p LEFT JOIN `$from_text_table` pt ON p.post_id=pt.post_id" ) ;
		if( ! $irs ) $this->import_errordie() ;

		// users2topics
		$table_name = 'users2topics' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		/*$irs = $db->query( "INSERT INTO `$to_table` (uid,topic_id,u2t_time,u2t_marked,u2t_rsv) SELECT uid,topic_id,u2t_time,u2t_marked,u2t_rsv FROM `$from_table`" ) ;
	  if( ! $irs ) $this->import_errordie() ;*/
	}

	public function import_from_xhnewbb( $import_mid )
	{
		$db =& $this->mRoot->mController->mDB;
		$mReq = $this->mRoot->mContext->mRequest;
		$mydirname = $this->mDirname ;
		$from_prefix = 'xhnewbb' ;

		// get group_ids
		$group_handler =& xoops_gethandler( 'group' ) ;
		$group_objects = $group_handler->getObjects() ;
		$group_ids = array() ;
		foreach( $group_objects as $group_object ) {
			$group_ids[] = $group_object->getVar('groupid') ;
		}

		// categories
		$table_name = 'categories' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		$irs = $db->query( "INSERT INTO `$to_table` (cat_id,cat_title,cat_weight) SELECT cat_id,cat_title,cat_order FROM `$from_table`" ) ;
		if( ! $irs ) $this->import_errordie() ;

		// category_access
		$crs = $db->query( "SELECT cat_id FROM `$from_table`" ) ;
		$table_name = 'category_access' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		while( list( $cat_id ) = $db->fetchRow( $crs ) ) {
			foreach( $group_ids as $groupid ) {
				$irs = $db->query( "INSERT INTO `$to_table` VALUES ($cat_id,null,$groupid,1,1,1,1,0,0)" ) ;
				if( ! $irs ) $this->import_errordie() ;
			}
		}

		// forums
		$table_name = 'forums' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		$irs = $db->query( "INSERT INTO `$to_table` (forum_id,forum_title,forum_desc,forum_weight,cat_id) SELECT forum_id,forum_name,forum_desc,forum_weight,cat_id FROM `$from_table`" ) ;
		if( ! $irs ) $this->import_errordie() ;

		// forum_access
		$frs = $db->query( "SELECT forum_id,forum_access,forum_type FROM `$from_table`" ) ;
		$table_name = 'forum_access' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$from_mods_table = $db->prefix( $from_prefix.'_'.'forum_mods' ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		while( list( $forum_id , $forum_access , $forum_type ) = $db->fetchRow( $frs ) ) {
			// moderator by uid
			$mrs = $db->query( "SELECT user_id FROM `$from_mods_table` WHERE forum_id=$forum_id" ) ;
			while( list( $uid ) = $db->fetchRow( $mrs ) ) {
				$irs = $db->query( "INSERT INTO `$to_table` VALUES ($forum_id,$uid,null,1,1,1,1,1)" ) ;
				if( ! $irs ) $this->import_errordie() ;
			}
			// users on forum_access (ignore duplicate id error)
			$irs = $db->query( "INSERT INTO `$to_table` (forum_id,uid,can_post) SELECT forum_id,user_id,can_post FROM `$from_table` WHERE groupid IS NULL AND forum_id=$forum_id" ) ;
			// groups on forum_access
			foreach( $group_ids as $groupid ) {
				if( $forum_type ) {
					@list( $can_read , $can_post ) = $db->fetchRow( $db->query( "SELECT groupid,can_post FROM `$from_table` WHERE user_id IS NULL AND forum_id=$forum_id AND groupid=$groupid" ) ) ;
					if( ! empty( $can_read ) ) {
						$irs = $db->query( "INSERT INTO `$to_table` VALUES ($forum_id,null,$groupid,$can_post,1,1,1,0)" ) ;
						if( ! $irs ) $this->import_errordie() ;
					}
				} else {
					$can_post = 1 ;
					if( ( $groupid == 3 && $forum_access == 1 ) || $forum_access == 3 ) {
						$can_post = 0 ;
					}
					$irs = $db->query( "INSERT INTO `$to_table` VALUES ($forum_id,null,$groupid,$can_post,$can_post,$can_post,1,0)" ) ;
					if( ! $irs ) import_errordie() ;
				}
			}
		}

		// topics
		$table_name = 'topics' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		$irs = $db->query( "INSERT INTO `$to_table` (topic_id,topic_title,topic_views,forum_id,topic_locked,topic_sticky,topic_solved) SELECT topic_id,topic_title,topic_views,forum_id,topic_status,topic_sticky,topic_solved FROM `$from_table`" ) ;
		if( ! $irs ) import_errordie() ;

		// posts
		$table_name = 'posts' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$from_text_table = $db->prefix( $from_prefix.'_'.'posts_text') ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		$irs = $db->query( "INSERT INTO `$to_table` (post_id,pid,topic_id,post_time,modified_time,uid,poster_ip,modifier_ip,subject,html,smiley,xcode,br,number_entity,special_entity,icon,attachsig,invisible,approval,post_text) SELECT p.post_id,pid,topic_id,post_time,post_time,uid,poster_ip,poster_ip,subject,!nohtml,!nosmiley,1,1,1,1,IF(SUBSTRING(icon,5,1),SUBSTRING(icon,5,1),1),attachsig,0,1,pt.post_text FROM `$from_table` p LEFT JOIN `$from_text_table` pt ON p.post_id=pt.post_id" ) ;
		if( ! $irs ) import_errordie() ;

		// users2topics
		$table_name = 'users2topics' ;
		$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$from_table = $db->prefix( $from_prefix.'_'.$table_name ) ;
		$db->query( "DELETE FROM `$to_table`" ) ;
		$irs = $db->query( "INSERT INTO `$to_table` (uid,topic_id,u2t_time,u2t_marked,u2t_rsv) SELECT uid,topic_id,u2t_time,u2t_marked,u2t_rsv FROM `$from_table`" ) ;
		if( ! $irs ) import_errordie() ;

	}

	public function import_from_d3forum( $import_mid )
	{
		$db =& $this->mRoot->mController->mDB;
		$mReq = $this->mRoot->mContext->mRequest;
		$mydirname = $this->mDirname ;

		$module_handler =& xoops_gethandler( 'module' ) ;
		$from_module =& $module_handler->get( $import_mid ) ;

		foreach( $this->xcforum_tables as $table_name => $columns ) {
			$to_table = $db->prefix( $mydirname.'_'.$table_name ) ;
			$db->query( "DELETE FROM `$to_table`" ) ;
			if ($table_name == 'forumaccess'){
				$from_table = $db->prefix( $from_module->getVar('dirname').'_forum_access' ) ;
				$columns4sqlfrom = $columns4sql = implode( ',' , $columns ) ;
				$crs = $db->query( "SELECT * FROM `$from_table`" ) ;
				while( $result = $db->fetchArray( $crs ) ) {
					$forum_id = (int)$result['forum_id'];
					$groupid = (int)$result['groupid']>0 ? (int)$result['groupid'] : 'null';
					$uid = (int)$result['uid']>0 ? (int)$result['uid'] : 'null';
					$perm_arr['post']= (int)$result['can_post'];
					$perm_arr['html']= 0;
					$perm_arr['edit'] =  (int)$result['can_edit'];
					$perm_arr['delete'] =  (int)$result['can_delete'];
					$perm_arr['auto_approve'] =  (int)$result['post_auto_approved'];
					$perm_arr['moderate'] =  (int)$result['is_moderator'];
					$permissions = addslashes( serialize( $perm_arr ) );
					$irs = $db->query( "INSERT INTO `$to_table` (permit_id, forum_id, uid, groupid, permissions) VALUES (null, $forum_id, $uid, $groupid, '$permissions')" ) ;
					if( ! $irs ) $this->import_errordie() ;
				}
			} else {				if ($table_name == 'postvotes'){
					$from_table = $db->prefix( $from_module->getVar('dirname').'_post_votes' ) ;
				} elseif ($table_name == 'posthistories'){
					$from_table = $db->prefix( $from_module->getVar('dirname').'_post_histories' ) ;
				} else {
					$from_table = $db->prefix( $from_module->getVar('dirname').'_'.$table_name ) ;
				}
				$columns4sqlfrom = $columns4sql = implode( ',' , $columns ) ;
				$columns4sql = str_replace( 'cat_id', 'category_id', $columns4sql );
				$irs = $db->query( "INSERT INTO `$to_table` ($columns4sql) SELECT $columns4sqlfrom FROM `$from_table`" ) ;
			}
			if( ! $irs ) import_errordie() ;
		}
	}


	public function comimport_as_topics( $mid , $forum_id )
	{
		$db =& $this->mRoot->mController->mDB;
		$mReq = $this->mRoot->mContext->mRequest;
		$mydirname = $this->mDirname ;

		// check forum_id
		$frs = $db->query( "SELECT * FROM ".$db->prefix($mydirname."_forums")." WHERE forum_id=$forum_id" ) ;
		if( ! $frs ) import_errordie() ;
		if( $db->getRowsNum( $frs ) != 1 ) die( 'Invalid forum_id' ) ;

		// get comments configs from xoops_version.php of the module
		$module_handler =& xoops_gethandler( 'module' ) ;
		$module_obj =& $module_handler->get( $mid ) ;
		if( ! is_object( $module_obj ) ) die( 'Invalid mid' ) ;
		$com_configs = $module_obj->getInfo('comments') ;

		// get exparams (consider it as "static" like "page=article&")
		$ers = $db->query( "SELECT distinct com_exparams FROM ".$db->prefix("xoopscomments")." WHERE com_modid=$mid AND LENGTH(`com_exparams`) > 5 LIMIT 1" ) ;
		list( $exparam ) = $db->fetchRow( $ers ) ;
		if( empty( $exparam ) ) $exparam = '' ;
		else $exparam = str_replace( '&amp;' , '&' , $exparam ) ;

		if( substr( $exparam , -1 ) != '&' ) $exparam .= '&' ;

		// import it into the forum record as format
		$format = '{XOOPS_URL}/modules/'.$module_obj->getVar('dirname').'/'.$com_configs['pageName'].'?'.$exparam.$com_configs['itemName'].'=%s' ;
		$frs = $db->query( "UPDATE ".$db->prefix($mydirname."_forums")." SET forum_external_link_format='".addslashes($format)."' WHERE forum_id=$forum_id" ) ;
		if( ! $frs ) import_errordie() ;

		// import topics
		$to_table = $db->prefix( $mydirname.'_topics' ) ;
		$from_table = $db->prefix( 'xoopscomments' ) ;
		$crs = $db->query( "SELECT com_id,com_itemid,com_title FROM `$from_table` WHERE com_modid=$mid AND com_pid=0" ) ;
		if( ! $crs ) import_errordie() ;
		while( $row = $db->fetchArray( $crs ) ) {
			$trs = $db->query( "INSERT INTO `$to_table` SET forum_id=$forum_id,topic_external_link_id=".intval($row['com_itemid']).",topic_title='".addslashes($row['com_title'])."'" ) ;
			if( ! $trs ) import_errordie() ;
			$topic_id = $db->getInsertId() ;
			comimport_posts_recursive( $mydirname , $topic_id , intval( $row['com_id'] ) ) ;
			sync_topic( $mydirname , $topic_id ) ;
		}
	}


	protected function comimport_posts_recursive( $topic_id , $com_id , $pid4posts = 0 )
	{
		$db =& $this->mRoot->mController->mDB;
		$mReq = $this->mRoot->mContext->mRequest;
		$mydirname = $this->mDirname ;

		$to_table = $db->prefix( $mydirname.'_posts' ) ;
		$from_table = $db->prefix( 'xoopscomments' ) ;
		$irs = $db->query( "INSERT INTO `$to_table` (pid,topic_id,post_time,modified_time,uid,poster_ip,modifier_ip,subject,html,smiley,xcode,br,number_entity,special_entity,icon,attachsig,invisible,approval,post_text) SELECT $pid4posts,$topic_id,com_created,com_modified,com_uid,com_ip,com_ip,com_title,dohtml,dosmiley,doxcode,dobr,1,1,IF(SUBSTRING(com_icon,5,1),SUBSTRING(com_icon,5,1),1),com_sig,IF(com_status=3,1,0),IF(com_status<>1,1,0),com_text FROM `$from_table` WHERE com_id=$com_id" ) ;
		if( ! $irs ) import_errordie() ;
		$post_id = $db->getInsertId() ;

		$crs = $db->query( "SELECT com_id FROM `$from_table` WHERE com_pid=$com_id" ) ;
		while( list( $child_com_id ) = $db->fetchRow( $crs ) ) {
			$this->comimport_posts_recursive( $mydirname , $topic_id , $child_com_id , $post_id ) ;
		}
	}


	protected function export_forum_to_d3forum( $export_mid , $export_cat_id , $cat_id , $forum_id , $is_move = false )
	{
		$db =& $this->mRoot->mController->mDB;
		$mReq = $this->mRoot->mContext->mRequest;
		$mydirname = $this->mDirname ;

		$module_handler =& xoops_gethandler( 'module' ) ;
		$to_module =& $module_handler->get( $export_mid ) ;
		$export_mydirname = $to_module->getVar('dirname') ;

		// forums table
		$table_name = 'forums' ;
		$from_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$to_table = $db->prefix( $export_mydirname.'_'.$table_name ) ;
		$columns = array_diff( $this->xcforum_tables[$table_name] , array( 'forum_id' , 'cat_id' ) ) ;
		$columns4sql = implode( ',' , $columns ) ;
		$sql = "INSERT INTO `$to_table` ($columns4sql,`cat_id`) SELECT $columns4sql,$export_cat_id FROM `$from_table` WHERE forum_id=$forum_id" ;
		$ers = $db->query( $sql ) ;
		$export_forum_id = $db->getInsertId() ;
		if( $is_move ) $db->query( "DELETE FROM `$from_table` WHERE forum_id=$forum_id" ) ;

		// forum_access table
		$table_name = 'forum_access' ;
		$from_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$to_table = $db->prefix( $export_mydirname.'_'.$table_name ) ;
		$columns = array_diff( $this->xcforum_tables[$table_name] , array( 'forum_id' ) ) ;
		$columns4sql = implode( ',' , $columns ) ;
		$sql = "INSERT INTO `$to_table` ($columns4sql,`forum_id`) SELECT $columns4sql,$export_forum_id FROM `$from_table` WHERE forum_id=$forum_id" ;
		$ers = $db->query( $sql ) ;
		if( $is_move ) $db->query( "DELETE FROM `$from_table` WHERE forum_id=$forum_id" ) ;

		// topics etc.
		$table_name = 'topics' ;
		$from_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$trs = $db->query( "SELECT topic_id FROM `$from_table` WHERE forum_id=$forum_id ORDER BY topic_id" ) ;
		while( list( $topic_id ) = $db->fetchRow( $trs ) ) {
			export_topic_to_d3forum( $mydirname , $export_mid , $export_forum_id , $forum_id , $topic_id , $is_move ) ;
		}
	}


	function export_topic_to_d3forum( $export_mid , $export_forum_id , $forum_id , $topic_id , $is_move = false )
	{
		$db =& $this->mRoot->mController->mDB;
		$mReq = $this->mRoot->mContext->mRequest;
		$mydirname = $this->mDirname ;

		$module_handler =& xoops_gethandler( 'module' ) ;
		$to_module =& $module_handler->get( $export_mid ) ;
		$export_mydirname = $to_module->getVar('dirname') ;

		// topics table
		$table_name = 'topics' ;
		$from_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$to_table = $db->prefix( $export_mydirname.'_'.$table_name ) ;
		$columns = array_diff( $this->xcforum_tables[$table_name] , array( 'topic_id' , 'forum_id' ) ) ;
		$columns4sql = implode( ',' , $columns ) ;
		$sql = "INSERT INTO `$to_table` ($columns4sql,`forum_id`) SELECT $columns4sql,$export_forum_id FROM `$from_table` WHERE topic_id=$topic_id" ;
		$ers = $db->query( $sql ) ;
		$export_topic_id = $db->getInsertId() ;
		if( $is_move ) $db->query( "DELETE FROM `$from_table` WHERE topic_id=$topic_id" ) ;

		// users2topics table
		$table_name = 'users2topics' ;
		$from_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$to_table = $db->prefix( $export_mydirname.'_'.$table_name ) ;
		$columns = array_diff( $this->xcforum_tables[$table_name] , array( 'topic_id' ) ) ;
		$columns4sql = implode( ',' , $columns ) ;
		$sql = "INSERT INTO `$to_table` ($columns4sql,`topic_id`) SELECT $columns4sql,$export_topic_id FROM `$from_table` WHERE topic_id=$topic_id" ;
		$ers = $db->query( $sql ) ;
		if( $is_move ) $db->query( "DELETE FROM `$from_table` WHERE topic_id=$topic_id" ) ;

		// posts table
		$table_name = 'posts' ;
		$from_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$to_table = $db->prefix( $export_mydirname.'_'.$table_name ) ;
		$columns = array_diff( $this->xcforum_tables[$table_name] , array( 'post_id' , 'topic_id' ) ) ;
		$columns4sql = implode( ',' , $columns ) ;
		$prs = $db->query( "SELECT post_id FROM `$from_table` WHERE topic_id=$topic_id ORDER BY post_id" ) ;
		$post_conversions = array() ;
		while( list( $post_id ) = $db->fetchRow( $prs ) ) {
			$sql = "INSERT INTO `$to_table` ($columns4sql,`topic_id`) SELECT $columns4sql,$export_topic_id FROM `$from_table` WHERE post_id=$post_id" ;
			$ers = $db->query( $sql ) ;
			$post_conversions[ $post_id ] = $db->getInsertId() ;
			if( $is_move ) $db->query( "DELETE FROM `$from_table` WHERE post_id=$post_id" ) ;
		}

		// update pid of posts table
		foreach( $post_conversions as $post_id => $export_post_id ) {
			$sql = "UPDATE `$to_table` SET pid=$export_post_id WHERE pid=$post_id AND topic_id=$export_topic_id" ;
			$ers = $db->query( $sql ) ;
		}

		// post_votes table
		$table_name = 'post_votes' ;
		$from_table = $db->prefix( $mydirname.'_'.$table_name ) ;
		$to_table = $db->prefix( $export_mydirname.'_'.$table_name ) ;
		$columns = array_diff( $this->xcforum_tables[$table_name] , array( 'post_id' ) ) ;
		$columns4sql = implode( ',' , $columns ) ;
		foreach( $post_conversions as $post_id => $export_post_id ) {
			$sql = "INSERT INTO `$to_table` ($columns4sql,`post_id`) SELECT $columns4sql,$export_post_id FROM `$from_table` WHERE post_id=$post_id" ;
			$ers = $db->query( $sql ) ;
			if( $is_move ) $db->query( "DELETE FROM `$from_table` WHERE post_id=$post_id" ) ;
		}

		// sync topic, forum, category
		$this->sync_topic( $export_mydirname , $export_topic_id ) ;
		if( $is_move ) $this->sync_forum( $mydirname , $forum_id ) ;
	}



}
?>
